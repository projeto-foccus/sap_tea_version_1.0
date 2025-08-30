<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePerfilProfissionalTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('perfil_profissional', function (Blueprint $table) {
            $table->id('id_perfil_profissional');
            $table->string('nome_profissional', 250)->nullable();
            $table->string('especialidade_profissional', 250)->nullable();
            $table->text('observacoes_profissional')->nullable();
            $table->unsignedBigInteger('fk_id_aluno')->nullable();
            $table->date('data_cadastro_profissional')->nullable();
            
            $table->foreign('fk_id_aluno')
                  ->references('id_aluno')
                  ->on('aluno')
                  ->onDelete('cascade');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('perfil_profissional');
    }
}
