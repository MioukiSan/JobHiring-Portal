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
        Schema::create('applicants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->cascade()->constrained()->cascadeOnDelete();
            $table->foreignId('hiring_id')->cascade()->constrained()->cascadeOnDelete();
            $table->string('application_status')->default('pending');
            $table->string('competency_exam')->nullable(); //status like ongoing or passed or failed
            $table->string('competency_exam_result')->nullable(); //pdf file uploaded of exam result
            $table->string('pre_employment_exam')->nullable();
            $table->string('pre_employment_exam_result')->nullable();
            $table->string('initial_interview')->nullable();
            $table->string('initial_interview_result')->nullable();
            $table->string('final_interview')->nullable();
            $table->string('psycho_test')->nullable();
            $table->string('psycho_test_result')->nullable();
            $table->string('bei_result')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('applicants');
    }
};
