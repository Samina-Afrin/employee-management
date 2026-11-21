<?php

namespace App\Http\Controllers;

use App\Models\Attendance;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class AttendanceController extends Controller
{
    /**
     * Summary list — তারিখ অনুযায়ী
     */
    public function index(Request $request)
    {
        $query = Attendance::select(
                'attendance_date',
                DB::raw('COUNT(*) as total'),
                DB::raw('SUM(CASE WHEN status=1 THEN 1 ELSE 0 END) as present_count'),
                DB::raw('SUM(CASE WHEN status=2 THEN 1 ELSE 0 END) as late_count'),
                DB::raw('SUM(CASE WHEN status=3 THEN 1 ELSE 0 END) as absent_count'),
                DB::raw('SUM(CASE WHEN status=4 THEN 1 ELSE 0 END) as leave_count'),
                DB::raw('SUM(CASE WHEN status=5 THEN 1 ELSE 0 END) as holiday_count')
            )
            ->groupBy('attendance_date')
            ->orderByDesc('attendance_date');

        if ($request->filled('date')) {
            $query->whereDate('attendance_date', $request->date);
        }

        $summaries = $query->paginate(15);
        return view('attendance.index', compact('summaries'));
    }

    /**
     * Bulk attendance form
     */
    public function create(Request $request)
    {
        $date = $request->date ?? date('Y-m-d');
        $employees = Employee::orderBy('name')->get();
        $existing = Attendance::where('attendance_date', 'like', $date . '%')
            ->get()
            ->keyBy('employee_id');

        return view('attendance.create', compact('employees', 'date', 'existing'));
    }

    /**
     * Bulk attendance save (create or update)
     */
    public function store(Request $request)
    {

        $request->validate([
            'attendance_date' => 'required|date',
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:1,2,3,4,5',
        ]);

        $date = \Carbon\Carbon::parse($request->attendance_date)->format('Y-m-d');
        $userId = Auth::id();
        $saved = 0;

        DB::beginTransaction();
        try {
            foreach ($request->attendance as $employeeId => $row) {
                $existing = Attendance::withTrashed()
                    ->where('employee_id', $employeeId)
                    ->whereDate('attendance_date', $date)
                    ->first();

                if ($existing) {
                    if ($existing->trashed()) {
                        $existing->restore();
                    }

                    $existing->update([
                        'check_in'     => $row['check_in'] ?? null,
                        'check_out'    => $row['check_out'] ?? null,
                        'status'       => $row['status'],
                        'is_manual'    => 1,
                        'corrected_by' => $userId,
                        'remarks'      => $row['remarks'] ?? null,
                    ]);
                } else {
                    Attendance::create([
                        'employee_id'     => $employeeId,
                        'attendance_date' => $date,
                        'check_in'        => $row['check_in'] ?? null,
                        'check_out'       => $row['check_out'] ?? null,
                        'status'          => $row['status'],
                        'is_manual'       => 1,
                        'corrected_by'    => $userId,
                        'remarks'         => $row['remarks'] ?? null,
                    ]);
                }
                $saved++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }

        return redirect()->route('attendance.index')
            ->with('success', "Attendance saved for {$saved} employees on {$date}.");
    }

    /**
     * Show all attendance of a specific date
     */
    public function show($id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->attendance_date;

        $records = Attendance::with('employee')
            ->whereDate('attendance_date', $date)
            ->get();

        $summary = [
            'total'   => $records->count(),
            'present' => $records->where('status', 1)->count(),
            'late'    => $records->where('status', 2)->count(),
            'absent'  => $records->where('status', 3)->count(),
            'leave'   => $records->where('status', 4)->count(),
            'holiday' => $records->where('status', 5)->count(),
        ];

        return view('attendance.show', compact('records', 'date', 'summary'));
    }

    /**
     * Bulk edit — একই তারিখের সব attendance একসাথে edit
     */
    public function edit($id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->attendance_date;

        $records = Attendance::with('employee')
            ->whereDate('attendance_date', $date)
            ->get();

        return view('attendance.edit', compact('records', 'date'));
    }

    /**
     * Bulk update — একই তারিখের সব attendance একসাথে update
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'attendance' => 'required|array',
            'attendance.*.status' => 'required|in:1,2,3,4,5',
        ]);

        $userId = Auth::id();
        $updated = 0;

        DB::beginTransaction();
        try {
            foreach ($request->attendance as $attendanceId => $row) {
                $att = Attendance::find($attendanceId);
                if (!$att) continue;

                $att->update([
                    'check_in'     => $row['check_in'] ?? null,
                    'check_out'    => $row['check_out'] ?? null,
                    'status'       => $row['status'],
                    'is_manual'    => 1,
                    'corrected_by' => $userId,
                    'remarks'      => $row['remarks'] ?? null,
                ]);
                $updated++;
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Failed: ' . $e->getMessage());
        }

        return redirect()->route('attendance.index')
            ->with('success', "Attendance updated for {$updated} records.");
    }

    /**
     * Delete all attendance of a date
     */
    public function destroy($id)
    {
        $attendance = Attendance::findOrFail($id);
        $date = $attendance->attendance_date;

        Attendance::whereDate('attendance_date', $date)->delete();

        return redirect()->route('attendance.index')
            ->with('success', "All attendance records deleted for {$date}.");
    }
}