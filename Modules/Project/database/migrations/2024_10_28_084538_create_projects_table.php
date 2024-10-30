<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Project\Enums\ExperienceLevel;
use Modules\Project\Enums\ProjectLength;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->string('title');
            $table->text('description');
            $table->decimal('estimated_budget');

            $table->enum('length', [
                ProjectLength::SHORT_TERM->value,
                ProjectLength::MEDIUM_TERM->value,
                ProjectLength::LONG_TERM->value,
                ProjectLength::EXTENDED->value,
            ]);

            $table->enum('experience_level', [
                ExperienceLevel::ANY->value,
                ExperienceLevel::ENTRY->value,
                ExperienceLevel::INTERMEDIATE->value,
                ExperienceLevel::EXPERT->value,
            ]);

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
        Schema::dropIfExists('projects');
    }
};
