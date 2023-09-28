<div>
  <div class="sm:max-w-md mx-auto">
    <x-errors />
  </div>

  <x-jet-authentication-card>
    <div class="mb-4">
      {{ __('Please fill the form to join us.') }}
    </div>

    <form wire:submit.prevent="join" autocomplete="off">
      @csrf

      <div>
        <x-ui-input :label="__('Invitation Code')" wire:model.defer="form.code" />
      </div>

      <div class="mt-4">
        <x-ui-input :label="__('Full Name')" wire:model.defer="form.name" autofocus />
      </div>

      <div class="mt-4">
        <x-ui-input :label="__('Email Address')" wire:model.defer="form.email" name="form.email" type="email" />
      </div>

      <div class="mt-4">
        <x-ui-input :label="__('Username')" wire:model.defer="form.username" name="form.username" />
      </div>

      <div class="mt-4">
        <x-inputs.password :label="__('Password')" wire:model.defer="form.password" />
      </div>

      <div class="mt-4">
        <x-inputs.password :label="__('Confirm Password')" wire:model.defer="form.password_confirmation" />
      </div>

      @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
          <x-jet-label for="terms">
            <div class="inline-flex items-center">
              <x-checkbox name="terms" id="terms" wire:model.defer="form.terms" />

              <div class="ltr:ml-2 rtl:mr-2">
                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                    'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '" class="link">' . __('Terms of Service') . '</a>',
                    'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '" class="link">' . __('Privacy Policy') . '</a>',
                ]) !!}
              </div>
            </div>
          </x-jet-label>
        </div>
      @endif

      <div class="flex items-center justify-end mt-4">
        <x-jet-button class="ltr:ml-4 rtl:mr-4">
          {{ __('Join') }}
        </x-jet-button>
      </div>
    </form>
  </x-jet-authentication-card>
</div>
