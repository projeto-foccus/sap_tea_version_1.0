<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('funcionario', function (Blueprint $table) {
            $table->boolean('precisa_trocar_senha')->default(true)->after('password');
        });
    }

    public function down()
    {
        Schema::table('funcionario', function (Blueprint $table) {
            $table->dropColumn('precisa_trocar_senha');
        });
    }
};
