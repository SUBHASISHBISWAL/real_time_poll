@extends('layouts.app')

@section('title', 'Admin Dashboard')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Admin Dashboard</h2>
                <a href="{{ route('polls.index') }}" class="btn btn-outline-primary">Back to Polls</a>
            </div>

            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Manage Polls</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Question</th>
                                    <th>Status</th>
                                    <th>Total Votes</th>
                                    <th>Created At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($polls as $poll)
                                <tr>
                                    <td>{{ $poll->id }}</td>
                                    <td>{{ $poll->question }}</td>
                                    <td>
                                        <span class="badge {{ $poll->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
                                            {{ ucfirst($poll->status) }}
                                        </span>
                                    </td>
                                    <td>{{ $poll->votes_count }}</td>
                                    <td>{{ $poll->created_at->format('Y-m-d H:i') }}</td>
                                    <td>
                                        <a href="{{ url('/admin/polls/'.$poll->id.'/votes') }}" class="btn btn-sm btn-info">View IPs</a>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
