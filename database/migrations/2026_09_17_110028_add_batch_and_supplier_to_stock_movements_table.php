<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->string('batch_number', 50)->nullable()->after('quantity');
            $table->date('batch_expiry_date')->nullable()->after('batch_number');
            $table->foreignId('supplier_id')
                ->nullable()
                ->after('batch_expiry_date')
                ->constrained('suppliers')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
        });
    }

    public function down(): void
    {
        Schema::table('stock_movements', function (Blueprint $table) {
            $table->dropForeign(['supplier_id']);
            $table->dropColumn(['batch_number', 'batch_expiry_date', 'supplier_id']);
        });
    }
};
