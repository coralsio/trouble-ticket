<?php

namespace Corals\Modules\TroubleTicket\database\seeds;

use Corals\Modules\TroubleTicket\Models\IssueType;
use Corals\Modules\TroubleTicket\Models\Team;
use Corals\Utility\Category\Models\Category;
use Corals\Utility\ListOfValue\Facades\ListOfValues;
use Corals\Utility\ListOfValue\Models\ListOfValue;
use Corals\User\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TroubleTicketSettingsDatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $ttStatusParent = ListOfValue::query()->create([
            'code' => 'tt_status',
            'value' => 'Support Ticket Status Parent',
        ]);

        $ttStatusOptions = [
            'new' => [
                'label' => 'New',
                'properties' => [
                    'color' => 'info',
                ],
            ],
            'open' => [
                'label' => 'Open',
                'properties' => [
                    'color' => 'warning',
                ],
            ],
            'in_progress' => [
                'label' => 'In Progress',
                'properties' => [
                    'color' => 'primary',
                ],
            ],
            'resolved' => [
                'label' => 'Resolved',
                'properties' => [
                    'color' => 'success',
                ],
            ],
            'closed' => [
                'label' => 'Closed',
            ],
        ];

        ListOfValues::insertListOfValuesChildren($ttStatusParent, $ttStatusOptions);

        $ttPriorityParent = ListOfValue::query()->create([
            'code' => 'tt_priority',
            'value' => 'Support Ticket Priority Parent',
        ]);

        $ttPriorityOptions = [
            'low' => [
                'label' => 'Low',
                'properties' => [
                    'color' => 'info',
                ],
            ],
            'medium' => [
                'label' => 'Medium',
                'properties' => [
                    'color' => 'warning',
                ],
            ],
            'urgent' => [
                'label' => 'Urgent',
                'properties' => [
                    'color' => 'danger',
                ],
            ],
        ];

        ListOfValues::insertListOfValuesChildren($ttPriorityParent, $ttPriorityOptions);

        $parentCategory = Category::query()->create([
            'name' => 'Support Ticket Categories',
            'slug' => 'tt-categories',
            'module' => 'TroubleTicket',
            'status' => 'active',
            'parent_id' => null,
        ]);

        $sampleCategories = ['Technical', 'Communication', 'Missing Data', 'Other'];

        $sampleCategoriesObjects = collect([]);

        foreach ($sampleCategories as $category) {
            $sampleCategoriesObjects->push(Category::query()->create([
                'name' => $category,
                'slug' => Str::slug($category),
                'status' => 'active',
                'module' => 'TroubleTicket',
                'parent_id' => $parentCategory->id,
            ]));
        }


        $team = Team::query()->create([
            'name' => 'General Team',
        ]);

        $user = User::query()->first();

        $team->users()->create([
            'assignee_id' => $user->id,
            'assignee_type' => getModelMorphMap(User::class),
        ]);

        $issueType = IssueType::query()->create([
            'title' => 'General Issue',
            'team_id' => $team->id,
            'description' => 'General Support Ticket issue type',
            'solutions' => [
                ['title' => 'First solution', 'order' => 1, 'details' => 'Try this solution first'],
                [
                    'title' => 'Second solution',
                    'order' => 2,
                    'details' => 'Try this when first solution didn\'t work',
                ],
            ],
        ]);

        $categories = $sampleCategoriesObjects->pluck('id')->toArray();

        $issueType->categories()->sync(array_shift($categories));
    }
}
