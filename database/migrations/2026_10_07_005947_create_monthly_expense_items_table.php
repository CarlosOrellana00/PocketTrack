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
        Schema::create('monthly_expense_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('monthly_account_id')
                ->constrained('monthly_accounts')
                ->cascadeOnDelete();

            $table->foreignId('expense_id')
                ->nullable()
                ->constrained('expenses')
                ->nullOnDelete();

            $table->foreignId('category_id')
                ->constrained('categories')
                ->restrictOnDelete();

            $table->string('name', 100);

            $table->decimal('amount', 12, 2)->nullable();

            $table->date('expense_date')->nullable();

            $table->text('description')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monthly_expense_items');
    }
};
