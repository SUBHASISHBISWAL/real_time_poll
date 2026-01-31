@extends('layouts.app')

@section('title', 'Voter IPs - ' . $poll->question)

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>Voter IPs: {{ $poll->question }}</h2>
                <a href="{{ url('/admin/dashboard') }}" class="btn btn-outline-secondary">Back to Dashboard</a>
            </div>

            <div class="card shadow">
                <div class="card-header bg-dark text-white">
                    <h5 class="mb-0">Current Votes</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped align-middle">
                            <thead>
                                <tr>
                                    <th>IP Address</th>
                                    <th>Selected Option</th>
                                    <th>Voted At</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($votes as $vote)
                                <tr id="row-{{ str_replace('.', '-', $vote->ip_address) }}">
                                    <td><code>{{ $vote->ip_address }}</code></td>
                                    <td>{{ $vote->option->option_text }}</td>
                                    <td>{{ $vote->voted_at->format('Y-m-d H:i:s') }}</td>
                                    <td>
                                        <button class="btn btn-sm btn-warning release-btn" 
                                                data-ip="{{ $vote->ip_address }}" 
                                                data-poll-id="{{ $poll->id }}">
                                            Release
                                        </button>
                                        <button class="btn btn-sm btn-outline-info audit-btn" 
                                                data-ip="{{ $vote->ip_address }}" 
                                                data-poll-id="{{ $poll->id }}">
                                            Audit Trail
                                        </button>
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

<!-- Audit Trail Modal -->
<div class="modal fade" id="auditModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Audit Trail for IP: <span id="modalIp"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="auditContent">Loading...</div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script>
$(document).ready(function() {
    // Release Vote
    $('.release-btn').click(function() {
        var ip = $(this).data('ip');
        var pollId = $(this).data('poll-id');
        var btn = $(this);

        if(!confirm('Are you sure you want to release the vote for IP ' + ip + '? This will allow them to vote again.')) {
            return;
        }

        $.ajax({
            url: '/admin/polls/' + pollId + '/release/' + ip,
            type: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(response) {
                if(response.success) {
                    alert(response.message);
                    location.reload(); // Refresh to update list
                }
            },
            error: function(xhr) {
                alert('Error: ' + xhr.responseJSON.message);
            }
        });
    });

    // View Audit Trail
    $('.audit-btn').click(function() {
        var ip = $(this).data('ip');
        var pollId = $(this).data('poll-id');
        
        $('#modalIp').text(ip);
        $('#auditContent').html('<div class="text-center"><div class="spinner-border"></div></div>');
        var modal = new bootstrap.Modal(document.getElementById('auditModal'));
        modal.show();

        $.get('/admin/polls/' + pollId + '/audit/' + ip, function(response) {
            if(response.success) {
                var html = '<table class="table table-sm"><thead><tr><th>Action</th><th>Option</th><th>Timestamp</th></tr></thead><tbody>';
                response.history.forEach(function(item) {
                    var badgeClass = item.action === 'voted' ? 'bg-success' : 'bg-warning';
                    html += `
                        <tr>
                            <td><span class="badge ${badgeClass}">${item.action.toUpperCase()}</span></td>
                            <td>${item.option ? item.option.option_text : 'N/A'}</td>
                            <td>${new Date(item.created_at).toLocaleString()}</td>
                        </tr>
                    `;
                });
                html += '</tbody></table>';
                $('#auditContent').html(html);
            }
        });
    });
});
</script>
@endpush
