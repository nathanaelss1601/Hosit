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
        // Remove table_id column from transactions first (if exists)
        Schema::table('transactions', function (Blueprint $table) {
            if (Schema::hasColumn('transactions', 'table_id')) {
                // drop foreign key constraint if applicable, then column
                try {
                    $table->dropForeign(['table_id']);
                } catch (\Throwable $e) {
                    // ignore if FK not present
                }
                $table->dropColumn('table_id');
            }
        });

        // Drop the tables table
        if (Schema::hasTable('tables')) {
            Schema::dropIfExists('tables');
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::create('tables', function (Blueprint $table) {
            $table->id();
            $table->string('table_number')->unique();
            $table->integer('capacity');
            $table->enum('status', ['available', 'occupied', 'reserved'])->default('available');
            $table->timestamps();
        });

        Schema::table('transactions', function (Blueprint $table) {
            $table->foreignId('table_id')->nullable()->constrained()->onDelete('set null');
        });
    }
};
