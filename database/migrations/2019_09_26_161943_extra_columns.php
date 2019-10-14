<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ExtraColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('escalations', function($table){
            $table->string('email');
            $table->integer('level');
            $table->string('location');
            $table->string('format');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('escalations', function($table){
            $table->dropColumn('email');
            $table->dropColumn('level');
            $table->dropColumn('location');
            $table->dropColumn('format');
        });
    }
}
