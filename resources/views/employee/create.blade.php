@extends('layouts.master')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-3">
    <h3>Create Employee</h3>
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

<form action="{{ route('employee.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    @include('employee._form')
    <button class="btn btn-primary">Save</button>
    <a href="{{ route('employee.index') }}" class="btn btn-secondary">Cancel</a>
</form>
@endsection