@extends('_layouts.auth')

@section('body')
{{ Form::open(['url' => '/register', 'class' => 'form-signin form-signup']) }}
    <h2 class="form-signin-heading">Please sign up</h2>
    {{ Form::label('email', 'Email address', ['class' => 'sr-only', 'required' => '', 'autofocus' => '']) }}
    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email address']) }}

    {{ Form::label('password', 'Password', ['class' => 'sr-only', 'required' => '']) }}
    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}

    {{ Form::label('conf_password', 'Confirm Password', ['class' => 'sr-only', 'required' => '']) }}
    {{ Form::password('conf_password', ['class' => 'form-control', 'placeholder' => 'Confirm Password']) }}

    <button class="btn btn-lg btn-primary btn-block" type="submit">Sign up</button>
    <div class="links">
        <a href="/login">I have account</a>
    </div>
{{ Form::close() }}
@stop