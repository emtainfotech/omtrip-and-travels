<?php
declare(strict_types=1);

namespace App\Models;

/**
 * Enquiry model — handles contact and booking enquiries.
 */
class Enquiry extends BaseModel
{
    protected string $table = 'enquiries';

    /**
     * Get paginated enquiries for admin with optional filters.
     */
    public function getFiltered(array $filters = [], int $page = 1, int $perPage = 20): array
    {
        $sql = "
            SELECT e.*,
                   v.name AS vehicle_name,
                   p.name AS package_name
            FROM enquiries e
            LEFT JOIN vehicles v ON e.vehicle_id = v.id
            LEFT JOIN tour_packages p ON e.package_id = p.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['type'])) {
            $sql .= " AND e.type = ?";
            $params[] = $filters['type'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND e.status = ?";
            $params[] = $filters['status'];
        }
        if (!empty($filters['vehicle_id'])) {
            $sql .= " AND e.vehicle_id = ?";
            $params[] = $filters['vehicle_id'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(e.created_at) >= ?";
            $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(e.created_at) <= ?";
            $params[] = $filters['date_to'];
        }
        if (!empty($filters['search'])) {
            $sql .= " AND (e.name LIKE ? OR e.phone LIKE ? OR e.email LIKE ?)";
            $s = '%' . $filters['search'] . '%';
            $params[] = $s; $params[] = $s; $params[] = $s;
        }

        $total = (int) $this->db->fetchColumn("SELECT COUNT(*) FROM ({$sql}) AS t", $params);
        $sql .= " ORDER BY e.created_at DESC LIMIT ? OFFSET ?";
        $params[] = $perPage;
        $params[] = ($page - 1) * $perPage;

        return [
            'data'     => $this->db->fetchAll($sql, $params),
            'total'    => $total,
            'pages'    => (int) ceil($total / $perPage),
            'current'  => $page,
            'per_page' => $perPage,
        ];
    }

    /**
     * Get enquiry detail with related vehicle and package info.
     */
    public function getDetail(int $id): array|false
    {
        return $this->db->fetchOne("
            SELECT e.*,
                   v.name AS vehicle_name,
                   p.name AS package_name
            FROM enquiries e
            LEFT JOIN vehicles v ON e.vehicle_id = v.id
            LEFT JOIN tour_packages p ON e.package_id = p.id
            WHERE e.id = ?
            LIMIT 1
        ", [$id]);
    }

    /**
     * Get notes for an enquiry.
     */
    public function getNotes(int $enquiryId): array
    {
        return $this->db->fetchAll("
            SELECT n.*, a.name AS admin_name
            FROM enquiry_notes n
            JOIN admins a ON n.admin_id = a.id
            WHERE n.enquiry_id = ?
            ORDER BY n.created_at ASC
        ", [$enquiryId]);
    }

    /**
     * Add a note to an enquiry.
     */
    public function addNote(int $enquiryId, int $adminId, string $note): int|false
    {
        return $this->db->insert('enquiry_notes', [
            'enquiry_id' => $enquiryId,
            'admin_id'   => $adminId,
            'note'       => $note,
        ]);
    }

    /**
     * Get dashboard stats.
     */
    public function getDashboardStats(): array
    {
        $stats = [];
        $today = date('Y-m-d');
        $weekAgo = date('Y-m-d H:i:s', strtotime('-7 days'));
        $monthAgo = date('Y-m-d H:i:s', strtotime('-30 days'));

        $stats['new_today'] = (int) $this->db->fetchColumn(
            "SELECT COUNT(*) FROM enquiries WHERE status = 'new' AND created_at >= ?",
            [$today . ' 00:00:00']
        );
        $stats['new_week'] = (int) $this->db->fetchColumn(
            "SELECT COUNT(*) FROM enquiries WHERE status = 'new' AND created_at >= ?",
            [$weekAgo]
        );
        $stats['new_month'] = (int) $this->db->fetchColumn(
            "SELECT COUNT(*) FROM enquiries WHERE status = 'new' AND created_at >= ?",
            [$monthAgo]
        );
        $stats['total_unread'] = (int) $this->db->fetchColumn(
            "SELECT COUNT(*) FROM enquiries WHERE is_read = 0"
        );
        $stats['by_status'] = $this->db->fetchAll(
            "SELECT status, COUNT(*) AS cnt FROM enquiries GROUP BY status ORDER BY cnt DESC"
        );
        $stats['recent'] = $this->db->fetchAll("
            SELECT e.*, v.name AS vehicle_name
            FROM enquiries e
            LEFT JOIN vehicles v ON e.vehicle_id = v.id
            ORDER BY e.created_at DESC
            LIMIT 10
        ");
        $stats['top_vehicles'] = $this->db->fetchAll("
            SELECT v.name, COUNT(e.id) AS enquiry_count
            FROM enquiries e
            JOIN vehicles v ON e.vehicle_id = v.id
            WHERE e.vehicle_id IS NOT NULL
            GROUP BY e.vehicle_id
            ORDER BY enquiry_count DESC
            LIMIT 5
        ");

        return $stats;
    }

    /**
     * Get all for CSV export.
     */
    public function getAllForExport(array $filters = []): array
    {
        $sql = "
            SELECT e.id, e.type, e.name, e.phone, e.email,
                   e.pickup_location, e.drop_location, e.travel_date,
                   e.return_date, e.passengers, e.message, e.status,
                   e.source_page, e.created_at,
                   v.name AS vehicle_name,
                   p.name AS package_name
            FROM enquiries e
            LEFT JOIN vehicles v ON e.vehicle_id = v.id
            LEFT JOIN tour_packages p ON e.package_id = p.id
            WHERE 1=1
        ";
        $params = [];

        if (!empty($filters['status'])) {
            $sql .= " AND e.status = ?"; $params[] = $filters['status'];
        }
        if (!empty($filters['type'])) {
            $sql .= " AND e.type = ?"; $params[] = $filters['type'];
        }
        if (!empty($filters['date_from'])) {
            $sql .= " AND DATE(e.created_at) >= ?"; $params[] = $filters['date_from'];
        }
        if (!empty($filters['date_to'])) {
            $sql .= " AND DATE(e.created_at) <= ?"; $params[] = $filters['date_to'];
        }

        $sql .= " ORDER BY e.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Mark as read.
     */
    public function markRead(int $id): void
    {
        $this->db->update($this->table, ['is_read' => 1], ['id' => $id]);
    }

    /**
     * Update status.
     */
    public function updateStatus(int $id, string $status): void
    {
        $allowed = ['new', 'contacted', 'followup', 'confirmed', 'closed', 'spam'];
        if (in_array($status, $allowed)) {
            $this->db->update($this->table, ['status' => $status], ['id' => $id]);
        }
    }

    /**
     * Get monthly chart data for last 6 months.
     */
    public function getMonthlyChart(): array
    {
        $sixMonthsAgo = date('Y-m-d H:i:s', strtotime('-6 months'));
        $rows = $this->db->fetchAll(
            "SELECT created_at FROM enquiries WHERE created_at >= ? ORDER BY created_at ASC",
            [$sixMonthsAgo]
        );

        $counts = [];
        // Pre-fill last 6 months
        for ($i = 5; $i >= 0; $i--) {
            $m = date('Y-m', strtotime("-{$i} months"));
            $counts[$m] = 0;
        }

        foreach ($rows as $r) {
            $m = substr($r['created_at'], 0, 7);
            if (isset($counts[$m])) {
                $counts[$m]++;
            } else {
                $counts[$m] = 1;
            }
        }

        $result = [];
        foreach ($counts as $month => $total) {
            $result[] = ['month' => $month, 'total' => $total];
        }

        return $result;
    }
}

