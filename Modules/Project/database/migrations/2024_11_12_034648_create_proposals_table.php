<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Project\Enums\ProjectLength;
use Modules\Project\Enums\ProposalStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('project_id');
            $table->text('cover_letter');
            $table->decimal('bid');
            $table->decimal('transaction_fee');

            $table->enum('length', [
                ProjectLength::SHORT_TERM->value,
                ProjectLength::MEDIUM_TERM->value,
                ProjectLength::LONG_TERM->value,
                ProjectLength::EXTENDED->value,
            ]);

            $table
                ->enum('status', [
                    ProposalStatus::SUBMITTED->value,
                    ProposalStatus::ACTIVE->value,
                    ProposalStatus::PENDING_OFFER->value,
                ])
                ->default(ProposalStatus::SUBMITTED->value);

            $table->foreign('account_id')
                ->references('id')
                ->on('accounts')
                ->cascadeOnDelete();

            $table->foreign('project_id')
                ->references('id')
                ->on('projects')
                ->cascadeOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposals');
    }
};
