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
        Schema::create('product_attributes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')
                ->nullable()
                ->constrained('products')
                ->cascadeOnDelete();
            $table->foreignId('product_option_id')
                ->nullable()
                ->constrained('product_options')
                ->cascadeOnDelete();
            $table->foreignId('product_option_choice_id')
                ->nullable()
                ->constrained('product_option_choices')
                ->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['product_id', 'product_option_id', 'product_option_choice_id'], 'product_attributes_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_attributes');
    }
};
