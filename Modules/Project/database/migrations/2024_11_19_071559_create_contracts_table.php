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
        Schema::create('contracts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('project_id');
            $table->unsignedBigInteger('proposal_id')->unique();

            $table->decimal('amount');
            $table->decimal('platform_fee_percentage');
            $table->decimal('total_amount');
            $table->date('end_date');

            $table
                ->enum('status', [
                    ContractStatus::PENDING->value,
                    ContractStatus::ACTIVE->value,
                    ContractStatus::COMPLETED->value,
                ])
                ->default(ContractStatus::PENDING->value);

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();

            $table->foreign('proposal_id')
                ->references('id')
                ->on('proposals')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('contracts');
    }
};
