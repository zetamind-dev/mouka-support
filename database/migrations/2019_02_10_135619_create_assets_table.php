<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name', 60);
            $table->integer('user_id')->unsigned();
            $table->string('email', 80);
            $table->string('employeeno',10);
            $table->string('computer_type', 50);
            $table->string('laptop_name', 50);
            $table->string('laptop_model', 50);
            $table->string('laptop_serial_no', 50);
            $table->string('laptop_duration',50);
            $table->text('remark');
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
        Schema::dropIfExists('assets');
    }
}
