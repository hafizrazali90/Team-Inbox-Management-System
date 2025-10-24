<?php

namespace Database\Seeders;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Department;
use App\Models\User;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class ConversationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        $cxDepartment = Department::where('slug', 'cx')->first();
        $cxAgents = User::whereHas('role', function ($q) {
            $q->where('slug', 'cx');
        })->get();

        // Create 10 sample conversations
        for ($i = 0; $i < 10; $i++) {
            $assignedAgent = $cxAgents->random();
            $status = $faker->randomElement(['open', 'pending', 'closed']);

            $conversation = Conversation::create([
                'whatsapp_id' => '+6019' . $faker->numberBetween(1000000, 9999999),
                'contact_name' => $faker->name,
                'contact_phone' => '+6019' . $faker->numberBetween(1000000, 9999999),
                'department_id' => $cxDepartment->id,
                'assigned_to' => $assignedAgent->id,
                'status' => $status,
                'last_message_at' => $faker->dateTimeBetween('-7 days', 'now'),
                'first_response_at' => $faker->optional(0.7)->dateTimeBetween('-7 days', 'now'),
                'response_count' => $faker->numberBetween(1, 10),
                'is_ai_handled' => $faker->boolean(20), // 20% AI handled
            ]);

            // Create 3-8 messages for each conversation
            $messageCount = $faker->numberBetween(3, 8);
            for ($j = 0; $j < $messageCount; $j++) {
                $isInbound = $j % 2 === 0; // Alternate between inbound and outbound

                Message::create([
                    'conversation_id' => $conversation->id,
                    'whatsapp_message_id' => 'wamid.' . $faker->uuid,
                    'direction' => $isInbound ? 'inbound' : 'outbound',
                    'type' => 'text',
                    'content' => $faker->sentence($faker->numberBetween(5, 15)),
                    'sender_id' => $isInbound ? null : $assignedAgent->id,
                    'status' => 'delivered',
                    'created_at' => $faker->dateTimeBetween('-7 days', 'now'),
                ]);
            }
        }
    }
}
