<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('plano_rotacao', function (Blueprint $table) {
            $table->id();
            $table->integer('dia')->nullable(true);
            $table->unsignedBigInteger('pastagem_id');
            $table->json('animais');
            $table->integer('qtd_animal');
            $table->decimal('forragem_disponivel');
        });
    }

    public function down(): void{
        Schema::dropIfExists('plano_rotacao');
    }
};
