==============================
TIMS FUNCTIONAL SPECIFICATION — PHASE 1 (MVP)

Scope:
TIMS MVP focuses on the CX team.
Goal: Replace multiple personal WhatsApp accounts with one shared inbox under a single Meta number, while preserving role hierarchy, assignment, and visibility.

Core Modules

Unified Inbox:

Real-time WhatsApp chat (Cloud API webhook).

Message types: text, image, video, document, voice.

Internal notes tab.

Tagging system (New Lead, Payment, Tutor Request, Follow-Up).

Filters by status (Open, Pending, Closed).

Reassignment by Manager or AM.

Follow-up scheduler with reminders.

AI auto-reply handoff to CX agent.

Broadcast Module:

Send outbound messages to:

Individual (recipient number)

Group / Label filter

Uploaded CSV list

Uses approved WhatsApp templates.

Supports scheduling and tracking (future-ready report table).

User & Role Management:

Separate login for TIMS (no SIMS SSO).

Role permissions: Admin > OM > Mgr > AM > CX.

Department field = “CX” for MVP.

Managers and OM can view all chats across teams.

AI Integration (Sofia):

Handles off-hour auto-replies.

Detects FAQ patterns and routes to human agent.

Logs AI response ratio for analytics.

Editable prompt and knowledge base file for future training.

Dashboard & Analytics:
Admin Dashboard:

Total Chats Today

Open vs Closed Chats

Avg Response Time

Chats per Agent

Follow-up Queue
Manager Dashboard:

Team-specific stats

Agent performance leaderboard

Data Archival:

Daily cron to move >6 month records to archive table or S3.

Archive search panel under Admin.

APIs (Phase 1)

POST /api/webhook/whatsapp – Receive incoming messages
POST /api/messages/send – Send outbound message
GET /api/conversations – List conversations with filters
GET /api/conversations/{id} – Fetch chat history
POST /api/conversations/{id}/assign – Assign to user
POST /api/conversations/{id}/notes – Add note
POST /api/conversations/{id}/tags – Add tag
POST /api/broadcast/send – Broadcast to group/CSV
GET /api/analytics/summary – Dashboard summary data

Database Entities

Tables:
users, roles, departments, conversations, messages, notes, tags,
conversation_tags, broadcasts, broadcast_contacts, activity_logs,
ai_logs, archives

Performance & Security

Message Load: Optimized pagination + indexing for 100k monthly chats.
Target Latency: ≤1 second message delivery under 100 concurrent users.
Security: Laravel Sanctum auth + HTTPS.
Backup: Daily DB dump + S3 media replication.
Privacy: Only authorized roles can view chat content.

Future Phase Features

Phase 2 – SIMS integration (customer lookup API)
Phase 2 – TX department queue
Phase 3 – CR renewal team
Phase 3 – AI auto-tagging + intent classification
Phase 4 – Omni-channel integration (FB / IG / Email)

Brand & UI

Color: Blue–Teal gradient
Font: Inter
Tagline: “All Conversations. One Team. One Inbox.”
Layout: 3-column design (Sidebar / Chat List / Chat + Profile Panel)

=========================================================
END OF DOCUMENT
