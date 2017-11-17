
@component('user.profile.component.form',[
    'url' => action('User\UserController@updateEmail'),
    'title' => __('profile/page.changeEmail'),
    'fields' => [[
        'label' => __('profile/page.email'),
        'name' => 'email',
        'value' => $user->email,
    ],],
    'buttonText' => __('profile/page.updateEmail'),
])
@endcomponent