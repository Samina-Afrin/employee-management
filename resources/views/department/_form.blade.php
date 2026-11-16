@php $data = $dept ?? null; @endphp

<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Department Name *</label>
        <input type="text" name="department_name" class="form-control"
               value="{{ old('department_name', $data->department_name ?? '') }}" required>
       
    </div>

    <div class="col-md-6 mb-3">
        <label class="form-label">Department Code</label>
        <input type="text" name="department_code" class="form-control"
               value="{{ old('department_code', $data->department_code ?? '') }}" maxlength="20">
        
    </div>

    <div class="col-md-12 mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="3"
                  maxlength="255">{{ old('description', $data->description ?? '') }}</textarea>
    </div>

    <div class="col-md-4 mb-3">
        <label class="form-label">Status *</label>
        <select name="status" class="form-select" required>
            <option value="1" @selected(old('status', $data->status ?? 1)==1)>Active</option>
            <option value="0" @selected(old('status', $data->status ?? 1)==0)>Inactive</option>
        </select>
    </div>
</div>