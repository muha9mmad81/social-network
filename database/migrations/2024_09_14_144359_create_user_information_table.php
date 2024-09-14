<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_information', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('dob')->nullable();
            $table->string('dob_visibility')->default('everyone');
            $table->string('gender')->nullable();
            $table->string('gender_visibility')->default('everyone');
            $table->string('city')->nullable();
            $table->string('city_visibility')->default('everyone');
            $table->string('country')->nullable();
            $table->string('country_visibility')->default('everyone');
            $table->string('profile_image')->nullable();
            $table->string('cover_image')->nullable();
            $table->text('about')->nullable();
            $table->string('about_visibility')->default('everyone');
            $table->text('link1')->nullable();
            $table->string('link1_visibility')->default('everyone');
            $table->text('link2')->nullable();
            $table->string('link2_visibility')->default('everyone');
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
        Schema::dropIfExists('user_information');
    }
};
