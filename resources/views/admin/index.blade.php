@extends('adminlte::page')
@section('title', 'Admin Portal')
@section('content')
    <div class="container">
        <div class="row">
            @session('success')
                <div class="alert alert-success" role="alert">
                    {{ session('success') }}
                </div>
            @endsession
        </div>
        <h2 class="pt-4">Usuarios</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Nombre</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-primary">Ver detalles</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="d-flex justify-content-center mt-4">
        {{ $users->links('pagination::bootstrap-4') }}
    </div>

@endsection
