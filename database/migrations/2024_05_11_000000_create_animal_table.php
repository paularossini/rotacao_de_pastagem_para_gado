<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration{
    public function up(): void{
        Schema::create('animal', function (Blueprint $table) {
            $table->id();
            $table->decimal('peso');
            $table->integer('idade');
            $table->decimal('necessidade_nutricional');
            $table->unsignedBigInteger('pastagem_atual')->nullable();
        });
    }

    public function down(): void{
        Schema::dropIfExists('animal');
    }
};
