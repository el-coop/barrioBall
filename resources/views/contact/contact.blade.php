@extends('layouts.app')

@section('title',__('navbar.contactLink'))

@section('content')
    @parent
    <div class="container-fluid">
        <div class="hero text-center">
            <h1>@lang('navbar.contactLink')</h1>
        </div>
    </div>
    <contact-page inline-template v-cloak>
        <div class="container">
            <div class="row justify-content-between my-5">
                <div class="col-12 col-md-7 mb-5">
                    <transition name="fade">
                        <div class="overlay d-flex justify-content-center align-items-center flex-column"
                             v-if="sending || sent || error">
                            <div v-if="sending">
                                <i class="fa fa-spinner fa-spin fa-4x mb-3"></i>
                                <h4 class="text-center">
                                    @lang('global/contact.sending')
                                </h4>
                            </div>
                            <div v-if="sent">
                                <h3 class="text-center">
                                    @lang('global.success')!
                                </h3>
                                <h5 class="text-center" @click="sent=false">
                                    <a href="#">@lang('global/contact.sendAgain')</a>
                                </h5>
                            </div>
                            <div v-if="error">
                                <h3 class="text-center">
                                    @lang('global.error')!
                                </h3>
                                <h5 class="text-center" @click="error=false">
                                    @{{ error }}<br>
                                    <a href="#">@lang('global/contact.sendAgain')</a>
                                </h5>
                            </div>
                        </div>
                    </transition>
                    <h3 class="text-center mb-2">@lang('global/contact.subHeadline')</h3>
                    <h1 class="text-center mb-4 display-5 font-weight-bold">@lang('global/contact.headline')</h1>
                    <ajax-form btn-wrapper-class="d-none"
                               event="success"
                               :errors-box="false"
                               action="{{ action('HomeController@contactUs') }}"
                               @submit-clicked="sending = true"
                               @success="sending = false; sent = true"
                               @error="showError">
                        <div class="form-group">
                            <input type="email" class="form-control form-control-lg" name="email"
                                   placeholder="@lang('global/contact.email')" value="{{ $user ? $user->email : '' }}"
                                   required>
                        </div>
                        <div class="form-group">
                            <select class="form-control form-control-lg" name="subject" required>
                                <option value="help">@lang('global/contact.help')</option>
                                <option value="feedback">@lang('global/contact.feedback')</option>
                                <option value="coding">@lang('I want to help coding')</option>
                                <option value="translation"{{ Request::input('query', null) == 'translate' ? 'selected' : '' }}>@lang('global/contact.translation')</option>
                                <option value="contribute"{{ Request::input('query', null) == 'contribute' ? ' selected' : '' }}>@lang('global/contact.contribute')</option>
                                <option value="other">@lang('global/contact.other')</option>
                            </select>
                        </div>
                        <div class="form-group textarea-button">
                    <textarea class="form-control form-control-lg" placeholder="@lang('global/contact.yourMessage')"
                              rows="5" name="message" required></textarea>
                            <button class="btn btn-logo bigger-scale-hover">
                                <i class="fa fa-send"></i>
                            </button>
                        </div>
                    </ajax-form>
                </div>
                <div class="col-12 col-md-3">
                    <h4 class="mb-3">@lang('global/contact.connect')</h4>
                    <p>@lang('global/contact.connectText')</p>
                    <h5 class="font-weight-bold">@lang('global/contact.address')</h5>
                    <p>@lang('global/contact.addressText')
                    </p>
                    <h5 class="font-weight-bold">@lang('global/welcome.languages')</h5>
                    <p>@lang('global/contact.languagesText')</p>
                </div>
            </div>
        </div>
    </contact-page>
    <div class="footer py-2">
        <div class="container">
            @include('welcome.footer')
        </div>
    </div>
@endsection