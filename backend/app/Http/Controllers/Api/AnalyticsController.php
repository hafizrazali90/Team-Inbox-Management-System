<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    /**
     * Get dashboard summary analytics.
     */
    public function summary(Request $request)
    {
        $user = $request->user();
        $departmentId = $user->isAdmin() || $user->isOperationManager()
            ? $request->query('department_id')
            : $user->department_id;

        $today = Carbon::today();

        // Base query with department filter
        $conversationsQuery = Conversation::query();
        if ($departmentId) {
            $conversationsQuery->where('department_id', $departmentId);
        }

        // Total chats today
        $totalChatsToday = (clone $conversationsQuery)
            ->whereDate('created_at', $today)
            ->count();

        // Open vs Closed chats
        $openChats = (clone $conversationsQuery)
            ->where('status', 'open')
            ->count();

        $closedChats = (clone $conversationsQuery)
            ->where('status', 'closed')
            ->count();

        $pendingChats = (clone $conversationsQuery)
            ->where('status', 'pending')
            ->count();

        // Average response time (in minutes)
        $avgResponseTime = (clone $conversationsQuery)
            ->whereNotNull('first_response_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time')
            ->value('avg_time') ?? 0;

        // Follow-up count (pending follow-ups)
        $followUpCount = (clone $conversationsQuery)
            ->whereNotNull('follow_up_at')
            ->where('follow_up_at', '>=', now())
            ->where('status', 'pending')
            ->count();

        // Chats per agent (top 5)
        $chatsPerAgent = (clone $conversationsQuery)
            ->select('assigned_to', DB::raw('COUNT(*) as chat_count'))
            ->whereNotNull('assigned_to')
            ->groupBy('assigned_to')
            ->with('assignedUser:id,name,avatar')
            ->orderBy('chat_count', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($item) {
                return [
                    'user_id' => $item->assigned_to,
                    'user_name' => $item->assignedUser?->name ?? 'Unknown',
                    'user_avatar' => $item->assignedUser?->avatar,
                    'chat_count' => $item->chat_count,
                ];
            });

        // AI performance metrics
        $aiHandledCount = (clone $conversationsQuery)
            ->where('is_ai_handled', true)
            ->count();

        $totalConversations = (clone $conversationsQuery)->count();
        $aiHandoffRate = $totalConversations > 0
            ? round(($aiHandledCount / $totalConversations) * 100, 2)
            : 0;

        // Response time trend (last 7 days)
        $responseTimeTrend = (clone $conversationsQuery)
            ->whereNotNull('first_response_at')
            ->where('created_at', '>=', Carbon::now()->subDays(7))
            ->selectRaw('DATE(created_at) as date, AVG(TIMESTAMPDIFF(MINUTE, created_at, first_response_at)) as avg_time')
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        return response()->json([
            'summary' => [
                'total_chats_today' => $totalChatsToday,
                'open_chats' => $openChats,
                'closed_chats' => $closedChats,
                'pending_chats' => $pendingChats,
                'avg_response_time_minutes' => round($avgResponseTime, 2),
                'follow_up_count' => $followUpCount,
                'ai_handoff_rate' => $aiHandoffRate,
            ],
            'chats_per_agent' => $chatsPerAgent,
            'response_time_trend' => $responseTimeTrend,
        ]);
    }

    /**
     * Get detailed agent performance.
     */
    public function agentPerformance(Request $request)
    {
        $user = $request->user();
        $departmentId = $user->isAdmin() || $user->isOperationManager()
            ? $request->query('department_id')
            : $user->department_id;

        $agents = User::where('department_id', $departmentId)
            ->whereHas('role', function ($q) {
                $q->where('slug', 'cx');
            })
            ->withCount([
                'assignedConversations as total_conversations',
                'assignedConversations as open_conversations' => function ($q) {
                    $q->where('status', 'open');
                },
                'assignedConversations as closed_conversations' => function ($q) {
                    $q->where('status', 'closed');
                },
            ])
            ->get();

        return response()->json([
            'agents' => $agents,
        ]);
    }
}
