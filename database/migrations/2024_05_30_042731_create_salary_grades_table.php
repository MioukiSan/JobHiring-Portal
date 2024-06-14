<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('salary_grades', function (Blueprint $table) {
            $table->id();
            $table->integer('selection_board_id');
            $table->foreignId('applicant_id')->constrained()->cascadeOnDelete()->nullable();
            $table->string('salary_grade')->nullable();
            $table->string('dependability')->nullable();
            $table->string('creative')->nullable();
            $table->string('initiative')->nullable();
            $table->string('time_management')->nullable();
            $table->string('planning')->nullable();
            $table->string('adaptability')->nullable();
            $table->string('teamwork')->nullable();
            $table->string('self_management')->nullable();
            $table->string('communication')->nullable();
            $table->string('service_delivery')->nullable();
            $table->string('customer_focus')->nullable();
            $table->string('management')->nullable();
            $table->string('staff_management')->nullable();
            $table->string('strategic_planning')->nullable();
            $table->string('org_awareness')->nullable();
            $table->string('monitor_evaluation')->nullable();
            $table->string('strategy_creatively')->nullable();
            $table->string('leading_change')->nullable();
            $table->string('building_relationship')->nullable();
            $table->string('coaching')->nullable();
            $table->string('create_nurture_performance')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('salary_grades');
    }
};
