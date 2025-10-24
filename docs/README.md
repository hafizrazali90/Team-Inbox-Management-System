==============================
TIMS README

Title: TIMS — Team Inbox Management System
Tagline: “All Conversations. One Team. One Inbox.”

Overview

TIMS is a centralized WhatsApp team inbox platform for SifuTutor’s Customer Experience (CX) team.
It replaces dozens of personal accounts with a shared Meta Cloud API integration and role-based web dashboard.
Future expansions include Tutor Experience (TX) and Customer Retention (CR).

Tech Stack

Backend: Laravel 10 + MySQL + Laravel WebSockets
Frontend: React 18 + Tailwind CSS
Messaging API: Meta WhatsApp Cloud API
Storage: AWS S3 / DigitalOcean Spaces
AI Layer: OpenAI API (Sofia AI auto-response + human handoff support)

Folder Structure

/docs - Specifications & architecture
/backend - Laravel API + migrations + webhooks
/frontend - React UI + components + Redux store
/scripts - Deployment & cron scripts
.env.example - Environment variables

Environment Variables

WHATSAPP_TOKEN=
WHATSAPP_PHONE_ID=
WHATSAPP_BUSINESS_ID=
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_BUCKET=
FRONTEND_URL=
BACKEND_URL=
OPENAI_API_KEY=

Setup Instructions

Backend:
cd backend
composer install
php artisan migrate
php artisan serve

Frontend:
cd frontend
npm install
npm run dev

WebSocket Server:
php artisan websockets:serve

Roles & Permissions

Admin – Full access (all teams)
Operation Manager – Cross-team overview
Manager – Department management & assignment
Assistant Manager – Sub-team supervision
CX – Handles assigned chats only

Key Features (MVP)

Real-time WhatsApp messaging

Tagging and follow-up scheduler

Broadcast to group / CSV / individual

Internal notes and assignment

AI assistant integration (Sofia)

Role-based permissions and analytics

Future Modules

SIMS CRM integration

TX and CR departments

AI auto-tagging and summaries

Omni-channel integration

Branding

Color Palette: Blue / Teal
Font: Inter
Tagline: “All Conversations. One Team. One Inbox.”
Logo: Simple “T chat-bubble” icon (optional)

=========================================================
END OF DOCUMENT