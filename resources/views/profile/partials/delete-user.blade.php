<div class="card" style="box-shadow: none;">
    <div class="card-content">
        <h2 class="title is-4 mb-4 pb-3">Delete Account </h2>
        <p>Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account,
            please download any data or information that you wish to retain.
        </p>
        <form id="form-delete-account" action="{{ route('profileAuthentication.destroy') }}" method="post">
        @csrf
        @method('delete')
            <div class="field mt-4">
                <div class="control">
                    <button class="button has-background-danger has-text-white" id="btn-delete-account">
                    <svg xmlns="http://www.w3.org/2000/svg" class="custom-icon-action" viewBox="0 0 24 24">
                                        <path fill="currentColor" d="M7 4a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v2h4a1 1 0 1 1 0 2h-1.069l-.867 12.142A2 2 0 0 1 17.069 22H6.93a2 2 0 0 1-1.995-1.858L4.07 8H3a1 1 0 0 1 0-2h4zm2 2h6V4H9zM6.074 8l.857 12H17.07l.857-12zM10 10a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1m4 0a1 1 0 0 1 1 1v6a1 1 0 1 1-2 0v-6a1 1 0 0 1 1-1" />
                                    </svg>
                        <span class="ml-1">{{ __('Delete Account') }}</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>