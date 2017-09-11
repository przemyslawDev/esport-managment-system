<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Role;

class AddAdminAndGuestUsers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('users')->truncate();

        $guest_role = Role::where('name', 'guest')->first();
        $system_admin_role = Role::where('name', 'system_admin')->first();

        $user = new User();

        $user->email = 'user@example.com';
        $user->password = bcrypt('secret');
        $user->active = true;
        $user->save();

        $user->attachRole($guest_role);

        $admin = new User();
        $admin->email = 'admin@example.com';
        $admin->password = bcrypt('secret');
        $admin->active = true;
        $admin->save();

        $admin->attachRole($system_admin_role);
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
