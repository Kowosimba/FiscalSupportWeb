<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
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

// --- Public Pages ---
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/contact-us', 'contact')->name('contact');
    Route::get('/our-team', 'team')->name('team');
    Route::get('/pricing', 'pricing')->name('pricing');
});

// --- Public Actions ---
Route::post('/send-email', [MailController::class, 'sendMail'])->name('send_email');
Route::post('/submit-ticket', [SupportTicketController::class, 'store'])->name('submit.ticket');

// --- Public FAQ ---
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');

// --- Public Blog Routes ---
Route::prefix('blog')->name('blog.')->controller(BlogController::class)->group(function () {
    Route::get('/', 'frontIndex')->name('index');
    Route::get('/category/{category}', 'byCategory')->name('category');
    Route::get('/search/results', 'search')->name('search');
    Route::get('/{slug}', 'frontShow')->name('details');
});

// Blog Comments (Public)
Route::post('/blogs/{blog}/comments', [BlogCommentController::class, 'store'])->name('blog-comments.store');

// --- Public Services ---
Route::prefix('services')->name('services.')->group(function () {
    Route::get('/', [ServiceController::class, 'index'])->name('index');
    Route::get('/{slug}', [ServiceController::class, 'show'])->name('show');
});

// Service Resource Downloads
Route::get('/service-resource/{resource}/download', [ServiceResourceController::class, 'download'])
    ->name('service-resource.download');

// --- Newsletter (Public) ---
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
    // Logout
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

    // --- User Ticket Management ---
    Route::prefix('tickets')->name('tickets.')->controller(SupportTicketController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/my-tickets', 'myTickets')->name('mine');
        Route::get('/{ticket}', 'show')->name('show');
        Route::get('/{ticket}/reopen', 'reopen')->name('reopen');
        Route::patch('/{ticket}/status-priority', 'updateStatusPriority')->name('updateStatusPriority');
        Route::post('/{ticket}/comments', 'addComment')->name('addComment');
    });

    // --- Notifications ---
    Route::prefix('notifications')->name('notifications.')->controller(NotificationController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{notification}/read', 'markAsRead')->name('read');
        Route::post('/read-all', 'markAllAsRead')->name('read-all');
    });
});

// =============================================================================
// ADMIN ROUTES (Authenticated + Role-Based Access)
// =============================================================================

Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    
    // --- Dashboard ---
    Route::get('/', [SupportTicketController::class, 'index'])->name('index');

    // --- FAQ Category Management ---
    Route::prefix('faq-categories')->name('faq-categories.')->controller(FaqCategoryController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{faqCategory}/edit', 'edit')->name('edit');
        Route::put('/{faqCategory}', 'update')->name('update');
        Route::delete('/{faqCategory}', 'destroy')->name('destroy');
    });

    // --- FAQ Management ---
    Route::prefix('faqs')->name('faqs.')->controller(FaqController::class)->group(function () {
        Route::get('/', 'adminIndex')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{faq}/edit', 'edit')->name('edit');
        Route::put('/{faq}', 'update')->name('update');
        Route::delete('/{faq}', 'destroy')->name('destroy');
    });

    // --- Blog Management ---
    Route::prefix('blogs')->name('blogs.')->controller(BlogController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('/{blog}', 'show')->name('show');
        Route::get('/{blog}/edit', 'edit')->name('edit');
        Route::put('/{blog}', 'update')->name('update');
        Route::delete('/{blog}', 'destroy')->name('destroy');
        Route::post('/{blog}/toggle-featured', 'toggleFeatured')->name('toggle-featured');
        Route::post('/bulk-action', 'bulkAction')->name('bulk-action');
    });

    // --- Ticket Management ---
    Route::prefix('tickets')->name('tickets.')->controller(SupportTicketController::class)->group(function () {
        Route::get('/all', 'allTickets')->name('all');
        Route::get('/open', 'openTickets')->name('open');
        Route::get('/solved', 'solvedTickets')->name('solved');
        Route::get('/pending', 'pendingTickets')->name('pending');
        Route::get('/unassigned', 'unassignedTickets')->name('unassigned');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'adminStore')->name('store');
        Route::put('/{ticket}/assign', 'assignTechnician')->name('assign');
    });

    // --- User Management ---
    Route::prefix('users')->name('users.')->controller(AdminUserController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/{user}/assign-role', 'assignRole')->name('assign-role');
    });

    // --- Content Management ---
    Route::get('/content', [ContentController::class, 'index'])->name('content.index');

    // --- Comment Management ---
    Route::resource('comments', AdminCommentController::class)->only(['index', 'edit', 'update', 'destroy']);

    // --- Service Management ---
    Route::prefix('services')->name('services.')->group(function () {
        Route::get('/', [AdminServiceController::class, 'index'])->name('index');
        Route::post('/', [AdminServiceController::class, 'store'])->name('store');
        Route::put('/{service}', [AdminServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [AdminServiceController::class, 'destroy'])->name('destroy');
        // Service Resources
        Route::post('/{service}/resources', [ServiceResourceController::class, 'store'])->name('resources.store');
        Route::delete('/{service}/resources/{resource}', [ServiceResourceController::class, 'destroy'])->name('resources.destroy');
    });

    // --- Newsletter Management ---
    Route::prefix('subscribers')->name('subscribers.')->controller(NewsletterSubscriberController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::delete('/{subscriber}', 'destroy')->name('destroy');
    });

    // Newsletter Campaigns
    Route::resource('newsletters', NewsletterCampaignController::class)->except(['destroy']);
    Route::post('newsletters/{newsletter}/send', [NewsletterCampaignController::class, 'send'])->name('newsletters.send');

    // --- Contact Management ---
    Route::resource('contacts', CustomerContactController::class);

    // =============================================================================
    // JOB CARDS / CALL LOGS MANAGEMENT (IT Support System)
    // =============================================================================
    
    Route::prefix('call-logs')
          ->name('call-logs.')
          ->controller(CallLogController::class)
          ->group(function () {

        // ---- General dashboards & lists (visible to every logged-in user) ----
        Route::get('/', 'index')->name('index');                    // full list + filters
        Route::get('/dashboard', 'dashboard')->name('dashboard');   // KPI widgets
        
        // ---- Quick filtered views ------------------------------------------
        Route::get('/my-jobs', 'myJobs')->name('my-jobs');         // engineer dashboard
        Route::get('/in-progress', 'inProgress')->name('in-progress');
        Route::get('/completed', 'completed')->name('completed');
        Route::get('/pending', 'pending')->name('pending');
        Route::get('/unassigned', 'unassigned')->name('unassigned');
        Route::get('/assigned', 'assigned')->name('assigned');
        Route::get('/cancelled', 'cancelled')->name('cancelled');

        // ---- View individual job cards (accessible to all authenticated users) ----
        Route::get('/{callLog}', 'show')->name('show');            // single card details

        // ---- Create – admin & accounts only --------------------------------
        Route::middleware(['role:admin,accounts'])->group(function () {
            Route::get('/create', 'create')->name('create');
            Route::post('/', 'store')->name('store');
        });

        // ---- Edit / update – admin, accounts, assigned engineer ------------
        Route::middleware(['role:technician,admin,accounts'])->group(function () {
            Route::get('/{callLog}/edit', 'edit')->name('edit');
            Route::put('/{callLog}', 'update')->name('update');
        });

        // ---- Delete – admin only -------------------------------------------
        Route::middleware(['role:admin'])->group(function () {
            Route::delete('/{callLog}', 'destroy')->name('destroy');
        });

        // ---- Assignment & status management --------------------------------
        Route::middleware(['role:admin,accounts'])->group(function () {
            Route::post('/{callLog}/assign', 'assign')->name('assign');
        });

        // Status changes:
        // • engineers can update cards assigned to them
        // • admin/accounts can update any card
        Route::patch('/{callLog}/status', 'updateStatus')->name('update-status');
        Route::post('/{callLog}/complete', 'complete')->name('complete');   // convenience route

        // ---- Reports & data export – admin only ----------------------------
        Route::middleware(['role:admin'])->group(function () {
            Route::get('/reports', 'reports')->name('reports');
            Route::get('/export', 'export')->name('export');       // ?format=csv|excel|pdf
        });
    });

    // --- Call Reports Management (Legacy - can be removed if not needed) ---
    Route::prefix('call-reports')->name('call-reports.')->controller(CallReportController::class)->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/export', 'export')->name('export');
        Route::post('/generate', 'generate')->name('generate');
    });
});
