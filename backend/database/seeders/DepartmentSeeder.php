<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $departments = [
            [
                'name' => 'Customer Experience',
                'slug' => 'cx',
                'description' => 'Handles customer inquiries, support, and lead management',
                'is_active' => true,
            ],
            [
                'name' => 'Tutor Experience',
                'slug' => 'tx',
                'description' => 'Manages tutor onboarding, support, and engagement (Phase 2)',
                'is_active' => false, // Not active in Phase 1
            ],
            [
                'name' => 'Customer Retention',
                'slug' => 'cr',
                'description' => 'Focuses on customer renewals and retention strategies (Phase 3)',
                'is_active' => false, // Not active in Phase 1
            ],
        ];

        foreach ($departments as $department) {
            Department::create($department);
        }
    }
}
