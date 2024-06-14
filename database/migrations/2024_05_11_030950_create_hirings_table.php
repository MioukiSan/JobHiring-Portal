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
        Schema::create('hirings', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->string('job_position');
            $table->string('job_description');
            $table->integer('salary_grade');
            $table->enum('contract_type', ['COS', 'Permanent']);
            $table->enum('job_type', ['Entry', 'SRS-1', 'SRS-2']);
            $table->enum('job_status', ['Open', 'Closed', 'Archived', 'Profiling Stage', 'Pre-Employment Exam', 'Competency Exam', 'Final Shortlisting', 'Initial Interview', 'Final Interview', 'PsychoTest', 'BEI']);
            $table->string('department');
            $table->string('competency_exam_date')->nullable();
            $table->string('pre_employment_exam_date')->nullable();
            $table->string('initial_interview_date')->nullable();
            $table->string('final_interview_date')->nullable();
            $table->string('psycho_test_date')->nullable();
            $table->string('bei_date')->nullable();
            $table->dateTime('closing');
            $table->timestamps('');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hirings');
    }
};
