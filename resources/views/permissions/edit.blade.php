
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
@stop


@section('content')
    <h1>Editar Permiso</h1>
    <form action="{{ route('permissions.update', $permission->id) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="name">Nombre del Permiso:</label>
        <input type="text" name="name" id="name" value="{{ $permission->name }}">
        <button type="submit">Actualizar</button>
    </form>
@endsection