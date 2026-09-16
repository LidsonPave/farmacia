<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medicine_id')
                ->constrained('medicines')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->foreignId('user_id')
                ->constrained('users')
                ->restrictOnDelete()
                ->cascadeOnUpdate();
            $table->enum('type', ['entrada', 'saida']);
            $table->integer('quantity')->unsigned();
            $table->enum('reason', ['compra', 'venda', 'ajuste', 'devolucao', 'perda']);
            $table->string('reference', 100)->nullable();
            $table->timestamps();

            $table->index('type');
            $table->index('reference');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('stock_movements');
    }
};
