@extends('layouts.sss')

@section('content')
    <h1>Edit Visitor</h1>

    {!! Form::open(['action' => ['PostsController@update', $post->id], 'method' => 'POST', 'enctype' => 'multipart/form-data']) !!}
    <div class="form-row align-items-center">
        <div class="col-sm-3 my-1">
            {{Form::label('visitor_name', 'Visitor Name')}}
            {{Form::text('visitor_name', $post->visitor_name, ['class' => 'form-control', 'readonly' => 'true',  'placeholder' => 'Visitor Name'])}}
        </div>
        <div class="col-sm-3 my-1">
            {{Form::label('visitor_phone', 'Visitor Phone')}}
            {{Form::text('visitor_phone', $post->visitor_phone, ['class' => 'form-control', 'readonly' => 'true', 'placeholder' => 'Visitor Phone'])}}
        </div>
        <div class="col-sm-3 my-1">
            {{Form::label('date', 'Date')}}
            {{Form::text('date', $post->date, ['class' => 'form-control', 'readonly' => 'true', 'placeholder' => 'Date'])}}
        </div>
        <div class="col-sm-3 my-1">
            {{Form::label('notificationTime', 'Time of Appointment')}}
            {{Form::text('notificationTime', $post->notificationTime, ['class' => 'form-control', 'readonly' => 'true', 'placeholder' => 'Time of Appointment'])}}
        </div>
        <div class="col-sm-3 my-1">
            {{Form::label('coy_name', 'COY Name')}}
            {{Form::text('coy_name', $post->coy_name, ['class' => 'form-control', 'placeholder' => 'COY Name'])}}
        </div>
        <div class="col-sm-3 my-1">
            {{Form::label('coy_address', 'COY Address')}}
            {{Form::text('coy_address', $post->coy_address, ['class' => 'form-control', 'placeholder' => 'COY Address'])}}
        </div>
        
        <div class="col-sm-3 my-1">
            {{Form::label('time_in', 'Time In')}}
            <input type="time" class="form-control" name="time-in">
        </div>
        <div class="col-sm-3 my-1">
        
            {{Form::label('time_out', 'Time Out')}}
            <input type="time" class="form-control" name="time-out">
            {!! Form::hidden('timezoneOffset', null, array('id' => 'user-timezone')) !!}
        </div>

        <!-- <input type="time" id="appt" class = 'form-control' name="time_in"
            min="9:00" max="18:00" placeholder='{{($post->time_out)}}' required> -->
        
        <div class="col-sm-3 my-1">
        <br>
        <div class="row">
        
        </div>
            {{Form::file('cover_image')}}
            <br><br>
        </div>

        <div class="col-sm-3 my-1">
            {{ Form::select('status', ['SELECT VISITOR STATUS', 'within' => 'within', 'left' => 'left'], null, ['class' => 'form-control']) }}
        </div>
        
        <div class="col-sm-3 my-1">
        {{Form::hidden('_method','PUT')}}        
        {{Form::submit('Submit', ['class'=>'btn btn-primary'])}}
        </div>
        
    </div>
    {!! Form::close() !!}
    
@endsection
