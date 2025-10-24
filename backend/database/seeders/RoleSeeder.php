<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Admin',
                'slug' => 'admin',
                'level' => 5,
                'description' => 'Full system access across all departments',
                'permissions' => [
                    'manage_users',
                    'manage_roles',
                    'manage_departments',
                    'view_all_conversations',
                    'assign_conversations',
                    'manage_settings',
                    'view_analytics',
                    'manage_broadcasts',
                ],
            ],
            [
                'name' => 'Operation Manager',
                'slug' => 'operation_manager',
                'level' => 4,
                'description' => 'Cross-department oversight and reporting',
                'permissions' => [
                    'view_all_conversations',
                    'view_analytics',
                    'assign_conversations',
                    'manage_broadcasts',
                ],
            ],
            [
                'name' => 'Manager',
                'slug' => 'manager',
                'level' => 3,
                'description' => 'Department management and team assignment',
                'permissions' => [
                    'view_department_conversations',
                    'assign_conversations',
                    'manage_team',
                    'view_department_analytics',
                    'manage_broadcasts',
                ],
            ],
            [
                'name' => 'Assistant Manager',
                'slug' => 'assistant_manager',
                'level' => 2,
                'description' => 'Sub-team supervision and escalation handling',
                'permissions' => [
                    'view_department_conversations',
                    'assign_conversations',
                    'handle_escalations',
                    'view_department_analytics',
                ],
            ],
            [
                'name' => 'CX Agent',
                'slug' => 'cx',
                'level' => 1,
                'description' => 'Handle assigned customer conversations',
                'permissions' => [
                    'view_assigned_conversations',
                    'send_messages',
                    'add_notes',
                    'add_tags',
                ],
            ],
        ];

        foreach ($roles as $role) {
            Role::create($role);
        }
    }
}
