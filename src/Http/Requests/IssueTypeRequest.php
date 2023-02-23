<?php

namespace Corals\Modules\TroubleTicket\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\TroubleTicket\Models\IssueType;

class IssueTypeRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(IssueType::class, 'issue_type');

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(IssueType::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'description' => 'required',
                'team_id' => 'required',
                'categories' => 'required',
                'solutions.*.title' => 'required',
                'solutions.*.order' => 'required|numeric',
                'solutions.*.details' => 'required',
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'title' => 'required|max:191|unique:tt_ticket_issue_types,title',
            ]);
        }

        if ($this->isUpdate()) {
            $issueType = $this->route('issue_type');

            $rules = array_merge($rules, [
                'title' => 'required|max:191|unique:tt_ticket_issue_types,title,' . $issueType->id,
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        $attributes = [];

        foreach ($this->get('solutions', []) ?? [] as $index => $solution) {
            $attributes["solutions.*.title"] = __('TroubleTicket::attributes.issue_type.solutions.title');
            $attributes["solutions.*.order"] = __('TroubleTicket::attributes.issue_type.solutions.order');
            $attributes["solutions.*.details"] = __('TroubleTicket::attributes.issue_type.solutions.details');
        }

        return $attributes;
    }

    public function getValidatorInstance()
    {
        if ($this->isUpdate()) {
            $data = $this->all();

            $data['solutions'] = $data['solutions'] ?? [];

            $this->getInputSource()->replace($data);
        }
        
        return parent::getValidatorInstance();
    }
}
