@extends('layouts.master')

@section('title', 'Edit Department')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Department: {{ $department->department_name }}</h4>
        <a href="{{ route('department.index') }}" class="btn btn-secondary">Back</a>
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

    <form action="{{ route('department.update', $department) }}" method="POST">
        @csrf
        @method('PUT')
        @include('department._form', ['dept' => $department])
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('department.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection