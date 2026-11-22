@extends('layouts.master')

@section('title', 'Apply Leave')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Apply Leave</h4>
        <a href="{{ route('leave.index') }}" class="btn btn-secondary">Back</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('leave.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee *</label>
                <select name="employee_id" class="form-select" required>
                    <option value="">-- Select Employee --</option>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}" @selected(old('employee_id')==$emp->id)>
                            {{ $emp->employee_code }} - {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Leave Type *</label>
                <select name="leave_type" class="form-select" required>
                    <option value="">-- Select Type --</option>
                    <option value="1" @selected(old('leave_type')==1)>Casual (10 days)</option>
                    <option value="2" @selected(old('leave_type')==2)>Sick (14 days)</option>
                    <option value="3" @selected(old('leave_type')==3)>Annual (18 days)</option>
                    <option value="4" @selected(old('leave_type')==4)>Festival (11 days)</option>
                    <option value="5" @selected(old('leave_type')==5)>Maternity (112 days)</option>
                    <option value="6" @selected(old('leave_type')==6)>Paternity (5 days)</option>
                    <option value="7" @selected(old('leave_type')==7)>Unpaid</option>
                    <option value="8" @selected(old('leave_type')==8)>Compensatory</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date *</label>
                <input type="date" name="start_date" class="form-control" required
                       value="{{ old('start_date', date('Y-m-d')) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">End Date *</label>
                <input type="date" name="end_date" class="form-control" required
                       value="{{ old('end_date', date('Y-m-d')) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Yearly Limit Days (Optional)</label>
                <input type="number" step="0.5" name="yearly_limit_days" class="form-control"
                       value="{{ old('yearly_limit_days') }}"
                       placeholder="">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Reason</label>
                <textarea name="reason" class="form-control" rows="3"
                          maxlength="255">{{ old('reason') }}</textarea>
            </div>
        </div>

        <button class="btn btn-primary">Submit Application</button>
        <a href="{{ route('leave.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection