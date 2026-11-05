<?php
declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Core\Controller;
use App\Core\Session;
use App\Models\{Enquiry, Vehicle};

/**
 * EnquiryController — Admin enquiry management, filtering, notes, and CSV export.
 */
class EnquiryController extends Controller
{
    /**
     * List all enquiries with search and filters.
     */
    public function index(array $params = []): void
    {
        $this->requireAdmin();

        $enquiryModel = new Enquiry();

        // Filters from GET
        $filters = [
            'type'       => $this->get('type'),
            'status'     => $this->get('status'),
            'vehicle_id' => $this->getInt('vehicle_id'),
            'date_from'  => $this->get('date_from'),
            'date_to'    => $this->get('date_to'),
            'search'     => $this->get('search'),
        ];

        // Clean empty filters
        $filters = array_filter($filters, fn($v) => $v !== '' && $v !== 0);

        // Check if CSV export requested
        if ($this->get('export') === 'csv') {
            $this->exportCsv($filters);
            return;
        }

        $page = max(1, $this->getInt('page', 1));
        $result = $enquiryModel->getFiltered($filters, $page, 20);
        $vehicles = (new Vehicle())->all(['is_active' => 1], 'name ASC');

        $this->view('admin/enquiries/index', [
            'pageTitle'  => 'Enquiries & Bookings',
            'enquiries'  => $result['data'],
            'pagination' => $result,
            'filters'    => $filters,
            'vehicles'   => $vehicles,
        ], 'layouts/admin');
    }

    /**
     * View enquiry details and its notes.
     */
    public function show(array $params = []): void
    {
        $this->requireAdmin();

        $id = (int) ($params['id'] ?? 0);
        $enquiryModel = new Enquiry();
        $enquiry = $enquiryModel->getDetail($id);

        if (!$enquiry) {
            Session::flash('error', 'Enquiry not found.');
            $this->redirect('/admin/enquiries');
        }

        // Mark as read if not already
        if (empty($enquiry['is_read'])) {
            $enquiryModel->markRead($id);
        }

        $notes = $enquiryModel->getNotes($id);

        $this->view('admin/enquiries/show', [
            'pageTitle' => "Enquiry #{$id} — {$enquiry['name']}",
            'enquiry'   => $enquiry,
            'notes'     => $notes,
        ], 'layouts/admin');
    }

    /**
     * Update enquiry status.
     */
    public function updateStatus(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $status = sanitize($this->post('status'));

        $enquiryModel = new Enquiry();
        $enquiryModel->updateStatus($id, $status);

        $admin = Session::getAdmin();
        logActivity((int)$admin['id'], 'update_status', 'enquiry', $id, "Updated enquiry status to '{$status}'");

        if ($this->isAjax()) {
            $this->jsonSuccess('Status updated.');
        }

        Session::flash('success', 'Enquiry status updated successfully.');
        $this->redirect("/admin/enquiries/{$id}");
    }

    /**
     * Add an internal note to an enquiry.
     */
    public function addNote(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $note = sanitize($this->post('note'));

        if (empty($note)) {
            if ($this->isAjax()) $this->jsonError('Note content cannot be empty.');
            $this->redirect("/admin/enquiries/{$id}");
        }

        $admin = Session::getAdmin();
        $adminId = (int) $admin['id'];

        $enquiryModel = new Enquiry();
        $enquiryModel->addNote($id, $adminId, $note);

        logActivity($adminId, 'add_note', 'enquiry', $id, "Added internal note");

        if ($this->isAjax()) {
            $this->jsonSuccess('Note added.', [
                'note'  => htmlspecialchars($note),
                'admin' => htmlspecialchars($admin['name'] ?? 'Admin'),
            ]);
        }

        Session::flash('success', 'Note added successfully.');
        $this->redirect("/admin/enquiries/{$id}");
    }

    /**
     * Delete an enquiry.
     */
    public function delete(array $params = []): void
    {
        $this->requireAdmin();
        $this->verifyCsrf();

        $id = (int) ($params['id'] ?? 0);
        $enquiryModel = new Enquiry();
        $enquiryModel->delete($id);

        $admin = Session::getAdmin();
        logActivity((int)$admin['id'], 'delete', 'enquiry', $id, "Deleted enquiry #{$id}");

        Session::flash('success', 'Enquiry deleted.');
        $this->redirect('/admin/enquiries');
    }

    /**
     * Export enquiries to CSV.
     */
    private function exportCsv(array $filters): void
    {
        $enquiryModel = new Enquiry();
        $rows = $enquiryModel->getAllForExport($filters);

        $filename = 'enquiries_export_' . date('Y-m-d_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename="' . $filename . '"');

        $out = fopen('php://output', 'w');
        // UTF-8 BOM for proper Excel display
        fprintf($out, chr(0xEF).chr(0xBB).chr(0xBF));

        fputcsv($out, [
            'ID', 'Type', 'Customer Name', 'Phone', 'Email',
            'Pickup Location', 'Drop Location', 'Travel Date', 'Return Date',
            'Passengers', 'Vehicle Name', 'Package Name', 'Status', 'Message', 'Created At'
        ]);

        foreach ($rows as $r) {
            fputcsv($out, [
                $r['id'],
                ucfirst($r['type']),
                $r['name'],
                $r['phone'],
                $r['email'] ?? '',
                $r['pickup_location'] ?? '',
                $r['drop_location'] ?? '',
                $r['travel_date'] ?? '',
                $r['return_date'] ?? '',
                $r['passengers'] ?? '',
                $r['vehicle_name'] ?? '',
                $r['package_name'] ?? '',
                ucfirst($r['status']),
                $r['message'] ?? '',
                $r['created_at'],
            ]);
        }

        fclose($out);
        exit;
    }
}

