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
        <div x-data="{ contact: {{ $settings['contact'] ? 'true' : 'false' }} }" class="col-span-6 grid grid-cols-1 sm:grid-cols-6 gap-6">
          <!-- Icon -->
          <div class="col-span-6 md:col-span-3">
            <div x-data="{ iconName: null, iconPreview: null }">
              <!-- Icon File Input -->
              <input type="file" class="hidden" wire:model="icon" x-ref="icon"
                x-on:change="
            iconName = $refs.icon.files[0].name;
            const reader = new FileReader();
            reader.onload = (e) => {
                iconPreview = e.target.result;
            };
            reader.readAsDataURL($refs.icon.files[0]);
          " />

              <x-jet-label for="icon" value="{{ __('Icon') }}" />

              <!-- Current Icon -->
              @if ($settings['icon'] ?? null)
                <div class="mt-2" x-show="! iconPreview">
                  <img src="{{ storage_url($settings['icon']) }}" alt="{{ $settings['name'] }}" class="rounded-md h-20 w-20 object-cover">
                </div>
              @endif

              <!-- New Icon Preview -->
              <div class="mt-2" x-show="iconPreview" style="display: none;">
                <span class="block rounded-md w-20 h-20 bg-cover bg-no-repeat bg-center"
                  x-bind:style="'background-image: url(\'' + iconPreview + '\');'">
                </span>
              </div>

              <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.icon.click()">
                {{ __('Select A New Icon') }}
              </x-jet-secondary-button>

              @if ($settings['icon'] ?? null)
                <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteIcon">
                  {{ __('Remove Icon') }}
                </x-jet-secondary-button>
              @endif

              <p class="text-xs mt-2">{{ __('Max. size 200mb and dimension min 24x24px and max 250x250px with ration 1/1') }}</p>
              <x-jet-input-error for="icon" class="mt-2" />
            </div>
          </div>

          <!-- Logo -->
          <div class="col-span-6 md:col-span-3">
            <div x-data="{ logoName: null, logoPreview: null }">
              <!-- Logo File Input -->
              <input type="file" class="hidden" wire:model="logo" x-ref="logo"
                x-on:change="
            logoName = $refs.logo.files[0].name;
            const reader = new FileReader();
            reader.onload = (e) => {
                logoPreview = e.target.result;
            };
            reader.readAsDataURL($refs.logo.files[0]);
          " />

              <x-jet-label for="logo" value="{{ __('Logo') }}" />

              <!-- Current Logo -->
              @if ($settings['logo'] ?? null)
                <div class="mt-2" x-show="! logoPreview">
                  <img src="{{ storage_url($settings['logo']) }}" alt="{{ $settings['name'] }}"
                    class="rounded-md h-20 w-full max-w-xs object-cover">
                </div>
              @endif

              <!-- New Logo Preview -->
              <div class="mt-2" x-show="logoPreview" style="display: none;">
                <span class="block rounded-md w-full max-w-xs h-20 bg-cover bg-no-repeat bg-center"
                  x-bind:style="'background-image: url(\'' + logoPreview + '\');'">
                </span>
              </div>

              <x-jet-secondary-button class="mt-2 mr-2" type="button" x-on:click.prevent="$refs.logo.click()">
                {{ __('Select A New Logo') }}
              </x-jet-secondary-button>

              @if ($settings['logo'] ?? null)
                <x-jet-secondary-button type="button" class="mt-2" wire:click="deleteLogo">
                  {{ __('Remove Logo') }}
                </x-jet-secondary-button>
              @endif

              <p class="text-xs mt-2">{{ __('Max. size 1000mb and max dimension of 600x150px') }}</p>
              <x-jet-input-error for="logo" class="mt-2" />
            </div>
          </div>

          <!-- Name -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-input :label="__('Name')" wire:model.defer="settings.name" />
          </div>

          <!-- Mode -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Mode')" wire:model.defer="settings.mode">
              <option value=""></option>
              <option value="Public">{{ __('Public') }}</option>
              <option value="Private">{{ __('Private') }}</option>
              <option value="Maintenance">{{ __('Maintenance') }}</option>
            </x-ui-native-select>
          </div>

          <!-- Limit -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Results Per Page')" wire:model.defer="settings.per_page">
              <option value=""></option>
              <option value="10">10</option>
              <option value="15">15</option>
              <option value="20">20</option>
              <option value="25">25</option>
              <option value="50">50</option>
            </x-ui-native-select>
          </div>

          <!-- Title -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-input :label="__('Page Title')" wire:model.defer="settings.title" />
          </div>

          <!-- Description -->
          <div class="col-span-6">
            <x-textarea :label="__('Meta Description')" wire:model.defer="settings.description" />
          </div>

          <!-- Contact Email -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-input :label="__('Contact Email')" type="email" wire:model.defer="settings.contact_email" />
          </div>

          <!-- Language -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Language')" wire:model.defer="settings.language">
              <option value=""></option>
              @foreach ($languages as $language)
                <option value="{{ $language['value'] }}">{{ $language['label'] }}</option>
              @endforeach
            </x-ui-native-select>
          </div>

          <!-- Theme -->
          <div class="col-span-6 sm:col-span-3" style="display:none;">
            <x-ui-native-select :label="__('Theme')" wire:model.defer="settings.theme" >
              <option value=""></option>
              <option value="">{{ __('Let user select') }}</option>
              <option value="dark">{{ __('Dark') }}</option>
              <option value="light" selected>{{ __('Light') }}</option>
            </x-ui-native-select>
          </div>

          <!-- RTL Support -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('RTL Support')" wire:model.defer="settings.rtl">
              <option value=""></option>
              <option value="0">{{ __('No') }}</option>
              <option value="1">{{ __('Yes') }}</option>
            </x-ui-native-select>
          </div>

          <!-- Sticky sidebar -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Sticky Sidebar')" wire:model.defer="settings.sticky_sidebar">
              <option value=""></option>
              <option value="0">{{ __('No') }}</option>
              <option value="1">{{ __('Yes') }}</option>
            </x-ui-native-select>
          </div>

          <!-- Members Page -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Members Page')" wire:model.defer="settings.member_page">
              <option value=""></option>
              <option value="0">{{ __('No') }}</option>
              <option value="1">{{ __('Yes') }}</option>
            </x-ui-native-select>
          </div>

          <!-- Allow Upload File -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Upload File')" wire:model.defer="settings.allowed_upload">
              <option value=""></option>
              <option value="0">{{ __('No') }}</option>
              <option value="1">{{ __('Yes') }}</option>
            </x-ui-native-select>
          </div>

          <!-- Allowed File Types -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-input :label="__('Allowed File Types (Extensions separated by comma)')" wire:model.defer="settings.allowed_files" />
          </div>

          <!-- Search Length -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-input :label="__('Min. Search Length')" wire:model.defer="settings.search_length" />
          </div>

          <!-- Search Backdrop -->
          <div class="col-span-6 sm:col-span-3">
            <x-ui-native-select :label="__('Search Backdrop')" wire:model.defer="settings.search_backdrop">
              <option value=""></option>
              <option value="0">{{ __('No') }}</option>
              <option value="1">{{ __('Yes') }}</option>
            </x-ui-native-select>
          </div>

          <!-- Enable -->
          <div class="col-span-6 mt-4">
            <h4 class="font-bold">{{ __('Toggle Features') }}</h4>
          </div>

          <div class="col-span-6 flex flex-wrap gap-x-6 gap-y-4">
            <div class="">
              <label for="articles" class="inline-flex items-center">
                <x-checkbox id="articles" wire:model.defer="settings.articles" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Articles') }}</span>
              </label>
            </div>
            <div class="">
              <label for="knowledgebase" class="inline-flex items-center">
                <x-checkbox id="knowledgebase" wire:model.defer="settings.knowledgebase" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Knowledge base') }}</span>
              </label>
            </div>
            <div class="">
              <label for="faqs" class="inline-flex items-center">
                <x-checkbox id="faqs" wire:model.defer="settings.faqs" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('FAQs') }}</span>
              </label>
            </div>
            <div class="">
              <label for="contact-p" class="inline-flex items-center">
                <x-checkbox id="contact-p" wire:model.defer="settings.contact" x-model="contact" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Contact Page') }}</span>
              </label>
            </div>
          </div>

          <!-- Conatct text -->
          <div x-show="contact" x-transition class="col-span-6">
            <x-textarea :label="__('Contact Page Text')" wire:model.defer="settings.contact_page" />
          </div>
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

  <div class="max-w-7xl mx-auto pb-10 sm:px-6 lg:px-8">
    <x-jet-action-section>
      <x-slot name="title">
        {{ __('Sitemap') }}
      </x-slot>

      <x-slot name="description">
        {{ __('Please review or generate sitemap.') }}
      </x-slot>

      <x-slot name="content">
        <div class="mb-4">
          {{ __('Sitemap') }}: <a href="{{ url('/sitemap.xml') }}" class="link" target="_blank">{{ url('/sitemap.xml') }}</a>
        </div>
        <x-jet-button type="button" wire:click="sitemap" wire:loading.attr="disabled">
          {{ __('Generate Now') }}
        </x-jet-button>
      </x-slot>
    </x-jet-action-section>
  </div>
</div>
