@foreach($joinRequests as $request)
    <div class="list-group-item d-flex justify-content-between align-items-center">
        <a>{{$request->username}}</a>
        <div class="btn-group">
            @if(! $match->isFull())
            <button class="btn btn-success"
                    @click="toggleModal({
                            url: '{{ action('Match\MatchUserController@acceptJoin', $match) }}',
                            title: '@lang('match/show.message')',
                            class: 'btn-success',
                            delete: false,
                            user: {{ $request->id }},
                            buttonText: '@lang('match/show.add',['user' => $request->username])',
                            buttonIcon: 'fa-plus-circle'
                        })">
                <i class="fa fa-check"></i>
            </button>
            @endif
            <button class="btn btn-danger"
                    @click="toggleModal({
                            url: '{{ action('Match\MatchUserController@rejectJoin', $match) }}',
                            title: '@lang('match/show.message')',
                            class: 'btn-danger',
                            delete: true,
                            user: {{ $request->id }},
                            buttonText: '@lang('match/show.rejectRequest',['user' => $request->username])',
                            buttonIcon: 'fa-minus-circle'
                        })">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
@endforeach
