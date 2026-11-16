@extends('layouts.master')

@section('title', 'Create Designation')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Create Designation</h4>
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

    <form action="{{ route('designation.store') }}" method="POST">
        @csrf
        @include('designation._form')
        <button class="btn btn-primary">Save</button>
        <a href="{{ route('designation.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection