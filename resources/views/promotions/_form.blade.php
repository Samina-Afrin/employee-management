<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Employee *</label>
        <select name="employee_id" class="form-select" required>
            <option value="">— Select —</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" @selected(old('employee_id', $promotion->employee_id ?? '') == $emp->id)>
                    {{ $emp->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Promotion Date *</label>
        <input type="date" name="promotion_date" class="form-control"
               value="{{ old('promotion_date', optional($promotion->promotion_date ?? null)->format('Y-m-d')) }}" required>
    </div>
    <div class="col-md-3">
        <label class="form-label">Promotion Type *</label>
        <select name="promotion_type" class="form-select" required>
            @foreach(['Promotion','Increment','Transfer'] as $t)
                <option value="{{ $t }}" @selected(old('promotion_type', $promotion->promotion_type ?? 'Promotion') == $t)>{{ $t }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Old Designation</label>
        <select name="old_designation_id" class="form-select">
            <option value="">— None —</option>
            @foreach($designations as $d)
                <option value="{{ $d->id }}" @selected(old('old_designation_id', $promotion->old_designation_id ?? '') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">New Designation</label>
        <select name="new_designation_id" class="form-select">
            <option value="">— None —</option>
            @foreach($designations as $d)
                <option value="{{ $d->id }}" @selected(old('new_designation_id', $promotion->new_designation_id ?? '') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Old Department</label>
        <select name="old_department_id" class="form-select">
            <option value="">— None —</option>
            @foreach($departments as $d)
                <option value="{{ $d->id }}" @selected(old('old_department_id', $promotion->old_department_id ?? '') == $d->id)>{{ $d->name }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">New Department</label>