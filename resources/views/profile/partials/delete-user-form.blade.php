<header class="mb-4">
    <h3 class="h5 text-danger">
        {{ __('Delete Account') }}
    </h3>

    <p class="text-muted small">
        {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Before deleting your account, please download any data or information that you wish to retain.') }}
    </p>
</header>

<div>
    <x-danger-button 
        data-bs-toggle="modal" 
        data-bs-target="#confirm-user-deletion"
    >{{ __('Delete Account') }}</x-danger-button>
</div>

<x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()">
    <div class="modal-header border-bottom border-danger">
        <h5 class="modal-title text-danger" id="confirm-user-deletionLabel">
            {{ __('Delete Account') }}
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>

    <form method="post" action="{{ route('profile.destroy') }}" class="modal-body">
        @csrf
        @method('delete')

        <h6 class="text-danger mb-3">
            {{ __('Are you sure you want to delete your account?') }}
        </h6>

        <p class="text-muted small mb-4">
            {{ __('Once your account is deleted, all of its resources and data will be permanently deleted. Please enter your password to confirm you would like to permanently delete your account.') }}
        </p>

        <div class="mb-3">
            <x-input-label for="password" value="{{ __('Password') }}" />
            <x-text-input
                id="password"
                name="password"
                type="password"
                placeholder="{{ __('Password') }}"
                autofocus
            />
            <x-input-error :messages="$errors->userDeletion->get('password')" />
        </div>

        <div class="modal-footer">
            <x-secondary-button type="button" data-bs-dismiss="modal">
                {{ __('Cancel') }}
            </x-secondary-button>

            <x-danger-button>
                {{ __('Delete Account') }}
            </x-danger-button>
        </div>
    </form>
</x-modal>
