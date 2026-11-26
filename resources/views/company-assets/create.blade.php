@extends('layouts.master')

@section('content')
<div class="container">
    <h3>Add Company Asset</h3>

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('company-assets.store') }}" method="POST">
        @csrf
        @include('company-assets._form', ['asset' => null])
        <button class="btn btn-success">Save</button>
        <a href="{{ route('company-assets.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection