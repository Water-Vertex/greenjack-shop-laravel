<?php

use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\SquareWebhookController;
use App\Livewire\Admin\Banners;
use App\Livewire\Admin\Blog;
use App\Livewire\Admin\BrandProblems;
use App\Livewire\Admin\Brands;
use App\Livewire\Admin\Categories;
use App\Livewire\Admin\Dashboard;
use App\Livewire\Admin\Models;
use App\Livewire\Admin\Pages;
use App\Livewire\Admin\Problems;
use App\Livewire\Admin\Products;
use App\Livewire\Admin\RepairRequests;
use App\Livewire\Admin\SubCategories;
use App\Livewire\Admin\PageMetaService;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\Logout;
use App\Livewire\User\Cart;
use App\Livewire\User\Checkout;
use App\Livewire\User\Contact;
use App\Livewire\User\DeviceRepair;
use App\Livewire\User\PrintingMarketing;
use App\Livewire\User\Products as UserProducts;
use App\Livewire\User\Repair;
use App\Livewire\User\Shop;
use App\Livewire\User\ProgramaticSeoPage;
use App\Livewire\User\ShopDetails;
use App\Livewire\User\BlogList;
use App\Livewire\User\BlogDetail;
use App\Livewire\Admin\Settings;
use App\Livewire\Admin\Contacts;
use App\Livewire\Admin\Series;
use App\Livewire\Admin\PageMeta;
use App\Livewire\Admin\Policies;
use App\Livewire\Admin\PrintingRequest;
use App\Livewire\Admin\ProgramaticSeo;
use App\Livewire\Admin\Orders;
use App\Livewire\Admin\Sitemap;
use App\Livewire\Auth\CustomerLogin;
use App\Livewire\Auth\CustomerLogout;
use App\Livewire\Auth\CustomerRegistration;
use App\Livewire\Auth\ForgetPassword;
use App\Livewire\Auth\RepairCustomerLogin;
use App\Livewire\Auth\RepairCustomerLogout;
use App\Livewire\Auth\RepairCustomerRegistration;
use App\Livewire\User\DeviceRepairPage;
use App\Livewire\User\ShopByCategory;
use App\Livewire\User\Blogs as UserBlog;
use App\Livewire\User\Categories as UserCategories;
use App\Livewire\User\Home;
use App\Livewire\User\MyAccount;
use App\Livewire\User\RepairMyAccount;
use App\Livewire\User\OrderDetail;
use App\Livewire\User\PaymentCancelled;
use App\Livewire\User\PaymentSuccess;
use App\Livewire\User\Policy;
use App\Livewire\User\ProgramaticSeo as ProgramaticSeoUser;
use App\Livewire\User\ShopPaymentCancelled;
use App\Livewire\User\ShopPaymentSuccess;
use App\Livewire\User\Wishlist;
use App\Mail\SimpleRepairConfirmation;
use App\Models\RepairRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

// Public routes - no authentication needed
Route::get('/admin/login', Login::class)->name('login');
Route::get('/logout', Logout::class)->name('logout');
Route::get('/register', CustomerRegistration::class)->name('customer.register');
Route::get('/customer-login', CustomerLogin::class)->name('customer.login');
Route::get('/customer-logout', CustomerLogout::class)->name('customer.logout');
Route::get('/repair-customer-register', RepairCustomerRegistration::class)->name('repair.customer.registration');
Route::get('/repair-customer-login', RepairCustomerLogin::class)->name('repair.customer.login');
Route::get('/repair-customer-logout', RepairCustomerLogout::class)->name('repair.customer.logout');
Route::get('/customer/forgot-password', ForgetPassword::class)->name('customer.forgot-password');
Route::get('/customer/reset-password', ForgetPassword::class)->name('customer.reset-password');

Route::get('/home', Home::class)->name('home');
Route::get('/contact-us', Contact::class)->name('contact-us');
Route::get('/device-repairing-categories', DeviceRepairPage::class)->name('device-repair-page');

// Device Repair Routes
Route::get('/device-repair/{slug}', DeviceRepair::class)->name('device-repair');
Route::get('/device-repair/{slug}/{step}', DeviceRepair::class)->name('device-repair.step');

// Payment Routes - FIX: Use the full class names
Route::get('/payment-success/{id}', \App\Livewire\User\PaymentSuccess::class)->name('payment.success');
Route::get('/payment-cancelled/{id}', \App\Livewire\User\PaymentCancelled::class)->name('payment.cancelled');

Route::get('/repair', Repair::class)->name('repair');
Route::get('/printing-marketing', PrintingMarketing::class)->name('printing');
Route::get('/', Shop::class)->name('shop');
Route::get('/my-account', MyAccount::class)->name('customer.account')->middleware('customer.auth');
Route::get('/repair-my-account', RepairMyAccount::class)->name('repair.customer.account')->middleware('customer.auth');
Route::get('/order/{orderId}/details', OrderDetail::class)->name('order.details')->middleware('customer.auth');
Route::get('/products', UserProducts::class)->name('products');
Route::get('/product/{slug}', ShopDetails::class)->name('shop.details');
Route::get('/categries', UserCategories::class)->name('categories');
Route::get('/category/{slug}', ShopByCategory::class)->name('shop.by.category');
Route::get('/cart', Cart::class)->name('cart.page');
Route::get('/checkout', Checkout::class)->name('checkout.page');
Route::get('/blogs', BlogList::class)->name('blogs');
Route::get('/blogs', UserBlog::class)->name('blogs');
Route::get('/blog/{slug}', BlogDetail::class)->name('blog.detail');
Route::get('/wishlist', Wishlist::class)->name('wishlist');
Route::get('/policy/{slug}', Policy::class)->name('policy');
Route::get('/{slug}', ProgramaticSeoUser::class)->name('seo.show');

// Stripe Success Callback - Store session_id in session
Route::get('/checkout/success', function () {
    $sessionId = request()->get('session_id');
    $orderNumber = request()->get('order_number');
    $token = request()->get('token');

    Log::info('Checkout success callback received', [
        'session_id' => $sessionId,
        'order_number' => $orderNumber,
        'token' => $token,
        'all_params' => request()->all()
    ]);

    // Check if it's a Stripe callback (has session_id)
    if ($sessionId) {
        // Store in session directly (not flash)
        Session::put('stripe_session_id', $sessionId);
        Log::info('Stripe session_id stored in session', ['session_id' => $sessionId]);
    }

    // Check if it's a PayPal callback (has token)
    if ($token) {
        Session::put('paypal_token', $token);
        Log::info('PayPal token stored in session', ['token' => $token]);
    }

    return redirect()->route('shop.payment.success', ['id' => $orderNumber]);
})->name('checkout.success');

// Stripe Cancel Callback
Route::get('/checkout/cancel', function () {
    Log::info('Stripe cancel callback received');
    return redirect()->route('checkout.page')->with('error', 'Payment was cancelled.');
})->name('checkout.cancel');

// PayPal Success Callback - Keep for backward compatibility
Route::get('/paypal/success', function () {
    $token = request()->get('token');
    $orderNumber = request()->get('order_number');

    Log::info('PayPal success callback received', [
        'token' => $token,
        'order_number' => $orderNumber
    ]);

    if ($token) {
        Session::put('paypal_token', $token);
    }

    return redirect()->route('shop.payment.success', ['id' => $orderNumber]);
})->name('paypal.success');

// PayPal Cancel Callback
Route::get('/paypal/cancel', function () {
    Log::info('PayPal cancel callback received');
    return redirect()->route('checkout.page')->with('error', 'Payment was cancelled.');
})->name('paypal.cancel');

Route::get('/shop/payment/success/{id}', ShopPaymentSuccess::class)->name('shop.payment.success');
Route::get('/shop/payment/cancelled/{id}', ShopPaymentCancelled::class)->name('shop.payment.cancelled');

Route::get('/checkout/square/callback', Checkout::class)->name('checkout.square.callback');
Route::get('/checkout/cancel', function () {
    return redirect()->route('checkout')->with('error', 'Payment cancelled.');
})->name('checkout.cancel');

Route::get('/test-email-new/{id}', function ($id) {
    try {
        $repair = RepairRequest::with(['category', 'brand', 'model'])->find($id);

        if (!$repair) {
            return "Repair request #{$id} not found.";
        }

        if (empty($repair->email)) {
            return "No email address found for repair request #{$id}.";
        }

        // Force SMTP configuration
        config([
            'mail.default' => 'smtp',
            'mail.mailers.smtp.host' => env('MAIL_HOST', 'thegreenjack.com'),
            'mail.mailers.smtp.port' => env('MAIL_PORT', 465),
            'mail.mailers.smtp.encryption' => env('MAIL_ENCRYPTION', 'ssl'),
            'mail.mailers.smtp.username' => env('MAIL_USERNAME'),
            'mail.mailers.smtp.password' => env('MAIL_PASSWORD'),
            'mail.from.address' => env('MAIL_FROM_ADDRESS'),
            'mail.from.name' => env('MAIL_FROM_NAME'),
        ]);

        // Send email
        Mail::to($repair->email)->send(new SimpleRepairConfirmation($repair));

        return response()->json([
            'success' => true,
            'message' => "Email sent to {$repair->email}",
            'repair_id' => $repair->id,
            'email' => $repair->email,
            'mail_config' => [
                'driver' => config('mail.default'),
                'host' => config('mail.mailers.smtp.host'),
                'port' => config('mail.mailers.smtp.port'),
                'encryption' => config('mail.mailers.smtp.encryption'),
                'from' => config('mail.from.address'),
            ]
        ]);

    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString(),
        ], 500);
    }
});

// Admin routes - requires authentication
Route::middleware(['auth', 'preventback'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', Dashboard::class)->name('index');
    Route::get('/pages', Pages::class)->name('pages');
    Route::get('/policies', Policies::class)->name('policies');
    Route::get('/categories', Categories::class)->name('categories');
    Route::get('/sub-categories', SubCategories::class)->name('sub-categories');
    Route::get('/products', Products::class)->name('products');
    Route::get('/brands', Brands::class)->name('brands');
    Route::get('/brand-models', Models::class)->name('brand-models');
    Route::get('/problems', Problems::class)->name('problems');
    Route::get('/series', Series::class)->name('series');
    Route::get('/brand-problems', BrandProblems::class)->name('brand-problems');
    Route::get('/repair-requests', RepairRequests::class)->name('repair-requests');
    Route::get('/settings', Settings::class)->name('settings');
    Route::get('/seo-settings', PageMeta::class)->name('seo-settings');
    Route::get('/contacts', Contacts::class)->name('contacts');
    Route::get('/printing-requests', PrintingRequest::class)->name('printing-requests');
    Route::get('/blogs', Blog::class)->name('blogs');
    Route::get('/banners', Banners::class)->name('banners');
    Route::get('/programatic-seo', ProgramaticSeo::class)->name('programatic-seo');
    Route::get('/orders', Orders::class)->name('orders');
});

Route::get('/sitemap', Sitemap::class)->name('admin.sitemap');

    // YEH NAYA ROUTE ADD KARO
    Route::get('/sitemap/download-xml', function () {
        $urls = [];

        $statics = [
            ['url' => url('/'),           'priority' => '1.0', 'changefreq' => 'daily',   'lastmod' => now()->toDateString()],
            ['url' => url('/contact-us'), 'priority' => '0.7', 'changefreq' => 'monthly', 'lastmod' => now()->toDateString()],
            ['url' => url('/blogs'),      'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString()],
            ['url' => url('/shop'),       'priority' => '0.9', 'changefreq' => 'weekly',  'lastmod' => now()->toDateString()],
        ];
        $urls = array_merge($urls, $statics);

        \App\Models\Blog::latest()->get()->each(fn($i) => $urls[] = [
            'url' => url('/blog/' . $i->slug), 'priority' => '0.8',
            'changefreq' => 'monthly', 'lastmod' => optional($i->updated_at)->toDateString()
        ]);
        \App\Models\Category::latest()->get()->each(fn($i) => $urls[] = [
            'url' => url('/category/' . $i->slug), 'priority' => '0.6',
            'changefreq' => 'weekly', 'lastmod' => optional($i->updated_at)->toDateString()
        ]);
        \App\Models\Product::latest()->get()->each(fn($i) => $urls[] = [
            'url' => url('/product/' . $i->slug), 'priority' => '0.7',
            'changefreq' => 'weekly', 'lastmod' => optional($i->updated_at)->toDateString()
        ]);

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";
        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . e($url['url']) . "</loc>\n";
            $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>";

        return response()->streamDownload(
            fn() => print($xml),
            'sitemap.xml',
            ['Content-Type' => 'application/xml']
        );
    })->name('sitemap.download');
