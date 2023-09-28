<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $user->id ? __('Edit User') : __('New User') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('users')">{{ __('List User') }}</x-link>
      </x-slot>

      <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6">
          <x-ui-input :label="__('Name')" wire:model.defer="user.name" />
        </div>

        <!-- Email -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input type="email" :label="__('Email')" wire:model.defer="user.email" />
        </div>

        <!-- Username -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Username')" wire:model.defer="user.username" />
        </div>

        @if ($user->id != auth()->id())
          <!-- Password -->
          <div class="col-span-6 sm:col-span-3">
            <x-inputs.password :label="__('Password')" wire:model.defer="password" />
          </div>
          <div class="col-span-6 sm:col-span-3">
            <x-inputs.password :label="__('Confirm Password')" wire:model.defer="password_confirmation" />
          </div>

          <!-- Roles -->
          <div class="col-span-6 flex flex-wrap gap-x-6 gap-y-4">
            <fieldset class="space-y-3">
              <legend class="">Role</legend>
              <div class="flex flex-wrap gap-x-8 gap-y-3">
                @foreach ($all_roles as $role)
                  <div class="">
                    <label for="role-{{ $role->id }}" class="inline-flex items-center">
                      <input type="radio" id="role-{{ $role->id }}" value="{{ $role->name }}" wire:model.defer="roles"
                        name="roles"
                        class="rounded-full border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600" />
                      <span class="ltr:ml-2 rtl:mr-2 text-sm {{ $errors->has('roles') ? 'text-red-600' : '' }}">{{ __($role->name) }}</span>
                    </label>
                  </div>
                @endforeach
              </div>
              <x-jet-input-error for="roles" />
            </fieldset>
          </div>

          <!-- Active -->
          <div class="col-span-6">
            <label for="active" class="inline-flex items-center">
              <x-checkbox id="active" wire:model.defer="user.active" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Active') }}</span>
            </label>
          </div>

          <!-- Banned -->
          <div class="col-span-6">
            <label for="banned" class="inline-flex items-center">
              <x-checkbox id="banned" wire:model.defer="user.banned" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Banned') }}</span>
            </label>
          </div>
        @endif
      </x-slot>

      <x-slot name="actions">
        <x-jet-action-message class="ltr:mr-3 rtl:ml-3" on="saved">
          {{ __('Saved.') }}
        </x-jet-action-message>

        <x-jet-button wire:loading.attr="disabled" wire:target="image">
          {{ __('Save') }}
        </x-jet-button>
      </x-slot>
    </x-jet-form-section>
  </div>
</div>
