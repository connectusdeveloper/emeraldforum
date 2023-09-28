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
        <!-- Editor -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Editor')" wire:model.defer="settings.editor">
            <option value=""></option>
            <option value="markdown">SimpleDME (markdown)</option>
            <option value="html">TinyMCE (html)</option>
          </x-ui-native-select>
        </div>

        <!-- Review New User Threads -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Review New User Threads')" wire:model.defer="settings.review_option">
            <option value=""></option>
            <option value="0">{{ __('Disable') }}</option>
            <option value="-1">{{ __('All') }}</option>
            <option value="5">{{ __('First five (5) threads & posts') }}</option>
            <option value="10">{{ __('First ten (10) threads & posts') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Banned Words -->
        <div class="col-span-6">
          <x-ui-input :label="__('Banned Words')" wire:model.defer="settings.banned_words" />
        </div>

        <!-- Allow to change vote -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="'Allow to change vote'" wire:model.defer="settings.voting">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Guest Reply -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Guest Reply')" wire:model.defer="settings.guest_reply">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Allow Delete -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Allow Delete')" wire:model.defer="settings.allow_delete">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="-1">{{ __('Yes') }}</option>
            <option value="10">{{ __('Allow within first 10 minutes') }}</option>
            <option value="30">{{ __('Allow within first 30 minutes') }}</option>
            <option value="60">{{ __('Allow within first hour') }}</option>
            <option value="1440">{{ __('Allow within a day') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Flag Option -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Flag Option')" wire:model.defer="settings.flag_option">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Flag Option -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Hide Flagged')" wire:model.defer="settings.hide_flagged">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- User Signature -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('User Signature')" wire:model.defer="settings.signature">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Registration -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Registration')" wire:model.defer="settings.registration">
            <option value=""></option>
            <option value="0">{{ __('Disable') }}</option>
            <option value="1">{{ __('Enable') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Notifications -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Notifications')" wire:model.defer="settings.notifications">
            <option value=""></option>
            <option value="0">{{ __('Disable') }}</option>
            <option value="super">{{ __('Super Admins only') }}</option>
            <option value="admin">{{ __('Super Admins & Admins') }}</option>
            <option value="moderator">{{ __('Super Admins, Admins & Moderators') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Tags Cloud -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Tags Cloud')" wire:model.defer="settings.tags_cloud">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Trending Threads -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Trending Threads')" wire:model.defer="settings.trending_threads">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- Who to follow -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Who to follow')" wire:model.defer="settings.top_members">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        <!-- File System -->
        <div x-data="{ disk: '{{ $settings['disk'] ?? 'local' }}' }" class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('File System')" wire:model.defer="settings.disk" x-model="disk">
            <option value=""></option>
            <option value="local">{{ __('Local') }}</option>
            <option value="s3">{{ __('AWS S3 and competible') }}</option>
          </x-ui-native-select>
          <div x-show="disk == 's3'" x-transition class="text-sm mt-1">
            {{ __('S3 competible storages like MinIO, DO Spaces & Wasabi can be used too.') }}<br />
            {{ __('Please set these in your `.env` file.') }}
            <pre class="text-xs font-bold whitespace-normal p-2 rounded">AWS_ACCESS_KEY_ID, AWS_SECRET_ACCESS_KEY, AWS_DEFAULT_REGION, AWS_BUCKET, AWS_URL, & AWS_ENDPOINT</pre>
          </div>
        </div>

        <!-- Upload Size -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Upload Size')" wire:model.defer="settings.upload_size" />
        </div>

        <!-- Allow to change vote -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="'Auto load videos'" wire:model.defer="settings.auto_load_video">
            <option value=""></option>
            <option value="0">{{ __('No') }}</option>
            <option value="1">{{ __('Yes') }}</option>
          </x-ui-native-select>
        </div>

        {{-- <!-- Replacement Word -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Replacement Words')" wire:model.defer="settings.replacement_word" />
        </div> --}}
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
