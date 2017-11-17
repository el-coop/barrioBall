<form method="post" action="{{ action('User\UserController@delete') }}">
    {{ csrf_field() }}
    {{ method_field('delete') }}
    <h4>
        @lang('profile/page.deleteAccount')
    </h4>
    <hr>

    <div class="form-group">
        <p>@lang('profile/page.deleteWarning')</p>

        <swal-submit class="btn btn-outline-danger"
                     title="@lang('match/show.areYouSure')"
                     confirm-text=" @lang('profile/page.deleteYourAccount')"
                     cancel-text="No"
        ><i class="fa fa-times-circle"></i> @lang('profile/page.deleteYourAccount')
        </swal-submit>
    </div>
</form>
