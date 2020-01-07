<?php

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Support\Str;

class PermissionTableSeeder extends BaseSeeder
{
    /**
     * Run fake seeds - for non production environments
     *
     * @return mixed
     */
    public function runFake() {

    }

    /**
     * Run seeds to be ran only on production environments
     *
     * @return mixed
     */
    public function runProduction() {

    }

    /**
     * Run seeds to be ran on every environment (including production)
     *
     * @return mixed
     */
    public function runAlways() {

        DB::table('role_has_permissions')->truncate();
        DB::table('permissions')->truncate();

       $permissions = [
           'role-list',
           'role-create',
           'role-edit',
           'role-delete'
        ];


        $ids = [];

        foreach ($permissions as $permission) {
            $p = Permission::create(['guard_name' => 'api','name' => $permission]);
            if(is_object($p)){
              $ids[] = $p->name;
            }
        }

        foreach ($permissions as $permission) {
            $p = Permission::create(['guard_name' => 'web','name' => $permission]);
            if(is_object($p)){
              $ids[] = $p->name;
            }
        }

        $roles = Role::all();
        foreach($roles as $role){
          $role->syncPermissions($ids);
        }

    }
}
