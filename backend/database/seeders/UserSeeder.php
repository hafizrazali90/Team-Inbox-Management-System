<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Role;
use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cxDepartment = Department::where('slug', 'cx')->first();
        $adminRole = Role::where('slug', 'admin')->first();
        $managerRole = Role::where('slug', 'manager')->first();
        $cxRole = Role::where('slug', 'cx')->first();

        // Admin user
        User::create([
            'name' => 'Admin User',
            'email' => 'admin@tims.local',
            'password' => Hash::make('password123'),
            'role_id' => $adminRole->id,
            'department_id' => $cxDepartment->id,
            'phone' => '+60123456789',
            'is_active' => true,
        ]);

        // Manager user
        User::create([
            'name' => 'Sarah Johnson',
            'email' => 'sarah.manager@tims.local',
            'password' => Hash::make('password123'),
            'role_id' => $managerRole->id,
            'department_id' => $cxDepartment->id,
            'phone' => '+60123456790',
            'is_active' => true,
        ]);

        // Sample CX agents
        $cxAgents = [
            [
                'name' => 'Alice Wong',
                'email' => 'alice.wong@tims.local',
                'phone' => '+60123456791',
            ],
            [
                'name' => 'Bob Chen',
                'email' => 'bob.chen@tims.local',
                'phone' => '+60123456792',
            ],
            [
                'name' => 'Carol Tan',
                'email' => 'carol.tan@tims.local',
                'phone' => '+60123456793',
            ],
            [
                'name' => 'David Kumar',
                'email' => 'david.kumar@tims.local',
                'phone' => '+60123456794',
            ],
            [
                'name' => 'Emma Lee',
                'email' => 'emma.lee@tims.local',
                'phone' => '+60123456795',
            ],
        ];

        foreach ($cxAgents as $agent) {
            User::create([
                'name' => $agent['name'],
                'email' => $agent['email'],
                'password' => Hash::make('password123'),
                'role_id' => $cxRole->id,
                'department_id' => $cxDepartment->id,
                'phone' => $agent['phone'],
                'is_active' => true,
            ]);
        }
    }
}
