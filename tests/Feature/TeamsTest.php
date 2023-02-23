<?php

namespace Tests\Feature;

use Corals\Modules\TroubleTicket\Models\Team;
use Corals\User\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Auth;
use Tests\TestCase;

class TeamsTest extends TestCase
{
    use DatabaseTransactions;

    protected $team;

    protected function setUp(): void
    {
        parent::setUp(); // TODO: Change the autogenerated stub

        $user = User::query()->whereHas('roles', function ($query) {
            $query->where('name', 'superuser');
        })->first();
        Auth::loginUsingId($user->id);
    }

    public function test_teams_store()
    {
        $user = array_rand(User::all()->pluck('name', 'id')->toArray());

        $response = $this->post(
            'trouble-ticket/teams',
            [
                'name' => 'team',
                'users' => [$user],
            ]
        );

        $this->team = Team::query()->where('name', 'team')->first();

        $response->assertDontSee('The given data was invalid')
            ->assertRedirect('trouble-ticket/teams');

        $this->assertDatabaseHas('tt_teams', [
            'name' => $this->team->name,
        ]);
    }

    public function test_teams_show()
    {
        $this->test_teams_store();

        if ($this->team) {
            $response = $this->get('trouble-ticket/teams/' . $this->team->hashed_id);

            $response->assertViewIs('TroubleTicket::teams.show')->assertStatus(200);
        }
        $this->assertTrue(true);
    }


    public function test_teams_edit()
    {
        $this->test_teams_store();

        if ($this->team) {
            $response = $this->get('trouble-ticket/teams/' . $this->team->hashed_id . '/edit');

            $response->assertViewIs('TroubleTicket::teams.create_edit')->assertStatus(200);
        }
        $this->assertTrue(true);
    }

    public function test_teams_update()
    {
        $this->test_teams_store();

        if ($this->team) {
            $user = array_rand(User::all()->pluck('name', 'id')->toArray());

            $response = $this->put('trouble-ticket/teams/' . $this->team->hashed_id, [
                'name' => $this->team->name,
                'users' => [$user],
            ]);

            $response->assertRedirect('trouble-ticket/teams');
            $this->assertDatabaseHas('tt_teams', [
                'name' => $this->team->name,
            ]);
        }

        $this->assertTrue(true);
    }

    public function test_teams_delete()
    {
        $this->test_teams_store();

        if ($this->team) {
            $response = $this->delete('trouble-ticket/teams/' . $this->team->hashed_id);

            $response->assertStatus(200)->assertSeeText('Team has been deleted successfully.');

            $this->isSoftDeletableModel(Team::class);
            $this->assertDatabaseMissing('tt_teams', [
                'name' => $this->team->name,
            ]);
        }
        $this->assertTrue(true);
    }
}
