@extends('layouts.master')

@section('title', 'Create Notice')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Create Notice</h4>
        <a href="{{ route('notice.index') }}" class="btn btn-secondary">Back</a>
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

    <form action="{{ route('notice.store') }}" method="POST">
        @csrf

        <div class="row">
            <div class="col-md-12 mb-3">
                <label class="form-label">Title *</label>
                <input type="text" name="title" class="form-control" required
                       value="{{ old('title') }}" maxlength="200">
            </div>

            <div class="col-md-12 mb-3">
                <label class="form-label">Description *</label>
                <textarea name="description" class="form-control" rows="5" required>{{ old('description') }}</textarea>
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Notice Date *</label>
                <input type="date" name="notice_date" class="form-control" required
                       value="{{ old('notice_date', date('Y-m-d')) }}">
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Expire Date (optional)</label>
                <input type="date" name="expire_date" class="form-control"
                       value="{{ old('expire_date') }}">
           
            </div>

            <div class="col-md-4 mb-3">
                <label class="form-label">Status *</label>
                <select name="status" class="form-select" required>
                    <option value="1" @selected(old('status', 1)==1)>Active</option>
                    <option value="0" @selected(old('status')===0)>Hidden</option>
                </select>
            </div>
        </div>

        <button class="btn btn-primary">Save</button>
        <a href="{{ route('notice.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection