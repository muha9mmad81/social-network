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
        Schema::create('groups', function (Blueprint $table) {
            $table->id();
            $table->text('name');
            $table->text('description');
            $table->enum('privacy', ['public', 'private', 'hidden'])->default('public');
            $table->enum('invitation', ['all', 'admin & mod', 'admin'])->default('all');
            $table->enum('album', ['all', 'admin & mod', 'admin'])->default('all');
            $table->integer('forum')->default(0)->comment('0 for no & 1 for yes');
            $table->integer('created_by');
            $table->string('image')->nullable();
            $table->string('cover_image')->nullable();
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
        Schema::dropIfExists('groups');
    }
};
