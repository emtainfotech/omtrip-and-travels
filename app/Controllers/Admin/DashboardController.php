<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Models\{Enquiry, BlogPost, Vehicle};
use App\Core\Database;

/**
 * DashboardController — main admin overview & analytics.
 */
class DashboardController extends Controller
{
    public function index(array $params = []): void
    {
        $this->requireAdmin();

        $enquiryModel = new Enquiry();
        $stats        = $enquiryModel->getDashboardStats();
        $monthlyChart = $enquiryModel->getMonthlyChart();
        $recentBlogs  = (new BlogPost())->all([], 'created_at DESC', 5);

        // Click logs summary
        $clicksSummary = ['call' => 0, 'whatsapp' => 0];
        $recentClicks = [];
        $db = Database::getInstance();
        if ($db->isConnected()) {
            $rows = $db->fetchAll("SELECT type, COUNT(*) as cnt FROM click_logs GROUP BY type");
            foreach ($rows as $r) {
                $clicksSummary[$r['type']] = (int) $r['cnt'];
            }
            $recentClicks = $db->fetchAll("SELECT * FROM click_logs ORDER BY created_at DESC LIMIT 6");
        }

        // Prepare chart labels and counts
        $chartLabels = [];
        $chartValues = [];
        foreach ($monthlyChart as $row) {
            $chartLabels[] = date('M Y', strtotime($row['month'] . '-01'));
            $chartValues[] = (int) $row['total'];
        }

        // Status chart labels and counts
        $statusLabels = [];
        $statusValues = [];
        foreach ($stats['by_status'] as $s) {
            $statusLabels[] = ucfirst($s['status']);
            $statusValues[] = (int) $s['cnt'];
        }

        $this->view('admin/dashboard/index', [
            'pageTitle'     => 'Dashboard Overview',
            'stats'         => $stats,
            'recentBlogs'   => $recentBlogs,
            'clicksSummary' => $clicksSummary,
            'recentClicks'  => $recentClicks,
            'chartLabels'   => json_encode($chartLabels),
            'chartValues'   => json_encode($chartValues),
            'statusLabels'  => json_encode($statusLabels),
            'statusValues'  => json_encode($statusValues),
        ], 'layouts/admin');
    }
}

