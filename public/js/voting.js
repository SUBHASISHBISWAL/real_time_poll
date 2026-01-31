$(document).ready(function () {

    // Delegate click event for dynamically created vote button
    $(document).on('click', '#voteBtn', function () {
        var selectedOption = $('input[name="pollOption"]:checked').val();

        if (!selectedOption) {
            alert('Please select an option to vote!');
            return;
        }

        var pollId = currentPoll.id; // currentPoll is defined in index.blade.php
        var btn = $(this);

        // Disable button to prevent double clicks
        btn.prop('disabled', true).text('Submitting...');

        $.ajax({
            url: '/polls/' + pollId + '/vote',
            type: 'POST',
            data: {
                option_id: selectedOption,
                _token: $('meta[name="csrf-token"]').attr('content')
            },
            success: function (response) {
                if (response.success) {
                    // Show success message
                    $('#pollOptions').html(`
                        <div class="alert alert-success text-center">
                            <h4><i class="fas fa-check-circle"></i> Vote Recorded!</h4>
                            <p>${response.message}</p>
                        </div>
                    `);

                    // Future: Trigger Module 3 results update here
                }
            },
            error: function (xhr) {
                btn.prop('disabled', false).text('Submit Vote');

                var errorMsg = 'An error occurred';
                if (xhr.responseJSON && xhr.responseJSON.message) {
                    errorMsg = xhr.responseJSON.message;
                }

                alert(errorMsg);
            }
        });
    });
});
