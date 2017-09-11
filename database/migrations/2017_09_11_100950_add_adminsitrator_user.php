<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Role;

class AddAdminsitratorUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = new Role();
        $role->name = 'admin';
        $role->display_name = 'Administrator';
        $role->description = 'This is administrator.';
        $role->save();

        $user = new User();
        $user->email = 'administrator@example.com';
        $user->password = bcrypt('secret');
        $user->active = true;
        $user->save();

        $user->attachRole($role);
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
