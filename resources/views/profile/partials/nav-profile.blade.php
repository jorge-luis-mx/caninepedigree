<div class="columns is-multiline nav-profile-custom">

    <div class="column is-one-third">
        <a href="/profile">
            <div class="card {{ request()->is('profile') ? 'has-background-warning' : 'has-background-warning-light' }} ">
                <div class="card-content">
                    <div class="is-flex is-justify-content-center">
                        <strong class="is-block">Profile</strong>
                    </div>
                </div>
            </div>
        </a>
    </div>
    <div class="column is-one-third">
        <a href="/profile/authentication">
            <div class="card {{ request()->is('profile/authentication') ? 'has-background-warning' : 'has-background-warning-light' }}">
                <div class="card-content">
                    <div class="is-flex is-justify-content-center">
                        <strong class="is-block">Authentication</strong>
                    </div>
                </div>
            </div>
        </a>
    </div>

</div>