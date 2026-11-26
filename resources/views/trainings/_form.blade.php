<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Employee *</label>
        <select name="employee_id" class="form-select" required>
            <option value="">— Select —</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" @selected(old('employee_id', $training->employee_id ?? '') == $emp->id)>
                    {{ $emp->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Training Title *</label>
        <input type="text" name="training_title" class="form-control"
               value="{{ old('training_title', $training->training_title ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Training Type *</label>
        <select name="training_type" class="form-select" required>
            @foreach(['Internal','External','Online'] as $type)
                <option value="{{ $type }}" @selected(old('training_type', $training->training_type ?? '') == $type)>{{ $type }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Institute Name</label>
        <input type="text" name="institute_name" class="form-control"
               value="{{ old('institute_name', $training->institute_name ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Country</label>
        <input type="text" name="country" class="form-control"
               value="{{ old('country', $training->country ?? 'Bangladesh') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Start Date *</label>
        <input type="date" name="start_date" class="form-control"
               value="{{ old('start_date', optional($training->start_date ?? null)->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">End Date</label>
        <input type="date" name="end_date" class="form-control"
               value="{{ old('end_date', optional($training->end_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Duration (Hours)</label>
        <input type="number" name="duration_hours" class="form-control"
               value="{{ old('duration_hours', $training->duration_hours ?? '') }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Cost (BDT)</label>
        <input type="number" step="0.01" name="cost" class="form-control"
               value="{{ old('cost', $training->cost ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            @foreach(\App\Models\Training::statusLabels() as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $training->status ?? 1) == $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-12">
        <label class="form-label">Remarks</label>
        <textarea name="remarks" class="form-control" rows="2">{{ old('remarks', $training->remarks ?? '') }}</textarea>
    </div>
</div>