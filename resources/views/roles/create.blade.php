@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
@stop

@section('content')
<h1>Crear Permiso</h1>
    <form action="{{ route('roles.store') }}" method="POST">
        @csrf
        <label for="name">Nombre del rol:</label>
        <input type="text" name="name" id="name">
        <button type="submit">Guardar</button>
    </form>
@stop












