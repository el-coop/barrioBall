@component('user.profile.component.form',[
    'url' => action('User\UserController@updatePassword'),
    'title' => __('profile/page.changePassword'),
    'fields' => [[
        'label' => __('profile/page.password'),
        'name' => 'password',
        'value' => '',
        'type' => 'password',
    ],[
        'label' => __('profile/page.confirmPassword'),
        'name' => 'password_confirmation',
        'value' => '',
        'type' => 'password',
    ],],
    'buttonText' => __('profile/page.updatePassword'),
])
@endcomponent