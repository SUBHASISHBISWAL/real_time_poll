@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Login</h4>
                </div>
                <div class="card-body">
                    <div id="errorMsg" class="alert alert-danger d-none"></div>
                    
                    <form id="loginForm">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="remember" name="remember">
                            <label class="form-check-label" for="remember">Remember me</label>
                        </div>
                        <button type="submit" class="btn btn-primary w-100" id="submitBtn">Login</button>
                    </form>
                    
                    <div class="mt-3 text-center">
                        <p>Don't have an account? <a href="/register">Register here</a></p>
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
    $('#loginForm').submit(function(e) {
        e.preventDefault();
        
        $('#submitBtn').prop('disabled', true).text('Logging in...');
        $('#errorMsg').addClass('d-none');
        
        var formData = {
            email: $('#email').val(),
            password: $('#password').val(),
            remember: $('#remember').is(':checked')
        };
        
        $.ajax({
            url: '/login',
            type: 'POST',
            data: formData,
            success: function(response) {
                if(response.success) {
                    window.location.href = response.redirect;
                }
            },
            error: function(xhr) {
                var msg = 'Login failed. Please try again.';
                if(xhr.responseJSON && xhr.responseJSON.message) {
                    msg = xhr.responseJSON.message;
                }
                $('#errorMsg').text(msg).removeClass('d-none');
                $('#submitBtn').prop('disabled', false).text('Login');
            }
        });
    });
});
</script>
@endpush
