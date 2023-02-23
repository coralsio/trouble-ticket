<?php

namespace Corals\Modules\TroubleTicket\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\TroubleTicket\Models\TroubleTicket;

class TroubleTicketRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(TroubleTicket::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(TroubleTicket::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, []);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [

            ]);
        }

        if ($this->isUpdate()) {
            $troubleTicket = $this->route('trouble_ticket');

            $rules = array_merge($rules, [
            ]);
        }

        return $rules;
    }

    protected function getValidatorInstance()
    {
        if ($this->isStore() || $this->isUpdate()) {
            $data = $this->all();

            if (isset($data['is_public_owner'])) {
                $data['owner_type'] = null;
                $data['owner_id'] = null;
            } else {
                $data['public_owner'] = null;
                $data['owner_type'] = getModelMorphMap(\Corals\User\Models\User::class);
            }

            $this->getInputSource()->replace($data);
        }

        return parent::getValidatorInstance();
    }

    public function attributes()
    {
        return [
            'public_owner.email' => 'public owner email',
            'public_owner.name' => 'public owner email',
        ];
    }
}
