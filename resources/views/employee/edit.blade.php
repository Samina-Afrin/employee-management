@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Edit Employee: {{ $employee->employee_code }}</h3>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Back</a>
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

<form action="{{ route('employee.update', $employee) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('employee._form', ['employee' => $employee])
    <button class="btn btn-primary">Update</button>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection