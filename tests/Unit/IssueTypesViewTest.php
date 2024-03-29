<?php

namespace Tests\Feature;

use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class IssueTypesViewTest extends TestCase
{
    use DatabaseTransactions;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);
    }

    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_issue_types_view()
    {
        $response = $this->get('trouble-ticket/issue-types');

        $response->assertStatus(200)->assertViewIs('TroubleTicket::issue_types.index');
    }

    public function test_issue_types_create()
    {
        $response = $this->get('trouble-ticket/issue-types/create');

        $response->assertViewIs('TroubleTicket::issue_types.create_edit')->assertStatus(200);
    }
}
