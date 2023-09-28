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
          @if (auth()->user()->can('settings'))
            <x-link type="button" class="mt-6" :href="route('policies.edit')">{{ __('Update Policies') }}</x-link>
          @endif
        </div>
      </x-slot>

      <x-slot name="form">
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
                <img src="{{ $settings['icon'] }}" alt="{{ $settings['name'] }}" class="rounded-md h-20 w-20 object-cover">
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
                <img src="{{ $settings['logo'] }}" alt="{{ $settings['name'] }}" class="rounded-md h-20 w-full max-w-xs object-cover">
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
            <option value="Public">Public</option>
            <option value="Private">Private</option>
            <option value="Maintenance">Maintenance</option>
          </x-ui-native-select>
        </div>

        <!-- Limit -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Results Per Page')" wire:model.defer="settings.per_page">
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

        <!-- Banned Words -->
        <div class="col-span-6">
          <x-ui-input :label="__('Banned Words')" wire:model.defer="settings.banned_words" />
        </div>

        <!-- Replacement Word -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Replacement Words')" wire:model.defer="settings.replacement_word" />
        </div>

        <!-- Contact Email -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__('Contact Email')" type="email" wire:model.defer="settings.contact_email" />
        </div>

        <!-- Language -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Language')" wire:model.defer="settings.language">
            @foreach ($languages as $language)
              <option value="{{ $language['value'] }}">{{ $language['label'] }}</option>
            @endforeach
          </x-ui-native-select>
        </div>

        <!-- Theme -->
        <div class="col-span-6 sm:col-span-3" style="display:none;">
          <x-ui-native-select :label="__('Theme')" wire:model.defer="settings.theme">
            <option value="">{{ __('Let user select') }}</option>
            <option value="dark">{{ __('Dark') }}</option>
            <option value="light" selected>{{ __('Light') }}</option>
          </x-ui-native-select>
        </div>

        {{-- <!-- Thread Sorting -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Thread Sorting')" wire:model.defer="settings.sorting">
            <option value="created_at_desc">Newest thread first</option>
            <option value="created_at_asc">Oldest thread first</option>
            <option value="votes_desc">Higher votes thread first</option>
            <option value="votes_asc">Lower votes thread first</option>
            <option value="replied_at_desc">Thread with newest reply first</option>
            <option value="replied_at_asc">Thread with oldest reply first</option>
            <option value="views_asc">Thread with least views first</option>
          </x-ui-native-select>
        </div> --}}

        {{-- <!-- Reply Sorting -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Reply Sorting')" wire:model.defer="settings.reply_sorting">
            <option value="desc">Descending</option>
            <option value="asc">Ascending</option>
          </x-ui-native-select>
        </div> --}}

        <!-- Notifications -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Notifications')" wire:model.defer="settings.notifications">
            <option value="0">Disable</option>
            <option value="super">Super Admins only</option>
            <option value="admin">Super Admins & Admins</option>
            <option value="moderator">Super Admins, Admins & Moderators</option>
          </x-ui-native-select>
        </div>

        <!-- Editor -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Editor')" wire:model.defer="settings.editor">
            <option value="markdown">SimpleDME (markdown)</option>
            <option value="html">TinyMCE (html)</option>
          </x-ui-native-select>
        </div>

        <!-- Review New User Threads -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Review New User Threads')" wire:model.defer="settings.review_option">
            <option value="0">Disable</option>
            <option value="-1">All</option>
            <option value="5">First five (5) threads & posts</option>
            <option value="10">First ten (10) threads & posts</option>
          </x-ui-native-select>
        </div>

        <!-- Registration -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Registration')" wire:model.defer="settings.registration">
            <option value="0">Disable</option>
            <option value="1">Enable</option>
          </x-ui-native-select>
        </div>

        <!-- Allow to change vote -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="'Allow to change vote'" wire:model.defer="settings.voting">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Guest Reply -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Guest Reply')" wire:model.defer="settings.guest_reply">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Allow Delete -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Allow Delete')" wire:model.defer="settings.allow_delete">
            <option value="0">No</option>
            <option value="-1">Yes</option>
            <option value="10">{{ __('Allow within first 10 minutes') }}</option>
            <option value="30">{{ __('Allow within first 30 minutes') }}</option>
            <option value="60">{{ __('Allow within first hour') }}</option>
            <option value="1440">{{ __('Allow within a day') }}</option>
          </x-ui-native-select>
        </div>

        <!-- RTL Support -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('RTL Support')" wire:model.defer="settings.rtl">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Sticky sidebar -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Sticky Sidebar')" wire:model.defer="settings.sticky_sidebar">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Flag Option -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Flag Option')" wire:model.defer="settings.flag_option">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Flag Option -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Hide Flagged')" wire:model.defer="settings.hide_flagged">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Members Page -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Members Page')" wire:model.defer="settings.member_page">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- User Signature -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('User Signature')" wire:model.defer="settings.signature">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Allow Upload File -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Upload File Dropped on Body')" wire:model.defer="settings.allowed_upload">
            <option value="0">No</option>
            <option value="1">Yes</option>
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
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Trending Threads -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Trending Threads')" wire:model.defer="settings.trending_threads">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Who to follow -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select :label="__('Who to follow')" wire:model.defer="settings.top_members">
            <option value="0">No</option>
            <option value="1">Yes</option>
          </x-ui-native-select>
        </div>

        <!-- Conatct text -->
        <div class="col-span-6">
          <x-textarea :label="__('Contact Page Text')" wire:model.defer="settings.contact_page" />
        </div>

        <div class="col-span-6">
          <h4 class="mb-3 font-bold">{{ __('Ads & Analytics') }}</h4>

          <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Top Ad Code -->
            <div class="col-span-6">
              <x-textarea :label="__('Top Ad Code')" wire:model.defer="settings.top_ad_code" />
            </div>
            <!-- Sidebar Ad Code -->
            <div class="col-span-6">
              <x-textarea :label="__('Sidebar Ad Code')" wire:model.defer="settings.sidebar_ad_code" />
            </div>
            <!-- Bottom Ad Code -->
            <div class="col-span-6">
              <x-textarea :label="__('Bottom Ad Code')" wire:model.defer="settings.bottom_ad_code" />
            </div>
            <!-- Footer Code -->
            <div class="col-span-6">
              <x-textarea :label="__('Footer Code')" wire:model.defer="settings.footer_code" />
            </div>
          </div>
        </div>

        {{-- <div class="col-span-6">
          <h4 class="mb-3 font-bold">{{ __('Date & Time Format') }}</h4>

          <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Date Format -->
            <div class="col-span-6 sm:col-span-2">
              <x-ui-native-select :label="__('Year')" wire:model.defer="settings.year">
                <option value="Numeric">Numeric</option>
                <option value="2-digit">2-digit</option>
              </x-ui-native-select>
            </div>
            <div class="col-span-6 sm:col-span-2">
              <x-ui-native-select :label="__('Month')" wire:model.defer="settings.month">
                <option value="Numeric">Numeric</option>
                <option value="2-digit">2-digit</option>
                <option value="Long">Long</option>
                <option value="Short">Short</option>
                <option value="Narrow">Narrow</option>
              </x-ui-native-select>
            </div>
            <div class="col-span-6 sm:col-span-2">
              <x-ui-native-select :label="__('Day')" wire:model.defer="settings.day">
                <option value="Numeric">Numeric</option>
                <option value="2-digit">2-digit</option>
              </x-ui-native-select>
            </div>
            <div class="col-span-6 sm:col-span-3">
              <x-ui-native-select :label="__('Weekday')" wire:model.defer="settings.weekday">
                <option value="">Hide</option>
                <option value="Long">Long</option>
                <option value="Short">Short</option>
                <option value="Narrow">Narrow</option>
              </x-ui-native-select>
            </div>
            <div class="col-span-6 sm:col-span-3">
              <x-ui-native-select :label="'Time Format 12 Hours'" wire:model.defer="settings.hour12">
                <option value="1">Yes</option>
                <option value="0">No</option>
              </x-ui-native-select>
            </div>
          </div>
        </div> --}}

        <div class="col-span-6">
          <h4 class="mb-3 font-bold">{{ __('Social Links') }}</h4>

          <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Facebook Link -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Facebook Link')" wire:model.defer="settings.facebook_link" />
            </div>

            <!-- Instagram Link -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Instagram Link')" wire:model.defer="settings.instagram_link" />
            </div>

            <!-- Twitter Link -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Twitter Link')" wire:model.defer="settings.twitter_link" />
            </div>

            <!-- LinkedIn Link -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('LinkedIn Link')" wire:model.defer="settings.linkedin_link" />
            </div>

            <!-- GitHub Link -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('GitHub Link')" wire:model.defer="settings.github_link" />
            </div>

            <!-- Dribbble Link -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Dribbble Link')" wire:model.defer="settings.dribbble_link" />
            </div>

            <div class="col-span-6 flex flex-wrap gap-x-6 gap-y-4">
              <div class="">
                <label for="articles" class="inline-flex items-center">
                  <x-checkbox id="articles" wire:model.defer="settings.articles" />
                  <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Pages') }}</span>
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
                  <x-checkbox id="contact-p" wire:model.defer="settings.contact" />
                  <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Contact Page') }}</span>
                </label>
              </div>
            </div>
          </div>
        </div>

        <div class="col-span-6">
          <h4 class="mb-3 font-bold">{{ __('Social Login') }}</h4>

          <div class="">
            <label for="facebook_login" class="inline-flex items-center">
              <x-checkbox id="facebook_login" wire:model.defer="settings.facebook_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Facebook Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Facebook Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Facebook Client Id')" wire:model.defer="settings.facebook_client_id" />
            </div>
            <!-- Facebook Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Facebook Client Secret')" wire:model.defer="settings.facebook_client_secret" />
            </div>
          </div>

          <div class="mt-6">
            <label for="github_login" class="inline-flex items-center">
              <x-checkbox id="github_login" wire:model.defer="settings.github_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('GitHub Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- GitHub Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('GitHub Client Id')" wire:model.defer="settings.github_client_id" />
            </div>
            <!-- GitHub Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('GitHub Client Secret')" wire:model.defer="settings.github_client_secret" />
            </div>
          </div>

          <div class="mt-6">
            <label for="google_login" class="inline-flex items-center">
              <x-checkbox id="google_login" wire:model.defer="settings.google_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Google Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Google Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Google Client Id')" wire:model.defer="settings.google_client_id" />
            </div>
            <!-- Google Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Google Client Secret')" wire:model.defer="settings.google_client_secret" />
            </div>
          </div>

          <div class="mt-6">
            <label for="twitter_login" class="inline-flex items-center">
              <x-checkbox id="twitter_login" wire:model.defer="settings.twitter_login" />
              <span class="ltr:ml-2 rtl:mr-2 text-sm">{{ __('Twitter Social Authentication') }}</span>
            </label>
          </div>
          <div class="mt-2 grid grid-cols-1 sm:grid-cols-6 gap-6">
            <!-- Twitter Client Id -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Twitter Client Id')" wire:model.defer="settings.twitter_client_id" />
            </div>
            <!-- Twitter Client Secret -->
            <div class="col-span-6 sm:col-span-3">
              <x-ui-input :label="__('Twitter Client Secret')" wire:model.defer="settings.twitter_client_secret" />
            </div>
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
</div>
