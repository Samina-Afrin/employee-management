<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\PermissionRegistrar;
use App\Models\User;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        app()[PermissionRegistrar::class]->forgetCachedPermissions();

        // 2. Disable foreign key checks and truncate tables to reset auto-increment IDs
        Schema::disableForeignKeyConstraints();
        
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        Permission::truncate();
        Role::truncate();
        
        Schema::enableForeignKeyConstraints();

        Permission::create(['name' => 'create post']);
        Permission::create(['name' => 'edit post']);

        $adminRole = Role::create(['name' => 'admin']);
        $adminRole->givePermissionTo('create post');
        $adminRole->givePermissionTo('edit post');


        $user=User::first();
        if($user){
            $user->assignRole('admin');
        }
    }
}
