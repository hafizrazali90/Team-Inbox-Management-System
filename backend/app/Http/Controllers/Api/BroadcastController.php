<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Broadcast;
use App\Models\BroadcastContact;
use App\Models\ActivityLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BroadcastController extends Controller
{
    /**
     * Get all broadcasts.
     */
    public function index(Request $request)
    {
        $user = $request->user();
        $query = Broadcast::with(['department', 'creator']);

        // Filter by department if not admin/OM
        if (!$user->isAdmin() && !$user->isOperationManager()) {
            $query->where('department_id', $user->department_id);
        }

        return response()->json([
            'broadcasts' => $query->orderBy('created_at', 'desc')->paginate(20),
        ]);
    }

    /**
     * Create a new broadcast.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'message_content' => 'required|string',
            'recipient_type' => 'required|in:individual,group,csv,tag',
            'recipients' => 'required_if:recipient_type,individual|array',
            'recipients.*.phone_number' => 'required_with:recipients|string',
            'recipients.*.contact_name' => 'nullable|string',
            'csv_file' => 'required_if:recipient_type,csv|file|mimes:csv,txt',
            'tag_id' => 'required_if:recipient_type,tag|exists:tags,id',
            'scheduled_at' => 'nullable|date|after:now',
        ]);

        $user = $request->user();

        $broadcast = Broadcast::create([
            'department_id' => $user->department_id,
            'created_by' => $user->id,
            'name' => $request->name,
            'message_content' => $request->message_content,
            'recipient_type' => $request->recipient_type,
            'status' => $request->scheduled_at ? 'scheduled' : 'draft',
            'scheduled_at' => $request->scheduled_at,
        ]);

        // Process recipients
        $contacts = [];

        if ($request->recipient_type === 'individual' && $request->has('recipients')) {
            foreach ($request->recipients as $recipient) {
                $contacts[] = [
                    'broadcast_id' => $broadcast->id,
                    'phone_number' => $recipient['phone_number'],
                    'contact_name' => $recipient['contact_name'] ?? null,
                    'status' => 'pending',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
        } elseif ($request->recipient_type === 'csv' && $request->hasFile('csv_file')) {
            $file = $request->file('csv_file');
            $csvData = array_map('str_getcsv', file($file->getRealPath()));

            foreach ($csvData as $row) {
                if (isset($row[0]) && !empty($row[0])) {
                    $contacts[] = [
                        'broadcast_id' => $broadcast->id,
                        'phone_number' => $row[0],
                        'contact_name' => $row[1] ?? null,
                        'status' => 'pending',
                        'created_at' => now(),
                        'updated_at' => now(),
                    ];
                }
            }
        }

        if (!empty($contacts)) {
            BroadcastContact::insert($contacts);
            $broadcast->update(['total_recipients' => count($contacts)]);
        }

        // Log activity
        ActivityLog::create([
            'user_id' => $user->id,
            'action' => 'create_broadcast',
            'entity_type' => 'Broadcast',
            'entity_id' => $broadcast->id,
            'description' => "Created broadcast: {$broadcast->name}",
            'ip_address' => $request->ip(),
        ]);

        return response()->json([
            'message' => 'Broadcast created successfully',
            'broadcast' => $broadcast->load('contacts'),
        ], 201);
    }

    /**
     * Get broadcast details with contacts.
     */
    public function show($id)
    {
        $broadcast = Broadcast::with(['contacts', 'department', 'creator'])->findOrFail($id);

        return response()->json([
            'broadcast' => $broadcast,
        ]);
    }

    /**
     * Send broadcast (placeholder - actual sending would be queued).
     */
    public function send(Request $request, $id)
    {
        $broadcast = Broadcast::findOrFail($id);

        // Update status to sending
        $broadcast->update([
            'status' => 'sending',
            'started_at' => now(),
        ]);

        // In production, this would dispatch a job to send messages in batches
        // dispatch(new SendBroadcastJob($broadcast));

        return response()->json([
            'message' => 'Broadcast is being sent',
            'broadcast' => $broadcast,
        ]);
    }
}
