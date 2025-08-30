<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCamposInteracaoToPerfilEstudanteTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('perfil_estudante', function (Blueprint $table) {
            if (!Schema::hasColumn('perfil_estudante', 'contato_pc_04')) {
                $table->text('contato_pc_04')->nullable();
            }
            if (!Schema::hasColumn('perfil_estudante', 'reage_contato')) {
                $table->text('reage_contato')->nullable();
            }
            if (!Schema::hasColumn('perfil_estudante', 'interacao_escola_04')) {
                $table->text('interacao_escola_04')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('perfil_estudante', function (Blueprint $table) {
            if (Schema::hasColumn('perfil_estudante', 'contato_pc_04')) {
                $table->dropColumn('contato_pc_04');
            }
            if (Schema::hasColumn('perfil_estudante', 'reage_contato')) {
                $table->dropColumn('reage_contato');
            }
            if (Schema::hasColumn('perfil_estudante', 'interacao_escola_04')) {
                $table->dropColumn('interacao_escola_04');
            }
        });
    }
}
