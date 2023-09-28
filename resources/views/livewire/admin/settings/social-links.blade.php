<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ __('Application Settings') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Update your application settings.') }}
          </div>
          <div class="mt-6 flex flex-wrap gap-4">
            @if (!request()->routeIs('settings.general'))
              <x-link type="button" :href="route('settings.general')">{{ __('General') }}</x-link>
            @endif
            @if (!request()->routeIs('settings.forum'))
              <x-link type="button" :href="route('settings.forum')">{{ __('Forum') }}</x-link>
            @endif
            @if (!request()->routeIs('settings.ad'))
              <x-link type="button" :href="route('settings.ad')">{{ __('Ads & Analytics') }}</x-link>
            @endif
            @if (!request()->routeIs('settings.social.auth'))
              <x-link type="button" :href="route('settings.social.auth')">{{ __('Social Auth') }}</x-link>
            @endif
            @if (!request()->routeIs('settings.social.links'))
              <x-link type="button" :href="route('settings.social.links')">{{ __('Social Links') }}</x-link>
            @endif
            @if (!request()->routeIs('policies.edit'))
              <x-link type="button" :href="route('policies.edit')">{{ __('Policies') }}</x-link>
            @endif
          </div>
        </div>
      </x-slot>

      <x-slot name="form">
        <!-- Facebook Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Facebook Link')" wire:model.defer="settings.facebook_link" />
        </div>

        <!-- Instagram Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Instagram Link')" wire:model.defer="settings.instagram_link" />
        </div>

        <!-- Twitter Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Twitter Link')" wire:model.defer="settings.twitter_link" />
        </div>

        <!-- LinkedIn Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('LinkedIn Link')" wire:model.defer="settings.linkedin_link" />
        </div>

        <!-- GitHub Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('GitHub Link')" wire:model.defer="settings.github_link" />
        </div>

        <!-- Dribbble Link -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Dribbble Link')" wire:model.defer="settings.dribbble_link" />
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
