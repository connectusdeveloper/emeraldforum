<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $faq->id ?? null ? __('Edit FAQ') : __('New FAQ') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('faqs')">{{ __('List FAQs') }}</x-link>
      </x-slot>

      <x-slot name="form">
        <!-- Title -->
        <div class="col-span-6">
          <x-ui-input :label="__('Question')" wire:model.defer="faq.question" />
        </div>

        <!-- Slug -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Slug')" wire:model.defer="faq.slug" />
        </div>

        <!-- Order -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Order Number')" type="number" wire:model.defer="faq.order_no" />
        </div>

        <!-- Category -->
        @if ($faq_categories)
          <div class="col-span-6">
            <x-ui-native-select :label="__('Category')" wire:model.defer="faq.faq_category_id">
              <option>{{ __('Select') }}</option>
              <x-options :categories="$faq_categories" />
            </x-ui-native-select>
          </div>
        @endif

        <!-- Body -->
        <div class="col-span-6">
          <x-jet-label for="answer" value="{{ __('FAQ Body') }}" />
          <div class="@error('faq.answer') has-error @enderror">
            <x-editor wire:model="faq.answer" id="answer" property="answer" property_of="faq" :model="$faq->answer" />
          </div>
          <x-jet-input-error for="faq.answer" class="mt-1" />
        </div>

        <!-- Custom Fields -->
        <x-custom-fields model="FAQ" :custom_fields="$custom_fields" :extra_attributes="$faq->extra_attributes" />

        <div class="col-span-6 flex flex-wrap gap-x-6 gap-y-4">
          <div class="">
            <label for="active" class="inline-flex items-center">
              <x-checkbox id="active" wire:model.defer="faq.active" />
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
