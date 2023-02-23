<?php

namespace Corals\Modules\TroubleTicket\Http\Requests;

use Corals\Foundation\Http\Requests\BaseRequest;
use Corals\Modules\TroubleTicket\Models\Team;

class TeamRequest extends BaseRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        $this->setModel(Team::class);

        return $this->isAuthorized();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $this->setModel(Team::class);
        $rules = parent::rules();

        if ($this->isUpdate() || $this->isStore()) {
            $rules = array_merge($rules, [
                'properties.notifications_channels.email' => 'nullable|email',
                'users' => 'required|min:1'
            ]);
        }

        if ($this->isStore()) {
            $rules = array_merge($rules, [
                'name' => 'required|max:191|unique:tt_teams,name',
            ]);
        }

        if ($this->isUpdate()) {
            $team = $this->route('team');

            $rules = array_merge($rules, [
                'name' => 'required|max:191|unique:tt_teams,name,' . $team->id,
            ]);
        }

        return $rules;
    }

    public function attributes()
    {
        return [
            'properties.notifications_channels.email' => 'email',
            'properties.notifications_channels.slack' => 'slack',
        ];
    }
}
