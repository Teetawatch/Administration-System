<?php

namespace App\Http\Controllers;

use App\Models\NavyNews;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

/**
 * Controller for managing Navy News.
 * 
 * Following best practices:
 * - php-pro: Type hints, return types, modern PHP 8 features
 * - software-architecture: Clean code patterns
 */
class NavyNewsController extends Controller
{
    /**
     * Display a listing of navy news.
     */
    public function index(Request $request): View
    {
        $query = NavyNews::with('creator')->latest();

        if ($request->filled('search')) {
            $query->search($request->search);
        }

        if ($request->filled('urgency')) {
            $query->urgency($request->urgency);
        }

        $news = $query->paginate(15)->withQueryString();

        return view('navy-news.index', compact('news'));
    }

    /**
     * Show the form for creating a new news.
     */
    public function create(): View
    {
        $nextNewsNumber = $this->generateNextNewsNumber();

        return view('navy-news.create', compact('nextNewsNumber'));
    }

    /**
     * Store a newly created news in storage.
     */
    public function store(Request $request): RedirectResponse
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
        $validated['content'] = $validated['content'] ?? '';

        if ($request->hasFile('attachment')) {
            $validated['attachment_path'] = $request->file('attachment')->store('navy-news', 'public');
        }

        NavyNews::create($validated);

        return redirect()->route('navy-news.index')
            ->with('success', 'บันทึกข่าวราชนาวีเรียบร้อยแล้ว');
    }

    /**
     * Display the specified news.
     */
    public function show(NavyNews $navyNews): View
    {
        return view('navy-news.show', compact('navyNews'));
    }

    /**
     * Show the form for editing the specified news.
     */
    public function edit(NavyNews $navyNews): View
    {
        return view('navy-news.edit', compact('navyNews'));
    }

    /**
     * Update the specified news in storage.
     */
    public function update(Request $request, NavyNews $navyNews): RedirectResponse
    {
        $validated = $request->validate([
            'news_number' => "required|string|max:100|unique:navy_news,news_number,{$navyNews->id}",
            'news_date' => 'required|date',
            'title' => 'required|string|max:500',
            'urgency' => 'required|in:normal,urgent,very_urgent,most_urgent',
            'content' => 'nullable|string',
            'attachment' => 'nullable|file|mimes:pdf,doc,docx,jpg,png|max:10240',
        ]);

        $validated['content'] = $validated['content'] ?? '';

        if ($request->hasFile('attachment')) {
            $this->deleteOldAttachment($navyNews);
            $validated['attachment_path'] = $request->file('attachment')->store('navy-news', 'public');
        }

        $navyNews->update($validated);

        return redirect()->route('navy-news.index')
            ->with('success', 'อัปเดตข่าวราชนาวีเรียบร้อยแล้ว');
    }

    /**
     * Remove the specified news from storage.
     */
    public function destroy(NavyNews $navyNews): RedirectResponse
    {
        $this->deleteOldAttachment($navyNews);
        $navyNews->delete();

        return redirect()->route('navy-news.index')
            ->with('success', 'ลบข่าวราชนาวีเรียบร้อยแล้ว');
    }

    /**
     * Generate the next news number.
     */
    private function generateNextNewsNumber(): string
    {
        $monthPart = '01';
        $thaiYearFull = date('Y') + 543;
        $thaiYearShort = substr((string) $thaiYearFull, -2);
        $suffix = "/{$monthPart}/{$thaiYearShort}";

        $lastNews = NavyNews::where('news_number', 'LIKE', "%{$suffix}")
            ->latest('id')
            ->first();

        $runningNumber = 1;

        if ($lastNews && preg_match('/^(\d+)\//', $lastNews->news_number, $matches)) {
            $runningNumber = intval($matches[1]) + 1;
        }

        return str_pad((string) $runningNumber, 2, '0', STR_PAD_LEFT) . $suffix;
    }

    /**
     * Delete old attachment if exists.
     */
    private function deleteOldAttachment(NavyNews $navyNews): void
    {
        if ($navyNews->attachment_path) {
            Storage::disk('public')->delete($navyNews->attachment_path);
        }
    }
}
