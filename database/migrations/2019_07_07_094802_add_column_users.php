<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnUsers extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->integer('user_role_id')->default(0);
            $table->string('caption')->nullable()->default(null);
            $table->integer('crew')->default(0);
        });
    }
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('user_role_id');
            $table->dropColumn('caption');
            $table->dropColumn('crew');
        });
    }
}
