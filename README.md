# TIMS - Team Inbox Management System

> **"All Conversations. One Team. One Inbox."**

TIMS is a centralized WhatsApp team inbox platform built for the SifuTutor ecosystem. It replaces 50+ personal WhatsApp accounts with a unified, role-based, AI-assisted system for customer experience management.

## Tech Stack

### Backend
- **Framework**: Laravel 10
- **Database**: MySQL 8
- **Real-time**: Laravel Broadcasting API with Pusher
- **Authentication**: Laravel Sanctum (JWT)
- **Storage**: AWS S3 / DigitalOcean Spaces
- **Messaging**: Meta WhatsApp Cloud API
- **AI**: OpenAI API (Sofia assistant)

### Frontend
- **Framework**: React 18
- **Build Tool**: Vite
- **Styling**: Tailwind CSS
- **State Management**: Redux Toolkit
- **UI Layout**: 3-column design (Sidebar / Chat List / Chat + Profile Panel)

## Project Structure

```
Team-Inbox-Management-System/
├── backend/              # Laravel API
│   ├── app/
│   │   ├── Http/
│   │   │   ├── Controllers/Api/
│   │   │   └── Middleware/
│   │   └── Models/
│   ├── config/
│   ├── database/
│   │   ├── migrations/
│   │   └── seeders/
│   ├── routes/
│   └── .env.example
├── frontend/             # React UI
│   ├── src/
│   │   ├── components/
│   │   ├── pages/
│   │   ├── store/
│   │   ├── services/
│   │   └── App.jsx
│   ├── public/
│   └── .env.example
├── docs/                 # Documentation
│   ├── tims-architecture-blueprint.md
│   ├── tims-functional-spec-v1.md
│   └── README.md
└── README.md            # This file
```

## Features (MVP - Phase 1)

### Core Functionality
- ✅ Unified WhatsApp inbox for CX team
- ✅ Real-time message updates via WebSocket
- ✅ Role-based access control (Admin > OM > Manager > AM > CX)
- ✅ Conversation assignment and status management
- ✅ Tagging system (New Lead, Payment, Follow-Up, etc.)
- ✅ Internal notes for conversations
- ✅ Follow-up scheduler with reminders
- ✅ Broadcast messaging (CSV upload, group targeting)
- ✅ Analytics dashboard with performance metrics
- ✅ Sofia AI integration placeholders

### User Roles & Permissions

| Role | Access Level | Permissions |
|------|--------------|-------------|
| **Admin** | All teams | Full system control, user management, settings |
| **Operation Manager** | All teams | Cross-team oversight, reports, analytics |
| **Manager** | Own team | Assign conversations, manage team queue |
| **Assistant Manager** | Own team | Monitor agents, handle escalations |
| **CX Agent** | Assigned chats | Reply, add notes, set follow-ups |

## Getting Started

### Prerequisites

- **PHP** 8.1 or higher
- **Composer** (for Laravel dependencies)
- **Node.js** 18+ and npm
- **MySQL** 8.0+
- **Redis** (optional, for caching)

### Backend Setup

1. **Navigate to backend directory**
   ```bash
   cd backend
   ```

2. **Install dependencies**
   ```bash
   composer install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```

   Edit `.env` and configure:
   - Database credentials (DB_DATABASE, DB_USERNAME, DB_PASSWORD)
   - WhatsApp Cloud API credentials
   - AWS S3 or DigitalOcean Spaces credentials
   - OpenAI API key (optional for Sofia AI)
   - Pusher/WebSocket settings

4. **Generate application key**
   ```bash
   php artisan key:generate
   ```

5. **Run migrations and seed database**
   ```bash
   php artisan migrate --seed
   ```

   This will create:
   - 5 user roles (Admin, OM, Manager, AM, CX)
   - 3 departments (CX, TX, CR)
   - 7 sample users (1 admin, 1 manager, 5 CX agents)
   - 7 default tags
   - 10 sample conversations with messages

6. **Start Laravel development server**
   ```bash
   php artisan serve
   ```
   Backend will run on `http://localhost:8000`

   **Note on Real-time Broadcasting**:
   - For development, the system uses `BROADCAST_DRIVER=log` which logs events to Laravel logs
   - For production real-time features, configure Pusher.com or another WebSocket service
   - Set `BROADCAST_DRIVER=pusher` and configure Pusher credentials in `.env`
   - The abandoned `beyondcode/laravel-websockets` package has been removed

### Frontend Setup

1. **Navigate to frontend directory**
   ```bash
   cd frontend
   ```

2. **Install dependencies**
   ```bash
   npm install
   ```

3. **Configure environment**
   ```bash
   cp .env.example .env
   ```

   Edit `.env` if needed:
   ```env
   VITE_API_URL=http://localhost:8000/api
   VITE_WS_HOST=localhost
   VITE_WS_PORT=6001
   VITE_WS_KEY=tims-key
   ```

4. **Start development server**
   ```bash
   npm run dev
   ```
   Frontend will run on `http://localhost:3000`

### Default Login Credentials

After running seeders, you can login with:

**Admin Account**
- Email: `admin@tims.local`
- Password: `password123`

**Manager Account**
- Email: `sarah.manager@tims.local`
- Password: `password123`

**CX Agent Accounts**
- Email: `alice.wong@tims.local` / `bob.chen@tims.local` / etc.
- Password: `password123`

## API Endpoints

### Authentication
- `POST /api/auth/login` - User login
- `GET /api/auth/me` - Get authenticated user
- `POST /api/auth/logout` - User logout
- `POST /api/auth/register` - Register new user (Admin only)

### Conversations
- `GET /api/conversations` - List conversations (with filters)
- `GET /api/conversations/{id}` - Get conversation details
- `POST /api/conversations/{id}/assign` - Assign conversation to user
- `PATCH /api/conversations/{id}/status` - Update conversation status
- `POST /api/conversations/{id}/follow-up` - Set follow-up date

### Messages
- `POST /api/messages/send` - Send WhatsApp message

### Notes
- `GET /api/conversations/{id}/notes` - Get conversation notes
- `POST /api/conversations/{id}/notes` - Add note to conversation
- `DELETE /api/notes/{id}` - Delete a note

### Tags
- `GET /api/tags` - List all tags
- `POST /api/tags` - Create new tag
- `POST /api/conversations/{id}/tags` - Add tag to conversation
- `DELETE /api/conversations/{id}/tags/{tag_id}` - Remove tag

### Broadcasts
- `GET /api/broadcasts` - List all broadcasts
- `POST /api/broadcasts` - Create new broadcast
- `GET /api/broadcasts/{id}` - Get broadcast details
- `POST /api/broadcasts/{id}/send` - Send broadcast

### Analytics
- `GET /api/analytics/summary` - Dashboard summary
- `GET /api/analytics/agent-performance` - Agent performance metrics

### WhatsApp Webhook
- `GET /api/webhook/whatsapp` - Webhook verification
- `POST /api/webhook/whatsapp` - Receive incoming messages

### Broadcasting Channels (Real-time)
- `conversation.{id}` - Receive updates for a specific conversation
- `department.{id}` - Receive updates for all conversations in a department
- `user.{id}` - Receive personal notifications

**Broadcasting Events:**
- `MessageSent` - Fired when an agent sends a message
- `MessageReceived` - Fired when a customer message is received

## Database Schema

### Core Tables
- `users` - System users with role and department
- `roles` - Role definitions with permissions
- `departments` - CX, TX, CR departments
- `conversations` - WhatsApp conversations
- `messages` - Individual messages (inbound/outbound)
- `notes` - Internal notes for conversations
- `tags` - Conversation tags
- `conversation_tags` - Tag assignments
- `broadcasts` - Broadcast campaigns
- `broadcast_contacts` - Broadcast recipients
- `activity_logs` - User activity tracking
- `ai_logs` - Sofia AI interaction logs
- `archives` - Archived data (>6 months)

## Environment Variables

### Backend (.env)
```env
# Database
DB_CONNECTION=mysql
DB_DATABASE=tims
DB_USERNAME=root
DB_PASSWORD=

# WhatsApp Cloud API
WHATSAPP_TOKEN=your_whatsapp_token
WHATSAPP_PHONE_ID=your_phone_id
WHATSAPP_BUSINESS_ID=your_business_id
WHATSAPP_VERIFY_TOKEN=tims_webhook_verify_token

# Storage (S3 or Spaces)
AWS_ACCESS_KEY_ID=
AWS_SECRET_ACCESS_KEY=
AWS_BUCKET=tims-media

# OpenAI (Sofia AI)
OPENAI_API_KEY=
OPENAI_MODEL=gpt-4-turbo-preview

# WebSocket
PUSHER_APP_KEY=tims-key
PUSHER_APP_SECRET=tims-secret
```

### Frontend (.env)
```env
VITE_API_URL=http://localhost:8000/api
VITE_WS_HOST=localhost
VITE_WS_PORT=6001
VITE_WS_KEY=tims-key
```

## Development Workflow

1. **Start backend server**: `php artisan serve`
2. **Start frontend**: `npm run dev` (in frontend directory)
3. **Access application**: http://localhost:3000

**Note**: Broadcasting events are logged to Laravel logs in development mode. Check `storage/logs/laravel.log` to see broadcast events.

## Production Deployment

### Backend
```bash
composer install --optimize-autoloader --no-dev
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan migrate --force
```

### Frontend
```bash
npm run build
# Serve the dist/ folder with nginx or Apache
```

## WhatsApp Cloud API Setup

1. Create a Meta Business Account
2. Set up WhatsApp Business API
3. Get your Phone Number ID and Access Token
4. Configure webhook URL: `https://yourdomain.com/api/webhook/whatsapp`
5. Set webhook verify token in `.env` as `WHATSAPP_VERIFY_TOKEN`

## Future Roadmap

### Phase 2
- SIMS CRM integration (customer lookup API)
- TX (Tutor Experience) department
- Advanced AI features (auto-tagging, intent classification)

### Phase 3
- CR (Customer Retention) department
- AI conversation summaries
- Advanced analytics and reporting

### Phase 4
- Omni-channel support (Facebook, Instagram, Email)
- Mobile app for agents
- Voice message transcription

## Troubleshooting

### Broadcasting / Real-time Updates
- **Development**: Events are logged to `storage/logs/laravel.log` with `BROADCAST_DRIVER=log`
- **Production**: Configure Pusher or another WebSocket service
- Set `BROADCAST_DRIVER=pusher` and add Pusher credentials to `.env`
- Verify broadcasting routes are registered in `routes/channels.php`
- Check CORS configuration in `config/cors.php` for frontend access

### WhatsApp Webhook Not Receiving Messages
- Verify webhook URL is publicly accessible (use ngrok for local testing)
- Check webhook verify token matches `.env` configuration
- Review Laravel logs: `storage/logs/laravel.log`

### Database Connection Errors
- Verify MySQL is running
- Check database credentials in `.env`
- Ensure database exists: `CREATE DATABASE tims;`

## Contributing

This is an internal project for SifuTutor. For feature requests or bug reports, please contact the development team.

## License

Proprietary - SifuTutor Internal Use Only

## Support

For technical support, contact:
- Development Team: dev@sifututor.my
- System Administrator: admin@sifututor.my

---

**Built with ❤️ by the SifuTutor Engineering Team**
