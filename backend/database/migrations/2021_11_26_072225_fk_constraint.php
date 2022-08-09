<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class FkConstraint extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('forms', function (Blueprint $table) {;
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });

        Schema::table('employees', function (Blueprint $table) {;
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });

        Schema::table('field_types_forms', function (Blueprint $table) {;
            $table->foreign('typeform_id')->references('id')->on('forms')->onDelete('cascade');
        });

        Schema::table('employees_technologies', function (Blueprint $table) {;
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
            $table->foreign('technology_id')->references('id')->on('technologies')->onDelete('cascade');
        });

        Schema::table('profiles', function (Blueprint $table) {;
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
        });

        Schema::table('profiles_technologies', function (Blueprint $table) {;
            $table->foreign('profile_id')->references('id')->on('profiles')->onDelete('cascade');
            $table->foreign('technology_id')->references('id')->on('technologies')->onDelete('cascade');
        });

        Schema::table('campaigns_technologies', function (Blueprint $table) {;
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('technology_id')->references('id')->on('technologies')->onDelete('cascade');
        });

        Schema::table('campaigns', function (Blueprint $table) {;
            $table->foreign('employee_id')->references('id')->on('employees')->onDelete('cascade');
        });

        Schema::table('campaigns_positions', function (Blueprint $table) {;
            $table->foreign('campaign_id')->references('id')->on('campaigns')->onDelete('cascade');
            $table->foreign('position_id')->references('id')->on('positions')->onDelete('cascade');
        });        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
