@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Add Training Record</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('trainings.store') }}" method="POST">
        @csrf
        @include('trainings._form', ['training' => null])
        <button class="btn btn-success">Save</button>
        <a href="{{ route('trainings.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection