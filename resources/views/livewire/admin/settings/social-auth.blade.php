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
        <div class="col-span-6">
          <h4 class="mb-3 font-bold">{{ __('Social Login') }}</h4>

          <div class="">
            <label for="facebook_login" class="inline-flex items-center">
              <x-checkbox id="facebook_login" wire:model.defer="settings.facebook_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Facebook Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Facebook Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Facebook Client Id')" wire:model.defer="settings.facebook_client_id" />
            </div>
            <!-- Facebook Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Facebook Client Secret')" wire:model.defer="settings.facebook_client_secret" />
            </div>
          </div>

          <div class="mt-6">
            <label for="github_login" class="inline-flex items-center">
              <x-checkbox id="github_login" wire:model.defer="settings.github_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('GitHub Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- GitHub Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('GitHub Client Id')" wire:model.defer="settings.github_client_id" />
            </div>
            <!-- GitHub Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('GitHub Client Secret')" wire:model.defer="settings.github_client_secret" />
            </div>
          </div>

          <div class="mt-6">
            <label for="google_login" class="inline-flex items-center">
              <x-checkbox id="google_login" wire:model.defer="settings.google_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Google Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Google Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Google Client Id')" wire:model.defer="settings.google_client_id" />
            </div>
            <!-- Google Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Google Client Secret')" wire:model.defer="settings.google_client_secret" />
            </div>
          </div>

          <div class="mt-6">
            <label for="twitter_login" class="inline-flex items-center">
              <x-checkbox id="twitter_login" wire:model.defer="settings.twitter_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Twitter Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Twitter Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Twitter Client Id')" wire:model.defer="settings.twitter_client_id" />
            </div>
            <!-- Twitter Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Twitter Client Secret')" wire:model.defer="settings.twitter_client_secret" />
            </div>
          </div>
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
