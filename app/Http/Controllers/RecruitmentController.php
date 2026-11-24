<?php

namespace App\Http\Controllers;

use App\Models\Recruitment;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Employee;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RecruitmentController extends Controller
{
    public function index()
    {
        $vacancies = Recruitment::with(['department', 'designation'])
            ->whereNull('applicant_name')
            ->latest()
            ->paginate(10);

        $stats = [
            'open'       => Recruitment::whereNull('applicant_name')->where('vacancy_status', 1)->count(),
            'closed'     => Recruitment::whereNull('applicant_name')->where('vacancy_status', 0)->count(),
            'applicants' => Recruitment::whereNotNull('applicant_name')->count(),
            'selected'   => Recruitment::where('stage', 4)->count(),
        ];

        return view('recruitment.index', compact('vacancies', 'stats'));
    }

    public function create()
    {
        $departments = Department::all();
        $designations = Designation::all();
        return view('recruitment.create', compact('departments', 'designations'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_title'       => 'required|string|max:150',
            'department_id'   => 'required|exists:departments,id',
            'designation_id'  => 'required|exists:designations,id',
            'total_posts'     => 'required|integer|min:1',
            'job_description' => 'nullable|string',
            'last_apply_date' => 'required|date|after_or_equal:today',
        ]);

        $validated['vacancy_status'] = 1;

        Recruitment::create($validated);

        return redirect()->route('recruitment.index')
            ->with('success', 'Vacancy posted successfully!');
    }

    public function edit(Recruitment $recruitment)
    {
        $departments = Department::all();
        $designations = Designation::all();
        return view('recruitment.edit', compact('recruitment', 'departments', 'designations'));
    }

    public function update(Request $request, Recruitment $recruitment)
    {
        $validated = $request->validate([
            'job_title'       => 'required|string|max:150',
            'department_id'   => 'required|exists:departments,id',
            'designation_id'  => 'required|exists:designations,id',
            'total_posts'     => 'required|integer|min:1',
            'job_description' => 'nullable|string',
            'last_apply_date' => 'required|date',
            'vacancy_status'  => 'required|in:0,1',
        ]);

        $recruitment->update($validated);

        return redirect()->route('recruitment.index')
            ->with('success', 'Vacancy updated successfully!');
    }

    public function destroy(Recruitment $recruitment)
    {
        $recruitment->delete();
        return redirect()->route('recruitment.index')
            ->with('success', 'Vacancy deleted successfully!');
    }

    public function applicants(Recruitment $recruitment)
    {
        $applicants = Recruitment::where('job_title', $recruitment->job_title)
            ->whereNotNull('applicant_name')
            ->orderByDesc('created_at')
            ->get();

        return view('recruitment.applicants', compact('recruitment', 'applicants'));
    }

    public function apply(Request $request, Recruitment $recruitment)
    {
        $validated = $request->validate([
            'applicant_name' => 'required|string|max:150',
            'phone'          => 'required|string|max:20',
            'email'          => 'nullable|email|max:150',
            'cv'             => 'nullable|file|mimes:pdf,doc,docx|max:2048',
        ]);

        $exists = Recruitment::where('phone', $validated['phone'])
            ->when($validated['email'] ?? null, function ($q, $email) {
                return $q->orWhere('email', $email);
            })
            ->whereNotNull('applicant_name')
            ->exists();

        if ($exists) {
            return back()->with('error', 'Applicant with this phone/email already exists!');
        }

        $cvPath = null;
        if ($request->hasFile('cv')) {
            $cvPath = $request->file('cv')->store('cvs', 'public');
        }

        Recruitment::create([
            'job_title'       => $recruitment->job_title,
            'department_id'   => $recruitment->department_id,
            'designation_id'  => $recruitment->designation_id,
            'total_posts'     => $recruitment->total_posts,
            'job_description' => $recruitment->job_description,
            'last_apply_date' => $recruitment->last_apply_date,
            'vacancy_status'  => $recruitment->vacancy_status,
            'applicant_name'  => $validated['applicant_name'],
            'phone'           => $validated['phone'],
            'email'           => $validated['email'] ?? null,
            'cv_path'         => $cvPath,
            'stage'           => 1,
        ]);

        return back()->with('success', 'Applicant added successfully!');
    }

    public function updateStage(Request $request, Recruitment $recruitment)
    {
        $validated = $request->validate([
            'stage'          => 'required|integer|in:1,2,3,4,5',
            'interview_date' => 'nullable|date',
            'interview_note' => 'nullable|string',
        ]);

        $recruitment->update($validated);

        return back()->with('success', 'Stage updated successfully!');
    }

    public function convertToEmployee(Recruitment $recruitment)
    {
        if ($recruitment->stage != 4) {
            return back()->with('error', 'Only selected applicants can be converted!');
        }

        if ($recruitment->employee_id) {
            return back()->with('error', 'Already converted to employee!');
        }

        $lastEmployee = Employee::withTrashed()->orderByDesc('id')->first();
        $nextId = $lastEmployee ? $lastEmployee->id + 1 : 1;
        $employeeCode = 'EMP-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

        $employee = Employee::create([
            'employee_code'  => $employeeCode,
            'name'           => $recruitment->applicant_name,
            'phone'          => $recruitment->phone,
            'email'          => $recruitment->email,
            'department_id'  => $recruitment->department_id,
            'designation_id' => $recruitment->designation_id,
            'joining_date'   => Carbon::today(),
            'status'         => 1,
        ]);

        $recruitment->update(['employee_id' => $employee->id]);

        return back()->with('success', "Converted successfully! Employee Code: {$employeeCode}");
    }
}