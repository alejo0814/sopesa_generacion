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
<h1>Roles</h1>
<a href="{{ route('roles.create') }}">Crear Rol</a>
{{-- <ul>
    @foreach($roles as $role)
    <li>{{ $role->name }} - <a href="{{ route('roles.edit', $role->id) }}">Editar</a></li>
    @endforeach
</ul> --}}









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
          
            @foreach($roles as $role)
            <tr>
                <td>{{ $role->name }} </td>

                <td><a href="{{ route('roles.updatePermissions', $role->id) }}">Editar</a>
                 
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