
        <aside class="px-1">
            <div class="side-bar">

                <div class="box-logo">
                    <div class="container-box-logo">
                        <!-- <img id="logo-normal" class="" src="{{ asset('assets/img/cropped-airport-transportation-1.webp') }}" alt="logo">
                        <img id="logo-reduced" class="is-hidden" src="{{ asset('assets/img/logo.webp') }}" alt="logo">        -->
                        <img id="logo-normal" class="" src="{{ asset('assets/img/logo-canine.webp') }}" alt="logo">
                        <img id="logo-reduced" class="is-hidden" src="{{ asset('assets/img/logo-canine.webp') }}" alt="logo">  
                       
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
                    <ul class="menu-list is-hidden">
                        <li class="nav-drop">
                            <a href="/dashboard" class="nav-link is-flex is-justify-content-space-between">
                                <div class="is-flex is-justify-content-start is-align-items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 32 32"><path fill="#FBA53E"  d="M16 6C9.383 6 4 11.383 4 18a11.93 11.93 0 0 0 2.75 7.625l.281.375H24.97l.281-.375A11.93 11.93 0 0 0 28 18c0-6.617-5.383-12-12-12m0 2c5.535 0 10 4.465 10 10c0 2.266-.793 4.324-2.063 6H8.063C6.793 22.324 6 20.266 6 18c0-5.535 4.465-10 10-10m0 1c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1m-4 1.063c-.55 0-1 .449-1 1s.45 1 1 1s1-.45 1-1c0-.551-.45-1-1-1m8 0c-.55 0-1 .449-1 1s.45 1 1 1s1-.45 1-1c0-.551-.45-1-1-1M9.062 13c-.55 0-1 .45-1 1s.45 1 1 1c.551 0 1-.45 1-1s-.449-1-1-1m13.594.031L17 16.281A2 2 0 0 0 16 16a1.999 1.999 0 1 0 0 4a2 2 0 0 0 2-1.969V18l5.656-3.219zM8 17c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1m16 0c-.55 0-1 .45-1 1s.45 1 1 1s1-.45 1-1s-.45-1-1-1M9.062 21c-.55 0-1 .45-1 1s.45 1 1 1c.551 0 1-.45 1-1s-.449-1-1-1m13.876 0c-.551 0-1 .45-1 1s.449 1 1 1s1-.45 1-1s-.45-1-1-1"/></svg> 
                                    <span>{{ __('messages.side.dashboard') }}</span>
                                </div>
                            </a>
                        </li>
                    </ul>

                    

                        <ul class="menu-list is-hidden">
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
                                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 512 512"><path fill="#eab308" d="m231.6 16.18l16.7 120.02l73.8 20.5c37.3-11.2 78.5-18.2 102.3-43.6c9.7-10.3 17.2-24.78 9.1-37.92l-75.3 2.22l-14.6-31.79h-74.7c-7.7-11.71-22.8-20.46-37.3-29.43m5.7 145.22c-46.9 19.8-110.1 146.3-111.8 276.5c-34.02-58.1-24.9-122.6-2.9-202.6C55.31 287 4.732 448.4 133.1 486.9H346s-6.3-21.5-14.1-28.9c-12.7-12-48.2-20.2-48.2-20.2c27.8-39.2 33.5-71.7 38.6-103.9c4.5 59.8 40.7 126.8 57.4 153h76.5s4.6-15.9.2-21.5c-10.9-13.8-51.3-11.9-51.3-11.9c-31.1-107.2-46.3-260.2-90-273.2c-21.7-6.5-54.3-14.1-77.8-18.9"/></svg>
                                        <span>{{ __('messages.side.dogs') }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <ul class="menu-list">
                            <li class="nav-drop">
                                <a href="{{ route('pedigree.index') }}" class="nav-link is-flex is-justify-content-space-between">
                                    <div class="is-flex is-justify-content-start is-align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 24 24"><path fill="#eab308" d="M15.5 21.5v-2.308L8.116 15.5H3.5v-5h4.03l2.97-3.408V2.5h5v5h-4.03L8.5 10.908v3.65l7 3.5V16.5h5v5z"/></svg>
                                        <span>{{ __('messages.side.pedigree') }}</span>
                                    </div>
                                </a>
                            </li>
                        </ul>
                        
                        <ul class="menu-list ">
                            <li class="nav-drop">
                                <a href="{{ route('puppies.create') }}" class="nav-link is-flex is-justify-content-space-between">
                                    <div class="is-flex is-justify-content-start is-align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 512 512"><path fill="#eab308" d="m231.6 16.18l16.7 120.02l73.8 20.5c37.3-11.2 78.5-18.2 102.3-43.6c9.7-10.3 17.2-24.78 9.1-37.92l-75.3 2.22l-14.6-31.79h-74.7c-7.7-11.71-22.8-20.46-37.3-29.43m5.7 145.22c-46.9 19.8-110.1 146.3-111.8 276.5c-34.02-58.1-24.9-122.6-2.9-202.6C55.31 287 4.732 448.4 133.1 486.9H346s-6.3-21.5-14.1-28.9c-12.7-12-48.2-20.2-48.2-20.2c27.8-39.2 33.5-71.7 38.6-103.9c4.5 59.8 40.7 126.8 57.4 153h76.5s4.6-15.9.2-21.5c-10.9-13.8-51.3-11.9-51.3-11.9c-31.1-107.2-46.3-260.2-90-273.2c-21.7-6.5-54.3-14.1-77.8-18.9"/></svg>
                                        <span>Puppies</span>
                                    </div>
                                </a>
                            </li>
                        </ul>

                        <ul class="menu-list ">
                            <li class="nav-drop">
                                <a class="nav-link is-flex is-justify-content-space-between">
                                    <div class="is-flex is-justify-content-start is-align-items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 48 48"><path fill="none" stroke="#eab308" stroke-linecap="round" stroke-linejoin="round" stroke-width="4" d="m5 10l3 3l6-6M5 24l3 3l6-6M5 38l3 3l6-6m7-11h22M21 38h22M21 10h22"/></svg>
                                        <span>Breeding</span>
                                    </div>
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-chevron" viewBox="0 0 32 32"><path fill="currentColor" d="m16 4l7 7l-1.4 1.4L16 6.8l-5.6 5.6L9 11z"/></svg>
                                </a>
                                <ul class="submenu">
                                    <li>
                                        <a href="{{ route('breeding.create') }}" class="is-flex is-flex is-align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon-list" viewBox="0 0 256 256"><path fill="currentColor" d="M128 20a108 108 0 1 0 108 108A108.12 108.12 0 0 0 128 20m0 192a84 84 0 1 1 84-84a84.09 84.09 0 0 1-84 84"/></svg> 
                                            <span>New Request</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('breeding.listSent') }}" class="is-flex is-flex is-align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon-list" viewBox="0 0 256 256"><path fill="currentColor" d="M128 20a108 108 0 1 0 108 108A108.12 108.12 0 0 0 128 20m0 192a84 84 0 1 1 84-84a84.09 84.09 0 0 1-84 84"/></svg> 
                                            <span>Sent Requests</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{ route('breeding.index') }}" class="is-flex is-flex is-align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon-list" viewBox="0 0 256 256"><path fill="currentColor" d="M128 20a108 108 0 1 0 108 108A108.12 108.12 0 0 0 128 20m0 192a84 84 0 1 1 84-84a84.09 84.09 0 0 1-84 84"/></svg> 
                                            <span>Manage Requests</span>
                                        </a>
                                    </li>

                                    <li>
                                        <a href="{{ route('breeding.listCompleted') }}" class="is-flex is-flex is-align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon-list" viewBox="0 0 256 256"><path fill="currentColor" d="M128 20a108 108 0 1 0 108 108A108.12 108.12 0 0 0 128 20m0 192a84 84 0 1 1 84-84a84.09 84.09 0 0 1-84 84"/></svg> 
                                            <span>Confirmed Requests</span>
                                        </a>
                                    </li>
                                    <li class="is-hidden">
                                        <a href="#" class="is-flex is-flex is-align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon-list" viewBox="0 0 256 256"><path fill="currentColor" d="M128 20a108 108 0 1 0 108 108A108.12 108.12 0 0 0 128 20m0 192a84 84 0 1 1 84-84a84.09 84.09 0 0 1-84 84"/></svg> 
                                            <span>Pending Requests</span>
                                        </a>
                                    </li>


                                    <li class="is-hidden">
                                        <a href="" class="is-flex is-flex is-align-items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon-list" viewBox="0 0 256 256"><path fill="currentColor" d="M128 20a108 108 0 1 0 108 108A108.12 108.12 0 0 0 128 20m0 192a84 84 0 1 1 84-84a84.09 84.09 0 0 1-84 84"/></svg> 
                                            <span>Received Requests</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    

                </div>
            </div>

        </aside>
        <div id="overlay"></div>