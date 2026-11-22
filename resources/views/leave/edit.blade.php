@extends('layouts.master')

@section('title', 'Edit Leave')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Leave #{{ $leave->id }}</h4>
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

    <form action="{{ route('leave.update', $leave) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <div class="col-md-6 mb-3">
                <label class="form-label">Employee *</label>
                <select name="employee_id" class="form-select" required>
                    @foreach($employees as $emp)
                        <option value="{{ $emp->id }}"
                            @selected(old('employee_id', $leave->employee_id)==$emp->id)>
                            {{ $emp->employee_code }} - {{ $emp->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Leave Type *</label>
                <select name="leave_type" class="form-select" required>
                    <option value="1" @selected(old('leave_type', $leave->leave_type)==1)>Casual</option>
                    <option value="2" @selected(old('leave_type', $leave->leave_type)==2)>Sick</option>
                    <option value="3" @selected(old('leave_type', $leave->leave_type)==3)>Annual</option>
                    <option value="4" @selected(old('leave_type', $leave->leave_type)==4)>Festival</option>
                    <option value="5" @selected(old('leave_type', $leave->leave_type)==5)>Maternity</option>
                    <option value="6" @selected(old('leave_type', $leave->leave_type)==6)>Paternity</option>
                    <option value="7" @selected(old('leave_type', $leave->leave_type)==7)>Unpaid</option>
                    <option value="8" @selected(old('leave_type', $leave->leave_type)==8)>Compensatory</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Start Date *</label>
                <input type="date" name="start_date" class="form-control" required
                       value="{{ old('start_date', optional($leave->start_date)->format('Y-m-d')) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">End Date *</label>
                <input type="date" name="end_date" class="form-control" required
                       value="{{ old('end_date', optional($leave->end_date)->format('Y-m-d')) }}">
            </div>

            <div class="col-md-6 mb-3">
                <label class="form-label">Yearly Limit Days</label>
                <input type="number" step="0.5" name="yearly_limit_days" class="form-control"
                       value="{{ old('yearly_limit_days', $leave->yearly_limit_days) }}">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Reason</label>
                <textarea name="reason" class="form-control" rows="3"
                          maxlength="255">{{ old('reason', $leave->reason) }}</textarea>
            </div>
        </div>

        <button class="btn btn-primary">Update</button>
        <a href="{{ route('leave.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection