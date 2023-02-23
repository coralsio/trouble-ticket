<?php

namespace Corals\Modules\TroubleTicket\Services;


use Corals\Foundation\Services\BaseServiceClass;
use Corals\Media\Traits\MediaControllerTrait;
use Corals\Modules\TroubleTicket\Facades\TroubleTickets;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Models\PublicOwner;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;
use Corals\User\Models\User;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\Request;

class TroubleTicketService extends BaseServiceClass
{
    use ValidatesRequests, MediaControllerTrait;

    protected $excludedRequestParams = [
        'attachments',
        'auto_assignment',
        'assignees',
        'assignee_id',
        'is_public_owner',
        'public_owner',
        'g-recaptcha-response'
    ];

    public function preStoreUpdate($request, &$additionalData)
    {
        $issueTypeId = $request->get('issue_type_id');

        $issueType = IssueType::query()->find($issueTypeId);

        $additionalData['team_id'] = optional($issueType)->team_id;

        $this->validateTT($request);
    }

    public function preStore($request, &$additionalData)
    {
        $additionalData['code'] = TroubleTicket::getCode('TT', 'code');

        if ($request->get('is_public_owner') || !user()) {
            if ($request->input('public_owner.email') && $request->input('public_owner.name')) {
                $publicOwner = PublicOwner::query()->updateOrCreate(['email' => $request->input('public_owner.email')],
                    [
                        'name' => $request->input('public_owner.name')
                    ]);

                $additionalData['owner_type'] = getMorphAlias($publicOwner);
                $additionalData['owner_id'] = $publicOwner->id;
            }
            $request->request->add([
                'is_public_owner' => 1,
            ]);
        } elseif (user() && user()->cannot('fullCreate', TroubleTicket::class)) {
            $additionalData['owner_id'] = user()->id;
            $additionalData['owner_type'] = getMorphAlias(User::class);
        }

        if (!user() || user()->cannot('fullCreate', TroubleTicket::class)) {
            $additionalData['status'] = 'new';

            $request->request->add([
                'auto_assignment' => 1,
                'owner_type' => $additionalData['owner_type'] ?? null,
                'owner_id' => $additionalData['owner_id'] ?? null,
                'status' => $additionalData['status'],
            ]);
        }
    }

    /**
     * @param $request
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function validateTT($request)
    {
        $rules = config('trouble_ticket.models.troubleTicket.validation_rules');

        $attributes = [];

        $this->attachmentsValidation($request, $rules, $attributes);

        $attributes['public_owner.email'] = trans('TroubleTicket::attributes.troubleTicket.public_owner.email');
        $attributes['public_owner.name'] = trans('TroubleTicket::attributes.troubleTicket.public_owner.name');

        $messages = [
            'public_owner.email.required_with' => trans('validation.required', [
                'attribute' => trans('TroubleTicket::attributes.troubleTicket.public_owner.email')
            ]),
            'public_owner.name.required_with' => trans('validation.required', [
                'attribute' => trans('TroubleTicket::attributes.troubleTicket.public_owner.name')
            ]),
        ];

        if (!user()) {
            $rules['g-recaptcha-response'] = 'required|captcha';
        }

        $this->validate($request, $rules, $messages, $attributes);
    }

    public function postStore($request, $additionalData)
    {
        $assigneeId = $request->get('assignee_id');

        $latestActivityRecord = $this->model->activities()
            ->where('description', 'created')->where('log_name', 'default')
            ->latest()->first();

        $this->model->logActivity(trans('TroubleTicket::activities.tt_created', ['code' => $this->model->code]),
            $latestActivityRecord->changes());

        TroubleTickets::handleAssignment($this->model, $assigneeId);
    }

    public function postUpdate($request, $additionalData)
    {
        $assigneeId = $request->get('assignee_id');

        if ($assigneeId != TroubleTickets::getFirstTTAssignee($this->model)->id) {
            $this->model->assignees()->delete();

            TroubleTickets::handleAssignment($this->model, $assigneeId);
        }

        $latestActivityRecord = $this->model->activities()
            ->where('description', 'updated')->where('log_name', 'default')
            ->latest()->where('created_at', '>=', now()->subSeconds(30))->first();

        if ($latestActivityRecord) {
            $hasChanges = $latestActivityRecord->changes()->filter(function ($item) {
                return !empty($item);
            })->isNotEmpty();

            if ($hasChanges) {
                $this->model->logActivity(trans('TroubleTicket::activities.tt_updated', ['code' => $this->model->code]),
                    $latestActivityRecord->changes());
            }
        }
    }

    public function postStoreUpdate($request, &$additionalData)
    {
        $this->handleAttachments($request, $this->model);
    }

    /**
     * @param Request $request
     * @param TroubleTicket $troubleTicket
     * @return \Illuminate\Http\JsonResponse
     * @throws \Illuminate\Validation\ValidationException
     */
    public function addAttachments(Request $request, TroubleTicket $troubleTicket)
    {
        $rules = ["attachments" => 'required'];

        $attributes = [];

        $this->attachmentsValidation($request, $rules, $attributes);

        $this->validate($request, $rules, [], $attributes);

        try {
            $fileNames = $this->handleAttachments($request, $troubleTicket);

            $causer = user() ?: $troubleTicket->owner;

            $troubleTicket->logActivity(trans('TroubleTicket::activities.tt_attachment',
                ['causer' => $causer->getIdentifier(), 'files' => join(', ', $fileNames)]));

            $message = [
                'level' => 'success',
                'message' => trans('Corals::messages.success.created',
                    ['item' => trans('TroubleTicket::labels.trouble_ticket.attachment')]),
                'action' => 'site_reload',
            ];
        } catch (\Exception $exception) {
            log_exception($exception, TroubleTicket::class, 'addAttachments');
            $message = ['level' => 'error', 'message' => $exception->getMessage()];
            $code = 400;
        }

        return response()->json($message, $code ?? 200);
    }
}
