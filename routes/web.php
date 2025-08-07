<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\{
    HomeController,
    MailController,
    SupportTicketController,
    AuthController,
    AdminUserController,
    ContentController,
    BlogController,
    BlogCommentController,
    FaqController,
    ServiceController,
    NewsletterController,
    NotificationController,
    CallLogController,
    CallReportController,
    FaqCategoryController,
    AdminCommentController,
    ProfileController,
};

use App\Http\Controllers\Admin\AdminGlobalSearchController;

use App\Http\Controllers\Admin\{
    AdminBlogController,
    ServiceController as AdminServiceController,
    ServiceResourceController,
    NewsletterSubscriberController,
    NewsletterCampaignController,
    CustomerContactController,
};

// =============================================================================
// PUBLIC ROUTES (No Authentication Required)
// =============================================================================

// General Pages
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::get('/our-team', 'team')->name('team');
    Route::get('/pricing', 'pricing')->name('pricing');
});

// Contact Forms
Route::post('/send-email', [MailController::class, 'sendMail'])->name('send_email');
Route::post('/submit-ticket', [SupportTicketController::class, 'store'])->name('submit.ticket');

// Blog Routes
Route::prefix('blog')->name('blog.')->controller(BlogController::class)->group(function () {
    Route::get('/', 'frontIndex')->name('index');
    Route::get('/category/{category}', 'byCategory')->name('category');
    Route::get('/search/results', 'search')->name('search');
    Route::get('/{slug}', 'frontShow')->name('details');
});
Route::post('/blogs/{blog}/comments', [BlogCommentController::class, 'store'])->name('blog-comments.store');

// Services
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{slug}', [ServiceController::class, 'show'])->name('show');
});

// Service Resources
Route::get('/service-resource/{resource}/download', [ServiceResourceController::class, 'download'])
    ->name('service-resource.download');

// Newsletter
Route::prefix('newsletter')->name('newsletter.')->group(function () {
    Route::post('/subscribe', [NewsletterController::class, 'subscribe'])->name('subscribe');
    Route::get('/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])->name('unsubscribe');
});

Route::prefix('admin/users')->name('admin.users.')->controller(AdminUserController::class)->group(function () {
    Route::get('/activate/{token}', 'showActivationPage')->name('activate');
    Route::post('/activate', 'processActivation')->name('processActivation');
    Route::post('/{user}/toggle-activation', 'toggleActivation')->name('toggle-activation');
});

// Public FAQ listing
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');

// =============================================================================
// GUEST ROUTES (Authentication)
// =============================================================================

// Password reset routes
Route::middleware('guest')->group(function () {
    Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request');
    Route::post('/forgot-password', [AuthController::class, 'sendResetLinkEmail'])->name('password.email');
    Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset');
    Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update');
    
    // Auth routes
    Route::get('/register', [AuthController::class, 'showRegister'])->name('show.register');
    Route::post('/register', [AuthController::class, 'register'])->name('register');
    Route::get('/login', [AuthController::class, 'showLogin'])->name('show.login');
    Route::post('/login', [AuthController::class, 'login'])->name('login');
});

// =============================================================================
// AUTHENTICATED USER ROUTES
// =============================================================================

Route::middleware('auth')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Profile Management
    Route::prefix('profile')->name('profile.')->controller(ProfileController::class)->group(function () {
        Route::get('/', 'show')->name('show');
        Route::put('/update', 'updateProfile')->name('update');
        Route::put('/password', 'updatePassword')->name('password');
        Route::post('/avatar', 'updateAvatar')->name('avatar');
        Route::delete('/avatar', 'deleteAvatar')->name('avatar.delete');
        Route::put('/preferences', 'updatePreferences')->name('preferences');
    });

    // Notifications - Consolidated and cleaned up
    Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/recent', 'getRecent')->name('recent');
        Route::get('/count', 'getUnreadCount')->name('count');
        Route::post('/mark-all-read', 'markAllAsRead')->name('mark-all-read');
        Route::get('/{notification}/redirect', 'redirect')->name('redirect');
        Route::post('/{notification}/read', 'markAsRead')->name('read');
        Route::delete('/{notification}', 'destroy')->name('destroy');
    });

});
// =============================================================================
// ADMIN ROUTES (Authenticated + Role-Based Access)
// =============================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [SupportTicketController::class, 'index'])->name('index');

    // User Management
    Route::prefix('users')->name('users.')->controller(AdminUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{user}/assign-role', 'assignRole')->name('assign-role');
    });

    // FAQ Management
    Route::prefix('faqs')->name('faqs.')->group(function () {
        Route::get('/', [FaqController::class, 'adminIndex'])->name('index');
        Route::get('/create', [FaqController::class, 'create'])->name('create');
        Route::post('/', [FaqController::class, 'store'])->name('store');
        Route::get('/{faq}/edit', [FaqController::class, 'edit'])->name('edit');
        Route::put('/{faq}', [FaqController::class, 'update'])->name('update');
        Route::delete('/{faq}', [FaqController::class, 'destroy'])->name('destroy');
    });

    // FAQ Category Management
    Route::resource('faq-categories', FaqCategoryController::class)->except(['show']);

    // Blog Management
    Route::prefix('blogs')->name('blogs.')->controller(BlogController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{blog}/edit', 'edit')->name('edit');
        Route::put('/{blog}', 'update')->name('update');
        Route::delete('/{blog}', 'destroy')->name('destroy');
        Route::post('/{blog}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
    });

    // Tickets Management
    Route::prefix('tickets')->name('tickets.')->controller(SupportTicketController::class)->group(function () {
        // Static routes first
        Route::get('/', 'index')->name('index');
        Route::get('/all', 'allTickets')->name('all');
        Route::get('/open', 'openTickets')->name('open');
        Route::get('/solved', 'solvedTickets')->name('solved');
        Route::get('/pending', 'pendingTickets')->name('pending');
        Route::get('/unassigned', 'unassignedTickets')->name('unassigned');
        Route::get('/my-tickets', 'myTickets')->name('mine');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'adminStore')->name('store');

        // Dynamic routes with parameters
        Route::prefix('{ticket}')->group(function () {
            Route::get('/', 'show')->name('show');
            Route::get('/reopen', 'reopen')->name('reopen');
            Route::patch('/status-priority', 'updateStatusPriority')->name('updateStatusPriority');
            Route::post('/comments', 'addComment')->name('addComment');
            Route::put('/assign', 'assignTechnician')->name('assign');
        });
    });

    // Content Management
    Route::get('/content', [ContentController::class, 'index'])->name('content.index');
    Route::resource('comments', AdminCommentController::class)->only(['index', 'edit', 'update', 'destroy']);

    // Service Management
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [AdminServiceController::class, 'index'])->name('index');
        Route::post('/', [AdminServiceController::class, 'store'])->name('store');
        Route::put('/{service}', [AdminServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [AdminServiceController::class, 'destroy'])->name('destroy');
        Route::post('/{service}/resources', [ServiceResourceController::class, 'store'])->name('resources.store');
        Route::delete('/{service}/resources/{resource}', [ServiceResourceController::class, 'destroy'])->name('resources.destroy');
    });


   // Newsletter Management
        Route::prefix('subscribers')->name('subscribers.')->controller(NewsletterSubscriberController::class)->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/', 'store')->name('store'); 
            Route::delete('/{subscriber}', 'destroy')->name('destroy');
        });

    Route::resource('newsletters', NewsletterCampaignController::class)->except(['destroy']);
    Route::post('newsletters/{newsletter}/send', [NewsletterCampaignController::class, 'send'])->name('newsletters.send');

    // Call Logs Management
    Route::prefix('call-logs')->name('call-logs.')->controller(CallLogController::class)->group(function () {
        // Static routes MUST come first
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/export', 'export')->name('export');
        Route::post('/export', [CallReportController::class, 'export'])->name('export-post');
        
        // Status-based listing routes (all static)
        Route::get('/all', 'all')->name('all');
        Route::get('/my-jobs', 'myJobs')->name('my-jobs');
        Route::get('/in-progress', 'inProgress')->name('in-progress');
        Route::get('/completed', 'completed')->name('completed');
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/unassigned', 'unassigned')->name('unassigned');
        Route::put('/assigned', 'assigned')->name('assigned');
        Route::get('/cancelled', 'cancelled')->name('cancelled');
        Route::get('/reports', 'index')->name('reports');
        Route::post('/generate', 'generate')->name('generate');

        // Parameterized routes MUST come last
        Route::prefix('{callLog}')->group(function () {
            Route::get('/', 'show')->name('show');
            Route::get('/edit', 'edit')->name('edit');
            Route::put('/', 'update')->name('update');
            Route::delete('/', 'destroy')->name('destroy');
            Route::put('/assign', 'assign')->name('assign');
            Route::patch('/status', 'updateStatus')->name('update-status');
            Route::post('/complete', 'complete')->name('complete');
            Route::post('/notify-customer', 'notifyCustomer')->name('notify-customer');
        });
    });
});

// =============================================================================
// DEVELOPMENT/TESTING ROUTES
// =============================================================================

// Email Preview (Remove in production)
Route::get('/preview-email', function() {
    $callLog = App\Models\CallLog::first();
    return new App\Mail\JobCompletionNotification($callLog);
})->middleware('auth'); // Add auth middleware for security

// Global Search Route
Route::get('/admin/global-search', [App\Http\Controllers\AdminGlobalSearchController::class, 'globalSearch'])
    ->name('admin.global-search')
    ->middleware(['auth']);

// Customer Contacts Routes
Route::prefix('admin/contacts')->name('admin.contacts.')->middleware(['auth'])->group(function () {
    Route::get('/', [App\Http\Controllers\Admin\CustomerContactController::class, 'index'])->name('index');
    Route::get('/create', [App\Http\Controllers\Admin\CustomerContactController::class, 'create'])->name('create');
    Route::post('/', [App\Http\Controllers\Admin\CustomerContactController::class, 'store'])->name('store');
    Route::get('/{contact}', [App\Http\Controllers\Admin\CustomerContactController::class, 'show'])->name('show');
    Route::get('/{contact}/edit', [App\Http\Controllers\Admin\CustomerContactController::class, 'edit'])->name('edit');
    Route::put('/{contact}', [App\Http\Controllers\Admin\CustomerContactController::class, 'update'])->name('update');
    Route::delete('/{contact}', [App\Http\Controllers\Admin\CustomerContactController::class, 'destroy'])->name('destroy');
});

