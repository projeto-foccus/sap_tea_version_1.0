<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Comunicação
        Schema::table('cad_ativ_eixo_com_lin', function (Blueprint $table) {
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag']);
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro']);
            $table->unique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro'], 'unique_atividade_aluno_com_lin');
        });
        // Comportamento
        Schema::table('cad_ativ_eixo_comportamento', function (Blueprint $table) {
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag']);
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro']);
            $table->unique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro'], 'unique_atividade_aluno_comportamento');
        });
        // Socioemocional
        Schema::table('cad_ativ_eixo_int_socio', function (Blueprint $table) {
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag']);
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro']);
            $table->unique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro'], 'unique_atividade_aluno_int_socio');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Comunicação
        Schema::table('cad_ativ_eixo_com_lin', function (Blueprint $table) {
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro']);
            $table->unique(['aluno_id', 'cod_atividade', 'flag'], 'idx_unico_linha_com_lin');
        });
        // Comportamento
        Schema::table('cad_ativ_eixo_comportamento', function (Blueprint $table) {
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro']);
            $table->unique(['aluno_id', 'cod_atividade', 'flag'], 'idx_unico_linha_comportamento');
        });
        // Socioemocional
        Schema::table('cad_ativ_eixo_int_socio', function (Blueprint $table) {
            $table->dropUnique(['aluno_id', 'cod_atividade', 'flag', 'fase_cadastro']);
            $table->unique(['aluno_id', 'cod_atividade', 'flag'], 'idx_unico_linha_int_socio');
        });
    }
};
