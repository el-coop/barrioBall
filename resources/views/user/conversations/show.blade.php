@extends('layouts.app')
@section('title','Conversations')

@section('content')
    @parent
    @foreach($conversations as $conversation)
        @foreach($conversation->messages as $message)
            <li>{{$message->user->username}}</li>
            <li>{{$message->text}}</li>
        @endforeach
    @endforeach
@endsection