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
        Schema::create('cities', function (Blueprint $table) {
            $table->id();

            $table->string('name');
            $table->string('zip_code', 8); // String porque pode conter zeros à esquerda
            $table->char('ddd', 2);
            $table->string('ibge_code', 7); // String porque pode conter zeros à esquerda
            
            // Relacionamento com a tabela de UFs
            $table->foreignId('uf_id')->constrained('ufs')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cities');
    }
};
