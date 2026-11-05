<?php
declare(strict_types=1);

namespace App\Controllers;

use App\Core\Controller;
use App\Models\{Vehicle, TourPackage, BlogPost, VehicleCategory};

/**
 * SeoController — dynamic XML sitemap and robots.txt.
 */
class SeoController extends Controller
{
    public function sitemap(array $params = []): void
    {
        $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');

        $vehicles   = (new Vehicle())->getActiveVehicles();
        $packages   = (new TourPackage())->getActive();
        $posts      = (new BlogPost())->all(['status' => 'published'], 'published_at DESC');
        $categories = (new VehicleCategory())->getActive();

        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Static pages
        $staticUrls = [
            ['loc' => '/',                  'priority' => '1.0', 'changefreq' => 'daily'],
            ['loc' => '/fleet',             'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => '/packages',          'priority' => '0.9', 'changefreq' => 'weekly'],
            ['loc' => '/airport-transfers', 'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/booking',           'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/contact',           'priority' => '0.8', 'changefreq' => 'monthly'],
            ['loc' => '/about',             'priority' => '0.7', 'changefreq' => 'monthly'],
            ['loc' => '/gallery',           'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => '/testimonials',     'priority' => '0.6', 'changefreq' => 'weekly'],
            ['loc' => '/faq',              'priority' => '0.6', 'changefreq' => 'monthly'],
            ['loc' => '/blog',             'priority' => '0.8', 'changefreq' => 'daily'],
            ['loc' => '/privacy',          'priority' => '0.3', 'changefreq' => 'yearly'],
            ['loc' => '/terms',            'priority' => '0.3', 'changefreq' => 'yearly'],
        ];

        foreach ($staticUrls as $u) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($appUrl . $u['loc']) . "</loc>\n";
            echo "    <changefreq>" . $u['changefreq'] . "</changefreq>\n";
            echo "    <priority>" . $u['priority'] . "</priority>\n";
            echo "  </url>\n";
        }

        // Fleet category filters
        foreach ($categories as $cat) {
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($appUrl . '/fleet?category=' . $cat['slug']) . "</loc>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.85</priority>\n";
            echo "  </url>\n";
        }

        // Vehicles
        foreach ($vehicles as $v) {
            $lastmod = !empty($v['updated_at']) ? date('Y-m-d', strtotime($v['updated_at'])) : date('Y-m-d');
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($appUrl . '/fleet/' . $v['slug']) . "</loc>\n";
            echo "    <lastmod>{$lastmod}</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.85</priority>\n";
            echo "  </url>\n";
        }

        // Packages
        foreach ($packages as $pkg) {
            $lastmod = !empty($pkg['updated_at']) ? date('Y-m-d', strtotime($pkg['updated_at'])) : date('Y-m-d');
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($appUrl . '/packages/' . $pkg['slug']) . "</loc>\n";
            echo "    <lastmod>{$lastmod}</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.85</priority>\n";
            echo "  </url>\n";
        }

        // Blog posts
        foreach ($posts as $post) {
            $lastmod = !empty($post['updated_at']) ? date('Y-m-d', strtotime($post['updated_at'])) : date('Y-m-d');
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($appUrl . '/blog/' . $post['slug']) . "</loc>\n";
            echo "    <lastmod>{$lastmod}</lastmod>\n";
            echo "    <changefreq>monthly</changefreq>\n";
            echo "    <priority>0.75</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
        exit;
    }

    public function robots(array $params = []): void
    {
        $appUrl = rtrim(env('APP_URL', 'http://localhost'), '/');

        header('Content-Type: text/plain; charset=utf-8');
        echo "User-agent: *\n";
        echo "Disallow: /admin/\n";
        echo "Disallow: /api/\n";
        echo "Allow: /\n\n";
        echo "Sitemap: {$appUrl}/sitemap.xml\n";
        exit;
    }
}

