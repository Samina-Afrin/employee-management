@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Edit Training Record</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trainings.update', $training) }}" method="POST">
        @csrf @method('PUT')
        @include('trainings._form', ['training' => $training])
        <button class="btn btn-success">Update</button>
        <a href="{{ route('trainings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection