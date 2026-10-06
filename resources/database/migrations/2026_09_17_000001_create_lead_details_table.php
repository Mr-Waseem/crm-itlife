<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('lead_details', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('party_id');
            $table->string('lead_status', 30);
            $table->string('activity_type', 30);
            $table->date('follow_up_date')->nullable();
            $table->time('follow_up_time')->nullable();
            $table->unsignedInteger('assigned_to')->nullable();
            $table->string('assigned_to_name')->nullable();
            $table->text('remarks')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->unsignedBigInteger('supersedes_id')->nullable();
            $table->unsignedInteger('created_by')->nullable();
            $table->timestamps();

            $table->foreign('party_id')->references('id')->on('parties')->cascadeOnDelete();
            $table->foreign('assigned_to')->references('id')->on('users')->nullOnDelete();
            $table->foreign('created_by')->references('id')->on('users')->nullOnDelete();
            $table->foreign('supersedes_id')->references('id')->on('lead_details')->nullOnDelete();
            $table->index(['follow_up_date', 'completed_at', 'cancelled_at'], 'lead_calendar_open_idx');
            $table->index(['party_id', 'id'], 'lead_party_history_idx');
            $table->index(['assigned_to', 'follow_up_date'], 'lead_assignee_date_idx');
        });
    }

    public function down()
    {
        Schema::dropIfExists('lead_details');
    }
};
