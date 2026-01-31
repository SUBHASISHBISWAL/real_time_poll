@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Register</h4>
                </div>
                <div class="card-body">
                    <div id="errorMsg" class="alert alert-danger d-none"></div>
                    
                    <form id="registerForm">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required minlength="6">
                        </div>
                        <div class="mb-3">
                            <label for="password_confirmation" class="form-label">Confirm Password</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                        <button type="submit" class="btn btn-success w-100" id="submitBtn">Register</button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>Already have an account? <a href="/login">Login here</a></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    $('#registerForm').submit(function(e) {
        e.preventDefault();
        
        $('#submitBtn').prop('disabled', true).text('Registering...');
        $('#errorMsg').addClass('d-none');
        
        var formData = {
            name: $('#name').val(),
            email: $('#email').val(),
            password: $('#password').val(),
            password_confirmation: $('#password_confirmation').val()
        };
        
        $.ajax({
            url: '/register',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                var msg = 'Registration failed. Please check your input.';
                if(xhr.responseJSON && xhr.responseJSON.errors) {
                    var errors = xhr.responseJSON.errors;
                    msg = Object.values(errors).flat().join('<br>');
                }
                $('#errorMsg').html(msg).removeClass('d-none');
                $('#submitBtn').prop('disabled', false).text('Register');
            }
        });
    });
});
</script>
@endpush
