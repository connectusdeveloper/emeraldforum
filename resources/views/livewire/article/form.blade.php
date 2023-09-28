<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $article ?? null ? __('Edit Article') : __('New Article') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('articles')">{{ __('List Articles') }}</x-link>
      </x-slot>

      <x-slot name="form">
        <!-- Image -->
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
            @if ($article->image ?? null)
              <div class="mt-2" x-show="! imagePreview">
                <img src="{{ $article->image }}" alt="" class="rounded-md h-40 w-full object-cover">
              </div>
            @endif

            <!-- New Image Preview -->
            <div class="mt-2" x-show="imagePreview" style="display: none;">
              <span class="block rounded-md w-auto h-40 bg-cover bg-no-repeat bg-center"
                x-bind:style="'background-image: url(\'' + imagePreview + '\');'">
              </span>
            </div>

            <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.image.click()">
              {{ __('Select A New Image') }}
            </x-jet-secondary-button>

            @if ($article->image ?? null)
              <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteImage">
                {{ __('Remove Image') }}
              </x-jet-secondary-button>
            @endif

            <x-jet-input-error for="image" class="mt-2" />
          </div>
        </div>

        <!-- Title -->
        <div class="col-span-6">
          <x-ui-input :label="__('Article Title')" wire:model.defer="article.title" />
        </div>

        <!-- Slug -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Slug')" wire:model.defer="article.slug" />
        </div>

        <!-- Order -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Order Number')" type="number" wire:model.defer="article.order_no" />
        </div>

        <!-- Description -->
        <div class="col-span-6">
          <x-textarea :label="__('Meta Description')" wire:model.defer="article.description" />
        </div>

        <!-- Body -->
        <div class="col-span-6">
          <x-jet-label for="body" value="{{ __('Article Body') }}" />
          <div class="@error('article.body') has-error @enderror">
            <x-editor wire:model="article.body" id="body" property="body" property_of="article" :model="$article->body" />
          </div>
          <x-jet-input-error for="article.body" class="mt-1" />
        </div>

        <!-- Custom Fields -->
        <x-custom-fields model="article" :custom_fields="$custom_fields" :extra_attributes="$article->extra_attributes" />

        <div class="col-span-6 flex flex-wrap gap-x-6 gap-y-4">
          <div class="">
            <label for="active" class="inline-flex items-center">
              <x-checkbox id="active" wire:model.defer="article.active" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Active') }}</span>
            </label>
          </div>
          <div class="">
            <label for="noindex" class="inline-flex items-center">
              <x-checkbox id="noindex" wire:model.defer="article.noindex" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Robots noindex') }}</span>
            </label>
          </div>
          <div class="">
            <label for="nofollow" class="inline-flex items-center">
              <x-checkbox id="nofollow" wire:model.defer="article.nofollow" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Robots nofollow') }}</span>
            </label>
          </div>
        </div>

        <!-- Permission -->
        @if ($logged_in_user?->can('group-permissions'))
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('View Permission')" wire:model.defer="article.group">
              <option value="-1">{{ __('Public') }}</option>
              <option value="0">{{ __('All Members') }}</option>
              @forelse ($roles as $role)
                <option value="{{ $role->id }}">{{ $role->name }}</option>
              @empty
              @endforelse
            </x-ui-native-select>
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
