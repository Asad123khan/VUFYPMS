@extends('layouts.vufypms')

@section('content')
<div class="card shadow-sm">
    <div class="card-header bg-white"><h5 class="mb-0">User Management</h5></div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered table-striped align-middle">
                <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th width="220">Action</th>
                </tr>
                </thead>
                <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td><span class="badge bg-secondary">{{ $user->role }}</span></td>
                        <td>
                            <form method="POST" action="{{ route('admin.users.updateRole', $user) }}" class="d-flex gap-2">
                                @csrf
                                @method('PATCH')
                                <select name="role" class="form-select form-select-sm">
                                    <option value="student" @selected($user->role === 'student')>Student</option>
                                    <option value="supervisor" @selected($user->role === 'supervisor')>Supervisor</option>
                                    <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                </select>
                                <button class="btn btn-sm btn-primary">Update</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>

        {{ $users->links() }}
    </div>
</div>
@endsection
