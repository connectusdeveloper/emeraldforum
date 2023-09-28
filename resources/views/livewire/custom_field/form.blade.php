<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $custom_field->id ? __('Edit Custom Field') : __('New Custom Field') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('custom_fields')">{{ __('List Custom Field') }}</x-link>
      </x-slot>

      <x-slot name="form">
        <!-- Name -->
        <div class="col-span-6">
          <x-ui-input :label="__('Name')" wire:model.defer="custom_field.name" />
        </div>

        <!-- Models -->
        <div class="col-span-6">
          <x-ui-select :label="__('Models')" :options="['Page', 'Thread', 'Reply', 'Faq', 'KnowledgeBase', 'User']" wire:model.defer="custom_field.models" :multiselect="true">
          </x-ui-select>
        </div>

        <div class="col-span-6 grid grid-cols-6 gap-6" x-data="{ field_type: 'Text' }">
          <!-- Order -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-input :label="__('Order Number')" wire:model.defer="custom_field.order_no" />
          </div>

          <!-- Type -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Type')" :options="['Text', 'Date', 'Select', 'Secret', 'Checkbox', 'Radio', 'Textarea']" wire:model.defer="custom_field.type" x-model="field_type">
            </x-ui-native-select>
          </div>

          <!-- Order -->
          <div class="col-span-6" x-show="field_type == 'Select' || field_type == 'Checkbox' || field_type == 'Radio'"
            style="display: none">
            <x-ui-input :label="__('Options') . ' (' . __('separated by comma') . ')'" wire:model.defer="custom_field.options" />
          </div>
        </div>

        <!-- Description -->
        <div class="col-span-6">
          <x-textarea :label="__('Description')" wire:model.defer="custom_field.description" />
        </div>

        <div class="col-span-6">
          <label for="active" class="inline-flex items-center">
            <x-checkbox id="active" wire:model.defer="custom_field.active" />
            <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Active') }}</span>
          </label>
        </div>

        <div class="col-span-6">
          <label for="required" class="inline-flex items-center">
            <x-checkbox id="required" wire:model.defer="custom_field.required" />
            <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Required') }}</span>
          </label>
        </div>

        {{-- TODO: Private --}}
        {{-- <div class="col-span-6">
          <label for="show" class="inline-flex items-center">
            <x-checkbox id="show" wire:model.defer="custom_field.show" />
            <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Show publicly') }}</span>
          </label>
        </div> --}}
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
