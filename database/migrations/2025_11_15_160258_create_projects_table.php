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
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('address');

            $table->enum('type', ['residential', 'commercial', 'coolie-work'])
                ->default('residential');

            // FIXED: number() does not exist -> use integer or decimal
            $table->decimal('area', 10, 2)->nullable();
            $table->decimal('value', 12, 2)->nullable();

            $table->enum('status', [
                'started',
                'foundation',
                'structure',
                'plastering',
                'electrical and plumbing',
                'flooring',
                'painting',
                'finished',
                'stopped'
            ])->default('started');

            // Active flag
            $table->boolean('is_active')->default(true);

            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

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
