<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Define permissions
        $permissions = [
            'manage_users',
            'approve_offers',
            'review_requests',
            'manage_files',
            'upload_media',
            'request_review',
            'receive_offers',
            'send_offers',
            'view_talents',
            'review_talents',
            'award_achievements',
        ];

        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission, 'guard_name' => 'api']);
        }

        // Define roles and assign permissions
        $roles = [
            'Admin' => ['manage_users', 'approve_offers', 'review_requests', 'manage_files'],
            'Talent' => ['upload_media', 'request_review', 'receive_offers'],
            'Investor' => ['send_offers', 'view_talents'],
            'Mentor' => ['review_talents', 'award_achievements'],
        ];

        foreach ($roles as $roleName => $rolePermissions) {
            $role = Role::create(['name' => $roleName, 'guard_name' => 'api']);
            $role->givePermissionTo($rolePermissions);
        }
    }
}
