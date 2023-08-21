@extends('admin.admin_dashboard')
@section('admin')
    <div class="container">
        <h1>All Users</h1>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->role }}</td>
                        <td>
                            <div class="btn-group" role="group">
                                <a href="{{ route('admin.view.user', ['id' => $user->id]) }}" class="btn btn-primary">View</a>

                                <a href="{{ route('admin.edit.user', ['id' => $user->id]) }}" class="btn btn-warning">Edit</a>
                                
                                <form action="{{ route('admin.delete.user', ['id' => $user->id]) }}" method="post">
                                    @csrf
                                    @method('delete')
                                    <button type="submit" class="btn btn-danger">Delete</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
