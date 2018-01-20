@extends('layouts.app')
@section('title','Conversations')

@section('content')
    @parent
    @if (!$conversations->isEmpty())
        <conversations-page inline-template
                            :init-conversations="{{$conversations}}"
                            :current-user="{{$user->id}}">
            <div class="container">
                <mobile-view-parent class="row" ref="mobileView">
                    <mobile-view-child class="col-lg-3 col-12 px-0 btn-col" icon="fa-list">
                        <ul class="list-group">
                            <conversation-button v-for="conversation in conversations" :key="conversation.id"
                                                 :conversation="conversation"
                                                 :current-user="currentUser"
                                                 v-on:conversation-change="changeConversation"
                                                 :class="{active: conversation.id == currentConversation}">
                            </conversation-button>
                        </ul>
                    </mobile-view-child>
                    <mobile-view-child class="col-lg-8 col-12 px-0" icon="fa-comment">
                        <conversation btn-txt="@lang('conversations/conversation.button')" :current-user="currentUser"
                                      :sender="sender" :conversation="currentConversation">
                        </conversation>
                    </mobile-view-child>
                </mobile-view-parent>
            </div>
        </conversations-page>
    @else
        <div class="container">
            <div class="alert alert-info" role="alert">
                <h3>@lang('conversations/conversation.noConversations')</h3>
            </div>
        </div>
    @endif
@endsection