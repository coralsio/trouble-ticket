<?php

namespace Tests\Feature;

use Corals\Modules\TroubleTicket\Facades\TroubleTickets;
use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Utility\Category\Facades\Category;
use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class IssueTypesTest extends TestCase
{
    use DatabaseTransactions;

    protected $issueType;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);
    }

    public function test_issue_types_store()
    {
        $category = array_rand(Category::getCategoriesByParent('tt-categories'));
        $team = array_rand(TroubleTickets::getTeamsList()->toArray());

        $response = $this->post(
            'trouble-ticket/issue-types',
            [
                'title' => 'issue-type',
                'categories' => [$category],
                'team_id' => $team,
                'description' => 'issue-type',
            ]
        );

        $this->issueType = IssueType::query()->where('title', 'issue-type')->first();

        $response->assertDontSee('The given data was invalid')
            ->assertRedirect('trouble-ticket/issue-types');

        $this->assertDatabaseHas('tt_ticket_issue_types', [
            'title' => $this->issueType->title,
            'team_id' => $this->issueType->team_id,
        ]);
    }

    public function test_issue_types_show()
    {
        $this->test_issue_types_store();

        if ($this->issueType) {
            $response = $this->get('trouble-ticket/issue-types/' . $this->issueType->hashed_id);

            $response->assertViewIs('TroubleTicket::issue_types.show')->assertStatus(200);
        }
        $this->assertTrue(true);
    }

    public function test_issue_types_edit()
    {
        $this->test_issue_types_store();

        if ($this->issueType) {
            $response = $this->get('trouble-ticket/issue-types/' . $this->issueType->hashed_id . '/edit');

            $response->assertViewIs('TroubleTicket::issue_types.create_edit')->assertStatus(200);
        }
        $this->assertTrue(true);
    }

    public function test_issue_types_update()
    {
        $this->test_issue_types_store();

        if ($this->issueType) {
            $category = array_rand(Category::getCategoriesByParent('tt-categories'));

            $response = $this->put('trouble-ticket/issue-types/' . $this->issueType->hashed_id, [
                'title' => $this->issueType->title,
                'team_id' => $this->issueType->team_id,
                'categories' => [$category],
                'description' => $this->issueType->description,
            ]);

            $response->assertRedirect('trouble-ticket/issue-types');
            $this->assertDatabaseHas('tt_ticket_issue_types', [
                'title' => $this->issueType->title,
                'team_id' => $this->issueType->team_id,
            ]);
        }

        $this->assertTrue(true);
    }

    public function test_issue_types_delete()
    {
        $this->test_issue_types_store();

        if ($this->issueType) {
            $response = $this->delete('trouble-ticket/issue-types/' . $this->issueType->hashed_id);

            $response->assertStatus(200)->assertSeeText('Issue Type has been deleted successfully.');

            $this->isSoftDeletableModel(IssueType::class);
            $this->assertDatabaseMissing('tt_ticket_issue_types', [
                'title' => $this->issueType->title,
                'team_id' => $this->issueType->team_id,
            ]);
        }
        $this->assertTrue(true);
    }
}
