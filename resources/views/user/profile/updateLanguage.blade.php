@component('user.profile.component.form',[
    'url' => action('UserController@updateLanguage'),
    'title' => __('profile/page.changeLanguage'),
    'fields' => [[
        'type' => 'languages'
    ],],
    'buttonText' => __('profile/page.updateLanguage'),
])
@endcomponent