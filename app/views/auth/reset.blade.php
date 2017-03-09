@extends('_layouts.auth')

@section('body')
{{ Form::open(['url' => '/password/reset', 'class' => 'form-signin form-reset']) }}
    <h2 class="form-signin-heading">Change password</h2>
    {{ Form::hidden('token', $token) }}
    {{ Form::label('email', 'Email address', ['class' => 'sr-only', 'required' => '', 'autofocus' => '']) }}
    {{ Form::text('email', (is_null($reminder) ? null : $reminder->email), ['class' => 'form-control', 'placeholder' => 'Email address']) }}

    {{ Form::label('password', 'Password', ['class' => 'sr-only', 'required' => '']) }}
    {{ Form::password('password', ['class' => 'form-control', 'placeholder' => 'Password']) }}

    {{ Form::label('password_confirmation', 'Confirm Password', ['class' => 'sr-only', 'required' => '']) }}
    {{ Form::password('password_confirmation', ['class' => 'form-control', 'placeholder' => 'Confirm Password']) }}

    <button class="btn btn-lg btn-primary btn-block" type="submit">Change</button>
    <div class="links">
        <a href="/login">I have account</a>
    </div>
{{ Form::close() }}
@stop