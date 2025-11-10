# UPSIConnect - Quick Start Guide

## 🚀 Getting Started

### 1. Clear Cache & Optimize
```bash
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear
```

### 2. Start Development Servers
```bash
# Terminal 1 - Laravel
php artisan serve

# Terminal 2 - Vite (for assets)
npm run dev

# Terminal 3 - Queue Worker (optional, for async jobs)
php artisan queue:work

# Terminal 4 - Reverb (for real-time features)
php artisan reverb:start
```

---

## 📁 Project Structure

```
upsiconnect/
├── app/
│   ├── Events/                    # Broadcasting events
│   │   ├── MessageSent.php
│   │   ├── NewMessageNotification.php
│   │   └── UserTyping.php
│   ├── Http/Controllers/
│   │   ├── Admin/                 # Admin controllers
│   │   │   ├── ReportAdminController.php
│   │   │   ├── UserAdminController.php
│   │   │   └── VerificationController.php
│   │   ├── Pages/                 # Page controllers
│   │   │   ├── AdminPageController.php
│   │   │   ├── SearchPageController.php
│   │   │   └── StudentPageController.php
│   │   ├── AvailabilityController.php
│   │   ├── ChatController.php
│   │   ├── ChatRequestController.php
│   │   ├── FavoriteController.php       # ✨ NEW
│   │   ├── MessageController.php
│   │   ├── ProfileController.php
│   │   ├── ReportController.php
│   │   ├── ReviewController.php
│   │   ├── SearchController.php
│   │   ├── ServiceApplicationController.php
│   │   ├── ServiceRequestController.php
│   │   └── StudentServiceController.php
│   └── Models/
│       ├── Category.php
│       ├── ChatRequest.php
│       ├── Conversation.php
│       ├── Message.php
│       ├── Report.php
│       ├── Review.php
│       ├── ServiceApplication.php
│       ├── ServiceRequest.php
│       ├── StudentService.php
│       └── User.php                     # Updated with favorites
├── resources/views/
│   ├── admin/
│   ├── chat/
│   ├── components/
│   │   └── favorite-button.blade.php   # ✨ NEW
│   ├── favorites/
│   │   └── index.blade.php             # ✨ NEW
│   ├── layouts/
│   │   ├── app.blade.php
│   │   └── navigation.blade.php        # Updated
│   ├── search/
│   ├── services/
│   ├── service-requests/
│   └── students/
│       └── profile.blade.php           # Updated
└── routes/
    ├── web.php                          # Updated
    └── channels.php

✨ = Newly created or significantly updated
```

---

## 🎯 Key Features

### 1. User Management
- **Registration & Login** - Laravel Breeze authentication
- **Profile Management** - Edit user details, upload photos
- **Verification System** - Email, public, and staff verification
- **Availability Toggle** - Students can mark themselves as available/unavailable

### 2. Service System
- **Create Services** - Students create service listings
- **Browse Services** - Search and filter services by category
- **Service Requests** - Users request specific services from students
- **Service Applications** - Students apply to provide services

### 3. Chat System
- **Chat Requests** - Request to chat with users
- **Real-time Messaging** - WebSocket-powered instant messaging
- **Typing Indicators** - See when the other person is typing
- **Read Receipts** - Track message read status

### 4. Review System
- **Rate Users** - 5-star rating system
- **Leave Comments** - Detailed feedback
- **Average Ratings** - Displayed on profiles
- **Review Context** - Reviews linked to conversations/services

### 5. Favorites System ✨
- **Add to Favorites** - Save users for quick access
- **Favorites List** - View all favorited users
- **Toggle Button** - One-click add/remove
- **Profile Integration** - Favorite button on user profiles

### 6. Admin Features
- **User Verification** - Approve/reject verification requests
- **Report Management** - Review and act on user reports
- **User Moderation** - Ban, suspend, or blacklist users
- **Dashboard** - Overview of platform activity

---

## 🔐 User Roles & Permissions

| Role      | Can Create Services | Can Request Services | Admin Access | Staff Access |
|-----------|---------------------|----------------------|--------------|--------------|
| community | ❌                  | ✅                   | ❌           | ❌           |
| student   | ✅                  | ✅                   | ❌           | ❌           |
| staff     | ✅                  | ✅                   | ✅           | ✅           |
| admin     | ✅                  | ✅                   | ✅           | ✅           |

---

## 🛠️ Common Tasks

### Create a New Service (as Student)
1. Navigate to `/services/create`
2. Fill in service details
3. Set price range and timeline
4. Click "Create Service"

### Request a Service
1. Browse services at `/search`
2. View service details
3. Click "Request Service"
4. Fill in requirements and offered price
5. Submit request

### Send a Chat Request
1. Visit user's profile
2. Click "Send Message"
3. Include optional message
4. Wait for acceptance

### Add User to Favorites
1. Visit user's profile
2. Click "Add to Favorites" button
3. Button turns red when favorited
4. Access from `/favorites`

### Submit a Review
1. Complete a service/conversation
2. Click "Leave Review"
3. Select rating (1-5 stars)
4. Write comment (optional)
5. Submit

### Report a User
1. Visit user's profile
2. Click "Report User"
3. Select reason
4. Provide details
5. Submit report

---

## 📡 API Endpoints Quick Reference

### Authentication Required: ✅ | Public: 🌐

#### User & Profile
- 🌐 `GET /` - Home page
- ✅ `GET /dashboard` - User dashboard
- ✅ `GET /profile` - Edit profile
- ✅ `PATCH /profile` - Update profile
- 🌐 `GET /students/{user}/profile` - View student profile

#### Services
- ✅ `GET /search` - Browse services
- 🌐 `GET /search/services` - API search
- ✅ `GET /services/manage` - Manage my services
- ✅ `GET /services/create` - Create service form
- ✅ `POST /student-services` - Store service
- ✅ `PUT /student-services/{id}` - Update service
- ✅ `DELETE /student-services/{id}` - Delete service

#### Service Applications
- ✅ `GET /services/apply` - Apply form
- ✅ `POST /services/apply` - Submit application
- ✅ `GET /services/applications` - View applications
- ✅ `POST /service-applications/apply` - Apply from chat
- ✅ `POST /service-applications/{id}/accept` - Accept
- ✅ `POST /service-applications/{id}/decline` - Decline
- ✅ `POST /service-applications/{id}/complete` - Mark complete

#### Service Requests
- ✅ `GET /service-requests` - View requests
- ✅ `POST /service-requests` - Create request
- ✅ `GET /service-requests/{id}` - View details
- ✅ `PATCH /service-requests/{id}/accept` - Accept request
- ✅ `PATCH /service-requests/{id}/reject` - Reject request
- ✅ `PATCH /service-requests/{id}/in-progress` - Mark in progress
- ✅ `PATCH /service-requests/{id}/complete` - Mark complete
- ✅ `PATCH /service-requests/{id}/cancel` - Cancel

#### Chat System
- ✅ `GET /chat` - View conversations
- ✅ `GET /chat/{conversation}` - View conversation
- ✅ `GET /chat/request` - Request chat form
- ✅ `POST /chat-requests` - Send chat request
- ✅ `POST /chat-requests/{id}/accept` - Accept request
- ✅ `POST /chat-requests/{id}/decline` - Decline request
- ✅ `POST /messages` - Send message
- ✅ `POST /messages/typing` - Typing indicator

#### Favorites ✨
- ✅ `GET /favorites` - View favorites
- ✅ `POST /favorites` - Add to favorites
- ✅ `DELETE /favorites/{user}` - Remove from favorites
- ✅ `POST /favorites/toggle` - Toggle favorite
- ✅ `GET /favorites/{user}/check` - Check status

#### Reviews
- ✅ `POST /reviews` - Submit review

#### Reports
- ✅ `POST /reports` - Submit report

#### Admin (staff/admin only)
- ✅ `GET /admin/verifications` - View verifications
- ✅ `POST /admin/verifications/{user}/approve` - Approve
- ✅ `POST /admin/verifications/{user}/reject` - Reject
- ✅ `GET /admin/reports` - View reports
- ✅ `GET /admin/reports/index` - Reports API
- ✅ `POST /admin/reports/{report}/resolve` - Resolve report
- ✅ `POST /admin/users/{user}/ban` - Ban user
- ✅ `POST /admin/users/{user}/unban` - Unban user
- ✅ `POST /admin/users/{user}/suspend` - Suspend user
- ✅ `POST /admin/users/{user}/unsuspend` - Unsuspend user

#### Availability
- ✅ `POST /availability/toggle` - Toggle availability

---

## 🎨 Using Components

### Favorite Button
```blade
<!-- Basic usage -->
<x-favorite-button :user-id="$user->id" />

<!-- With favorite status -->
<x-favorite-button 
    :user-id="$user->id" 
    :is-favorited="auth()->user()->favorites()->where('favorited_user_id', $user->id)->exists()" 
/>

<!-- With custom classes -->
<x-favorite-button 
    :user-id="$user->id" 
    class="w-full justify-center" 
/>
```

---

## 🔄 Real-time Features

### Broadcasting Setup
Your app uses Laravel Reverb for WebSockets.

**Start Reverb:**
```bash
php artisan reverb:start
```

**Environment Variables:**
```env
BROADCAST_CONNECTION=reverb
REVERB_APP_ID=your-app-id
REVERB_APP_KEY=your-app-key
REVERB_APP_SECRET=your-app-secret
```

### Events Being Broadcast
1. **MessageSent** - New chat message
2. **NewMessageNotification** - Notify recipient
3. **UserTyping** - Typing indicator

### Listening to Events (Frontend)
```javascript
// In your chat.blade.php
Echo.private(`conversation.${conversationId}`)
    .listen('MessageSent', (e) => {
        // Handle new message
        appendMessage(e.message);
    })
    .listen('UserTyping', (e) => {
        // Handle typing indicator
        showTypingIndicator(e.userName, e.isTyping);
    });
```

---

## 🗃️ Database Models & Relationships

### User Model
```php
// Services
$user->services           // Services offered
$user->studentServices    // Alias for services

// Chat
$user->chatRequestsSent
$user->chatRequestsReceived
$user->conversationsAsStudent
$user->conversationsAsCustomer

// Reviews
$user->reviewsWritten
$user->reviewsReceived
$user->average_rating     // Computed attribute

// Favorites ✨
$user->favorites          // Users favorited by this user
$user->favoritedBy        // Users who favorited this user

// Helpers
$user->isStudent()
$user->isVerifiedPublic()
$user->isVerifiedStaff()
$user->isAvailable()
$user->trust_badge        // Computed attribute
```

### Service Model
```php
$service->user            // Provider
$service->category
$service->requests        // Service requests
```

### Conversation Model
```php
$conversation->student
$conversation->customer
$conversation->messages
$conversation->chatRequest
```

### Review Model
```php
$review->reviewer
$review->reviewee
$review->conversation
$review->serviceRequest
$review->serviceApplication
```

---

## 🐛 Troubleshooting

### Routes not working
```bash
php artisan route:clear
php artisan config:clear
```

### Views not updating
```bash
php artisan view:clear
```

### Assets not loading
```bash
npm run dev
# or
npm run build
```

### WebSockets not connecting
1. Check if Reverb is running: `php artisan reverb:start`
2. Verify `.env` broadcast settings
3. Check browser console for errors

### Database errors
```bash
# Reset database (WARNING: Deletes all data)
php artisan migrate:fresh

# Or just run pending migrations
php artisan migrate
```

---

## 📝 Testing

### Manual Testing Checklist

#### Favorites Feature ✨
- [ ] Can view favorites page
- [ ] Can add user to favorites
- [ ] Can remove user from favorites
- [ ] Favorite button updates correctly
- [ ] Favorites count is accurate
- [ ] Can click through to user profiles

#### Chat Feature
- [ ] Can send chat request
- [ ] Can accept/decline requests
- [ ] Can send messages
- [ ] Messages appear in real-time
- [ ] Typing indicators work
- [ ] Can view conversation history

#### Service Feature
- [ ] Can create service (as student)
- [ ] Can edit service
- [ ] Can delete service
- [ ] Can search/filter services
- [ ] Can request service
- [ ] Can apply for service

#### Review Feature
- [ ] Can submit review
- [ ] Review shows on profile
- [ ] Average rating updates
- [ ] Can view all reviews

---

## 🚀 Deployment Checklist

- [ ] Set `APP_ENV=production` in `.env`
- [ ] Set `APP_DEBUG=false` in `.env`
- [ ] Run `php artisan config:cache`
- [ ] Run `php artisan route:cache`
- [ ] Run `php artisan view:cache`
- [ ] Run `npm run build`
- [ ] Set up queue worker as daemon
- [ ] Set up Reverb as daemon
- [ ] Configure SSL certificate
- [ ] Set up database backups
- [ ] Configure proper file permissions

---

## 📞 Support

For issues or questions:
1. Check `storage/logs/laravel.log` for errors
2. Review this documentation
3. Check Laravel documentation: https://laravel.com/docs

---

**Happy Coding! 🎉**

Last Updated: November 9, 2025
