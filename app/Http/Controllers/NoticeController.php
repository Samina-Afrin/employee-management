<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NoticeController extends Controller
{
    public function index(Request $request)
    {
        $query = Notice::with('poster');

        if ($request->filled('search')) {
            $query->where('title', 'like', "%{$request->search}%");
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $notices = $query->latest('notice_date')->paginate(15);
        return view('notice.index', compact('notices'));
    }

    public function create()
    {
        return view('notice.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'notice_date' => 'required|date',
            'expire_date' => 'nullable|date|after_or_equal:notice_date',
            'status'      => 'required|in:0,1',
        ]);

        $data['posted_by'] = Auth::id();

        Notice::create($data);

        return redirect()->route('notice.index')
            ->with('success', 'Notice created successfully.');
    }

    public function show(Notice $notice)
    {
        $notice->load('poster');
        return view('notice.show', compact('notice'));
    }

    public function edit(Notice $notice)
    {
        return view('notice.edit', compact('notice'));
    }

    public function update(Request $request, Notice $notice)
    {
        $data = $request->validate([
            'title'       => 'required|string|max:200',
            'description' => 'required|string',
            'notice_date' => 'required|date',
            'expire_date' => 'nullable|date|after_or_equal:notice_date',
            'status'      => 'required|in:0,1',
        ]);

        $notice->update($data);

        return redirect()->route('notice.index')
            ->with('success', 'Notice updated successfully.');
    }

    public function destroy(Notice $notice)
    {
        $notice->delete();
        return redirect()->route('notice.index')
            ->with('success', 'Notice deleted successfully.');
    }
}