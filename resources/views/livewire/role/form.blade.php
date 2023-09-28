<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $role->id ? __('Edit Role') : __('New Role') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('roles')">{{ __('List Role') }}</x-link>
      </x-slot>

      <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6">
          <x-ui-input :label="__('Name')" wire:model.defer="role.name" />
        </div>

        <div class="col-span-6 mt-2 grid grid-cols-1 gap-8">
          <fieldset class="space-y-3">
            <legend class="">{{ __('Threads') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <div class="relative flex">
                <div class="absolute inset-0 z-20 bg-white/50 dark:bg-gray-900/50"></div>
                <label for="read-threads" class="inline-flex items-center self-stretch">
                  <x-checkbox id="read-threads" wire:model.defer="permissions.read-threads" />
                  <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
                </label>
              </div>
              <label for="create-threads" class="inline-flex items-center">
                <x-checkbox id="create-threads" wire:model.defer="permissions.create-threads" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-threads" class="inline-flex items-center">
                <x-checkbox id="update-threads" wire:model.defer="permissions.update-threads" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-threads" class="inline-flex items-center">
                <x-checkbox id="delete-threads" wire:model.defer="permissions.delete-threads" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Replies') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-replies" class="inline-flex items-center">
                <x-checkbox id="read-replies" wire:model.defer="permissions.read-replies" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-replies" class="inline-flex items-center">
                <x-checkbox id="create-replies" wire:model.defer="permissions.create-replies" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-replies" class="inline-flex items-center">
                <x-checkbox id="update-replies" wire:model.defer="permissions.update-replies" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-replies" class="inline-flex items-center">
                <x-checkbox id="delete-replies" wire:model.defer="permissions.delete-replies" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Knowledgebase') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-knowledgebase" class="inline-flex items-center">
                <x-checkbox id="read-knowledgebase" wire:model.defer="permissions.read-knowledgebase" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-knowledgebase" class="inline-flex items-center">
                <x-checkbox id="create-knowledgebase" wire:model.defer="permissions.create-knowledgebase" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-knowledgebase" class="inline-flex items-center">
                <x-checkbox id="update-knowledgebase" wire:model.defer="permissions.update-knowledgebase" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-knowledgebase" class="inline-flex items-center">
                <x-checkbox id="delete-knowledgebase" wire:model.defer="permissions.delete-knowledgebase" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('FAQs') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-faqs" class="inline-flex items-center">
                <x-checkbox id="read-faqs" wire:model.defer="permissions.read-faqs" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-faqs" class="inline-flex items-center">
                <x-checkbox id="create-faqs" wire:model.defer="permissions.create-faqs" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-faqs" class="inline-flex items-center">
                <x-checkbox id="update-faqs" wire:model.defer="permissions.update-faqs" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-faqs" class="inline-flex items-center">
                <x-checkbox id="delete-faqs" wire:model.defer="permissions.delete-faqs" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Pages') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-articles" class="inline-flex items-center">
                <x-checkbox id="read-articles" wire:model.defer="permissions.read-articles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-articles" class="inline-flex items-center">
                <x-checkbox id="create-articles" wire:model.defer="permissions.create-articles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-articles" class="inline-flex items-center">
                <x-checkbox id="update-articles" wire:model.defer="permissions.update-articles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-articles" class="inline-flex items-center">
                <x-checkbox id="delete-articles" wire:model.defer="permissions.delete-articles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Badges') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-badges" class="inline-flex items-center">
                <x-checkbox id="read-badges" wire:model.defer="permissions.read-badges" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-badges" class="inline-flex items-center">
                <x-checkbox id="create-badges" wire:model.defer="permissions.create-badges" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-badges" class="inline-flex items-center">
                <x-checkbox id="update-badges" wire:model.defer="permissions.update-badges" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-badges" class="inline-flex items-center">
                <x-checkbox id="delete-badges" wire:model.defer="permissions.delete-badges" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Categories') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-categories" class="inline-flex items-center">
                <x-checkbox id="read-categories" wire:model.defer="permissions.read-categories" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-categories" class="inline-flex items-center">
                <x-checkbox id="create-categories" wire:model.defer="permissions.create-categories" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-categories" class="inline-flex items-center">
                <x-checkbox id="update-categories" wire:model.defer="permissions.update-categories" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-categories" class="inline-flex items-center">
                <x-checkbox id="delete-categories" wire:model.defer="permissions.delete-categories" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Custom Fields') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-custom-fields" class="inline-flex items-center">
                <x-checkbox id="read-custom-fields" wire:model.defer="permissions.read-custom-fields" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-custom-fields" class="inline-flex items-center">
                <x-checkbox id="create-custom-fields" wire:model.defer="permissions.create-custom-fields" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-custom-fields" class="inline-flex items-center">
                <x-checkbox id="update-custom-fields" wire:model.defer="permissions.update-custom-fields" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-custom-fields" class="inline-flex items-center">
                <x-checkbox id="delete-custom-fields" wire:model.defer="permissions.delete-custom-fields" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Users') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-users" class="inline-flex items-center">
                <x-checkbox id="read-users" wire:model.defer="permissions.read-users" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-users" class="inline-flex items-center">
                <x-checkbox id="create-users" wire:model.defer="permissions.create-users" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-users" class="inline-flex items-center">
                <x-checkbox id="update-users" wire:model.defer="permissions.update-users" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-users" class="inline-flex items-center">
                <x-checkbox id="delete-users" wire:model.defer="permissions.delete-users" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Roles') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="read-roles" class="inline-flex items-center">
                <x-checkbox id="read-roles" wire:model.defer="permissions.read-roles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Read') }}</span>
              </label>
              <label for="create-roles" class="inline-flex items-center">
                <x-checkbox id="create-roles" wire:model.defer="permissions.create-roles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Create') }}</span>
              </label>
              <label for="update-roles" class="inline-flex items-center">
                <x-checkbox id="update-roles" wire:model.defer="permissions.update-roles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Update') }}</span>
              </label>
              <label for="delete-roles" class="inline-flex items-center">
                <x-checkbox id="delete-roles" wire:model.defer="permissions.delete-roles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Delete') }}</span>
              </label>
            </div>
          </fieldset>
          <fieldset class="space-y-3">
            <legend class="">{{ __('Administration') }}</legend>
            <div class="flex flex-wrap gap-x-6 gap-y-3">
              <label for="settings" class="inline-flex items-center">
                <x-checkbox id="settings" wire:model.defer="permissions.settings" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Settings') }}</span>
              </label>
              <label for="invitations" class="inline-flex items-center">
                <x-checkbox id="invitations" wire:model.defer="permissions.invitations" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Invitations') }}</span>
              </label>
              <label for="approve" class="inline-flex items-center">
                <x-checkbox id="approve" wire:model.defer="permissions.approve-threads" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Approve threads') }}</span>
              </label>
              <label for="review" class="inline-flex items-center">
                <x-checkbox id="review" wire:model.defer="permissions.review" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Review threads & replies') }}</span>
              </label>
              {{-- TODO: Private --}}
              {{-- <label for="review" class="inline-flex items-center">
                <x-checkbox id="review" wire:model.defer="permissions.private" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Show private threas & replies') }}</span>
              </label> --}}
              <label for="assign" class="inline-flex items-center">
                <x-checkbox id="assign" wire:model.defer="permissions.assign-badges" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Assign badges') }}</span>
              </label>
              <label for="meta-tags" class="inline-flex items-center">
                <x-checkbox id="meta-tags" wire:model.defer="permissions.meta-tags" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Allow SEO fields (slug, description, noindex & nofollow)') }}</span>
              </label>
              <label for="group-permissions" class="inline-flex items-center">
                <x-checkbox id="group-permissions" wire:model.defer="permissions.group-permissions" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Allow to select group for view (articles & threads) permissions') }}</span>
              </label>
            </div>
          </fieldset>
        </div>
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
