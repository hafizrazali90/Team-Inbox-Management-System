==============================
TIMS ARCHITECTURE BLUEPRINT (v1.0)
System Name: TIMS (Team Inbox Management System)
Purpose: Centralize all WhatsApp customer and tutor communication under one shared inbox for the SifuTutor ecosystem.
Core Goal: Replace 50+ personal WhatsApp accounts with a unified, role-based, AI-assisted system for the CX team (Phase 1), later expandable to TX and CR teams.
System Structure
Frontend: React + Tailwind CSS
Backend: Laravel 10 (REST API + WebSockets)
Database: MySQL 8
Real-Time Engine: Laravel WebSockets / Pusher
File Storage: AWS S3 or DigitalOcean Spaces (configurable)
Messaging API: Meta WhatsApp Cloud API
AI Layer: “Sofia” conversational assistant via OpenAI API (auto-response + handoff)
Core Modules
Inbox Module: Unified WhatsApp chat interface for CX team. Real-time updates, assignment, tagging, follow-up scheduler, and internal notes.
Broadcast Module: Send template or custom messages via group, label, or CSV upload. Future scheduling support included.
CRM Integration Module: Connects with SIMS API (Phase 2). Pulls customer/tutor profile and pushes communication logs.
AI Assistant Module: Handles off-hour responses, FAQs, and lead qualification. Auto-handoff to CX agent when required.
Analytics Module: Tracks CX performance metrics, AI impact, response times, and follow-up rate.
Admin Module: User management, role control, department setup, system settings, and webhook configuration.
Departments & Teams
Phase 1: CX (Customer Experience)
Phase 2: TX (Tutor Experience)
Phase 3: CR (Customer Retention)
Each department operates as a separate “Team” inside TIMS, with isolated queues but shared dashboards for Admin and Operations Manager.
Roles & Permissions
Admin – All Teams – Full system control, user creation, settings, analytics.
Operation Manager – All Teams – Department oversight + reports.
Manager – Own Team – Assign/reassign chats, manage queues.
Assistant Manager – Own Team – Monitor agents, handle escalations.
CX Agent – Assigned Chats Only – Reply to customers, add notes, set follow-ups.
Data Architecture (Simplified)
Users → Departments
Departments → Conversations
Conversations → Messages
Conversations → Notes
Conversations ↔ Tags
Campaigns → CampaignContacts
ActivityLogs tracks all user actions
Data Storage & Retention
Messages: MySQL (primary), archive >6 months to archive_messages or S3 (JSON).
Media: Stored on S3 or Spaces (bucket per team).
Retention Policy: Active 6 months → archive. Logs older than 12 months may be auto-purged to maintain database performance.
All archived data remains searchable via admin panel.
Analytics Metrics
Response Speed:
First Response Time – Avg delay between inbound msg and first reply.
Avg Response Time – Mean response interval across conversation.
Volume:
New Conversations – Initiated by CX daily.
Closed Conversations – Marked resolved.
Workload:
Chats per Agent – Daily handled count.
Follow-ups:
Pending Chats – Tagged “Follow-up”.
AI Performance:
AI Handoff Rate – % auto-handled by Sofia.
Branding
Color Palette: Blue / Teal (primary), White (background)
Font: Inter
Login Tagline: “All Conversations. One Team. One Inbox.”
=========================================================
END OF DOCUMENT
