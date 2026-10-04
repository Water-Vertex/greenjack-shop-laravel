<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Policy;
use App\Models\Product;
use App\Models\Category;
use App\Models\SubCategory;
use App\Models\Blog;
use App\Models\Brand;
use App\Models\BrandModel;
use App\Models\Series;
use App\Models\Page;
use App\Models\ProgramaticSeo;
use App\Models\Problem;
use App\Models\Attribute;
use App\Models\AttributeValue;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class SitemapController extends Controller
{
    // ===== STATIC PAGES =====
    private function getStaticPages(): array
    {
        return [
            [
                'url'        => url('/'),
                'title'      => 'Home',
                'priority'   => '1.0',
                'changefreq' => 'daily',
                'lastmod'    => now()->format('Y-m-d'),
            ],
            [
                'url'        => url('/products'),
                'title'      => 'Shop',
                'priority'   => '1.0',
                'changefreq' => 'monthly',
                'lastmod'    => now()->format('Y-m-d'),
            ],
            [
                'url'        => url('/contact-us'),
                'title'      => 'Contact Us',
                'priority'   => '0.7',
                'changefreq' => 'monthly',
                'lastmod'    => now()->format('Y-m-d'),
            ],
        ];
    }

    // ===== HELPER: SAFE MAP =====
    private function safeMap(string $modelClass, callable $callback): array
    {
        try {
            if (!class_exists($modelClass)) {
                return [];
            }
            return $modelClass::query()
                ->get()
                ->map($callback)
                ->filter()
                ->values()
                ->toArray();
        } catch (\Exception $e) {
            return [];
        }
    }

    // ===== DYNAMIC CONTENT =====
    private function getDynamicContent(): array
    {
        $categories = $this->safeMap(Category::class, function ($cat) {
            $slug = $cat->slug ?? Str::slug($cat->name ?? 'category-' . $cat->id);
            return [
                'url'        => url('/category/' . $slug),
                'title'      => $cat->name ?? 'Category',
                'priority'   => '0.9',
                'changefreq' => 'weekly',
                'lastmod'    => optional($cat->updated_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
            ];
        });

        $subcategories = $this->safeMap(SubCategory::class, function ($sub) {
            $slug = $sub->slug ?? Str::slug($sub->name ?? 'subcategory-' . $sub->id);
            return [
                'url'        => url('/subcategory/' . $slug),
                'title'      => $sub->name ?? 'SubCategory',
                'priority'   => '0.8',
                'changefreq' => 'weekly',
                'lastmod'    => optional($sub->updated_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
            ];
        });

        $products = $this->safeMap(Product::class, function ($product) {
            $slug = $product->slug ?? Str::slug($product->name ?? 'product-' . $product->id);
            return [
                'url'        => url('/product/' . $slug),
                'title'      => $product->name ?? 'Product',
                'priority'   => '0.9',
                'changefreq' => 'daily',
                'lastmod'    => optional($product->updated_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
            ];
        });

        $blogs = $this->safeMap(Blog::class, function ($blog) {
            $slug = $blog->slug ?? Str::slug($blog->title ?? 'blog-' . $blog->id);
            return [
                'url'        => url('/blog/' . $slug),
                'title'      => $blog->title ?? 'Blog',
                'priority'   => '0.7',
                'changefreq' => 'weekly',
                'lastmod'    => optional($blog->updated_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
            ];
        });

        $policies = $this->safeMap(Policy::class, function ($policy) {
            $slug = $policy->slug ?? Str::slug($policy->title ?? 'policy-' . $policy->id);
            return [
                'url'        => url('/policy/' . $slug),
                'title'      => $policy->title ?? 'Policy',
                'priority'   => '0.6',
                'changefreq' => 'monthly',
                'lastmod'    => optional($policy->updated_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
            ];
        });



        $seoPages = $this->safeMap(ProgramaticSeo::class, function ($seo) {
            $slug = $seo->slug ?? Str::slug($seo->title ?? 'seo-' . $seo->id);
            return [
                'url'        => url('/' . $slug),
                'title'      => $seo->title ?? 'SEO Page',
                'priority'   => '0.6',
                'changefreq' => 'weekly',
                'lastmod'    => optional($seo->updated_at)->format('Y-m-d') ?? now()->format('Y-m-d'),
            ];
        });

        return [
            'categories'    => $categories,
            'subcategories' => $subcategories,
            'products'      => $products,
            'blogs'         => $blogs,
            'policies'      => $policies,
            'seoPages'      => $seoPages,
        ];
    }

    // ===== ALL URLS =====
    private function getAllUrls(): array
    {
        $all     = $this->getStaticPages();
        $dynamic = $this->getDynamicContent();

        foreach ($dynamic as $items) {
            foreach ($items as $item) {
                $all[] = $item;
            }
        }

        return $all;
    }

    // ===== URL COUNTS =====
    private function getUrlCounts(): array
    {
        $dynamic = $this->getDynamicContent();
        $static  = count($this->getStaticPages());

        $counts = ['static' => $static];

        foreach ($dynamic as $key => $items) {
            $counts[$key] = count($items);
        }

        $counts['total'] = array_sum($counts);
        return $counts;
    }

    // ===== API: Get Sitemap Data for Angular =====
    public function getSitemapData()
    {
        return response()->json([
            'success'        => true,
            'staticPages'    => $this->getStaticPages(),
            'dynamicContent' => $this->getDynamicContent(),
            'urlCounts'      => $this->getUrlCounts(),
            'lastGenerated'  => now()->format('Y-m-d H:i:s'),
            'allUrls'        => $this->getAllUrls(),
        ]);
    }

    // ===== API: Download XML =====
    public function downloadXml()
    {
        $xml = $this->buildXml();

        return Response::make($xml, 200, [
            'Content-Type'        => 'application/xml',
            'Content-Disposition' => 'attachment; filename="sitemap.xml"',
        ]);
    }

    // ===== API: Download HTML =====
    public function downloadHtml()
    {
        $html = $this->buildHtml();

        return Response::make($html, 200, [
            'Content-Type'        => 'text/html',
            'Content-Disposition' => 'attachment; filename="sitemap.html"',
        ]);
    }

    // ===== API: Preview XML =====
    public function previewXml()
    {
        return Response::make($this->buildXml(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    // ===== WEB: View XML =====
    public function viewXml()
    {
        return Response::make($this->buildXml(), 200, [
            'Content-Type' => 'application/xml',
        ]);
    }

    // ===== BUILD XML =====
    private function buildXml(): string
    {
        $urls = $this->getAllUrls();

        $xml  = '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        foreach ($urls as $url) {
            $xml .= "  <url>\n";
            $xml .= "    <loc>" . htmlspecialchars($url['url']) . "</loc>\n";
            $xml .= "    <lastmod>{$url['lastmod']}</lastmod>\n";
            $xml .= "    <changefreq>{$url['changefreq']}</changefreq>\n";
            $xml .= "    <priority>{$url['priority']}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return $xml;
    }

    // ===== BUILD HTML SITEMAP (nested tree with Tailwind + FontAwesome) =====
private function buildHtml(): string
{
    $baseUrl     = url('/');
    $domain      = parse_url($baseUrl, PHP_URL_HOST);
    $staticPages = $this->getStaticPages();
    $dynamic     = $this->getDynamicContent();

    $categories     = $dynamic['categories'] ?? [];
    $subcategories  = $dynamic['subcategories'] ?? [];
    $products       = $dynamic['products'] ?? [];
    $blogs          = $dynamic['blogs'] ?? [];
    $seoPages       = $dynamic['seoPages'] ?? [];
    $policies       = $dynamic['policies'] ?? [];

    $totalDynamic = count($categories) + count($subcategories) + count($products)
                  + count($blogs)  + count($policies) + count($seoPages);

    // ---- Render one branch of the nested tree ----
    $renderBranch = function (string $label, array $items, string $icon, string $id) {
        if (empty($items)) return '';

        $out  = '
        <li class="tree-item">
            <button type="button" class="tree-toggle w-full flex items-center gap-3 px-4 py-3
                    bg-white border border-gray-200 rounded-xl hover:border-blue-300
                    hover:bg-blue-50 transition-all text-left group"
                    data-target="' . $id . '">
                <i class="' . $icon . ' text-blue-500 text-base w-5 text-center"></i>
                <span class="flex-1 font-semibold text-gray-800 text-sm">' . htmlspecialchars($label) . '</span>
                <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">'
                    . count($items) . '</span>
                <i class="fas fa-chevron-right text-gray-400 text-xs transition-transform duration-200 tree-arrow"></i>
            </button>

            <ul id="' . $id . '" class="tree-children hidden ml-6 mt-2 space-y-1 border-l-2 border-gray-100 pl-3">';

        foreach ($items as $item) {
            $out .= '
                <li>
                    <a href="' . htmlspecialchars($item['url']) . '" target="_blank"
                       class="flex items-center gap-2 px-3 py-2 rounded-lg text-sm text-gray-600
                              hover:bg-blue-50 hover:text-blue-600 transition-colors">
                        <i class="fas fa-link text-[10px] text-gray-400"></i>
                        <span class="truncate">' . htmlspecialchars($item['title']) . '</span>
                    </a>
                </li>';
        }

        $out .= '
            </ul>
        </li>';

        return $out;
    };

    $html = '<!DOCTYPE HTML>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Sitemap - ' . htmlspecialchars($domain) . '</title>
    <meta name="description" content="' . htmlspecialchars($domain) . ' Sitemap" />
    <meta name="keywords" content="' . htmlspecialchars($domain) . ', Sitemap" />
    <meta name="generator" content="Dynamic Sitemap Generator" />

    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Font Awesome -->
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css"
          integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA=="
          crossorigin="anonymous" referrerpolicy="no-referrer" />

    <style>
        .tree-children { transition: none; }
        .tree-toggle.active .tree-arrow { transform: rotate(90deg); }
        .tree-toggle.active { border-color: #3b82f6; background: #eff6ff; }
    </style>
</head>
<body class="bg-gray-50 min-h-screen">

    <div class="max-w-5xl mx-auto p-4 md:p-8">

        <!-- Header -->
        <div class="bg-white rounded-2xl border border-gray-200 p-6 md:p-8 shadow-sm mb-6">
            <div class="flex items-center gap-3 mb-2">
                <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center">
                    <i class="fas fa-sitemap text-blue-600 text-xl"></i>
                </div>
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-900">Sitemap</h1>
                    <p class="text-sm text-gray-500">' . htmlspecialchars($domain) . '</p>
                </div>
            </div>

            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 mt-6">
                <div class="bg-blue-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-blue-600">' . count($staticPages) . '</p>
                    <p class="text-xs text-blue-800 font-medium">Static Pages</p>
                </div>
                <div class="bg-emerald-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-emerald-600">' . count($categories) . '</p>
                    <p class="text-xs text-emerald-800 font-medium">Categories</p>
                </div>
                <div class="bg-amber-50 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-amber-600">' . count($products) . '</p>
                    <p class="text-xs text-amber-800 font-medium">Products</p>
                </div>
                <div class="bg-gray-100 rounded-xl p-3 text-center">
                    <p class="text-2xl font-bold text-gray-700">' . ($totalDynamic + count($staticPages)) . '</p>
                    <p class="text-xs text-gray-600 font-medium">Total URLs</p>
                </div>
            </div>

            <a href="' . $baseUrl . '"
               class="inline-flex items-center gap-2 mt-6 px-4 py-2 bg-blue-600 text-white
                      rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                <i class="fas fa-home"></i>
                Back to Home
            </a>
        </div>

        <!-- Static Pages -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 md:p-6 shadow-sm mb-6">
            <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-file text-blue-500"></i>
                Static Pages
                <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">'
                    . count($staticPages) . '</span>
            </h2>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">';

    foreach ($staticPages as $page) {
        $html .= '
            <a href="' . htmlspecialchars($page['url']) . '" target="_blank"
               class="flex items-center gap-3 px-4 py-3 bg-white border border-gray-200
                      rounded-xl hover:border-blue-300 hover:bg-blue-50 transition-all group">
                <i class="fas fa-globe text-blue-500"></i>
                <span class="font-medium text-gray-800 text-sm truncate">'
                    . htmlspecialchars($page['title']) . '</span>
                <i class="fas fa-external-link-alt text-gray-300 text-xs ml-auto
                          group-hover:text-blue-500 transition-colors"></i>
            </a>';
    }

    $html .= '
            </div>
        </div>

        <!-- Dynamic Content - Nested Tree -->
        <div class="bg-white rounded-2xl border border-gray-200 p-4 md:p-6 shadow-sm">
            <h2 class="flex items-center gap-2 text-lg font-bold text-gray-800 mb-4">
                <i class="fas fa-folder-tree text-emerald-500"></i>
                Dynamic Content
                <span class="text-xs font-medium text-gray-400 bg-gray-100 px-2 py-0.5 rounded-full">'
                    . $totalDynamic . '</span>
            </h2>

            <ul class="space-y-2">';

    $html .= $renderBranch('Categories',    $categories,    'fas fa-folder',      'tree-categories');
    $html .= $renderBranch('Subcategories', $subcategories, 'fas fa-folder-open', 'tree-subcategories');
    $html .= $renderBranch('Products',      $products,      'fas fa-box',         'tree-products');
    $html .= $renderBranch('Blogs',         $blogs,         'fas fa-newspaper',   'tree-blogs');
    $html .= $renderBranch('Policies',      $policies,      'fas fa-scale-balanced', 'tree-policies');
    $html .= $renderBranch('SEO Pages',     $seoPages,      'fas fa-magnifying-glass', 'tree-seo');

    $html .= '
            </ul>
        </div>

        <!-- Footer -->
        <div class="text-center mt-8 text-xs text-gray-400">
            <p>Generated on ' . now()->format('Y-m-d H:i:s') . '</p>
        </div>

    </div>

    <!-- Tree toggle script -->
    <script>
        document.querySelectorAll(".tree-toggle").forEach(function (btn) {
            btn.addEventListener("click", function (e) {
                e.preventDefault();

                var targetId = this.getAttribute("data-target");
                var children = document.getElementById(targetId);
                if (!children) return;

                var isOpen = !children.classList.contains("hidden");

                // Accordion behavior — close all others
                document.querySelectorAll(".tree-children").forEach(function (ul) {
                    if (ul.id !== targetId) ul.classList.add("hidden");
                });
                document.querySelectorAll(".tree-toggle").forEach(function (b) {
                    if (b !== btn) b.classList.remove("active");
                });

                // Toggle current
                if (isOpen) {
                    children.classList.add("hidden");
                    this.classList.remove("active");
                } else {
                    children.classList.remove("hidden");
                    this.classList.add("active");
                }
            });
        });
    </script>

</body>
</html>';

    return $html;
}

    // ===== REGENERATE: SAVE XML + HTML TO ROOT & PUBLIC =====
    public function regenerateXml()
    {
        Log::info('=== regenerateXml CALLED ===', [
            'time'        => now()->toDateTimeString(),
            'base_path'   => base_path(),
            'public_path' => public_path(),
        ]);

        try {
            $xml  = $this->buildXml();
            $html = $this->buildHtml();

            if (empty($xml))  throw new \Exception('XML content is empty');
            if (empty($html)) throw new \Exception('HTML content is empty');

            $results = [];

            // ---- Root directory ----
            $rootXml  = base_path('sitemap.xml');
            $rootHtml = base_path('sitemap.html');

            $w1 = @file_put_contents($rootXml, $xml);
            $w2 = @file_put_contents($rootHtml, $html);

            $results['root'] = [
                'xml'  => ['path' => $rootXml,  'written' => $w1 !== false, 'bytes' => $w1 ?: 0, 'exists' => file_exists($rootXml)],
                'html' => ['path' => $rootHtml, 'written' => $w2 !== false, 'bytes' => $w2 ?: 0, 'exists' => file_exists($rootHtml)],
            ];

            // ---- Public directory ----
            $publicXml  = public_path('sitemap.xml');
            $publicHtml = public_path('sitemap.html');

            $w3 = @file_put_contents($publicXml, $xml);
            $w4 = @file_put_contents($publicHtml, $html);

            $results['public'] = [
                'xml'  => ['path' => $publicXml,  'written' => $w3 !== false, 'bytes' => $w3 ?: 0, 'exists' => file_exists($publicXml)],
                'html' => ['path' => $publicHtml, 'written' => $w4 !== false, 'bytes' => $w4 ?: 0, 'exists' => file_exists($publicHtml)],
            ];

            $anySuccess = ($w1 !== false) || ($w2 !== false) || ($w3 !== false) || ($w4 !== false);

            if (!$anySuccess) {
                return response()->json([
                    'success'  => false,
                    'message'  => 'Files could not be written anywhere.',
                    'attempts' => $results,
                ], 500);
            }

            Log::info('=== Sitemap files written ===', $results);

            return response()->json([
                'success'      => true,
                'message'      => 'Sitemap (XML + HTML) regenerated successfully in root and public.',
                'files'        => $results,
                'xml_url'      => url('/sitemap.xml'),
                'html_url'     => url('/sitemap.html'),
                'generated_at' => now()->format('Y-m-d H:i:s'),
                'urlCounts'    => $this->getUrlCounts(),
            ]);

        } catch (\Exception $e) {
            Log::error('=== Sitemap regeneration FAILED ===', [
                'error' => $e->getMessage(),
                'file'  => $e->getFile(),
                'line'  => $e->getLine(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Sitemap generation failed.',
                'error'   => $e->getMessage(),
                'file'    => $e->getFile(),
                'line'    => $e->getLine(),
            ], 500);
        }
    }
}
