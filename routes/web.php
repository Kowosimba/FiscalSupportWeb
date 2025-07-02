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
    BlogCommentController
};
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ServiceController;
use App\Http\Controllers\NewsletterController;
use App\Http\Controllers\Admin\ServiceController as AdminServiceController;
use App\Http\Controllers\Admin\ServiceResourceController as ServiceResourceController;
use App\Http\Controllers\Admin\NewsletterSubscriberController;
use App\Http\Controllers\Admin\NewsletterCampaignController;


// Public Pages
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
    Route::get('/about-us', 'about')->name('about');
    Route::get('/contact-us', 'contact')->name('contact');
    //Route::get('/faqs', 'faqs')->name('faqs');
    Route::get('/our-team', 'team')->name('team');
    Route::get('/pricing', 'pricing')->name('pricing');
    Route::get('/services', 'services')->name('services');
});
Route::get('/faqs', [FaqController::class, 'index'])->name('faqs');

// Public Actions
Route::post('/send-email', [MailController::class, 'sendMail'])->name('send_email');
Route::post('/submit-ticket', [SupportTicketController::class, 'store'])->name('submit.ticket');

// Authentication
Route::controller(AuthController::class)->middleware('guest')->group(function () {
    Route::get('/register', 'showRegister')->name('show.register');
    Route::post('/register', 'register')->name('register');
    Route::get('/login', 'showLogin')->name('show.login');
    Route::post('/login', 'login')->name('login');
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// Authenticated User Routes
Route::middleware('auth')->group(function () {
    // Ticket Management
    Route::prefix('tickets')->controller(SupportTicketController::class)->group(function () {
        Route::get('/', 'index')->name('tickets.index');
        Route::get('/my-tickets', 'myTickets')->name('tickets.mine');
        Route::get('/{ticket}', 'show')->name('tickets.show');
        Route::patch('/{ticket}/status-priority', 'updateStatusPriority')->name('tickets.updateStatusPriority');
        Route::post('/{ticket}/comments', 'addComment')->name('tickets.addComment');
    });
});

// Admin Routes
Route::prefix('admin')->middleware('auth')->group(function () {
    // Dashboard
    Route::get('/', [SupportTicketController::class, 'index'])->name('admin.index');

    // Ticket Management
    Route::prefix('tickets')->controller(SupportTicketController::class)->group(function () {
        Route::get('/all', 'allTickets')->name('admin.tickets.all');
        Route::get('/open', 'openTickets')->name('admin.tickets.open');
        Route::get('/solved', 'solvedTickets')->name('admin.tickets.solved');
        Route::get('/pending', 'pendingTickets')->name('admin.tickets.pending');
        Route::get('/unassigned', 'unassignedTickets')->name('admin.tickets.unassigned');
        Route::get('/create', 'create')->name('admin.tickets.create');
        Route::post('/store', 'adminStore')->name('admin.tickets.store');
        Route::put('/{ticket}/assign', 'assignTechnician')->name('admin.tickets.assign');
    });

    // User Management
    Route::prefix('users')->controller(AdminUserController::class)->group(function () {
    Route::get('/', 'index')->name('admin.users.index');
    Route::post('/{user}/assign-role', 'assignRole')->name('admin.users.assign-role');
});

    // Content Management
    Route::get('/content', [ContentController::class, 'index'])->name('content.index');

    // Blog Management
    Route::resource('blogs', BlogController::class);
    
});

// Blog Routes (Frontend)
Route::prefix('blog')->controller(BlogController::class)->group(function () {
    Route::get('/', 'frontIndex')->name('blog.index');
    Route::get('/{slug}', 'frontShow')->name('blog.details');
    Route::get('/category/{category}', 'byCategory')->name('blog.category');
    Route::get('/search/results', 'search')->name('blog.search');
});
Route::get('/blogposts', [BlogController::class, 'index'])->name('blogposts');

Route::post('/blogs/{blog}/blog-comments', [BlogCommentController::class, 'store'])
    ->name('blog-comments.store');

    Route::prefix('admin')->middleware(['auth'])->group(function() {
    // FAQ Routes
    Route::get('/faqs', [FaqController::class, 'adminIndex'])->name('admin.faqs.index');
    Route::post('/faqs', [FaqController::class, 'store'])->name('admin.faqs.store');
    Route::put('/faqs/{faq}', [FaqController::class, 'update'])->name('admin.faqs.update');
    Route::delete('/faqs/{faq}', [FaqController::class, 'destroy'])->name('admin.faqs.destroy');
    Route::get('/faqs/{faq}/edit', [FaqController::class, 'edit']) ->name('admin.faqs.edit');
});
Route::get('/faqs/create', [FaqController::class, 'create'])->name('admin.faqs.create');


Route::prefix('admin')->middleware(['auth'])->group(function () {
    Route::resource('faq-categories', FaqCategoryController::class);
});

Route::delete('/admin/faq-categories/{faqCategory}', [FaqCategoryController::class, 'destroy'])->name('faq-categories.destroy');


// Services routes
Route::get('/services', [ServiceController::class, 'index'])->name('services.index');
Route::get('/services/{slug}', [ServiceController::class, 'show'])->name('services.show');

// Admin routes
Route::prefix('admin')->name('admin.')->middleware(['auth'])->group(function () {
    Route::get('/services', [AdminServiceController::class, 'index'])->name('services.index');
    Route::post('/services', [AdminServiceController::class, 'store'])->name('services.store');
    Route::put('/services/{service}', [AdminServiceController::class, 'update'])->name('services.update');
    Route::delete('/services/{service}', [AdminServiceController::class, 'destroy'])->name('services.destroy');
    
    // Service resources
    Route::post('/services/{service}/resources', [ServiceResourceController::class, 'store'])
        ->name('services.resources.store');
    Route::delete('/services/{service}/resources/{resource}', [ServiceResourceController::class, 'destroy'])
        ->name('services.resources.destroy');
});

// web.php
Route::get('/service-resource/{resource}/download', [ServiceResourceController::class, 'download'])->name('service-resource.download');

// Admin Newsletter Routes
Route::prefix('admin')->middleware(['auth'])->group(function () {
    // Subscribers Management
    Route::get('/subscribers', [NewsletterSubscriberController::class, 'index'])
        ->name('admin.subscribers.index');
    
    Route::delete('/subscribers/{subscriber}', [NewsletterSubscriberController::class, 'destroy'])
        ->name('admin.subscribers.destroy');

    // Newsletter Campaigns Management
    Route::get('/newsletters', [NewsletterCampaignController::class, 'index'])
        ->name('admin.newsletters.index');
    
    Route::get('/newsletters/create', [NewsletterCampaignController::class, 'create'])
        ->name('admin.newsletters.create');
    
    Route::post('/newsletters', [NewsletterCampaignController::class, 'store'])
        ->name('admin.newsletters.store');
    
    Route::get('/newsletters/{campaign}', [NewsletterCampaignController::class, 'show'])
        ->name('admin.newsletters.show');
    
    Route::post('/newsletters/{campaign}/send', [NewsletterCampaignController::class, 'send'])
        ->name('admin.newsletters.send');
});

// routes/web.php

// Newsletter routes
Route::post('/newsletter/subscribe', [NewsletterController::class, 'subscribe'])
    ->name('newsletter.subscribe');

Route::get('/newsletter/unsubscribe/{token}', [NewsletterController::class, 'unsubscribe'])
    ->name('newsletter.unsubscribe');