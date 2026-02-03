<?php

namespace App\Http\Controllers;

use App\Models\Certificate;
use App\Models\OutgoingDocument;
use App\Models\Personnel;
use App\Models\SchoolOrder;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Illuminate\View\View;

/**
 * Controller for the Dashboard.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class DashboardController extends Controller
{
    /**
     * Display the dashboard.
     */
    public function index(): View
    {
        $stats = $this->getStats();
        $recentDocuments = $this->getRecentDocuments();

        return view('dashboard', [
            'todayOutgoing' => $stats['todayOutgoing'],
            'yearCertificates' => $stats['yearCertificates'],
            'yearSchoolOrders' => $stats['yearSchoolOrders'],
            'totalPersonnel' => $stats['totalPersonnel'],
            'recentDocuments' => $recentDocuments,
        ]);
    }

    /**
     * Get dashboard statistics.
     *
     * @return array<string, int>
     */
    private function getStats(): array
    {
        $today = Carbon::today();
        $currentYear = Carbon::now()->year;

        return [
            'todayOutgoing' => OutgoingDocument::whereDate('created_at', $today)->count(),
            'yearCertificates' => Certificate::whereYear('created_at', $currentYear)->count(),
            'yearSchoolOrders' => SchoolOrder::whereYear('created_at', $currentYear)->count(),
            'totalPersonnel' => Personnel::count(),
        ];
    }

    /**
     * Get recent documents for activity feed.
     *
     * @return Collection
     */
    private function getRecentDocuments(): Collection
    {
        $recentOutgoing = $this->mapOutgoingDocuments();
        $recentCertificates = $this->mapCertificates();

        return $recentOutgoing
            ->concat($recentCertificates)
            ->sortByDesc('created_at')
            ->take(5);
    }

    /**
     * Map outgoing documents for display.
     *
     * @return Collection
     */
    private function mapOutgoingDocuments(): Collection
    {
        return OutgoingDocument::latest()
            ->take(5)
            ->get()
            ->map(fn($item) => $this->formatDocument(
                item: $item,
                type: 'outgoing_document',
                titlePrefix: 'หนังสือส่ง',
                titleField: 'document_number',
                descField: 'subject',
                routeName: 'outgoing-documents.show'
            ));
    }

    /**
     * Map certificates for display.
     *
     * @return Collection
     */
    private function mapCertificates(): Collection
    {
        return Certificate::latest()
            ->take(5)
            ->get()
            ->map(fn($item) => $this->formatDocument(
                item: $item,
                type: 'certificate',
                titlePrefix: 'หนังสือรับรอง',
                titleField: 'certificate_number',
                descField: null,
                routeName: 'certificates.show',
                customDesc: fn($i) => "{$i->personnel_name} ({$i->purpose})"
            ));
    }

    /**
     * Format a document for display in the activity feed.
     *
     * @param mixed $item
     * @param string $type
     * @param string $titlePrefix
     * @param string $titleField
     * @param string|null $descField
     * @param string $routeName
     * @param callable|null $customDesc
     * @return object
     */
    private function formatDocument(
        mixed $item,
        string $type,
        string $titlePrefix,
        string $titleField,
        ?string $descField,
        string $routeName,
        ?callable $customDesc = null
    ): object {
        $item->type = $type;
        $item->display_title = "{$titlePrefix}: {$item->{$titleField} }";
        $item->display_desc = $customDesc ? $customDesc($item) : ($descField ? $item->{$descField} : '');
        $item->display_date = $item->created_at;
        $item->route_name = $routeName;

        return $item;
    }
}
