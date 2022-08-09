<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('position_id')->unsigned();
            $table->string('name');
            $table->string('email');
            $table->string('address');
            $table->char('phone');
            $table->date('birthday');
            $table->string('experience');
            $table->string('cv');
            $table->string('role');
            $table->longText('description');
            $table->string('avatar');
            $table->string('status');
            $table->date('start_date');
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
        Schema::dropIfExists('employees');
    }
}
