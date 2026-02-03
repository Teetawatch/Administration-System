<?php

namespace App\Http\Controllers;

use App\Models\NavyNews;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class NavyNewsController extends Controller
{
    public function index(Request $request)
    {
        $query = NavyNews::with('creator')->latest();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('news_number', 'like', "%{$search}%")
                  ->orWhere('title', 'like', "%{$search}%");
            });
        }

        if ($request->filled('urgency')) {
            $query->where('urgency', $request->urgency);
        }

        $news = $query->paginate(15)->withQueryString();


        return view('navy-news.index', compact('news'));
    }

    public function create()
    {
        // Auto-generate news number (Format: XX/01/YY where XX=Running, YY=Thai Year 2 digits)
        // Example: 01/01/69, 02/01/69
        
        $monthPart = '01'; // Fixed as per request
        $thaiYearFull = date('Y') + 543;
        $thaiYearShort = substr($thaiYearFull, -2); // 69
        $suffix = "/{$monthPart}/{$thaiYearShort}";

        $lastNews = NavyNews::where('news_number', 'LIKE', "%{$suffix}")
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastNews) {
            // Extract the number part before the slash
            if (preg_match('/^(\d+)\//', $lastNews->news_number, $matches)) {
                $runningNumber = intval($matches[1]) + 1;
            }
        }

        $nextNewsNumber = str_pad($runningNumber, 2, '0', STR_PAD_LEFT) . $suffix;

        return view('navy-news.create', compact('nextNewsNumber'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'news_number' => 'required|string|max:100|unique:navy_news',
            'news_date' => 'required|date',
            'title' => 'required|string|max:500',
            'urgency' => 'required|in:normal,urgent,very_urgent,most_urgent',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $validated['created_by'] = Auth::id();

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('navy-news', 'public');
        }

        // Fix for SQLSTATE[23000]: Column 'content' cannot be null
        $validated['content'] = $validated['content'] ?? '';

        NavyNews::create($validated);

        return redirect()->route('navy-news.index')
            ->with('success', 'บันทึกข่าวราชนาวีเรียบร้อยแล้ว');
    }

    public function show(NavyNews $navyNews)
    {
        return view('navy-news.show', compact('navyNews'));
    }

    public function edit(NavyNews $navyNews)
    {
        return view('navy-news.edit', compact('navyNews'));
    }

    public function update(Request $request, NavyNews $navyNews)
    {
        $validated = $request->validate([
            'news_number' => 'required|string|max:100|unique:navy_news,news_number,' . $navyNews->id,
            'news_date' => 'required|date',
            'title' => 'required|string|max:500',
            'urgency' => 'required|in:normal,urgent,very_urgent,most_urgent',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        if ($request->hasFile('attachment')) {
            if ($navyNews->attachment_path) {
                Storage::disk('public')->delete($navyNews->attachment_path);
            }
            $validated['attachment_path'] = $request->file('attachment')->store('navy-news', 'public');
        }

        // Fix for SQLSTATE[23000]: Column 'content' cannot be null
        $validated['content'] = $validated['content'] ?? '';

        $navyNews->update($validated);

        return redirect()->route('navy-news.index')
            ->with('success', 'อัปเดตข่าวราชนาวีเรียบร้อยแล้ว');
    }

    public function destroy(NavyNews $navyNews)
    {
        if ($navyNews->attachment_path) {
            Storage::disk('public')->delete($navyNews->attachment_path);
        }

        $navyNews->delete();

        return redirect()->route('navy-news.index')
            ->with('success', 'ลบข่าวราชนาวีเรียบร้อยแล้ว');
    }
}
