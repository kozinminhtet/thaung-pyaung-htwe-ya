{{-- resources/views/admin/dashboard.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="admin-dashboard">

    <h1 class="mb-4">Admin Dashboard</h1>

    <div class="row g-4">

        {{-- Cards overview --}}
        <div class="col-md-4">
            <div class="card text-bg-primary">
                <div class="card-body">
                    <h5 class="card-title">Posts</h5>
                    <p class="card-text">Manage all posts in the system.</p>
                    <a href="{{ route('posts.index') }}" class="btn btn-light btn-sm">View Posts</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-success">
                <div class="card-body">
                    <h5 class="card-title">Users</h5>
                    <p class="card-text">Manage registered users and roles.</p>
                    <a href="{{ route('admin.users.index') }}" class="btn btn-light btn-sm">View Users</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-bg-warning">
                <div class="card-body">
                    <h5 class="card-title">Stats</h5>
                    <p class="card-text">Quick overview of site activity.</p>
                    <a href="#" class="btn btn-light btn-sm">View Stats</a>
                </div>
            </div>
        </div>

    </div>

    {{-- Optional: Recent activity table --}}
    <div class="mt-5">
        <h4>Recent Posts</h4>
        <table class="table table-striped table-bordered mt-3">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                {{-- Example row --}}
                <tr>
                    <td>1</td>
                    <td>မင်္ဂလာပါ</td>
                    <td>Admin</td>
                    <td>2026-03-29</td>
                    <td>
                        <a href="#" class="btn btn-sm btn-primary"><i class="bi bi-pencil"></i></a>
                        <a href="#" class="btn btn-sm btn-danger"><i class="bi bi-trash"></i></a>
                    </td>
                </tr>
                {{-- Loop through real posts later --}}
            </tbody>
        </table>
    </div>

</div>
@endsection