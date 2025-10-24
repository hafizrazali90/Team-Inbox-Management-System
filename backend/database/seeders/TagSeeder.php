<?php

namespace Database\Seeders;

use App\Models\Tag;
use App\Models\Department;
use Illuminate\Database\Seeder;

class TagSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cxDepartment = Department::where('slug', 'cx')->first();

        $tags = [
            [
                'name' => 'New Lead',
                'slug' => 'new-lead',
                'color' => '#10B981', // Green
                'department_id' => $cxDepartment->id,
            ],
            [
                'name' => 'Payment Issue',
                'slug' => 'payment-issue',
                'color' => '#EF4444', // Red
                'department_id' => $cxDepartment->id,
            ],
            [
                'name' => 'Tutor Request',
                'slug' => 'tutor-request',
                'color' => '#3B82F6', // Blue
                'department_id' => $cxDepartment->id,
            ],
            [
                'name' => 'Follow-Up',
                'slug' => 'follow-up',
                'color' => '#F59E0B', // Amber
                'department_id' => $cxDepartment->id,
            ],
            [
                'name' => 'Complaint',
                'slug' => 'complaint',
                'color' => '#DC2626', // Dark Red
                'department_id' => $cxDepartment->id,
            ],
            [
                'name' => 'Inquiry',
                'slug' => 'inquiry',
                'color' => '#8B5CF6', // Purple
                'department_id' => $cxDepartment->id,
            ],
            [
                'name' => 'Urgent',
                'slug' => 'urgent',
                'color' => '#F97316', // Orange
                'department_id' => $cxDepartment->id,
            ],
        ];

        foreach ($tags as $tag) {
            Tag::create($tag);
        }
    }
}
