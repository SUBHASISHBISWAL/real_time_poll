var resultInterval = null;

function startPollingResults(pollId) {
    // Clear any existing interval
    if (resultInterval) {
        clearInterval(resultInterval);
    }

    // Initial load
    loadResults(pollId);

    // Poll every 3 seconds (slightly slower than 1s to be kinder to the server)
    resultInterval = setInterval(function () {
        loadResults(pollId);
    }, 3000);
}

function stopPollingResults() {
    if (resultInterval) {
        clearInterval(resultInterval);
        resultInterval = null;
    }
}

function loadResults(pollId) {
    if (!$('#pollResults').is(':visible') && $('#pollDetailContainer').hasClass('d-none')) {
        stopPollingResults();
        return;
    }

    $.get('/polls/' + pollId + '/results', function (response) {
        if (response.success) {
            updateResultsUI(response);
        }
    }).fail(function () {
        console.error('Failed to fetch results');
    });
}

function updateResultsUI(data) {
    var container = $('#pollResults');
    if (container.length === 0) return;

    var html = `
        <div class="mt-3">
            <h5 class="text-center mb-4">Live Results (${data.total_votes} votes)</h5>
    `;

    data.results.forEach(function (result) {
        html += `
            <div class="mb-3">
                <div class="d-flex justify-content-between mb-1">
                    <span>${result.option_text}</span>
                    <span class="fw-bold">${result.percentage}% (${result.votes})</span>
                </div>
                <div class="progress" style="height: 25px;">
                    <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" 
                         style="width: ${result.percentage}%" 
                         aria-valuenow="${result.percentage}" aria-valuemin="0" aria-valuemax="100">
                    </div>
                </div>
            </div>
        `;
    });

    html += `
            <div class="text-center mt-4">
                <button class="btn btn-outline-secondary btn-sm" onclick="showVoteOptions()">Back to Voting</button>
            </div>
        </div>
    `;

    container.html(html);
}

function showVoteOptions() {
    $('#pollResults').addClass('d-none');
    $('#pollOptions').removeClass('d-none');
    stopPollingResults();
}
