<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Modules\Project\Enums\ProposalInvitationStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('proposal_invitations', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('account_id');
            $table->unsignedBigInteger('project_id');
            $table->text('message')->nullable();
            $table->text('rejection_message')->nullable();
            $table
                ->enum('status', [
                    ProposalInvitationStatus::PENDING->value,
                    ProposalInvitationStatus::REJECTED->value,
                    ProposalInvitationStatus::PROPOSAL_SUBMITTED->value,
                ])
                ->default(ProposalInvitationStatus::PENDING->value);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('proposal_invitations');
    }
};
