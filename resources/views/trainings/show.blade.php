@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Training Details</h3>

    <table class="table table-bordered">
        <tr><th>Employee</th><td>{{ $training->employee->name ?? '—' }}</td></tr>
        <tr><th>Title</th><td>{{ $training->training_title }}</td></tr>
        <tr><th>Type</th><td>{{ $training->training_type }}</td></tr>
        <tr><th>Institute</th><td>{{ $training->institute_name }}</td></tr>
        <tr><th>Country</th><td>{{ $training->country }}</td></tr>
        <tr><th>Start Date</th><td>{{ $training->start_date?->format('Y-m-d') }}</td></tr>
        <tr><th>End Date</th><td>{{ $training->end_date?->format('Y-m-d') }}</td></tr>
        <tr><th>Duration (Hours)</th><td>{{ $training->duration_hours }}</td></tr>
        <tr><th>Cost (BDT)</th><td>{{ number_format($training->cost, 2) }}</td></tr>
        <tr><th>Status</th><td>{{ \App\Models\Training::statusLabels()[$training->status] }}</td></tr>
        <tr><th>Remarks</th><td>{{ $training->remarks }}</td></tr>
    </table>

    <a href="{{ route('trainings.index') }}" class="btn btn-secondary">Back</a>
    <a href="{{ route('trainings.edit', $training) }}" class="btn btn-warning">Edit</a>
</div>
@endsection