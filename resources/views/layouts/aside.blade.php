
        <aside class="px-1">
            <div class="side-bar">

                <div class="box-logo">
                    <div class="container-box-logo">
                        <!-- <img id="logo-normal" class="" src="{{ asset('assets/img/cropped-airport-transportation-1.webp') }}" alt="logo">
                        <img id="logo-reduced" class="is-hidden" src="{{ asset('assets/img/logo.webp') }}" alt="logo">        -->
                        logo
                    </div>
                </div>

                <div class="menu pt-1">
                    <!-- <div class="menu-label-dasboard">
                        <a href="/dashboard">
                            <button>
                                <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 32 32"><path fill="currentColor" d="M16 6C9.383 6 4 11.383 4 18a11.93 11.93 0 0 0 2.75 7.625l.281.375H24.97l.281-.375A11.93 11.93 0 0 0 28 18c0-6.617-5.383-12-12-12m0 2c5.535 0 10 4.465 10 10c0 2.266-.793 4.324-2.063 6H8.063C6.793 22.324 6 20.266 6 18c0-5.535 4.465-10 10-10m0 1c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1m-4 1.063c-.55 0-1 .449-1 1s.45 1 1 1s1-.45 1-1c0-.551-.45-1-1-1m8 0c-.55 0-1 .449-1 1s.45 1 1 1s1-.45 1-1c0-.551-.45-1-1-1M9.062 13c-.55 0-1 .45-1 1s.45 1 1 1c.551 0 1-.45 1-1s-.449-1-1-1m13.594.031L17 16.281A2 2 0 0 0 16 16a1.999 1.999 0 1 0 0 4a2 2 0 0 0 2-1.969V18l5.656-3.219zM8 17c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1m16 0c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1M9.062 21c-.55 0-1 .45-1 1s.45 1 1 1c.551 0 1-.45 1-1s-.449-1-1-1m13.876 0c-.551 0-1 .45-1 1s.449 1 1 1s1-.45 1-1s-.45-1-1-1"/></svg> 
                                <span>Dashboard</span>
                            </button>
                        </a>
                    </div> -->
                    <ul class="menu-list">
                        <li class="nav-drop">
                            <a href="/dashboard" class="nav-link is-flex is-justify-content-space-between">
                                <div class="is-flex is-justify-content-start is-align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 32 32"><path fill="#FBA53E"  d="M16 6C9.383 6 4 11.383 4 18a11.93 11.93 0 0 0 2.75 7.625l.281.375H24.97l.281-.375A11.93 11.93 0 0 0 28 18c0-6.617-5.383-12-12-12m0 2c5.535 0 10 4.465 10 10c0 2.266-.793 4.324-2.063 6H8.063C6.793 22.324 6 20.266 6 18c0-5.535 4.465-10 10-10m0 1c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1m-4 1.063c-.55 0-1 .449-1 1s.45 1 1 1s1-.45 1-1c0-.551-.45-1-1-1m8 0c-.55 0-1 .449-1 1s.45 1 1 1s1-.45 1-1c0-.551-.45-1-1-1M9.062 13c-.55 0-1 .45-1 1s.45 1 1 1c.551 0 1-.45 1-1s-.449-1-1-1m13.594.031L17 16.281A2 2 0 0 0 16 16a1.999 1.999 0 1 0 0 4a2 2 0 0 0 2-1.969V18l5.656-3.219zM8 17c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1m16 0c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1M9.062 21c-.55 0-1 .45-1 1s.45 1 1 1c.551 0 1-.45 1-1s-.449-1-1-1m13.876 0c-.551 0-1 .45-1 1s.449 1 1 1s1-.45 1-1s-.45-1-1-1"/></svg> 
                                    <span>{{ __('messages.side.dashboard') }}</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                    <div id="step_three">

                        <ul class="menu-list">
                            <li class="nav-drop">
                                <a href="{{ route('profile.edit') }}" class="nav-link is-flex is-justify-content-space-between">
                                    <div class="is-flex is-justify-content-start is-align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 24 24"><g fill="none" stroke="#FBA53E" stroke-width="1.5"><circle cx="12" cy="9" r="3"/><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M17.97 20c-.16-2.892-1.045-5-5.97-5s-5.81 2.108-5.97 5"/></g></svg>
                                        <span>{{ __('messages.side.profile') }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <ul class="menu-list">
                            <li class="nav-drop">
                                <a href="{{ route('dogs.index') }}" class="nav-link is-flex is-justify-content-space-between">
                                    <div class="is-flex is-justify-content-start is-align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 48 48"><path fill="none" stroke="#eab308" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="m5 10l3 3l6-6M5 24l3 3l6-6M5 38l3 3l6-6m7-11h22M21 38h22M21 10h22"/></svg>
                                        <span>{{ __('messages.side.dogs') }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                    </div>

                </div>
            </div>

        </aside>