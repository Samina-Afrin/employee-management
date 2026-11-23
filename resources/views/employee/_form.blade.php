@php $emp = $employee ?? null; @endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Full Name *</label>
        <input type="text" name="name" class="form-control"
               value="{{ old('name', $emp->name ?? '') }}" required>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Phone</label>
        <input type="text" name="phone" class="form-control"
               value="{{ old('phone', $emp->phone ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">NID Number (10/13/17 digits)</label>
        <input type="text" name="nid_number" class="form-control"
               value="{{ old('nid_number', $emp->nid_number ?? '') }}">
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Photo</label>
        <input type="file" name="photo" class="form-control">
        @if($emp && $emp->photo)
            <img src="{{ asset('storage/'.$emp->photo) }}" width="80" class="mt-2 rounded">
        @endif
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Gender *</label>
        <select name="gender" class="form-select" required>
            <option value="1" @selected(old('gender', $emp->gender ?? 1)==1)>Male</option>
            <option value="2" @selected(old('gender', $emp->gender ?? 1)==2)>Female</option>
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Date of Birth</label>
        <input type="date" name="date_of_birth" class="form-control"
               value="{{ old('date_of_birth', optional($emp->date_of_birth ?? null)->format('Y-m-d')) }}">
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Joining Date *</label>
        <input type="date" name="joining_date" class="form-control" required
               value="{{ old('joining_date', optional($emp->joining_date ?? null)->format('Y-m-d') ?? date('Y-m-d')) }}">
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="2">{{ old('address', $emp->address ?? '') }}</textarea>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Department *</label>
        <select name="department_id" class="form-select" required>
            <option value="">-- Select Department --</option>
            @foreach(\App\Models\Department::orderBy('department_name')->get() as $dept)
                <option value="{{ $dept->id }}" @selected(old('department_id', $emp->department_id ?? '')==$dept->id)>
                    {{ $dept->department_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Designation *</label>
        <select name="designation_id" class="form-select" required>
            <option value="">-- Select Designation --</option>
            @foreach(\App\Models\Designation::orderBy('designation_name')->get() as $desig)
                <option value="{{ $desig->id }}" @selected(old('designation_id', $emp->designation_id ?? '')==$desig->id)>
                    {{ $desig->designation_name }}
                </option>
            @endforeach
        </select>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Job Type *</label>
        <select name="job_type" class="form-select" required>
            <option value="1" @selected(old('job_type', $emp->job_type ?? 1)==1)>Full Time</option>
            <option value="2" @selected(old('job_type', $emp->job_type ?? 1)==2)>Part Time</option>
            <option value="3" @selected(old('job_type', $emp->job_type ?? 1)==3)>Contract</option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Monthly Salary (৳) *</label>
        <input type="number" step="0.01" name="monthly_salary" class="form-control" required
               value="{{ old('monthly_salary', $emp->monthly_salary ?? 0) }}"
               placeholder="e.g. 30000">
        <small class="text-muted">Payroll er basic salary ei field theke asbe.</small>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Salary Type *</label>
        <select name="salary_type" class="form-select" required>
            <option value="monthly" @selected(old('salary_type', $emp->salary_type ?? 'monthly')=='monthly')>Monthly</option>
            <option value="daily" @selected(old('salary_type', $emp->salary_type ?? 'monthly')=='daily')>Daily</option>
            <option value="hourly" @selected(old('salary_type', $emp->salary_type ?? 'monthly')=='hourly')>Hourly</option>
        </select>
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            <option value="1" @selected(old('status', $emp->status ?? 1)==1)>Active</option>
            <option value="2" @selected(old('status', $emp->status ?? 1)==2)>Inactive</option>
            <option value="3" @selected(old('status', $emp->status ?? 1)==3)>Resigned</option>
        </select>
    </div>
</div>