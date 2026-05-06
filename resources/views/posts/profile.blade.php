{{-- resources/views/account/profile.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container mt-3">

    <h1 class="mb-4">Account Profile</h1>

    {{-- User Info --}}
    <div class="card mb-4">
        <div class="card-body">
            <p><strong>Name:</strong> {{ auth()->user()->name }}</p>
            <p><strong>Email:</strong> {{ auth()->user()->email }}</p>
            <p><strong>Joined:</strong> {{ auth()->user()->created_at->format('Y-m-d') }}</p>
        </div>
    </div>

    {{-- Update Account Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <h5>Update Profile</h5>

            <form method="POST" action="{{ route('profile.update') }}">
                @csrf
                @method('PATCH')

                <div class="mb-3">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email) }}"
                        class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password <small>(optional)</small></label>
                    <input type="password" name="password" class="form-control">
                </div>

                <div class="mb-3">
                    <label class="form-label">Confirm Password</label>
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <button type="submit" class="btn btn-primary">Update Account</button>
            </form>
        </div>
    </div>

    {{-- Logout --}}
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn btn-danger">
                    Logout
                </button>
            </form>
        </div>
    </div>

</div>
@endsection