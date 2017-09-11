<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Role;

class CreateGuestAndAdminRole extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $guest = new Role();
        $guest->name = 'guest';
        $guest->display_name = 'Guest';
        $guest->description = 'This is the guest user.';
        $guest->save();

        $system_admin = new Role();
        $system_admin->name = 'system_admin';
        $system_admin->display_name = 'System Administrator';
        $system_admin->description = 'This is the system admin.';
        $system_admin->save();
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
