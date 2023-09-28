<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ __('Update Policies') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Update your application settings.') }}
          </div>
          @if (auth()->user()->can('settings'))
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
          @endif
        </div>
      </x-slot>

      <x-slot name="form">
        <!-- Privacy Policy -->
        <div class="col-span-6">
          <x-textarea :label="__('Privacy Policy')" wire:model.defer="form.policy" />
        </div>

        <!-- Terms of Service -->
        <div class="col-span-6">
          <x-textarea :label="__('Terms of Service')" wire:model.defer="form.terms" />
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
