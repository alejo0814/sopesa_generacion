@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<h1>SOPESA S.A E.S.P</h1>
@stop

@section('content')
<div class="container">
    <h1>Editar Roles para {{ $user->name }}</h1>
    <form action="{{ route('users.update_roles', $user->id) }}" method="POST">
        @csrf
        @foreach($roles as $role)
            <div>
                <input type="checkbox" name="roles[]" value="{{ $role->id }}"
                    {{ $user->roles->contains($role->id) ? 'checked' : '' }}>
                <label>{{ $role->name }}</label>
            </div>
        @endforeach
        <button type="submit">Actualizar Roles</button>
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