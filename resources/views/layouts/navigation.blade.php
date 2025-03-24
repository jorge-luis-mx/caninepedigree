            <nav class="navbar is-white is-transparent box-shadow-y">

                <div id="step_four" class="navbar-brand is-flex is-align-items-center">
                    <div class="navbar-item icon-navbar-item menu-hamburger mr-2" id="menuHamburger">
                        <svg xmlns="http://www.w3.org/2000/svg" class="custom-bar"  viewBox="0 0 24 24"><path fill="currentColor" d="M20 11H4c-.6 0-1 .4-1 1s.4 1 1 1h16c.6 0 1-.4 1-1s-.4-1-1-1M4 8h16c.6 0 1-.4 1-1s-.4-1-1-1H4c-.6 0-1 .4-1 1s.4 1 1 1m16 8H4c-.6 0-1 .4-1 1s.4 1 1 1h16c.6 0 1-.4 1-1s-.4-1-1-1"/></svg>
                    </div>
                    <!-- <div class="navbar-item icon-navbar-item">
                        <svg xmlns="http://www.w3.org/2000/svg"  class="custom-gravity" viewBox="0 0 16 16"><path fill="currentColor" fill-rule="evenodd" d="M11.5 7a4.5 4.5 0 1 1-9 0a4.5 4.5 0 0 1 9 0m-.82 4.74a6 6 0 1 1 1.06-1.06l2.79 2.79a.75.75 0 1 1-1.06 1.06z" clip-rule="evenodd"/></svg>
                    </div> -->

                    
                    <a href="{{ route('dogs.index') }}" class="nav-link">
                        <div class="navbar-item"> {{ __('messages.nav.dogs') }}</div>  
                    </a>

                </div>

                <div class="navbar-menu">
                    <div class="navbar-end">

                        <div class="navbar-item has-dropdown is-hoverable">
                            <div class="background-color is-flex is-justify-content-center is-align-items-center pl-1 pr-4">
                                <svg xmlns="http://www.w3.org/2000/svg" width="128" height="128" viewBox="0 0 64 64"><path fill="#ed4c5c" d="M48 6.6C43.3 3.7 37.9 2 32 2v4.6z"/><path fill="#fff" d="M32 11.2h21.6C51.9 9.5 50 7.9 48 6.6H32z"/><path fill="#ed4c5c" d="M32 15.8h25.3c-1.1-1.7-2.3-3.2-3.6-4.6H32z"/><path fill="#fff" d="M32 20.4h27.7c-.7-1.6-1.5-3.2-2.4-4.6H32z"/><path fill="#ed4c5c" d="M32 25h29.2c-.4-1.6-.9-3.1-1.5-4.6H32z"/><path fill="#fff" d="M32 29.7h29.9c-.1-1.6-.4-3.1-.7-4.6H32z"/><path fill="#ed4c5c" d="M61.9 29.7H32V32H2c0 .8 0 1.5.1 2.3h59.8c.1-.8.1-1.5.1-2.3s0-1.6-.1-2.3"/><path fill="#fff" d="M2.8 38.9h58.4c.4-1.5.6-3 .7-4.6H2.1c.1 1.5.4 3.1.7 4.6"/><path fill="#ed4c5c" d="M4.3 43.5h55.4c.6-1.5 1.1-3 1.5-4.6H2.8c.4 1.6.9 3.1 1.5 4.6"/><path fill="#fff" d="M6.7 48.1h50.6c.9-1.5 1.7-3 2.4-4.6H4.3c.7 1.6 1.5 3.1 2.4 4.6"/><path fill="#ed4c5c" d="M10.3 52.7h43.4c1.3-1.4 2.6-3 3.6-4.6H6.7c1 1.7 2.3 3.2 3.6 4.6"/><path fill="#fff" d="M15.9 57.3h32.2c2.1-1.3 3.9-2.9 5.6-4.6H10.3c1.7 1.8 3.6 3.3 5.6 4.6"/><path fill="#ed4c5c" d="M32 62c5.9 0 11.4-1.7 16.1-4.7H15.9c4.7 3 10.2 4.7 16.1 4.7"/><path fill="#428bc1" d="M16 6.6c-2.1 1.3-4 2.9-5.7 4.6c-1.4 1.4-2.6 3-3.6 4.6c-.9 1.5-1.8 3-2.4 4.6c-.6 1.5-1.1 3-1.5 4.6c-.4 1.5-.6 3-.7 4.6c-.1.8-.1 1.6-.1 2.4h30V2c-5.9 0-11.3 1.7-16 4.6"/><path fill="#fff" d="m25 3l.5 1.5H27l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm4 6l.5 1.5H31l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H23l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm4 6l.5 1.5H27l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H19l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H11l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm20 6l.5 1.5H31l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H23l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H15l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm12 6l.5 1.5H27l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H19l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H11l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm2.8-14l1.2-.9l1.2.9l-.5-1.5l1.2-1h-1.5L13 9l-.5 1.5h-1.4l1.2.9zm-8 12l1.2-.9l1.2.9l-.5-1.5l1.2-1H5.5L5 21l-.5 1.5h-1c0 .1-.1.2-.1.3l.8.6z"/></svg>
                            </div>
                            <div class="navbar-dropdown is-boxed is-right mt-1">
                                <a href="{{ url('/lang/en') }}" class="navbar-item is-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-profile" width="128" height="128" viewBox="0 0 64 64"><path fill="#ed4c5c" d="M48 6.6C43.3 3.7 37.9 2 32 2v4.6z"/><path fill="#fff" d="M32 11.2h21.6C51.9 9.5 50 7.9 48 6.6H32z"/><path fill="#ed4c5c" d="M32 15.8h25.3c-1.1-1.7-2.3-3.2-3.6-4.6H32z"/><path fill="#fff" d="M32 20.4h27.7c-.7-1.6-1.5-3.2-2.4-4.6H32z"/><path fill="#ed4c5c" d="M32 25h29.2c-.4-1.6-.9-3.1-1.5-4.6H32z"/><path fill="#fff" d="M32 29.7h29.9c-.1-1.6-.4-3.1-.7-4.6H32z"/><path fill="#ed4c5c" d="M61.9 29.7H32V32H2c0 .8 0 1.5.1 2.3h59.8c.1-.8.1-1.5.1-2.3s0-1.6-.1-2.3"/><path fill="#fff" d="M2.8 38.9h58.4c.4-1.5.6-3 .7-4.6H2.1c.1 1.5.4 3.1.7 4.6"/><path fill="#ed4c5c" d="M4.3 43.5h55.4c.6-1.5 1.1-3 1.5-4.6H2.8c.4 1.6.9 3.1 1.5 4.6"/><path fill="#fff" d="M6.7 48.1h50.6c.9-1.5 1.7-3 2.4-4.6H4.3c.7 1.6 1.5 3.1 2.4 4.6"/><path fill="#ed4c5c" d="M10.3 52.7h43.4c1.3-1.4 2.6-3 3.6-4.6H6.7c1 1.7 2.3 3.2 3.6 4.6"/><path fill="#fff" d="M15.9 57.3h32.2c2.1-1.3 3.9-2.9 5.6-4.6H10.3c1.7 1.8 3.6 3.3 5.6 4.6"/><path fill="#ed4c5c" d="M32 62c5.9 0 11.4-1.7 16.1-4.7H15.9c4.7 3 10.2 4.7 16.1 4.7"/><path fill="#428bc1" d="M16 6.6c-2.1 1.3-4 2.9-5.7 4.6c-1.4 1.4-2.6 3-3.6 4.6c-.9 1.5-1.8 3-2.4 4.6c-.6 1.5-1.1 3-1.5 4.6c-.4 1.5-.6 3-.7 4.6c-.1.8-.1 1.6-.1 2.4h30V2c-5.9 0-11.3 1.7-16 4.6"/><path fill="#fff" d="m25 3l.5 1.5H27l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm4 6l.5 1.5H31l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H23l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm4 6l.5 1.5H27l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H19l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H11l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm20 6l.5 1.5H31l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H23l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H15l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm12 6l.5 1.5H27l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H19l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm-8 0l.5 1.5H11l-1.2 1l.4 1.5l-1.2-.9l-1.2.9l.4-1.5l-1.2-1h1.5zm2.8-14l1.2-.9l1.2.9l-.5-1.5l1.2-1h-1.5L13 9l-.5 1.5h-1.4l1.2.9zm-8 12l1.2-.9l1.2.9l-.5-1.5l1.2-1H5.5L5 21l-.5 1.5h-1c0 .1-.1.2-.1.3l.8.6z"/></svg> English
                                </a>
                                <a href="{{ url('/lang/es') }}" class="navbar-item is-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-profile" width="128" height="128" viewBox="0 0 64 64"><path fill="#ed4c5c" d="M62 32c0-13.1-8.3-24.2-20-28.3v56.6C53.7 56.2 62 45.1 62 32"/><path fill="#75a843" d="M2 32c0 13.1 8.4 24.2 20 28.3V3.7C10.4 7.8 2 18.9 2 32"/><path fill="#fff" d="M42 3.7C38.9 2.6 35.5 2 32 2s-6.9.6-10 1.7v56.6c3.1 1.1 6.5 1.7 10 1.7s6.9-.6 10-1.7z"/><path fill="#75a843" d="M40.7 31.7c-.1-.3-.2.1-.2.2c0-.3-.1-.5-.3-.8c-.3.3-.4.7-.4 1.1c-.1-.3-.2-.6-.5-.7c.2.3.1.5.1.8c0 .4.3.7.3 1.1s-.2.7-.5 1v-.3c-1 .4-.8.9-.8 1.8s-.5.7-1.1 1.2c.1-.2.1-.5 0-.7c0 .6-.8.6-.9 1.1c-.1.2 0 .6-.3.7c-.2.1-.5.3-.5.5c-.2-.1-.4-.1-.7-.1c.1-.1.2-.1.3-.2c-1-.5-1.3.6-2.2.7c-.3 0-1-.4-1 0c.9 0 1.3.8 2 .8c.6 0 1.1-.5 1.7-.5c-.1 0-.4-.2-.7-.2c.5-.3.8-.9 1.4-.9c1 0 1.5-.2 1.8-1.2c-.1 0-.2 0-.3.1c0-.4.4-.9.8-1.1c1-.2.9-1 1.5-1.7q-.3 0-.6.3c0-.6-.1-1.2.4-1.6c1-.5.9-.7.7-1.4m-17.4 0c-.2.8-.3 1 .5 1.5c.6.4.4 1 .4 1.6c-.2-.2-.3-.3-.6-.3c.6.6.5 1.5 1.5 1.7c.3.2.8.7.8 1.1c-.1 0-.2-.1-.3-.1c.3 1 .8 1.2 1.8 1.2c.6 0 .9.6 1.4.9c-.3 0-.6.1-.7.2c.6 0 1.1.6 1.7.5c.7 0 1.2-.8 2-.8c0-.4-.7 0-1 0c-.8-.2-1.1-1.2-2.2-.8c.1.1.2.2.3.2c-.2 0-.5-.1-.7.1c-.1-.2-.3-.4-.5-.5c-.2-.2-.2-.5-.3-.7c-.2-.5-.9-.6-.9-1.1c-.1.2-.1.5 0 .7c-.6-.5-1.1-.3-1.1-1.2c0-.8.2-1.4-.8-1.8v.3c-.3-.3-.5-.5-.5-1c0-.4.4-.7.3-1.1c0-.3-.1-.5.1-.8c-.3.1-.4.4-.5.7c0-.4-.1-.8-.4-1.1c-.1.2-.2.5-.3.8c.1-.2 0-.5 0-.2m2.3 5"/><path fill="#428bc1" d="M34.7 37.7c-.2 0-.3-.2-.4-.3c0-.2.8-.4.9-.4v-.2c-1.3.4-5.2.7-6-.7c-.4.6-1.1.2-1.8-.3c.1.2.5 1.2.5 1.1c.2.1 1.5.4 1.5.7l-.3.3c2.1.7 3.4.9 5.6-.2"/><path fill="#ed4c5c" d="M33.4 37.7c.1-.1.1-.2.1-.4l-.1-.3c.2-.2 0-.7-.3-.6c-.2.1-.1.1-.3 0c-.1 0-.1-.1-.2-.1h-1.8c-.2 0-.2-.1-.4-.1c-.2-.1-.3.1-.4.2c0 .2.1.2.1.3s-.1.2-.1.4s.1.3.2.4c-.4.3 0 .8.4.5c.1.2.4.2.7.2h1.1c.2 0 .6.1.7-.2c.3.5.7-.1.3-.3"/><path fill="#428bc1" d="M36.6 35.1c-.8-1-1.2.5-2.1.4c.2-1.2-1.6-.8-2.1-.5c.1-.1.2-.3.3-.5c-.4 0-.9.2-1.2-.1c-.6-.4-1.4-.6-1.9.1c-.4-.6-1.5-1.2-2.1-.6c0-.6-.6-1.5-1.3-1.4c-.8.1-.5 1.1-.1 1.5c.3.3.7.4 1.1.4c0 .2.1.3.2.4c.5.4 1.5.5 2 .1c0 1 2 1 2.5.5c-.2.5-.4 1.5.3 1.5c.6 0 .4-.6.9-.8c.4-.2.9-.3 1.3-.1c.7.4 2.9.3 2.2-.9"/><g fill="#75a843"><path d="M28.4 32.6c-.1-.2-.3-.4-.3-.4c-.4.1-.4-.2-.5-.5c-.1-.5-.8-.7-.8-1.1s.4-.8.1-1.2s-.8-.6-.8-.4c-.1.2.4.2.4.7c0 .6-.6 1.1.1 1.6c.4.6.4 1.7 1.1 1.7c.3 0 .6-.1.7-.4"/><path d="M29.9 32.1c-.2-.4 0-1-.4-1.3c-.5-.4-1.5.4-1.6-.6c0-.2 1.1-1.4 1.3-1.5c.3-.4.2-1.1-.1-1.3c-.4-.2-.5 0-.8.4c.1.1.5.3.5.4c-.3.7-1.2.8-1.4 1.5c-.2.5-.1 1.3.4 1.6c.3.2.5.2.8.2c.9-.1.4.1.5.3c0 .2.3.9.8.3m-3-4.9c0 .6.6.5 1 .4c0-.2 0-.4.1-.6c-.2.1-.5.1-.6 0c-.3-.2.7-.6.6-.6c0 0-1 .5-.6-.1c.1-.2.3-.4.4-.6s-.3 0-.3 0c-.1 0-.2 0-.3.1c-.6.5-.4.8-.3 1.4"/></g><path fill="#89664c" d="M37.5 27.2c-.5-.7-2.4-3-5.1-3.2c-.3 0-1.1.3-1.4.4c-.9.6 1 1.1 1.1 1.5c0 .2.1.3.1.5c-.3-.5-.6-.8-.7-1c-.5.2-1.3-.1-1.4-.6c-.1.2-.1.3-.3.5c0-.3-.1-.3-.2-.5c0 .3 0 .7-.3.8c.1-.2 0-.4-.1-.6c0 .3 0 .5-.2.7c.1-.2-.1-.3-.1-.5c-.1.8-1.2 1.1-.1 1.4c.4.1.6 0 1 .2c.1.1.3.3.3.2c0 .1-.4.5-.1.6c-.1.1-.4.7-.4.7c.3 0 .1.2-.1.4c-.3.5 0 1 .1 1.6c0-.2.3.1.3.4c0 .4.4.6.5.9c-.5-.5-.6 0 0 .3c-.5 0-.6.3-.1.5c-1.2 0-.2.5 0 .6c.8.2 1.4-.4 1.4-.5c0 0 1.6 1.6 1.7 1.6s.9-.4 1.1-.2c.2.3.4 0 .6.1c.2.2 1 0 1.2-.1c.1 0 1.1-.2.9-.4c-.5-.4-1.1-.8-1.6-1.2s-1-.7-1.4-1.1c-.2-.2-.2-.6-.3-.9c.9.9 1.8 1.4 1.7-.3c.6.5 1.8 3.3 1.9 3.3c.3 0 0-3 0-3.3c.4.4.4 3.7.5 3.7c.5.1 1.2-4.3-.5-6.5"/><path fill="#ffce31" d="M30.3 32.3c-.1 0-.6-.7-.6-.8c.3 1.2-.9.5-.6 0c-.4-.1-1.4.5-1 1.1c-.2.4 1.4.1 1.5-.1c.4.9 1.4-.1.7-.2m4.7 3c.5-.7-1.6-.9-1.4-1.7c-.3.2-.7.9-1.1.7c-.2-.1-1 .4-.6.7c0-.4.2-.1.5-.2c-.1.3-.4.6.1.8c-.2-.6.6-.3.5-.6c-.2-.2.7-.2.8-.2c.3 0 1.3.3.9.6c0 0 .2 0 .3-.1m-6.7-8.6c-.1.2-.5.5-.5.8c0 .2.5.7.7.4c-.6-.4.1-.7.4-.7c.1 0 .1.2.1.2c.1.1.9-.2.8-.4c.1-.4-1.2-.4-1.5-.3m0 0"/></svg> Español
                                </a>
                                <!-- <a href="#" class="navbar-item is-flex">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-profile" width="128" height="128" viewBox="0 0 64 64"><path fill="#428bc1" d="M1.9 32c0 13.1 8.4 24.2 20 28.3V3.7C10.3 7.8 1.9 18.9 1.9 32"/><path fill="#ed4c5c" d="M61.9 32c0-13.1-8.3-24.2-20-28.3v56.6c11.7-4.1 20-15.2 20-28.3"/><path fill="#fff" d="M21.9 60.3c3.1 1.1 6.5 1.7 10 1.7s6.9-.6 10-1.7V3.7C38.8 2.6 35.5 2 31.9 2s-6.9.6-10 1.7z"/></svg> Francais
                                </a> -->
                            </div>
                        </div>

                        <div id="step_five" class="navbar-item has-dropdown is-hoverable">

                            <div class="img-profile is-flex is-justify-content-center is-align-items-center">
                                <div class="circle-letter has-text-white  is-flex is-justify-content-center is-align-items-center">
                                    {{ ucfirst(substr(Auth::user()->pvr_auth_username, 0, 1)) }}
                                </div>
                            </div>

                            <div class="navbar-dropdown is-boxed is-right mt-1">

                                <a href="{{ route('dashboard') }}" class="navbar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 24 24"><path fill="none" stroke="#FBA53E" stroke-width="2" d="M4 5a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v5a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1zm10 0a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zM4 16a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1zm10-3a1 1 0 0 1 1-1h4a1 1 0 0 1 1 1v6a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1z"/></svg>
                                    <span>{{__('messages.nav.profile.dashboard')}}</span>
                                </a>
                               
                                <a href="{{ route('profile.edit') }}" class="navbar-item">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 24 24"><g fill="none" stroke="#FBA53E" stroke-width="1.5"><circle cx="12" cy="9" r="3"/><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" d="M17.97 20c-.16-2.892-1.045-5-5.97-5s-5.81 2.108-5.97 5"/></g></svg>
                                    <span>{{__('messages.nav.profile.profile')}}</span>
                                </a>
                                <a href="{{ route('logout') }}" class="navbar-item" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-size-icon" viewBox="0 0 24 24"><path fill="#FBA53E" d="m6.265 3.807l1.147 1.639a8 8 0 1 0 9.176 0l1.147-1.639A9.99 9.99 0 0 1 22 12c0 5.523-4.477 10-10 10S2 17.523 2 12a9.99 9.99 0 0 1 4.265-8.193M11 12V2h2v10z"/></svg>
                                    <span>{{__('messages.nav.profile.logOut')}}</span>
                                </a>
                                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                                    @csrf
                                </form>
                            </div>
                        </div>

                    </div>
                </div>
            </nav>