<?php

use App\Enums\ProfileStatusType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('campaign_id')->unsigned();
            $table->string('email');
            $table->string('name');
            $table->char('phone');
            $table->enum('status', ProfileStatusType::getValues())->default(ProfileStatusType::NEWS);
            $table->string('cv');
            $table->longText('description')->nullable();
            $table->string('avatar')->nullable();
            $table->string('other')->nullable();
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
        Schema::dropIfExists('profiles');
    }
}
