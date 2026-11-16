@extends('layouts.master')

@section('title', 'Edit Designation')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Edit Designation: {{ $designation->designation_name }}</h4>
        <a href="{{ route('designation.index') }}" class="btn btn-secondary">Back</a>
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

    <form action="{{ route('designation.update', $designation) }}" method="POST">
        @csrf
        @method('PUT')
        @include('designation._form', ['designation' => $designation, 'departments' => $departments])
        <button class="btn btn-primary">Update</button>
        <a href="{{ route('designation.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection