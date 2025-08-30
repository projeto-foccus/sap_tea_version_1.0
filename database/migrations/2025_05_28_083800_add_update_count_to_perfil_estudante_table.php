<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddUpdateCountToPerfilEstudanteTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        if (!Schema::hasColumn('perfil_estudante', 'update_count')) {
            Schema::table('perfil_estudante', function (Blueprint $table) {
                $table->integer('update_count')->default(0)->after('fk_id_aluno');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        if (Schema::hasColumn('perfil_estudante', 'update_count')) {
            Schema::table('perfil_estudante', function (Blueprint $table) {
                $table->dropColumn('update_count');
            });
        }
    }
}
