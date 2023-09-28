<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-form-section submit="save">
      <x-slot name="title">
        {{ $thread->id ? __('Edit Thread') : __('New Thread') }}
      </x-slot>

      <x-slot name="description">
        <div>{{ __('Please fill the form and save.') }}</div>
        <x-link type="button" class="mt-6" :href="route('threads')">{{ __('List Threads') }}</x-link>
      </x-slot>

      <x-slot name="form">
        <!-- Title -->
        <div class="col-span-6 {{ $logged_in_user?->can('meta-tags') ? 'sm:col-span-3' : '' }}">
          <x-ui-input :label="__('Title')" wire:model.defer="thread.title" />
        </div>

        @if ($logged_in_user?->can('meta-tags'))
          <!-- Slug -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-input :label="__('Slug')" wire:model.defer="thread.slug" />
          </div>

          <!-- Description -->
          <div class="col-span-6">
            <x-textarea :label="__('Description')" wire:model.defer="thread.description" />
          </div>
        @endif

        <!-- Category -->
        @if ($categoriesMenu)
          <div class="col-span-6">
            <x-ui-native-select :label="__('Category')" wire:model.defer="thread.category_id">
              <option>{{ __('Select') }}</option>
              <x-options :categories="$categoriesMenu" />
            </x-ui-native-select>
          </div>
        @endif

        <!-- Body -->
        <div class="col-span-6">
          <x-jet-label for="body" value="{{ __('Thread Body') }}" />
          <div class="@error('thread.body') has-error @enderror">
            <x-editor wire:model="thread.body" id="body" property="body" property_of="thread" :model="$thread->body" />
          </div>
          <x-jet-input-error for="thread.body" class="mt-1" />
        </div>

        <!-- Tags -->
        <div class="col-span-6">
          <x-jet-label for="body" value="{{ __('Tags') }}" />
          <div x-data @tags-update="console.log('tags updated', $event.detail.tags)"
            data-tags='{{ $thread?->id ? $thread->tags->pluck('name') : '[]' }}'>
            <div x-data="{
                open: false,
                textInput: '',
                tags: [],
                matched: [],
                init() {
                    this.tags = JSON.parse(this.$el.parentNode.getAttribute('data-tags'));
                },
                addTag(tag) {
                    tag = tag.trim();
                    if (tag != '' && !this.hasTag(tag)) { this.tags.push(tag); }
                    this.clearSearch();
                    this.$refs.textInput.focus();
                    this.fireTagsUpdateEvent();
                },
                fireTagsUpdateEvent() {
                    @this.$set('tags', this.tags);
                    this.$el.dispatchEvent(new CustomEvent('tags-update', {
                        detail: { tags: this.tags },
                        bubbles: true,
                    }));
                },
                hasTag(tag) {
                    var tag = this.tags.find(e => (e.toLowerCase() === tag.toLowerCase()))
                    return tag != undefined;
                },
                removeTag(index) {
                    this.tags.splice(index, 1);
                    this.fireTagsUpdateEvent();
                },
                search(q) {
                    if (q.includes(',')) {
                        q.split(',').forEach(function(val) {
                            this.addTag(val);
                        }, this)
                    }
                    fetch('{{ route('search.tags') }}', {
                        headers: {
                            'Accept': 'application/json',
                            'Content-Type': 'application/json',
                            'X-Requested-With': 'XMLHttpRequest',
                            'X-CSRF-Token': '{{ csrf_token() }}'
                        },
                        method: 'post',
                        credentials: 'same-origin',
                        body: JSON.stringify({ search: q })
                    }).then(res => {
                        res.json().then((d) => this.matched = d.map(t => t.name)).catch();
                    }).catch();
            
                    this.toggleSearch();
                },
                clearSearch() {
                    this.textInput = '';
                    this.toggleSearch();
                },
                toggleSearch() {
                    this.open = this.textInput != '';
                }
            }" x-init="init('parentEl')" @click.away="clearSearch()" @keydown.escape="clearSearch()">
              <div class="relative" @keydown.enter.prevent="addTag(textInput)">
                <input x-model="textInput" x-ref="textInput" @input="search($event.target.value)"
                  class="mt-1 py-2 placeholder-gray-400 dark:bg-gray-800 dark:placeholder-gray-500 border border-gray-300 focus:ring-primary-500 focus:border-primary-500 dark:border-gray-700 form-input block w-full sm:text-sm rounded-md transition ease-in-out duration-100 focus:outline-none shadow-sm"
                  placeholder="{{ __('Type tag & press ,') }}">
                <div x-show="open" style="display: none">
                  <div class="absolute z-40 left-0 mt-2 w-full">
                    <div class="py-1 text-sm bg-white dark:bg-gray-700 rounded shadow-lg border border-gray-300 dark:border-gray-700">
                      <template x-for="match in matched">
                        <a @click.prevent="addTag(match)" class="block py-1 px-5 cursor-pointer hover:bg-primary-600 hover:text-white"
                          x-text="match"></a>
                      </template>
                      <a @click.prevent="addTag(textInput)" class="block py-1 px-5 cursor-pointer hover:bg-primary-600 hover:text-white">
                        {{ __('Add tag') }} "<span class="font-semibold" x-text="textInput"></span>"</a>
                    </div>
                  </div>
                </div>
                <!-- selections -->
                <template x-for="(tag, index) in tags">
                  <div class="bg-gray-200 dark:bg-gray-700 inline-flex items-center text-sm rounded-md mt-2 ltr:mr-1 rtl:ml-1">
                    <span class="ltr:ml-2 ltr:mr-1 rtl:mr-2 rtl:ml-1 px-1 leading-relaxed truncate max-w-xs" x-text="tag"></span>
                    <button @click.prevent="removeTag(index)"
                      class="w-6 h-7 inline-block align-middle text-gray-500 dark:text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 focus:outline-none">
                      <svg class="w-6 h-6 fill-current mx-auto" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                        <path fill-rule="evenodd"
                          d="M15.78 14.36a1 1 0 0 1-1.42 1.42l-2.82-2.83-2.83 2.83a1 1 0 1 1-1.42-1.42l2.83-2.82L7.3 8.7a1 1 0 0 1 1.42-1.42l2.83 2.83 2.82-2.83a1 1 0 0 1 1.42 1.42l-2.83 2.83 2.83 2.82z" />
                      </svg>
                    </button>
                  </div>
                </template>
              </div>
            </div>
          </div>
          <x-jet-input-error for="thread.body" class="mt-1" />
        </div>

        {{-- <!-- Image -->
        <div class="col-span-6 sm:col-span-3">
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
            @if ($thread['image'] ?? null)
              <div class="mt-2" x-show="! imagePreview">
                <img src="{{ $thread['image'] }}" alt="{{ $thread['name'] }}" class="rounded-md h-20 w-20 object-cover">
              </div>
            @endif

            <!-- New Image Preview -->
            <div class="mt-2" x-show="imagePreview" style="display: none;">
              <span class="block rounded-md w-20 h-20 bg-cover bg-no-repeat bg-center"
                x-bind:style="'background-image: url(\'' + imagePreview + '\');'">
              </span>
            </div>

            <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.image.click()">
              {{ __('Select A New Image') }}
            </x-jet-secondary-button>

            @if ($thread['image'] ?? null)
              <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteImage">
                {{ __('Remove Image') }}
              </x-jet-secondary-button>
            @endif

            <x-jet-input-error for="image" class="mt-1" />
          </div>
        </div>

        <!-- Attachments -->
        <div class="col-span-6 sm:col-span-3">
          <div x-data="{ attachmentsName: [] }">
            <!-- Attachments File Input -->
            <input type="file" multiple class="hidden" wire:model="attachments" x-ref="attachments"
              x-on:change="attachmentsName = Array.from($refs.attachments.files)" />

            <x-jet-label for="attachments" value="{{ __('Attachments') }}" />

            <!-- Current Attachments -->
            @if ($thread['attachments'] ?? null)
              <div class="my-3 flex flex-wrap gap-x-6 gap-y-3 text-sm">
                @foreach ($thread['attachments'] as $attachment)
                  <span class="text-xs">{{ $loop->iteration }}.</span> {{ $attachment->name }}
                  <a href="#">
                    <x-icon type="trash" />
                  </a>
                @endforeach
              </div>
            @endif

            <!-- New Attachments Preview -->
            <div class="my-3 flex flex-wrap gap-x-6 gap-y-3 text-sm" x-show="attachmentsName && attachmentsName.length"
              style="display: none;">
              <template x-for="(file, index) in attachmentsName">
                <span>
                  <span class="text-xs" x-text="index+1"></span>.
                  <span class="" x-text="file.name"></span>
                </span>
              </template>
            </div>

            <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.attachments.click()">
              {{ __('Select Attachments') }}
            </x-jet-secondary-button>

            <x-jet-input-error for="attachments" class="mt-1" />
          </div>
        </div> --}}

        <!-- Custom Fields -->
        <x-custom-fields model="thread" :custom_fields="$custom_fields" :extra_attributes="$thread->extra_attributes" />

        @if ($logged_in_user?->can('meta-tags'))
          <div class="col-span-6 flex flex-wrap gap-x-6 gap-y-4">
            <div class="">
              <label for="active" class="inline-flex items-center">
                <x-checkbox id="active" wire:model.defer="thread.active" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Active') }}</span>
              </label>
            </div>
            {{-- TODO: Private --}}
            {{-- <div class="">
              <label for="private" class="inline-flex items-center">
                <x-checkbox id="private" wire:model.defer="thread.private" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Private') }}</span>
              </label>
            </div> --}}
            <div class="">
              <label for="noindex" class="inline-flex items-center">
                <x-checkbox id="noindex" wire:model.defer="thread.noindex" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Robots noindex') }}</span>
              </label>
            </div>
            <div class="">
              <label for="nofollow" class="inline-flex items-center">
                <x-checkbox id="nofollow" wire:model.defer="thread.nofollow" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Robots nofollow') }}</span>
              </label>
            </div>
            <div class="">
              <label for="sticky" class="inline-flex items-center">
                <x-checkbox id="sticky" wire:model.defer="thread.sticky" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Stick on top') }}</span>
              </label>
            </div>
            <div class="">
              <label for="sticky_category" class="inline-flex items-center">
                <x-checkbox id="sticky_category" wire:model.defer="thread.sticky_category" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Stick on top for category only') }}</span>
              </label>
            </div>
          </div>
        @endif

        <!-- Permission -->
        @if ($logged_in_user?->can('group-permissions'))
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('View Permission')" wire:model.defer="thread.group">
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
