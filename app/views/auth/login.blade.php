@extends('_layouts.auth')

@section('body')
{{ Form::open(['url' => '/login', 'class' => 'form-signin']) }}
    <h2 class="form-signin-heading">Please sign in</h2>
    {{ Form::label('email', 'Email address', ['class' => 'sr-only', 'required' => '', 'autofocus' => '']) }}
    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email address']) }}

    {{ Form::label('password', 'Password', ['class' => 'sr-only', 'required' => '']) }}
    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
    <div class="links">
        <a href="/password/remind">Forgot password?</a>
        <a href="/signup">Create account</a>
    </div>
{{ Form::close() }}
@stop