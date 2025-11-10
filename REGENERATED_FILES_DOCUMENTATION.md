# UPSIConnect - Regenerated Files Documentation

## Overview
This document lists all files that have been regenerated based on your database schema after the accidental discard. Your Laravel application is a student service marketplace platform with chat, reviews, favorites, and admin moderation features.

---

## Database Structure Summary

Based on your SQL file, your application has these main features:

### Core Tables
1. **users** - User accounts (students, community members, staff, admin)
2. **categories** - Service categories
3. **student_services** - Services offered by students
4. **service_requests** - Requests for services
5. **service_applications** - Applications to provide services
6. **chat_requests** - Requests to initiate conversations
7. **conversations** - Active chat conversations
8. **messages** - Chat messages
9. **reviews** - User reviews and ratings
10. **reports** - User reports for moderation
11. **favorites** - User favorites list

---

## Newly Regenerated Files

### 1. FavoriteController
**Path:** `app/Http/Controllers/FavoriteController.php`

**Features:**
- View all favorites (`index()`)
- Add user to favorites (`store()`)
- Remove user from favorites (`destroy()`)
- Toggle favorite status (`toggle()`)
- Check if user is favorited (`check()`)

**Usage Example:**
```php
// In your views or controllers
auth()->user()->favorites; // Get all favorited users
auth()->user()->favoritedBy; // Get users who favorited this user
```

### 2. Favorites Index View
**Path:** `resources/views/favorites/index.blade.php`

**Features:**
- Grid display of favorited users
- Profile photos/avatars
- Service count for each favorited user
- Quick access to user profiles
- Remove from favorites functionality
- Empty state with link to browse services

### 3. Favorite Button Component
**Path:** `resources/views/components/favorite-button.blade.php`

**Features:**
- Reusable button component
- Toggle favorite status with AJAX
- Visual feedback (heart icon fills when favorited)
- Toast notifications for success/error
- Automatic UI updates

**Usage in Blade Templates:**
```blade
<x-favorite-button 
    :user-id="$user->id" 
    :is-favorited="auth()->user()->favorites()->where('favorited_user_id', $user->id)->exists()" 
/>
```

### 4. Updated Routes
**Path:** `routes/web.php`

**New Routes Added:**
```php
// Favorites Management
Route::get('/favorites', [FavoriteController::class, 'index'])->name('favorites.index');
Route::post('/favorites', [FavoriteController::class, 'store'])->name('favorites.store');
Route::delete('/favorites/{user}', [FavoriteController::class, 'destroy'])->name('favorites.destroy');
Route::post('/favorites/toggle', [FavoriteController::class, 'toggle'])->name('favorites.toggle');
Route::get('/favorites/{user}/check', [FavoriteController::class, 'check'])->name('favorites.check');
```

### 5. Updated User Model
**Path:** `app/Models/User.php`

**New Relationships Added:**
```php
// Get users that this user has favorited
public function favorites()
{
    return $this->belongsToMany(User::class, 'favorites', 'user_id', 'favorited_user_id')
                ->withTimestamps();
}

// Get users who have favorited this user
public function favoritedBy()
{
    return $this->belongsToMany(User::class, 'favorites', 'favorited_user_id', 'user_id')
                ->withTimestamps();
}
```

### 6. Updated Navigation
**Path:** `resources/views/layouts/navigation.blade.php`

**New Menu Items Added:**
- My Favorites
- Messages
- My Services (for students)
- Service Requests
- My Applications

### 7. Updated Student Profile
**Path:** `resources/views/students/profile.blade.php`

**Changes:**
- Replaced static favorite button with dynamic `<x-favorite-button>` component
- Now properly toggles favorite status with AJAX

---

## Existing Controllers (Already Present)

Your application already has these controllers working:

1. **ChatController** - Manages conversations
2. **ChatRequestController** - Handles chat request initiation
3. **MessageController** - Sends and manages messages
4. **ReviewController** - Creates and manages reviews
5. **ReportController** - User reporting system
6. **ServiceRequestController** - Service request management
7. **ServiceApplicationController** - Service applications
8. **StudentServiceController** - Manage student services
9. **SearchController** - Search functionality
10. **ProfileController** - User profile management
11. **AvailabilityController** - Toggle user availability

### Admin Controllers
1. **VerificationController** - User verification approval
2. **ReportAdminController** - Moderation of reports
3. **UserAdminController** - User management (ban/suspend)

---

## How to Use the New Features

### 1. Adding a User to Favorites

**From JavaScript (AJAX):**
```javascript
fetch('/favorites/toggle', {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
        'Accept': 'application/json',
    },
    body: JSON.stringify({ user_id: userId })
})
```

**Using the Component:**
```blade
<x-favorite-button :user-id="$user->id" />
```

### 2. Viewing Favorites

Navigate to: `/favorites` or use the menu: **User Menu → My Favorites**

### 3. Checking if a User is Favorited

```php
$isFavorited = auth()->user()->favorites()
    ->where('favorited_user_id', $targetUserId)
    ->exists();
```

---

## Database Relationships Overview

### User Relationships
```php
// One-to-Many
user->services()              // Services offered by student
user->chatRequestsSent()      // Chat requests sent
user->chatRequestsReceived()  // Chat requests received
user->conversationsAsStudent()
user->conversationsAsCustomer()
user->reviewsWritten()
user->reviewsReceived()

// Many-to-Many
user->favorites()             // Users favorited by this user
user->favoritedBy()          // Users who favorited this user
```

### Other Key Relationships
```php
// Conversations
conversation->student()
conversation->customer()
conversation->messages()
conversation->chatRequest()

// Services
service->user()              // Service provider
service->category()
service->requests()

// Reviews
review->reviewer()
review->reviewee()
review->conversation()
review->serviceRequest()
review->serviceApplication()
```

---

## API Endpoints Reference

### Chat System
- `POST /chat-requests` - Send chat request
- `POST /chat-requests/{id}/accept` - Accept chat request
- `POST /chat-requests/{id}/decline` - Decline chat request
- `GET /chat` - View all conversations
- `GET /chat/{conversation}` - View specific conversation
- `POST /messages` - Send message
- `POST /messages/typing` - Broadcast typing indicator

### Services
- `GET /search/services` - Search services
- `POST /student-services` - Create service
- `PUT /student-services/{id}` - Update service
- `DELETE /student-services/{id}` - Delete service
- `GET /services/manage` - Manage my services

### Service Requests
- `GET /service-requests` - View requests
- `POST /service-requests` - Create request
- `PATCH /service-requests/{id}/accept` - Accept request
- `PATCH /service-requests/{id}/reject` - Reject request
- `PATCH /service-requests/{id}/complete` - Mark completed

### Reviews
- `POST /reviews` - Submit review

### Favorites (NEW)
- `GET /favorites` - View favorites
- `POST /favorites` - Add to favorites
- `DELETE /favorites/{user}` - Remove from favorites
- `POST /favorites/toggle` - Toggle favorite status
- `GET /favorites/{user}/check` - Check favorite status

### Reports
- `POST /reports` - Submit report

### Admin
- `POST /admin/verifications/{user}/approve` - Approve verification
- `POST /admin/verifications/{user}/reject` - Reject verification
- `GET /admin/reports/index` - View reports
- `POST /admin/reports/{report}/resolve` - Resolve report
- `POST /admin/users/{user}/ban` - Ban user
- `POST /admin/users/{user}/unban` - Unban user
- `POST /admin/users/{user}/suspend` - Suspend user
- `POST /admin/users/{user}/unsuspend` - Unsuspend user

---

## Broadcasting Channels

**Path:** `routes/channels.php`

### Configured Channels:
1. **App.Models.User.{id}** - Private user channel
2. **conversation.{conversationId}** - Private conversation channel

### Events Being Broadcast:
- `MessageSent` - New message in conversation
- `NewMessageNotification` - Notify recipient of new message
- `UserTyping` - Typing indicator

---

## User Roles

Your application supports these user roles:
1. **community** - Regular users (default)
2. **student** - Students who can offer services
3. **staff** - UPSI staff members
4. **admin** - System administrators

### Role-Based Features:
- **Students**: Can create and manage services
- **Staff/Admin**: Access to verification and reports pages
- **Community**: Can request services and chat with students

---

## User Verification Types

1. **Email Verification** (`email_verified_at`)
2. **Public Verification** (`public_verified_at`, `verification_status`)
3. **Staff Verification** (`staff_verified_at`, `staff_email`)

### Trust Badges:
- "Pelajar UPSI Terkini" - Current UPSI student
- "Staf UPSI Rasmi" - Official UPSI staff
- "Pengguna Disahkan" - Verified user
- "Belum Disahkan" - Not verified

---

## Security Features

1. **User Moderation:**
   - Suspension system (`is_suspended`)
   - Blacklist system (`is_blacklisted`, `blacklist_reason`)
   - Report system for user misconduct

2. **Service Control:**
   - Availability toggle (`is_available`)
   - Service activation (`is_active`)
   - Service request statuses

3. **Authorization:**
   - Middleware protection on all authenticated routes
   - Ownership checks in controllers
   - CSRF protection on all forms

---

## Next Steps to Complete Your Application

1. **Test the Favorites Feature:**
   ```bash
   # Clear cache
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

2. **Ensure Broadcasting is Set Up:**
   ```bash
   # Install and run Laravel Reverb or Pusher
   php artisan reverb:start
   ```

3. **Run Migrations (if needed):**
   ```bash
   php artisan migrate
   ```

4. **Compile Assets:**
   ```bash
   npm run dev
   # or for production
   npm run build
   ```

---

## Testing Checklist

- [ ] User can view favorites page
- [ ] User can add another user to favorites
- [ ] User can remove a user from favorites
- [ ] Favorite button updates correctly on profile pages
- [ ] Navigation menu shows all new items
- [ ] Chat system works
- [ ] Service requests work
- [ ] Reviews can be submitted
- [ ] Admin can moderate users and reports
- [ ] Broadcasting/real-time chat works

---

## Notes

- All existing controllers and views were already present in your codebase
- The main missing piece was the **Favorites** functionality
- Navigation has been enhanced with better menu structure
- The favorite button component is reusable across all user profile pages
- All routes use proper middleware authentication
- CSRF tokens are included in all POST requests

---

## Support & Maintenance

### Common Commands:
```bash
# Clear all caches
php artisan optimize:clear

# Run queue workers (for jobs)
php artisan queue:work

# Start Reverb server (for WebSockets)
php artisan reverb:start

# View routes
php artisan route:list

# Run tests
php artisan test
```

### Database Seeding:
If you need to populate test data:
```bash
php artisan db:seed
```

---

**Last Updated:** November 9, 2025
**Laravel Version:** 11.x
**PHP Version:** 8.x
