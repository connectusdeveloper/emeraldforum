<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $knowledge_base->id ?? null ? __('Edit Knowledge Base') : __('New Knowledge Base') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('knowledgebase')">{{ __('List Knowledge Bases') }}</x-link>
      </x-slot>

      <x-slot name="form">
        <!-- Title -->
        <div class="col-span-6">
          <x-ui-input :label="__('Title')" wire:model.defer="knowledge_base.title" />
        </div>

        <!-- Slug -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Slug')" wire:model.defer="knowledge_base.slug" />
        </div>

        <!-- Order -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Order Number')" type="number" wire:model.defer="knowledge_base.order_no" />
        </div>

        <!-- Category -->
        {{-- @if ($k_b_categories)
          <div class="col-span-6">
            <x-ui-native-select :label="__('Category')" wire:model.defer="knowledge_base.k_b_category_id">
              <x-options :categories="$k_b_categories" />
            </x-ui-native-select>
          </div>
        @endif --}}
        @if ($k_b_categories)
          <div class="col-span-6">
            <x-ui-native-select :label="__('Category')" wire:model.defer="knowledge_base.k_b_category_id">
              <option>{{ __('Select') }}</option>
              <x-options :categories="$k_b_categories" />
            </x-ui-native-select>
          </div>
        @endif

        <!-- Body -->
        <div class="col-span-6">
          <x-jet-label for="body" value="{{ __('Knowledge Base Body') }}" />
          <div class="@error('knowledge_base.body') has-error @enderror">
            <x-editor wire:model="knowledge_base.body" id="body" property="body" property_of="knowledge_base" :model="$knowledge_base->body" />
          </div>
          <x-jet-input-error for="knowledge_base.body" class="mt-1" />
        </div>

        <!-- Custom Fields -->
        <x-custom-fields model="KnowledgeBase" :custom_fields="$custom_fields" :extra_attributes="$knowledge_base->extra_attributes" />

        <div class="col-span-6 flex flex-wrap gap-x-6 gap-y-4">
          <div class="">
            <label for="active" class="inline-flex items-center">
              <x-checkbox id="active" wire:model.defer="knowledge_base.active" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Active') }}</span>
            </label>
          </div>
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
