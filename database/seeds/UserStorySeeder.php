<?php

use Illuminate\Database\Seeder;
use Carbon\Carbon;
use App\Models\Role;
use App\Models\User;

class UserStorySeeder extends BaseSeeder
{

    public function runFake()
    {
        //DB::table('roles')->truncate();
        DB::table('users')->truncate();

        $roles = [
            [
                'name' => 'Admin',
            ],
            [
                'name' => 'Customer',
            ]
        ];

        // foreach ($roles as $q) {
        //     Role::create(['name' => $q['name'],'guard_name' => 'web']);
        // }
/*
        foreach ($roles as $q) {
            Role::create(['name' => $q['name'],'guard_name' => 'web']);
        }*/

        $users = [
            [
                'name' => 'Admin',
                'email' => 'admin@gmail.com',
                'password' => bcrypt('12345678')
            ]
        ];

        foreach ($users as $q) {
            $user = User::create([
                'name' => $q['name'],
                'email' => $q['email'],
                'password' => $q['password'],
                'email_verified_at' => Carbon::now()->toDateTimeString()
            ]);

            $roles = Role::all();
            foreach($roles as $role){
                $user->assignRole(Role::findByName($role->name,'web'));
                break;
            }
        }
    }
}
