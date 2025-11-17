<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('site_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('project_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('company_id')
                ->constrained()
                ->cascadeOnDelete();

            // Type of work
            $table->enum('type', ['contract', 'sub work', 'coolie'])
                ->default('contract');

            // Date of transaction
            $table->date('transaction_date')->required();

            // Extra details
            $table->string('details')->nullable();
            $table->text('description')->nullable();

            // Category and type
            $table->enum('category', ['receipt', 'expense', 'labour', 'purchase'])
                ->default('receipt');

            $table->enum('transaction_type', ['expense', 'receipt'])
                ->default('expense');

            // Cement fields
            $table->decimal('cement_rate', 10, 2)->nullable();
            $table->decimal('cement_quantity', 10, 2)->nullable();
            $table->decimal('cement_total_price', 10, 2)->nullable();

            // Additional expense
            $table->decimal('expense', 10, 2)->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('site_transactions');
    }
};
