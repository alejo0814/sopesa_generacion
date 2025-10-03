@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>SOPESA S.A E.S.P</h1>
@stop

@section('content')
<div class="container">
    <h1>Usuarios</h1>
    <table class="table">
        <thead>
            <tr>
                <th>Nombre</th>
                <th>Email</th>
                <th>Roles</th>
                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
            @foreach($users as $user)
                <tr>
                    <td>{{ $user->name }}</td>
                    <td>{{ $user->email }}</td>
                    <td>{{ implode(', ', $user->roles->pluck('name')->toArray()) }}</td>
                    <td>
                        <a href="{{ route('users.edit_roles', $user->id) }}" class="btn btn-primary">Editar Roles</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection


@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->

@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop