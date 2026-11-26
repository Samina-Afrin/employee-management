<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label">Asset Code *</label>
        <input type="text" name="asset_code" class="form-control"
               value="{{ old('asset_code', $asset->asset_code ?? '') }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label">Asset Name *</label>
        <input type="text" name="asset_name" class="form-control"
               value="{{ old('asset_name', $asset->asset_name ?? '') }}" required>
    </div>
    <div class="col-md-4">
        <label class="form-label">Category *</label>
        <select name="category" class="form-select" required>
            @foreach($categories as $c)
                <option value="{{ $c }}" @selected(old('category', $asset->category ?? '') == $c)>{{ $c }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-4">
        <label class="form-label">Serial Number</label>
        <input type="text" name="serial_number" class="form-control"
               value="{{ old('serial_number', $asset->serial_number ?? '') }}">
    </div>
    <div class="col-md-4">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            @foreach(\App\Models\CompanyAsset::statusLabels() as $val => $label)
                <option value="{{ $val }}" @selected(old('status', $asset->status ?? 1) == $val)>{{ $label }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-6">
        <label class="form-label">Employee (if assigned)</label>
        <select name="employee_id" class="form-select">
            <option value="">— None —</option>
            @foreach($employees as $emp)
                <option value="{{ $emp->id }}" @selected(old('employee_id', $asset->employee_id ?? '') == $emp->id)>
                    {{ $emp->name }}
                </option>
            @endforeach
        </select>
    </div>
    <div class="col-md-3">
        <label class="form-label">Given Date</label>
        <input type="date" name="given_date" class="form-control"
               value="{{ old('given_date', optional($asset->given_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-md-3">
        <label class="form-label">Return Date</label>
        <input type="date" name="return_date" class="form-control"
               value="{{ old('return_date', optional($asset->return_date ?? null)->format('Y-m-d')) }}">
    </div>
    <div class="col-12">
        <label class="form-label">Note</label>
        <textarea name="note" class="form-control" rows="2">{{ old('note', $asset->note ?? '') }}</textarea>
    </div>
</div>