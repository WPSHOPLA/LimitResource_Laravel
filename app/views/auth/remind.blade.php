@extends('_layouts.auth')

@section('body')
{{ Form::open(['url' => '/password/remind', 'class' => 'form-signin form-remind']) }}
    <h2 class="form-signin-heading">Password Reminder</h2>
    {{ Form::label('email', 'Email address', ['class' => 'sr-only', 'required' => '', 'autofocus' => '']) }}
    {{ Form::text('email', null, ['class' => 'form-control', 'placeholder' => 'Email address']) }}

    <button class="btn btn-lg btn-primary btn-block" type="submit">Remind password</button>
    <div class="links">
        <a href="/login">Back to login</a>
    </div>
{{ Form::close() }}
@stop