<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $faq_category->id ? __('Edit FAQ Category') : __('New FAQ Category') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('faq.categories')">{{ __('List FAQ Category') }}</x-link>
        <x-link type="button" class="mt-6" :href="route('faqs')">{{ __('List FAQs') }}</x-link>
      </x-slot>

      <x-slot name="form">
        {{-- <!-- Image -->
        <div class="col-span-6">
          <div x-data="{ imageName: null, imagePreview: null }">
            <!-- Image File Input -->
            <input type="file" class="hidden" wire:model="image" x-ref="image"
              x-on:change="
              imageName = $refs.image.files[0].name;
              const reader = new FileReader();
              reader.onload = (e) => {
                  imagePreview = e.target.result;
              };
              reader.readAsDataURL($refs.image.files[0]);
            " />

            <x-jet-label for="image" value="{{ __('Image') }}" />

            <!-- Current Image -->
            @if ($faq_category['image'] ?? null)
              <div class="mt-2" x-show="! imagePreview">
                <img src="{{ $faq_category['image'] }}" alt="{{ $faq_category['name'] }}" class="rounded-full h-20 w-20 object-cover">
              </div>
            @endif

            <!-- New Image Preview -->
            <div class="mt-2" x-show="imagePreview" style="display: none;">
              <span class="block rounded-full w-20 h-20 bg-cover bg-no-repeat bg-center"
                x-bind:style="'background-image: url(\'' + imagePreview + '\');'">
              </span>
            </div>

            <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.image.click()">
              {{ __('Select A New Image') }}
            </x-jet-secondary-button>

            @if ($faq_category['image'] ?? null)
              <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteImage">
                {{ __('Remove Image') }}
              </x-jet-secondary-button>
            @endif

            <x-jet-input-error for="image" class="mt-1" />
          </div>
        </div> --}}

        <!-- Name -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Name')" wire:model.defer="faq_category.name" />
        </div>

        <!-- Slug -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Slug')" wire:model.defer="faq_category.slug" />
        </div>

        <!-- Order -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Order Number')" wire:model.defer="faq_category.order_no" />
        </div>

        <!-- Title -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Page Title')" wire:model.defer="faq_category.title" />
        </div>

        <!-- Description -->
        <div class="col-span-6">
          <x-textarea :label="__('Meta Description')" wire:model.defer="faq_category.description" />
        </div>

        <!-- Parent Category -->
        @if ($mainCategories)
          <div class="col-span-6">
            <x-ui-select :label="__('Parent Category')" :options="$mainCategories" option-value="id" option-label="name"
              wire:model.defer="faq_category.faq_category_id">
            </x-ui-select>
          </div>
        @endif

        <div class="col-span-6">
          <label for="active" class="inline-flex items-center">
            <x-checkbox id="active" wire:model.defer="faq_category.active" />
            <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Active') }}</span>
          </label>
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
