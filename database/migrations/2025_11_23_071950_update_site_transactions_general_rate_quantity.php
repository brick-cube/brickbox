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
        Schema::table('site_transactions', function (Blueprint $table) {
            // Remove cement fields
            $table->dropColumn([
                'cement_rate',
                'cement_quantity',
                'cement_total_price',
            ]);

            // Add general rate and quantity
            $table->decimal('rate', 10, 2)->nullable()->after('transaction_type');
            $table->decimal('quantity', 10, 2)->nullable()->after('rate');
            $table->decimal('total_price', 10, 2)->nullable()->after('quantity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_transactions', function (Blueprint $table) {
            // Rollback — restore cement fields
            $table->decimal('cement_rate', 10, 2)->nullable();
            $table->decimal('cement_quantity', 10, 2)->nullable();
            $table->decimal('cement_total_price', 10, 2)->nullable();

            // Remove general fields
            $table->dropColumn(['rate', 'quantity', 'total_price']);
        });
    }
};
