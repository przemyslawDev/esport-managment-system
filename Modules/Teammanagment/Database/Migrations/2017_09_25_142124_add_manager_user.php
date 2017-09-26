<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\User;
use App\Role;

class AddManagerUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $role = new Role();
        $role->name = 'manager';
        $role->display_name = 'Manager';
        $role->description = 'This is manager.';
        $role->save();

        $user = new User();
        $user->email = 'manager@example.com';
        $user->password = bcrypt('secret');
        $user->active = true;
        $user->confirmed = true;
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
        
    }
}
