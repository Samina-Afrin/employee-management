<?php
namespace App\Http\Controllers;

use App\Models\{User, PerformanceReview, PerformanceGoal};
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    // ========== REVIEWS ==========
    public function index() {
        $reviews = PerformanceReview::with(['employee', 'manager'])->latest()->paginate(15);
        return view('performance.index', compact('reviews'));
    }

    public function create() {
        $users = User::orderBy('name')->get();
        return view('performance.create', compact('users'));
    }

    public function store(Request $request) {
        $data = $request->validate([
            'user_id'     => 'required|exists:users,id',
            'manager_id'  => 'nullable|exists:users,id',
            'review_year' => 'required|integer|min:2000|max:2100',
            'period'      => 'required|in:jan_dec,jul_jun',
        ]);
        $data['status'] = 'draft';
        PerformanceReview::create($data);
        return redirect()->route('performance.index')->with('success', 'Review created');
    }

    public function show(PerformanceReview $performance) {
        $performance->load(['employee', 'manager', 'goals']);
        return view('performance.show', compact('performance'));
    }

    public function submit(PerformanceReview $performance) {
        $performance->update(['status' => 'submitted', 'review_date' => now()]);
        return back()->with('success', 'Submitted for approval');
    }

    public function approve(PerformanceReview $performance) {
        $performance->update(['status' => 'approved']);
        return back()->with('success', 'Approved');
    }

    public function updateRating(Request $request, PerformanceReview $performance) {
        $data = $request->validate([
            'rating'          => 'required|numeric|min:0|max:5',
            'manager_comment' => 'nullable|string',
        ]);
        $performance->update($data);
        return back()->with('success', 'Rating saved');
    }

    // ========== GOALS / KPI ==========
    public function goalsIndex() {
        $goals = PerformanceGoal::with('review.employee')->latest()->paginate(15);
        return view('performance.goals', compact('goals'));
    }

    public function storeGoal(Request $request, PerformanceReview $performance) {
        $data = $request->validate([
            'title'    => 'required|string|max:255',
            'target'   => 'required|numeric|min:1',
            'achieved' => 'nullable|numeric|min:0',
            'weight'   => 'required|integer|min:1|max:100',
        ]);
        $data['achieved'] = $data['achieved'] ?? 0;
        $performance->goals()->create($data);
        $performance->recalcRating();
        return back()->with('success', 'Goal added');
    }

    public function updateGoal(Request $request, PerformanceGoal $goal) {
        $data = $request->validate(['achieved' => 'required|numeric|min:0']);
        $goal->update($data);
        $goal->review->recalcRating();
        return back()->with('success', 'Goal updated');
    }

    public function destroyGoal(PerformanceGoal $goal) {
        $review = $goal->review;
        $goal->delete();
        $review->recalcRating();
        return back()->with('success', 'Goal removed');
    }
}