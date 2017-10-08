@component('welcome.components.verticalButtons',[
    'buttons' => [
        [
            'url' => action('Match\MatchController@showCreate'),
            'text' => __('navbar.createLink'),
            'class' => 'btn-info',
        ],
        [
            'url' => action('Match\MatchController@showSearch'),
            'text' => __('navbar.searchLink'),
            'class' => 'btn-primary',
        ],
        [
            'url' => action('UserController@show'),
            'text' => __('navbar.profileLink'),
            'class' => 'btn-outline-dark',
        ],
    ]
])
@endcomponent