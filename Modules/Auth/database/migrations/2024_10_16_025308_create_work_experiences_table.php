<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Auth\Enums\EmploymentType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('work_experiences', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('job_title');
            $table->string('company');
            $table->string('website');
            $table->string('country');
            $table->enum('employment', [
                EmploymentType::FULL_TIME->value,
                EmploymentType::PART_TIME->value,
                EmploymentType::INTERN->value,
                EmploymentType::CONTRACT->value,
            ]);
            $table->text('description');
            $table->unsignedInteger('start_month');
            $table->unsignedInteger('start_year');
            $table->unsignedInteger('end_month')->nullable();
            $table->unsignedInteger('end_year')->nullable();
            $table->boolean('is_current');

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_experiences');
    }
};
