<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use Illuminate\Support\Facades\DB;

class UpdateUsersTableForWp extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {

            $table->string('password')->nullable()->change();
        });

        // Migrate from WP Users > Laravel Users
        DB::table('wp_users')
            ->orderBy('id')
            ->chunk(100, function ($wp_users) {

                foreach ($wp_users as $wp_user) {

                    // add new user (if doesn't exist)
                    User::firstOrCreate(
                        [
                            'email' => $wp_user->user_email,
                        ],
                        [
                            'name' => $wp_user->user_nicename,
                            'password' => $wp_user->user_pass
                        ]
                    );
                }
});

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
}



