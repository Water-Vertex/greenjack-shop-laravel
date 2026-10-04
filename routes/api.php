<?php
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AdminDashboardController;
use App\Http\Controllers\Api\AttributeController;
use App\Http\Controllers\Api\ApiAttributeController;
use App\Http\Controllers\Api\AttributeValueController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BannerController;
use App\Http\Controllers\Api\BlogController;
use App\Http\Controllers\Api\BrandController;
use App\Http\Controllers\Api\BrandModelController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\PageController;
use App\Http\Controllers\Api\PageMetaController;
use App\Http\Controllers\Api\PolicyController;
use App\Http\Controllers\Api\PrintingRequestController;
use App\Http\Controllers\Api\ProblemController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ProgramaticSeoController;
use App\Http\Controllers\Api\RepairRequestController;
use App\Http\Controllers\Api\SeriesController;
use App\Http\Controllers\Api\SettingsController;
use App\Http\Controllers\Api\SubCategoryController;
use App\Http\Controllers\Api\SitemapController;
use App\Http\Controllers\Api\PosApiController;
use App\Models\Student;

// ============================================
// PUBLIC ROUTES - NO AUTHENTICATION REQUIRED
// ============================================

// Admin Public Routes
Route::post('admin/login', [AuthController::class, 'login']);
Route::post('/admin/forgot-password', [AuthController::class, 'adminForgotPassword']);
Route::post('/admin/validate-token', [AuthController::class, 'adminValidateToken']);
Route::post('/admin/reset-password', [AuthController::class, 'adminResetPassword']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

Route::get('/admin/sitemap', [SitemapController::class, 'getSitemapData']);
Route::get('/admin/sitemap/download', [SitemapController::class, 'downloadXml']);
Route::get('/admin/sitemap/preview',   [SitemapController::class, 'previewXml']);
Route::post('/admin/sitemap/regenerate', [SitemapController::class, 'regenerateXml']);

// ============================================
// ADMIN PROTECTED ROUTES
// ============================================
Route::middleware('auth:sanctum')->prefix('admin')->group(function () {
Route::put('/reset-password', [AuthController::class, 'AdminresetPassword']);
    Route::post('/change-password', [AuthController::class, 'adminChangePassword']);
    // Dashboard routes
    Route::prefix('dashboard')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index']);
        Route::get('/quick-stats', [AdminDashboardController::class, 'getQuickStats']);
        Route::get('/revenue-summary', [AdminDashboardController::class, 'getRevenueSummary']);
        Route::get('/order-status-distribution', [AdminDashboardController::class, 'getOrderStatusDistribution']);
    });

    // Category Routes
    Route::prefix('categories')->group(function () {
        Route::get('/', [CategoryController::class, 'index']);
        Route::post('/', [CategoryController::class, 'store']);
        Route::get('/parent', [CategoryController::class, 'getParentCategories']);
        Route::post('/analyze-seo', [CategoryController::class, 'analyzeSeo']);
        Route::get('/{id}', [CategoryController::class, 'show']);
        Route::put('/{id}', [CategoryController::class, 'update']);
        Route::delete('/{id}', [CategoryController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [CategoryController::class, 'toggleStatus']);
        Route::delete('/{id}/image', [CategoryController::class, 'removeImage']);
        Route::post('/{id}/analyze-seo', [CategoryController::class, 'analyzeSeo']);
    });

    // SubCategory Routes
    Route::prefix('subcategories')->group(function () {
        Route::get('/', [SubCategoryController::class, 'index']);
        Route::post('/', [SubCategoryController::class, 'store']);
        Route::post('/analyze-seo', [SubCategoryController::class, 'analyzeSeo']);
        Route::get('/{id}', [SubCategoryController::class, 'show']);
        Route::put('/{id}', [SubCategoryController::class, 'update']);
        Route::delete('/{id}', [SubCategoryController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [SubCategoryController::class, 'toggleStatus']);
        Route::delete('/{id}/image', [SubCategoryController::class, 'removeImage']);
        Route::post('/{id}/analyze-seo', [SubCategoryController::class, 'analyzeSeo']);
    });

    // ============================================
    // ATTRIBUTE ROUTES (NEW)
    // ============================================
    Route::prefix('attributes')->group(function () {
        Route::get('/', [AttributeController::class, 'index']);
        Route::post('/', [AttributeController::class, 'store']);
        Route::get('/{id}', [AttributeController::class, 'show']);
        Route::put('/{id}', [AttributeController::class, 'update']);
        Route::delete('/{id}', [AttributeController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [AttributeController::class, 'toggleStatus']);
        Route::get('/{id}/values', [AttributeController::class, 'getValues']);
    });

    // Attribute Values Routes
    Route::prefix('attribute-values')->group(function () {
        Route::get('/', [AttributeValueController::class, 'index']);
        Route::post('/', [AttributeValueController::class, 'store']);
        Route::get('/{id}', [AttributeValueController::class, 'show']);
        Route::put('/{id}', [AttributeValueController::class, 'update']);
        Route::delete('/{id}', [AttributeValueController::class, 'destroy']);
        Route::delete('/{id}/image', [AttributeValueController::class, 'removeImage']);
    });

    // ============================================
    // PRODUCT ROUTES (UPDATED)
    // ============================================
    Route::prefix('products')->group(function () {
        // Main CRUD
        Route::get('/', [ProductController::class, 'index']);
        Route::post('/', [ProductController::class, 'store']);
        Route::get('/{id}', [ProductController::class, 'show']);
        Route::put('/{id}', [ProductController::class, 'update']);
        Route::delete('/{id}', [ProductController::class, 'destroy']);

        // Status Management
        Route::patch('/{id}/toggle-status', [ProductController::class, 'toggleStatus']);

        // Image Management
        Route::delete('/{id}/image', [ProductController::class, 'removeImage']);
        Route::delete('/{id}/gallery/{imageId}', [ProductController::class, 'removeGalleryImage']);

        // Import/Export
        Route::get('/template', [ProductController::class, 'downloadTemplate']);
        Route::post('/import', [ProductController::class, 'importProducts']);

        // ============================================
        // ATTRIBUTE & VARIATION ROUTES (NEW)
        // ============================================

        // Get available attributes for product creation
        Route::get('/attributes/available', [ProductController::class, 'getAvailableAttributes']);

        // Get product specific attributes
        Route::get('/{id}/attributes', [ProductController::class, 'getAttributes']);

        // Sync product attributes
        Route::post('/{id}/attributes/sync', [ProductController::class, 'syncAttributes']);

        // Get product variations
        Route::get('/{id}/variations', [ProductController::class, 'getVariations']);

        // Update variation stock
        Route::post('/variations/{variationId}/stock', [ProductController::class, 'updateVariationStock']);

        // Remove variation image
        Route::delete('/variations/{variationId}/image', [ProductController::class, 'removeVariationImage']);

        // Delete a single variation
        Route::delete('/{id}/variations/{variationId}', [ProductController::class, 'deleteVariation']);
        
        Route::delete('/{id}/variations', [ProductController::class, 'deleteAllVariations']);

        // Update variation status
        Route::patch('/variations/{variationId}/toggle-status', [ProductController::class, 'toggleVariationStatus']);
    });
    
     Route::prefix('apiattributes')->group(function () {
        Route::get('/', [ApiAttributeController::class, 'index']);
        Route::post('/', [ApiAttributeController::class, 'store']);
        Route::get('/{id}', [ApiAttributeController::class, 'show']);
        Route::put('/{id}', [ApiAttributeController::class, 'update']); // Use PUT instead of POST
        Route::delete('/{id}', [ApiAttributeController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [ApiAttributeController::class, 'toggleStatus']);
        Route::get('/{id}/values', [ApiAttributeController::class, 'getValues']);
    });

     // Brand Routes
    Route::prefix('brands')->group(function () {
        Route::get('/', [BrandController::class, 'index']);
        Route::post('/', [BrandController::class, 'store']);
        Route::get('/{id}', [BrandController::class, 'show']);
        Route::put('/{id}', [BrandController::class, 'update']);
        Route::delete('/{id}', [BrandController::class, 'destroy']);
        Route::delete('/{id}/image', [BrandController::class, 'removeImage']);
    });

    // Brand Model Routes
    Route::prefix('brand-models')->group(function () {
        // STATIC ROUTES (must come before {id} routes)
        Route::get('/categories', [BrandModelController::class, 'getCategories']);
        Route::get('/brands-by-category', [BrandModelController::class, 'getBrandsByCategory']);
        Route::get('/all-brands', [BrandModelController::class, 'getAllBrands']);
        Route::post('/check-slug', [BrandModelController::class, 'checkSlug']);
        Route::post('/bulk-delete', [BrandModelController::class, 'bulkDelete']);

        // DYNAMIC ROUTES (with {id} parameters)
        Route::get('/', [BrandModelController::class, 'index']);
        Route::post('/', [BrandModelController::class, 'store']);
        Route::get('/{id}', [BrandModelController::class, 'show']);
        Route::put('/{id}', [BrandModelController::class, 'update']);
        Route::delete('/{id}', [BrandModelController::class, 'destroy']);
        Route::delete('/{id}/image', [BrandModelController::class, 'removeImage']);
    });

    // Series Routes
    Route::prefix('series')->group(function () {
        Route::get('/', [SeriesController::class, 'index']);
        Route::post('/', [SeriesController::class, 'store']);

        // Static routes (must come before {id} routes)
        Route::get('/brands-with-models', [SeriesController::class, 'getBrandsWithModels']);
        Route::get('/models-by-category-brand', [SeriesController::class, 'getModelsByCategoryAndBrand']);

        // Dynamic routes
        Route::get('/{id}', [SeriesController::class, 'show']);
        Route::put('/{id}', [SeriesController::class, 'update']);
        Route::delete('/{id}', [SeriesController::class, 'destroy']);
    });

    Route::prefix('problems')->group(function () {
        Route::get('/', [ProblemController::class, 'index']);
        Route::post('/', [ProblemController::class, 'store']);
        Route::get('/{id}', [ProblemController::class, 'show']);
        Route::put('/{id}', [ProblemController::class, 'update']);
        Route::delete('/{id}', [ProblemController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [ProblemController::class, 'toggleStatus']);
    });

    // Repair Request Routes
    Route::prefix('repair-requests')->group(function () {
        Route::get('/', [RepairRequestController::class, 'index']);
        Route::get('/status-counts', [RepairRequestController::class, 'getStatusCounts']);
        Route::get('/{id}', [RepairRequestController::class, 'show']);
        Route::put('/{id}/status', [RepairRequestController::class, 'update']);
        Route::delete('/{id}', [RepairRequestController::class, 'destroy']);
    });

    // Printing Request Routes
    Route::prefix('printing-requests')->group(function () {
        Route::get('/', [PrintingRequestController::class, 'index']);
        Route::get('/{id}', [PrintingRequestController::class, 'show']);
        Route::patch('/{id}/status', [PrintingRequestController::class, 'updateStatus']);
        Route::delete('/{id}', [PrintingRequestController::class, 'destroy']);
    });

    Route::prefix('blogs')->group(function () {
        Route::get('/', [BlogController::class, 'index']);
        Route::post('/', [BlogController::class, 'store']);
        Route::post('/analyze-seo', [BlogController::class, 'analyzeSeo']);
        Route::get('/{id}', [BlogController::class, 'show']);
        Route::put('/{id}', [BlogController::class, 'update']);
        Route::delete('/{id}', [BlogController::class, 'destroy']);
        Route::patch('/{id}/toggle-status', [BlogController::class, 'toggleStatus']);
        Route::delete('/{id}/image', [BlogController::class, 'removeImage']);
        Route::post('/{id}/analyze-seo', [BlogController::class, 'analyzeSeo']);
    });

    // Page Routes
    Route::prefix('pages')->group(function () {
        Route::get('/', [PageController::class, 'index']);
        Route::post('/', [PageController::class, 'store']);
        Route::post('/analyze-seo', [PageController::class, 'analyzeSeo']);
        Route::get('/{id}', [PageController::class, 'show']);
        Route::put('/{id}', [PageController::class, 'update']);
        Route::delete('/{id}', [PageController::class, 'destroy']);
        Route::delete('/{id}/image/{imageType}', [PageController::class, 'removeImage']);
        Route::post('/{id}/analyze-seo', [PageController::class, 'analyzeSeo']);
    });

    // Policy Routes
    Route::prefix('policies')->group(function () {
        Route::get('/', [PolicyController::class, 'index']);
        Route::post('/', [PolicyController::class, 'store']);
        Route::post('/analyze-seo', [PolicyController::class, 'analyzeSeo']);
        Route::get('/{id}', [PolicyController::class, 'show']);
        Route::put('/{id}', [PolicyController::class, 'update']);
        Route::delete('/{id}', [PolicyController::class, 'destroy']);
        Route::post('/{id}/analyze-seo', [PolicyController::class, 'analyzeSeo']);
    });

    // Banner Routes
    Route::prefix('banners')->group(function () {
        Route::get('/', [BannerController::class, 'index']);
        Route::post('/', [BannerController::class, 'store']);
        Route::get('/{id}', [BannerController::class, 'show']);
        Route::put('/{id}', [BannerController::class, 'update']);
        Route::delete('/{id}', [BannerController::class, 'destroy']);
        Route::delete('/{id}/image', [BannerController::class, 'removeImage']);
    });

    // Page Meta Routes
    Route::prefix('page-meta')->group(function () {
        Route::get('/', [PageMetaController::class, 'index']);
        Route::put('/', [PageMetaController::class, 'update']);
        Route::post('/analyze-seo/{page}', [PageMetaController::class, 'analyzeSeo']);
        Route::get('/overall-health', [PageMetaController::class, 'getOverallHealth']);
    });

    // Programmatic SEO Routes
    Route::prefix('programatic-seo')->group(function () {
        Route::get('/', [ProgramaticSeoController::class, 'index']);
        Route::get('/template', [ProgramaticSeoController::class, 'downloadTemplate']);
        Route::post('/import', [ProgramaticSeoController::class, 'import']);
        Route::get('/{id}', [ProgramaticSeoController::class, 'show']);
        Route::put('/{id}', [ProgramaticSeoController::class, 'update']);
        Route::delete('/{id}', [ProgramaticSeoController::class, 'destroy']);
    });

    // Settings Routes
    Route::prefix('settings')->group(function () {
        Route::get('/', [SettingsController::class, 'index']);
        Route::post('/', [SettingsController::class, 'update']);
        Route::delete('/image/{type}', [SettingsController::class, 'removeImage']);
    });

    // Order Routes
    Route::prefix('orders')->group(function () {
        Route::get('/', [OrderController::class, 'index']);
        Route::get('/{id}', [OrderController::class, 'show']);
        Route::patch('/{id}/status', [OrderController::class, 'updateStatus']);
        Route::delete('/{id}', [OrderController::class, 'destroy']);
    });
});



    Route::get('/categories', [PosApiController::class, 'categories']);
    Route::get('/products', [PosApiController::class, 'products']);
    
Route::prefix('orders')->group(function () {
    // Get all orders with filters
    Route::get('/', [PosApiController::class, 'getOrders']);
    
    // Get order statistics
    Route::get('/stats', [PosApiController::class, 'getOrderStats']);
    
    // Get single order
    Route::get('/{id}', [PosApiController::class, 'getOrder']);
    
    // Update order status
    Route::put('/{id}/status', [PosApiController::class, 'updateOrderStatus']);
    
    // Delete order
    Route::delete('/{id}', [PosApiController::class, 'deleteOrder']);
});
