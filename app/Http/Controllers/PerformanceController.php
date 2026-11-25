<?php

namespace App\Http\Controllers;

use App\Models\Appraisal;
use App\Models\AppraisalCycle;
use App\Models\Goal;
use App\Models\ProbationEvaluation;
use App\Models\CareerRecommendation;

class PerformanceController extends Controller
{
    public function index()
    {
        $totalAppraisals = Appraisal::count();

        $completedAppraisals = Appraisal::where(
            'status', 'completed'
        )->count();

        $pendingAppraisals = Appraisal::where(
            'status', '!=', 'completed'
        )->count();

        $totalGoals = Goal::count();

        $completedGoals = Goal::where(
            'status', 'completed'
        )->count();

        $pendingProbations = ProbationEvaluation::whereNull(
            'recommendation'
        )->count();

        $pendingRecommendations = CareerRecommendation::where(
            'status', 'pending'
        )->count();

        $cycles = AppraisalCycle::latest()->get();

        $recentAppraisals = Appraisal::with([
            'employee', 'cycle'
        ])->latest()->take(5)->get();

        return view('performance.index', compact(
            'totalAppraisals',
            'completedAppraisals',
            'pendingAppraisals',
            'totalGoals',
            'completedGoals',
            'pendingProbations',
            'pendingRecommendations',
            'cycles',
            'recentAppraisals'
        ));
    }
}