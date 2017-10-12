@foreach($match->joinRequests as $request)
    <div class="list-group-item d-flex justify-content-between align-items-center">
        <a>{{$request->username}}</a>
        <div class="btn-group">
            <button class="btn btn-success"
                    @click="toggleModal({
                            url: '{{ action('Match\MatchUsersController@acceptJoin', $match) }}',
                            class: 'btn-success',
                            delete: false,
                            user: {{ $request->id }},
                            buttonText: '@lang('match/show.add',['user' => $request->username])'
                        })">
                <i class="fa fa-check"></i>
            </button>
            <button class="btn btn-danger"
                    @click="toggleModal({
                            url: '{{ action('Match\MatchUsersController@rejectJoin', $match) }}',
                            class: 'btn-danger',
                            delete: true,
                            user: {{ $request->id }},
                            buttonText: '@lang('match/show.rejectRequest',['user' => $request->username])'
                        })">
                <i class="fa fa-times"></i>
            </button>
        </div>
    </div>
@endforeach
