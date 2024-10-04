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
        Schema::create('user_email_notification_preferences', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->enum('activity_mention', ['Yes', 'No'])->default('Yes');
            $table->enum('activity_replies', ['Yes', 'No'])->default('Yes');
            $table->enum('message', ['Yes', 'No'])->default('Yes');
            $table->enum('membership_invitation', ['Yes', 'No'])->default('Yes');
            $table->enum('send_friend_request', ['Yes', 'No'])->default('Yes');
            $table->enum('accept_friend_request', ['Yes', 'No'])->default('Yes');
            $table->enum('group_invitation', ['Yes', 'No'])->default('Yes');
            $table->enum('group_info_update', ['Yes', 'No'])->default('Yes');
            $table->enum('group_administrator_mod', ['Yes', 'No'])->default('Yes');
            $table->enum('join_private_group', ['Yes', 'No'])->default('Yes');
            $table->enum('group_request', ['Yes', 'No'])->default('Yes');
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
        Schema::dropIfExists('user_email_notification_preferences');
    }
};
