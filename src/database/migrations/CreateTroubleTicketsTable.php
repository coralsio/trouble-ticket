<?php

namespace Corals\Modules\TroubleTicket\database\migrations;

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTroubleTicketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tt_teams', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

            $table->text('properties')->nullable();

            $table->softDeletes();
            $table->auditable();
            $table->timestamps();
        });

        Schema::create('tt_ticket_issue_types', function (Blueprint $table) {
            $table->increments('id');

            $table->string('title');
            $table->unsignedInteger('team_id');
            $table->text('description')->nullable();
            $table->text('solutions')->nullable();

            $table->text('properties')->nullable();

            $table->softDeletes();
            $table->auditable();
            $table->timestamps();

            $table->foreign('team_id')
                ->references('id')
                ->on('tt_teams')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('tt_trouble_tickets', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('code')->unique();
            $table->string('title')->nullable();
            $table->text('description')->nullable();

            $table->string('status')->default('new')->index();
            $table->string('priority')->default('low')->index();
            $table->date('due_date')->nullable()->index();
            $table->timestamp('closed_at')->nullable();
            $table->boolean('archived')->default(0)->index();
            $table->double('estimated_hours')->nullable();

            $table->unsignedInteger('category_id');
            $table->unsignedInteger('issue_type_id');
            $table->unsignedInteger('team_id');

            $table->nullableMorphs('model');
            $table->nullableMorphs('owner');

            $table->text('properties')->nullable();

            $table->softDeletes();
            $table->auditable();
            $table->timestamps();

            $table->index(['model_id', 'model_type']);
            $table->index(['owner_id', 'owner_type']);

            $table->foreign('category_id')
                ->references('id')
                ->on('utility_categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('team_id')
                ->references('id')
                ->on('tt_teams')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('issue_type_id')
                ->references('id')
                ->on('tt_ticket_issue_types')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });

        Schema::create('tt_assignments', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->morphs('model');
            $table->morphs('assignee');

            $table->text('properties')->nullable();

            $table->softDeletes();
            $table->auditable();
            $table->timestamps();

            $table->index(['model_id', 'model_type']);
            $table->index(['assignee_id', 'assignee_type']);
        });

        Schema::create('tt_public_owners', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('email');
            $table->string('name');

            $table->text('properties')->nullable();

            $table->softDeletes();
            $table->auditable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tt_trouble_tickets');
        Schema::dropIfExists('tt_assignments');
        Schema::dropIfExists('tt_ticket_issue_types');
        Schema::dropIfExists('tt_teams');
        Schema::dropIfExists('tt_public_owners');
    }
}
