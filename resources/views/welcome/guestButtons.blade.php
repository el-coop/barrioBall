@component('welcome.components.verticalButtons',[
    'buttons' => [
        [
            'url' => action('Auth\LoginController@showLoginForm'),
            'text' => __('navbar.loginLink'),
            'class' => 'btn-primary',
        ],
        [
            'url' => action('Auth\RegisterController@showRegistrationForm'),
            'text' => __('navbar.registerLink'),
            'class' => 'btn-outline-dark',
        ],
    ]
])
@endcomponent