@extends('layouts.master')

@section('title', 'Notice Details')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>Notice Details</h4>
        <a href="{{ route('notice.index') }}" class="btn btn-secondary">Back</a>
    </div>

    <div class="card">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">{{ $notice->title }}</h5>
        </div>
        <div class="card-body">
            <p>{!! nl2br(e($notice->description)) !!}</p>
        </div>
        <div class="card-footer text-muted">
            <div class="row">
                <div class="col-md-3">
                    <strong>Notice Date:</strong> {{ $notice->notice_date?->format('d M Y') }}
                </div>
                <div class="col-md-3">
                    <strong>Expire Date:</strong> {{ $notice->expire_date?->format('d M Y') ?? '—' }}
                </div>
                <div class="col-md-3">
                    <strong>Posted By:</strong> {{ $notice->poster->name ?? '—' }}
                </div>
                <div class="col-md-3">
                    <strong>Status:</strong>
                    @if($notice->status == 1)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Hidden</span>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <a href="{{ route('notice.edit', $notice) }}" class="btn btn-warning mt-3">Edit</a>
</div>
@endsection