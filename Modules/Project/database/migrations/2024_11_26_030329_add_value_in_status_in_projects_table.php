<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Project\Enums\ProjectStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table) {
            $table
                ->enum('status', [
                    ProjectStatus::DRAFT->value,
                    ProjectStatus::ACTIVE->value,
                    ProjectStatus::CLOSED->value,
                ])
                ->default(ProjectStatus::ACTIVE->value)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
