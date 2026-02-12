<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CorporateController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserTagController;
use App\Http\Controllers\EventBookingController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\Admin\CatalogueController;
use App\Http\Controllers\Admin\AuctionController;
use App\Http\Controllers\Admin\ItemController;
use App\Http\Controllers\Admin\ItemImageController;
use App\Http\Controllers\Admin\TagController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\BandController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\BidController;
use App\Http\Controllers\Admin\SettlementController;
use App\Http\Controllers\Admin\EventController as AdminEventController;

// Public corporate pages
Route::get('/', [CorporateController::class, 'index'])->name('home');
Route::get('/about', [CorporateController::class, 'about'])->name('about');
Route::get('/services', [CorporateController::class, 'services'])->name('services');
Route::get('/contact', [CorporateController::class, 'contact'])->name('contact');
Route::get('/how-to-bid', [CorporateController::class, 'howToBid'])->name('how-to-bid');
Route::get('/sell-with-us', [CorporateController::class, 'sellWithUs'])->name('sell-with-us');
Route::get('/valuation', [CorporateController::class, 'valuation'])->name('valuation');
Route::get('/privacy-policy', [CorporateController::class, 'privacyPolicy'])->name('privacy-policy');
Route::get('/terms-conditions', [CorporateController::class, 'termsConditions'])->name('terms-conditions');
Route::get('/cookie-policy', [CorporateController::class, 'cookiePolicy'])->name('cookie-policy');
Route::get('/locations', [CorporateController::class, 'locations'])->name('locations.index');
Route::get('/items', [CorporateController::class, 'browseItems'])->name('items.browse');
Route::get('/items/{item}', [CorporateController::class, 'showItem'])->name('items.show');

// Event booking routes
Route::get('/events/{id}/book', [EventBookingController::class, 'show'])->name('events.book');
Route::post('/events/{id}/book', [EventBookingController::class, 'store'])->name('events.book.store');
Route::get('/bookings/{booking}/confirmation', [EventBookingController::class, 'confirmation'])->name('events.booking.confirmation');
Route::get('/bookings/{booking}/manage', [EventBookingController::class, 'manage'])->name('events.booking.manage')->middleware('auth');
Route::post('/bookings/{booking}/cancel', [EventBookingController::class, 'cancel'])->name('events.booking.cancel')->middleware('auth');
Route::get('/bookings/{booking}/calendar/{type}', [EventBookingController::class, 'addToCalendar'])->name('events.booking.calendar');

// Event pages - merged: carousel + filtering functionality
Route::get('/events', [EventController::class, 'index'])->name('events');
Route::get('/events/{id}', [EventController::class, 'show'])->name('events.show');

Route::get('/auctions', [CorporateController::class, 'browseAuctions'])->name('auctions.browse');
Route::get('/auctions/{auction}', [CorporateController::class, 'showAuction'])->name('auctions.show');

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/inbox', [App\Http\Controllers\InboxController::class, 'index'])->name('inbox.index');
    Route::get('/inbox/{announcement}', [App\Http\Controllers\InboxController::class, 'show'])->name('inbox.show');
});

// Dashboard routing
Route::get('/dashboard', function () {
    $user = auth()->user();
    if ($user->role === 'customer') {
        return redirect()->route('customer.dashboard');
    }
    return redirect()->route('staff.dashboard');
})->middleware('auth')->name('dashboard');

// Profile routes
Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/newsletter', [ProfileController::class, 'newsletter'])->name('profile.newsletter');
    
    // Tag preferences
    Route::get('/profile/tags', [UserTagController::class, 'index'])->name('profile.tags');
    Route::put('/profile/tags', [UserTagController::class, 'update'])->name('profile.tags.update');
    Route::post('/profile/tags/{tag}/toggle', [UserTagController::class, 'toggle'])->name('profile.tags.toggle');
});

// Authenticated dashboards
Route::middleware('auth')->group(function () {
    Route::middleware('role:customer')
        ->get('/customer', [DashboardController::class, 'customer'])
        ->name('customer.dashboard');

    Route::middleware('role:staff,approver,admin')
        ->get('/staff', [DashboardController::class, 'staff'])
        ->name('staff.dashboard');
});

// Admin routes (Admin only)
Route::prefix('admin')->middleware(['auth', 'role:admin'])->name('admin.')->group(function () {
    //Announcements
    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
    
    // Tags
    Route::resource('tags', TagController::class);
    
    // Categories
    Route::resource('categories', CategoryController::class);
    
    // Bands
    Route::resource('bands', BandController::class);
    
    // Locations
    Route::resource('locations', LocationController::class);
    Route::delete('locations/{location}/image', [LocationController::class, 'deleteImage'])->name('locations.deleteImage');
    
    // Users
    Route::resource('users', UserController::class);
    Route::patch('users/{user}/approve', [UserController::class, 'approve'])->name('users.approve');
    
    // Bids
    Route::get('bids', [BidController::class, 'index'])->name('bids.index');
    Route::get('bids/{bid}', [BidController::class, 'show'])->name('bids.show');
    
    // Settlements
    Route::resource('settlements', SettlementController::class);
    
    // Events Management (authorization handled in controller)
    Route::resource('events', AdminEventController::class);
});

// Item approval routes
Route::middleware(['auth', 'role:admin,staff,approver'])->group(function () {
    Route::post('/admin/items/{item}/submit-approval', [ItemController::class, 'submitForApproval'])
        ->name('admin.items.submit-approval');
});

Route::middleware(['auth', 'approver'])->group(function () {
    Route::post('/admin/items/{item}/approve', [ItemController::class, 'approve'])
        ->name('admin.items.approve');
    Route::post('/admin/items/{item}/reject', [ItemController::class, 'reject'])
        ->name('admin.items.reject');
});

// Catalogue approval routes
Route::middleware(['auth', 'role:admin,staff,approver'])->group(function () {
    Route::post('/admin/catalogues/{catalogue}/submit-approval', [CatalogueController::class, 'submitForApproval'])
        ->name('admin.catalogues.submit-approval');
});

Route::middleware(['auth', 'approver'])->group(function () {
    Route::post('/admin/catalogues/{catalogue}/approve', [CatalogueController::class, 'approve'])
        ->name('admin.catalogues.approve');
    Route::post('/admin/catalogues/{catalogue}/reject', [CatalogueController::class, 'reject'])
        ->name('admin.catalogues.reject');
});

// Admin + Staff routes
Route::prefix('admin')->middleware(['auth', 'role:admin,staff'])->name('admin.')->group(function () {
    // Auctions
    Route::resource('auctions', AuctionController::class);
    
    // Items - specific routes MUST come before resource routes
    Route::delete('items/{item}/images/{image}', [ItemController::class, 'deleteImage'])->name('items.deleteImage');
    Route::post('items/{item}/images', [ItemImageController::class, 'store'])->name('items.images.store');
    Route::patch('items/{item}/primary-image', [ItemController::class, 'setPrimaryImage'])->name('items.primary-image');
    Route::resource('items', ItemController::class);
    
    // Catalogues
    Route::resource('catalogues', CatalogueController::class);
    Route::post('catalogues/{catalogue}/items', [CatalogueController::class, 'addItem'])->name('catalogues.addItem');
    Route::delete('catalogues/{catalogue}/items/{item}', [CatalogueController::class, 'removeItem'])->name('catalogues.removeItem');

    Route::resource('announcements', App\Http\Controllers\Admin\AnnouncementController::class);
    Route::post('announcements/generate-message', [App\Http\Controllers\Admin\AnnouncementController::class, 'generateMessage'])->name('announcements.generate-message');
});


// Staff customer management routes
Route::prefix('staff')->middleware(['auth', 'role:admin,staff,approver'])->name('staff.')->group(function () {
    Route::get('/customers', [App\Http\Controllers\Staff\CustomerController::class, 'index'])->name('customers.index');
    Route::get('/customers/{user}', [App\Http\Controllers\Staff\CustomerController::class, 'show'])->name('customers.show');
});

// Auction approval routes
Route::middleware(['auth', 'role:admin,staff,approver'])->group(function () {
    Route::post('/admin/auctions/{auction}/submit-approval', [AuctionController::class, 'submitForApproval'])
        ->name('admin.auctions.submit-approval');
});

Route::middleware(['auth', 'approver'])->group(function () {
    Route::post('/admin/auctions/{auction}/approve', [AuctionController::class, 'approve'])
        ->name('admin.auctions.approve');
    Route::post('/admin/auctions/{auction}/reject', [AuctionController::class, 'reject'])
        ->name('admin.auctions.reject');
});

// Approval pages
Route::middleware(['auth', 'approver'])->prefix('admin/approvals')->name('admin.approvals.')->group(function () {
    // Items
    Route::get('/items', [App\Http\Controllers\Admin\ApprovalController::class, 'items'])->name('items');
    Route::get('/items/{item}', [App\Http\Controllers\Admin\ApprovalController::class, 'showItem'])->name('item');
    
    // Auctions
    Route::get('/auctions', [App\Http\Controllers\Admin\ApprovalController::class, 'auctions'])->name('auctions');
    Route::get('/auctions/{auction}', [App\Http\Controllers\Admin\ApprovalController::class, 'showAuction'])->name('auction');

    Route::get('/catalogues', [App\Http\Controllers\Admin\ApprovalController::class, 'catalogues'])->name('catalogues');
    Route::get('/catalogues/{catalogue}', [App\Http\Controllers\Admin\ApprovalController::class, 'showCatalogue'])->name('catalogue');
});

// PDF Catalogue Generation
Route::middleware(['auth'])->group(function () {
    Route::get('/catalogues/{catalogue}/pdf', [App\Http\Controllers\CataloguePdfController::class, 'generate'])
        ->name('catalogues.pdf.generate');
    Route::get('/catalogues/{catalogue}/pdf/download', [App\Http\Controllers\CataloguePdfController::class, 'download'])
        ->name('catalogues.pdf.download');
    Route::post('/catalogues/{catalogue}/pdf/regenerate', [App\Http\Controllers\CataloguePdfController::class, 'regenerate'])
        ->name('catalogues.pdf.regenerate');
});

// Test PDF generation - temporary for debugging
Route::get('/test-pdf', function() {
    try {
        \Log::info('Starting PDF test');
        
        // Test 1: Check if Spatie PDF is loaded
        \Log::info('Spatie PDF Facade loaded: ' . class_exists(\Spatie\LaravelPdf\Facades\Pdf::class) ? 'YES' : 'NO');
        
        // Test 2: Check Chrome path
        $chromePaths = [
            '/usr/bin/google-chrome',
            '/usr/bin/chromium',
            '/usr/bin/chromium-browser',
        ];
        
        foreach ($chromePaths as $path) {
            if (file_exists($path)) {
                \Log::info("Chrome found at: $path");
                exec("$path --version", $output);
                \Log::info("Version: " . implode(' ', $output));
            }
        }
        
        // Test 3: Simple HTML to PDF
        $pdf = \Spatie\LaravelPdf\Facades\Pdf::view('pdf.test')
            ->format('a4');
            
        \Log::info('PDF object created');
        
        $pdfPath = storage_path('app/public/test.pdf');
        $pdf->save($pdfPath);
        
        \Log::info('PDF saved to: ' . $pdfPath);
        
        if (file_exists($pdfPath)) {
            return response()->file($pdfPath);
        }
        
        return 'PDF generation succeeded but file not found';
        
    } catch (\Exception $e) {
        \Log::error('PDF Test Error: ' . $e->getMessage());
        \Log::error('Stack trace: ' . $e->getTraceAsString());
        return 'Error: ' . $e->getMessage();
    }
})->middleware('auth');

// Customer Seat Booking
Route::middleware(['auth', 'verified', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/auctions/{auction}/seat-booking', [SeatBookingController::class, 'show'])
        ->name('seat-booking.show');
    Route::post('/auctions/{auction}/seat-booking', [SeatBookingController::class, 'book'])
        ->name('seat-booking.book');
    Route::delete('/auctions/{auction}/seat-booking', [SeatBookingController::class, 'cancel'])
        ->name('seat-booking.cancel');
});

require __DIR__.'/auth.php';