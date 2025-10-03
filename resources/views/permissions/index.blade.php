@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
@stop

@section('content')
{{-- <p>publico sopesa</p>
@role('admin')
<p>contenido admin</p>
@endrole
@role('escritor')
<p>contenido escritor</p>
@endrole --}}
<h1>Permisos</h1>
<a href="{{ route('permissions.create') }}">Crear Permiso</a>
{{-- <ul>
@foreach($permissions as $permission)
    <li>{{ $permission->name }} - <a href="{{ route('permissions.edit', $permission->id) }}">Editar</a></li>
    @endforeach
</ul>

 --}}







<div class="container">

    <!--  <h1>Generación Diaria</h1> -->
    <table class="table" id="example">
        <thead>
            <tr>
                <th>rol</th>

                <th>Acciones</th>
            </tr>
        </thead>
        <tbody>
          
            @foreach($permissions as $permission)
            <tr>
                <td>{{ $permission->name }} </td>

                <td><a href="{{ route('permissions.edit', $permission->id) }}">Editar</a>
                 
                </td>
            </tr>
            @endforeach
           
        </tbody>
    </table>
</div>
@stop
























@section('css')
<link rel="stylesheet" href="/css/admin_custom.css">
<!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->

@stop

@section('js')
<script>
    console.log('Hi!');
</script>
@stop