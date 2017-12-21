@extends('layouts.app')

@section('title',__('global/welcome.about'))

@section('content')
    @parent
    <div class="container-fluid">
        <div class="hero text-center">
            <h1>@lang('global/welcome.about')</h1>
        </div>
    </div>
    <about-page inline-template>
        <div class="container my-5">
            <div class="row">
                <div class="col-12 col-lg-8 ox-hidden">
                    <transition name="slide-fade">
                        <div v-if="active == 'barrioball'" key="barrioball">
                            <h5 class="about-header text-center text-lg-left">Barrio Ball</h5>
                            <p class="about-content">
                                Barrio-Ball is a tool for the pick-up football community. We aim to connect players who
                                want to play, with groups who are missing players.
                            </p>
                        </div>
                        <div v-if="active == 1" key="1">
                            <h5 class="about-header text-center text-lg-left">The Team</h5>
                            <p class="about-content">
                                Cras mattis eros ac purus blandit consectetur. Sed in lectus nulla. Nullam vehicula sem
                                sed justo laoreet, non dapibus elit faucibus
                            </p>
                        </div>
                        <div v-if="active == 2" key="2">
                            <h5 class="about-header text-center text-lg-left">Privacy</h5>
                            <p class="about-content">
                                We believe in complete privacy for all our users. We only save the necessary data to
                                function. We also avoid services and code that would track our users.
                            </p>
                        </div>
                        <div v-if="active == 3" key="3">
                            <h5 class="about-header text-center text-lg-left">Open Source</h5>
                            <p class="about-content">
                                All our code is open source and released under GPL-3.0 licence. We believe that open
                                source code provides opportunities to share and learn.
                            </p>
                        </div>
                        <div v-if="active == 4" key="4">
                            <h5 class="about-header text-center text-lg-left">Football</h5>
                            <div class="about-content">
                                <blockquote class="blockquote">
                                    <p class="mb-0">Football, bloody hell!</p>
                                    <footer class="blockquote-footer">Sir Alex Ferguson</footer>
                                </blockquote>
                                <p>
                                    No matter their level, no matter if they get paid for it, Every day millions of people
                                    experience that.
                                </p>
                            </div>
                        </div>
                    </transition>
                </div>
                <div class="col-12 col-lg-4 d-flex">
                    <div class="row about-choices align-self-center">
                        <div class="col-12 d-lg-none">
                            <button class="btn btn-block btn-primary" :class="{ active: active == 'barrioball' }"
                                    @click="active = 'barrioball'">Barrio Ball
                            </button>
                        </div>
                        <div class="col-3 col-lg-6 py-3 circle-container">
                            <div class="circle mx-auto" :class="{ active: active == 1 }" @click="active = 1">
                                <i class="fa fa-4x fa-users m-auto"></i>
                            </div>
                            <div class="mt-1 text-center about-title">
                                The Team
                            </div>
                        </div>
                        <div class="col-3 col-lg-6 py-3 circle-container">
                            <div class="circle mx-auto" :class="{ active: active == 2 }" @click="active = 2">
                                <i class="fa fa-4x fa-user-secret"></i>
                            </div>
                            <div class="mt-1 text-center about-title">
                                Privacy
                            </div>
                        </div>
                        <div class="col-3 col-lg-6 py-3 circle-container">
                            <div class="circle mx-auto" :class="{ active: active == 3 }" @click="active = 3">
                                <i class="fa fa-4x fa-folder-open m-auto"></i>
                            </div>
                            <div class="mt-1 text-center about-title">
                                Open Source
                            </div>
                        </div>
                        <div class="col-3 col-lg-6 py-3 circle-container">
                            <div class="circle mx-auto" :class="{ active: active == 4 }" @click="active = 4">
                                <i class="fa fa-4x fa-futbol-o m-auto"></i>
                            </div>
                            <div class="mt-1 text-center about-title">
                                Football
                            </div>
                        </div>
                        <button class="btn btn-logo smaller-hover d-none d-lg-block"
                                :class="{ active: active == 'barrioball' }"
                                @click="active = 'barrioball'"></button>
                    </div>
                </div>
            </div>
        </div>
    </about-page>
    <div class="footer py-2">
        <div class="container">
            @include('welcome.footer')
        </div>
    </div>
@endsection