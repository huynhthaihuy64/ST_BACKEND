<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFieldTypeFormsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('field_types_forms', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('typeform_id')->unsigned();
            $table->string('label');
            $table->string('description')->nullable();
            $table->string('value')->nullable();
            $table->string('index');
            $table->integer('require')->unsigned();
            $table->string('type');
            $table->string('name');
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
        Schema::dropIfExists('field_types_forms');
    }
}
