@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
@stop


@section('content')
<div class="container">
    <h1>Editar Permisos para {{ $role->name }}</h1>
    <form action="{{ route('roles.update_permissions', $role->id) }}" method="POST">
        @csrf
        @foreach($permissions as $permission)
            <div>
                <input type="checkbox" name="permissions[]" value="{{ $permission->id }}"
                    {{ $role->hasPermissionTo($permission->name) ? 'checked' : '' }}>
                <label>{{ $permission->name }}</label>
            </div>
        @endforeach
        <button type="submit">Actualizar Permisos</button>
    </form>
</div>
@endsection
















@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->

@stop

@section('js')
<script>
    console.log('Hi!');
</script>
@stop