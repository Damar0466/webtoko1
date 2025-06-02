<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('akuns', function (Blueprint $table) {
            $table->enum('role', ['admin', 'customer'])->default('customer');
        });
    }

    public function down()
    {
        Schema::table('akuns', function (Blueprint $table) {
            $table->dropColumn('role');
        });
    }
};