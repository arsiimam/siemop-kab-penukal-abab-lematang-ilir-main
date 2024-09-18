<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;

class PermissionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'Manage Role',
            'Create Role',
            'Edit Role',
            'Delete Role',
            'Manage User',
            'Create User',
            'Edit User',
            'Delete User',
            'Manage Permission',
            'Create Permission',
            'Edit Permission',
            'Delete Permission',
            'Manage Setting'
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }
    }
}
