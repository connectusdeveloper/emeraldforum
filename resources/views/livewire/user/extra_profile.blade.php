<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ __('Extra Profile Information') }}
      </x-slot>

      <x-slot name="description">
        {{ __('Update your profile information.') }}
      </x-slot>

      <x-slot name="form">

        <!-- Name -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Display Name')" wire:model.defer="meta.display_name" />
        </div>

        <!-- Name -->
        <div class="col-span-6 sm:col-span-3">
          <x-datetime-picker :label="__('Date of Birth')" without-time="true" wire:model.defer="dob" />
        </div>

        <!-- BIO -->
        <div class="col-span-6">
          <x-textarea :label="__('Bio')" wire:model.defer="meta.bio" />
        </div>

        @if ($settings['signature'] ?? null)
          <!-- Signature -->
          <div class="col-span-6">
            <x-textarea :label="__('Signature')" wire:model.defer="meta.signature" />
          </div>
        @endif

        <!-- Facebook Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Facebook Link')" wire:model.defer="meta.facebook_link" />
        </div>

        <!-- Instagram Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Instagram Link')" wire:model.defer="meta.instagram_link" />
        </div>

        <!-- Twitter Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Twitter Link')" wire:model.defer="meta.twitter_link" />
        </div>

        <!-- LinkedIn Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('LinkedIn Link')" wire:model.defer="meta.linkedin_link" />
        </div>

        <!-- GitHub Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('GitHub Link')" wire:model.defer="meta.github_link" />
        </div>

        <!-- Dribbble Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Dribbble Link')" wire:model.defer="meta.dribbble_link" />
        </div>

        <!-- Custom Fields -->
        <x-custom-fields model="User" :custom_fields="$custom_fields" :extra_attributes="$user->extra_attributes" />

        <!-- Disable messages -->
        <div class="col-span-6">
          <label for="disable_messages" class="inline-flex items-center">
            <x-checkbox id="disable_messages" wire:model.defer="meta.disable_messages" />
            <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Disable messages') }}</span>
          </label>
        </div>

      </x-slot>

      <x-slot name="actions">
        <x-jet-action-message class="ltr:mr-3 rtl:ml-3" on="saved">
          {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="logo">
          {{ __('Save') }}
        </x-jet-button>
      </x-slot>
    </x-jet-form-section>
  </div>
</div>
