<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\OutgoingDocument;
use App\Models\Certificate;
use App\Models\SchoolOrder;
use App\Models\Personnel;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Counts
        $todayOutgoing = OutgoingDocument::whereDate('created_at', Carbon::today())->count();
        $yearCertificates = Certificate::whereYear('created_at', Carbon::now()->year)->count();
        $yearSchoolOrders = SchoolOrder::whereYear('created_at', Carbon::now()->year)->count();
        $totalPersonnel = Personnel::count();

        // Recent Activity (Mixed)
        $recentOutgoing = OutgoingDocument::latest()->take(5)->get()->map(function ($item) {
            $item->type = 'outgoing_document';
            $item->display_title = 'หนังสือส่ง: ' . $item->document_number;
            $item->display_desc = $item->subject;
            $item->display_date = $item->created_at;
            $item->route_name = 'outgoing-documents.show';
            return $item;
        });

        $recentCertificates = Certificate::latest()->take(5)->get()->map(function ($item) {
            $item->type = 'certificate';
            $item->display_title = 'หนังสือรับรอง: ' . $item->certificate_number;
            $item->display_desc = $item->personnel_name . ' (' . $item->purpose . ')';
            $item->display_date = $item->created_at;
            $item->route_name = 'certificates.show';
            return $item;
        });

        // Merge and Sort
        $recentDocuments = $recentOutgoing->concat($recentCertificates)
            ->sortByDesc('created_at')
            ->take(5);

        return view('dashboard', compact(
            'todayOutgoing',
            'yearCertificates',
            'yearSchoolOrders',
            'totalPersonnel',
            'recentDocuments'
        ));
    }
}
