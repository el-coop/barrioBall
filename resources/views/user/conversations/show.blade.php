@extends('layouts.app')
@section('title','Conversations')

@section('content')
    @parent
    <conversations-page inline-template
                        :current-user="{{$user->id}}">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <ul class="list-group">
                        <conversation-button v-for="conversation in conversations" :conversation="conversation"
                                             :current-user="currentUser" @conversation-change="changeConversation">
                        </conversation-button>
                    </ul>
                </div>
                <div class="col-6">
                    <conversation :conversation="currentConversation">
                    </conversation>
                </div>
            </div>
        </div>
    </conversations-page>
@endsection