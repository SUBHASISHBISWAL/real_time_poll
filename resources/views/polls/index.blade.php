@extends('layouts.app')

@section('title', 'Active Polls')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12">
            <h2 class="mb-4">Active Polls</h2>
            
            <div id="loadingMsg" class="text-center">
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
            
            <div id="pollsList" class="row g-3"></div>
            
            <div id="noPollsMsg" class="alert alert-info d-none">
                No active polls available at the moment.
            </div>
        </div>
    </div>
    
    <!-- Poll Detail Modal -->
    <div id="pollDetailContainer" class="mt-4 d-none">
        <div class="card shadow-lg">
            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                <h4 id="pollQuestion" class="mb-0"></h4>
                <button type="button" class="btn btn-sm btn-light" id="backToList">Back to List</button>
            </div>
            <div class="card-body">
                <div id="pollOptions"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
var currentPoll = null;

$(document).ready(function() {
    loadPolls();
    
    // Back to list button
    $('#backToList').click(function() {
        showPollsList();
    });
});

function loadPolls() {
    $('#loadingMsg').show();
    $('#pollsList').empty();
    
    $.get('/api/polls', function(response) {
        $('#loadingMsg').hide();
        
        if(response.success && response.polls.length > 0) {
            response.polls.forEach(function(poll) {
                var pollCard = `
                    <div class="col-md-4">
                        <div class="card poll-card h-100 shadow-sm" data-poll-id="${poll.id}" style="cursor: pointer;">
                            <div class="card-body">
                                <h5 class="card-title">${poll.question}</h5>
                                <p class="card-text text-muted">
                                    <small>Created by: ${poll.creator.name}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                `;
                $('#pollsList').append(pollCard);
            });
            
            // Click handler for poll cards
            $('.poll-card').click(function() {
                var pollId = $(this).data('poll-id');
                loadPollDetail(pollId);
            });
        } else {
            $('#noPollsMsg').removeClass('d-none');
        }
    });
}

function loadPollDetail(pollId) {
    $.get('/polls/' + pollId, function(response) {
        if(response.success) {
            currentPoll = response.poll;
            
            $('#pollQuestion').text(currentPoll.question);
            $('#pollOptions').empty();
            
            currentPoll.options.forEach(function(option) {
                var optionHtml = `
                    <div class="form-check mb-3 p-3 border rounded option-item" data-option-id="${option.id}">
                        <input class="form-check-input" type="radio" name="pollOption" id="option${option.id}" value="${option.id}">
                        <label class="form-check-label" for="option${option.id}">
                            ${option.option_text}
                        </label>
                    </div>
                `;
                $('#pollOptions').append(optionHtml);
            });
            
            // Add vote button
            $('#pollOptions').append('<button type="button" class="btn btn-primary mt-3" id="voteBtn">Submit Vote</button>');
            
            // Show poll detail, hide list
            $('#pollsList').parent().parent().addClass('d-none');
            $('#pollDetailContainer').removeClass('d-none');
        }
    }).fail(function(xhr) {
        alert('Error loading poll details');
    });
}

function showPollsList() {
    $('#pollDetailContainer').addClass('d-none');
    $('#pollsList').parent().parent().removeClass('d-none');
    currentPoll = null;
}
</script>
@endpush
