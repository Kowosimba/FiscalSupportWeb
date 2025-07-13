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
};

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

// =============================================================================
// GUEST ROUTES (Authentication)
// =============================================================================

Route::middleware('guest')->controller(AuthController::class)->group(function () {
    Route::get('/register', 'showRegister')->name('show.register');
    Route::post('/register', 'register')->name('register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/login', 'login')->name('login');
});

// =============================================================================
// AUTHENTICATED USER ROUTES
// =============================================================================

Route::middleware('auth')->group(function () {
    // Authentication
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // Notifications
    Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{notification}/read', 'markAsRead')->name('read');
        Route::post('/read-all', 'markAllAsRead')->name('read-all');
    });
});

// Public FAQ listing
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');

// Admin FAQ Management
Route::prefix('admin')->name('admin.')->group(function () {
    // Admin FAQ listing (custom)
    Route::get('faqs', [FaqController::class, 'adminIndex'])->name('faqs.index');

    // Admin CRUD routes (optional, but best to avoid resourceful route if you have custom methods)
    // Instead, define them manually to avoid confusion:
    Route::get('faqs/create', [FaqController::class, 'create'])->name('faqs.create');
    Route::post('faqs', [FaqController::class, 'store'])->name('faqs.store');
    Route::get('faqs/{faq}/edit', [FaqController::class, 'edit'])->name('faqs.edit');
    Route::put('faqs/{faq}', [FaqController::class, 'update'])->name('faqs.update');
    Route::delete('faqs/{faq}', [FaqController::class, 'destroy'])->name('faqs.destroy');
});


// =============================================================================
// ADMIN ROUTES (Authenticated + Role-Based Access)
// =============================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [SupportTicketController::class, 'index'])->name('index');

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

    // Admin Tickets Management
    Route::prefix('tickets')->name('tickets.')->controller(SupportTicketController::class)->group(function () {
        // Ticket Listing Views
        Route::get('/', 'index')->name('index'); // Default view
        Route::get('/all', 'allTickets')->name('all');
        Route::get('/open', 'openTickets')->name('open');
        Route::get('/solved', 'solvedTickets')->name('solved');
        Route::get('/pending', 'pendingTickets')->name('pending');
        Route::get('/unassigned', 'unassignedTickets')->name('unassigned');
        Route::get('/my-tickets', 'myTickets')->name('mine'); // Technicians' assigned tickets

        // Ticket Creation
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'adminStore')->name('store');

        // Single Ticket Operations
        Route::prefix('{ticket}')->group(function () {
            Route::get('/', 'show')->name('show');
            Route::get('/reopen', 'reopen')->name('reopen');
            Route::patch('/status-priority', 'updateStatusPriority')->name('updateStatusPriority');
            Route::post('/comments', 'addComment')->name('addComment');
            Route::put('/assign', 'assignTechnician')->name('assign');
        });
    });

    // User Management
    Route::prefix('users')->name('users.')->controller(AdminUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{user}/assign-role', 'assignRole')->name('assign-role');
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
        Route::delete('/{subscriber}', 'destroy')->name('destroy');
    });
    Route::resource('newsletters', NewsletterCampaignController::class)->except(['destroy']);
    Route::post('newsletters/{newsletter}/send', [NewsletterCampaignController::class, 'send'])->name('newsletters.send');

    // Contact Management
    Route::resource('contacts', CustomerContactController::class);

    // Call Logs Management
    Route::prefix('call-logs')->name('call-logs.')->controller(CallLogController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/dashboard', 'dashboard')->name('dashboard');
        Route::get('/my-jobs', 'myJobs')->name('my-jobs');
        Route::get('/in-progress', 'inProgress')->name('in-progress');
        Route::get('/completed', 'completed')->name('completed');
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/unassigned', 'unassigned')->name('unassigned');
        Route::get('/assigned', 'assigned')->name('assigned');
        Route::get('/cancelled', 'cancelled')->name('cancelled');
        Route::get('/{callLog}', 'show')->name('show');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{callLog}/edit', 'edit')->name('edit');
        Route::put('/{callLog}', 'update')->name('update');
        Route::delete('/{callLog}', 'destroy')->name('destroy');
        Route::post('/{callLog}/assign', 'assign')->name('assign');
        Route::patch('/{callLog}/status', 'updateStatus')->name('update-status');
        Route::post('/{callLog}/complete', 'complete')->name('complete');
        Route::get('/reports', 'reports')->name('reports');
        Route::get('/export', 'export')->name('export');
    });

    // Call Reports Management (Legacy)
    Route::prefix('call-reports')->name('call-reports.')->controller(CallReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::post('/generate', 'generate')->name('generate');
    });
});

Route::get('/notifications/{notification}/redirect', [NotificationController::class, 'redirect'])
    ->name('notifications.redirect');
