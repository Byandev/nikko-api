<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Project\Enums\ContractStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('contracts', function (Blueprint $table) {
            $table
                ->enum('status', [
                    ContractStatus::PENDING->value,
                    ContractStatus::ACTIVE->value,
                    ContractStatus::COMPLETED->value,
                    ContractStatus::REJECTED->value,
                ])
                ->default(ContractStatus::PENDING->value)
                ->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void {}
};
