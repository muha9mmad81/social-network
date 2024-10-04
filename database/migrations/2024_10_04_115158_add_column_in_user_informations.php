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
        Schema::table('user_information', function (Blueprint $table) {
            $table->enum('group_invite', ['all', 'friends'])->after('about_visibility')->default('all')->comment('who can send you the group invite');
            $table->enum('profile_visibiltiy', ['public', 'friends', 'private', 'logged_in'])->after('user_id')->default('public');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_information', function (Blueprint $table) {
            $table->dropColumn('group_invite');
            $table->dropColumn('profile_visibiltiy');
        });
    }
};
