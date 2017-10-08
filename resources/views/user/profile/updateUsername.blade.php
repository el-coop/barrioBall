
@component('user.profile.component.form',[
    'url' => action('UserController@updateUsername'),
    'title' => __('profile/page.changeUsername'),
    'fields' => [[
        'label' => __('profile/page.newUsername'),
        'name' => 'username',
        'value' => $user->username,
    ],],
    'buttonText' => __('profile/page.updateUsername'),
])
@endcomponent