<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('pastagem', function (Blueprint $table) {
            $table->id();
            $table->integer('capacidade_suporte');
            $table->decimal('quantidade_forragem');
            $table->integer('dias_recuperacao');
            $table->decimal('forragem_disponivel')->nullable(true);
        });
    }

    public function down(): void{
        Schema::dropIfExists('pastagem');
    }
};
