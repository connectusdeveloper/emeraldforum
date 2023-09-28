<header x-data="{
    search: '',
    result: [],
    searching: false,
    mobileMenu: false,
    showAnimation: false,
    async searchNow(query) {
        if (query.length >= '{{ $settings['search_length'] ?? 2 }}') {
            this.showAnimation = true;
            let res = await fetch('{{ route('search') }}', {
                method: 'post',
                body: JSON.stringify({ query }),
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-Token': '{{ csrf_token() }}',
                },
            });
            this.result = await res.json();
            this.showAnimation = false;
        }
    },
    async focusSearchConsole() {
        this.searching = true;
        this.showAnimation = false;
        await $nextTick();
        document.getElementById('search-console').focus();
        document.body.classList.add('overflow-hidden');
    },
    closeSearchConsole() {
        this.searching = false;
        document.body.classList.remove('overflow-hidden');
    }
}" x-init="$watch('search', value => searchNow(value))" class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 shadow mb-1"
  id="main-nav">
  @if (!$logged_in_user && ($settings['mode'] ?? null) == 'Private')
    <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
      <div class="flex h-16 justify-center">
        <x-jet-authentication-card-logo />
      </div>
    </div>
  @else
    <div class="mx-auto max-w-7xl px-2 sm:px-4 lg:px-8">
      <div class="flex h-16 justify-between">
        <div class="flex px-2 lg:px-0">
          <div class="flex flex-shrink-0 items-center">
            <x-jet-authentication-card-logo />
          </div>
          <div class="hidden ltr:lg:ml-6 rtl:lg:mr-6 lg:self-stretch lg:flex lg:items-center">
            <div class="flex gap-x-1">
              @if ($settings['faqs'] ?? null)
                <a href="{{ route('faqs.list') }}" @class([
                    'text-gray-700 dark:text-white px-3 py-2 rounded-md text-sm font-medium',
                    'bg-gray-100 dark:bg-gray-700 head-link-btn' => request()->routeIs('faqs.list'),
                    'bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' => !request()->routeIs(
                        'faqs.list'),
                ])>{{ __('FAQs') }}</a>
              @endif

              @if ($settings['knowledgebase'] ?? null)
                <a href="{{ route('knowledgebase.list') }}" @class([
                    'text-gray-700 dark:text-white px-3 py-2 rounded-md text-sm font-medium',
                    'bg-gray-100 dark:bg-gray-700 head-link-btn' => request()->routeIs('knowledgebase.list'),
                    'bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' => !request()->routeIs(
                        'knowledgebase.list'),
                ])>{{ __('Knowledge base') }}</a>
              @endif

              @if ($settings['articles'] ?? null)
                <a href="{{ route('articles.list') }}" @class([
                    'text-gray-700 dark:text-white px-3 py-2 rounded-md text-sm font-medium',
                    'bg-gray-100 dark:bg-gray-700 head-link-btn' => request()->routeIs('articles.list'),
                    'bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-700' => !request()->routeIs(
                        'articles.list'),
                ])>{{ __('Articles') }}</a>
              @endif

              @if (
                  ($logged_in_user?->can('review') && ($threads_require_review ?? null)) ||
                      ($logged_in_user?->can('approve-threads') && ($threads_require_approval ?? null)))
                <x-jet-dropdown align="{{ ($settings['rtl'] ?? null) == 1 ? 'left' : 'right' }}" width="56">
                  <x-slot name="trigger">
                    <button type="button"
                      class="text-yellow-700 dark:text-yellow-300 group p-2 inline-flex items-center rounded-full font-medium hover:text-yellow-900 dark:hover:text-yellow-100 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:bg-yellow-50 dark:hover:bg-yellow-800 text-sm"
                      aria-expanded="false">
                      <svg class="h-5 w-5 transition duration-150 ease-in-out group-hover:text-gray-500 dark:group-hover:text-gray-300"
                        xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-6 h-6">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M12 9v3.75m0-10.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.75c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.57-.598-3.75h-.152c-3.196 0-6.1-1.249-8.25-3.286zm0 13.036h.008v.008H12v-.008z" />
                      </svg>
                    </button>
                  </x-slot>

                  <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      {{ __('Review Threads') }}
                    </div>
                    @if ($logged_in_user?->can('review') && ($threads_require_review ?? null))
                      <x-jet-dropdown-link href="{{ route('threads', ['require_review' => 'yes']) }}">
                        {{ shortNumber($threads_require_review) }} {{ __('Require review') }}
                      </x-jet-dropdown-link>
                    @endif
                    @if ($logged_in_user?->can('approve-threads') && ($threads_require_approval ?? null))
                      <x-jet-dropdown-link href="{{ route('threads', ['require_approval' => 'yes']) }}">
                        {{ shortNumber($threads_require_approval) }} {{ __('Require approval') }}
                      </x-jet-dropdown-link>
                    @endif
                  </x-slot>
                </x-jet-dropdown>
              @endif
            </div>
          </div>
        </div>
        <div class="flex-1 flex justify-end">
          <div class="flex">
            <div class="flex gap-x-2 sm:gap-x-4">
              <!-- Mobile search button -->
              <div
                class="flex items-center justify-self-end gap-2 {{ $settings['theme'] ?? null ? '' : 'ltr:mr-0 rtl:ml-0 lg:ltr:mr-4 lg:rtl:ml-4' }}">
                <button type="button" @click="focusSearchConsole()"
                  class="flex items-center justify-center rounded-full p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-500 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                  aria-expanded="false">
                  <span class="sr-only">{{ __('Search') }}</span>
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-5 h-5">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                  </svg>
                </button>
                @if ($logged_in_user && $unreadCount)
                  <a href="{{ route('notifications') }}"
                    class="flex items-center justify-center rounded-full p-x sm:py-1.5 sm:px-4 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-500 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                    <span class="hidden sm:block text-sm font-bold ltr:mr-2 rtl:ml-2">{{ $unreadCount }}</span>
                    <span class="sr-only">{{ __('View notifications') }}</span>
                    <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                      stroke="currentColor" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                    </svg>
                  </a>
                @endif
              </div>
              <div class="flex items-center lg:hidden justify-self-end">
                <!-- Mobile menu button -->
                <button type="button" @click="mobileMenu = true"
                  class="inline-flex items-center justify-center rounded-md p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500"
                  aria-expanded="false">
                  <span class="sr-only">{{ __('Open main menu') }}</span>
                  <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                  </svg>
                </button>
              </div>
            </div>

            <!-- Mobile menu, show/hide based on mobile menu state. -->
            <div x-show="mobileMenu" x-transition class="lg:hidden">
              <div class="fixed inset-0 z-20 bg-black bg-opacity-25" aria-hidden="true"></div>
              <div @click.away="mobileMenu = false"
                class="absolute top-0 right-0 z-30 w-full max-w-none origin-top transform p-2 transition">
                <div
                  class="divide-y divide-gray-200 dark:divide-gray-700 rounded-lg bg-white dark:bg-gray-800 shadow-lg ring-1 ring-black ring-opacity-5">
                  <div class="pt-3 pb-2">
                    <div class="flex items-center justify-between px-4">
                      <div>
                        <a href="{{ route('threads') }}">
                          <x-jet-authentication-card-logo />
                        </a>
                      </div>
                      <div class="-mr-2">
                        <button type="button" @click="mobileMenu = false"
                          class="inline-flex items-center justify-center rounded-md bg-white dark:bg-gray-900 p-2 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-700 hover:text-gray-500 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                          <span class="sr-only">{{ __('Close menu') }}</span>
                          <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                          </svg>
                        </button>
                      </div>
                    </div>
                    <div class="mt-3 space-y-1 px-2 text-gray-900 hover:text-gray-800 dark:text-gray-100 dark:hover:text-gray-200">
                      <a href="{{ route('threads') }}"
                        class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Home') }}</a>
                      @if ($settings['faqs'] ?? null)
                        <a href="{{ route('faqs.list') }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('FAQs') }}</a>
                      @endif

                      @if ($settings['knowledgebase'] ?? null)
                        <a href="{{ route('knowledgebase.list') }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Knowledge base') }}</a>
                      @endif

                      @if ($settings['articles'] ?? null)
                        <a href="{{ route('articles.list') }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Articles') }}</a>
                      @endif

                      @if ($logged_in_user?->can('review') && ($threads_require_review ?? null))
                        <a href="{{ route('threads', ['require_review' => 'yes']) }}"
                          class="text-yellow-700 dark:text-yellow-300 block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ shortNumber($threads_require_review) }} {{ __('Threads require review') }}
                        </a>
                      @endif
                      @if ($logged_in_user?->can('approve-threads') && ($threads_require_approval ?? null))
                        <a href="{{ route('threads', ['require_approval' => 'yes']) }}"
                          class="text-yellow-700 dark:text-yellow-300 block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ shortNumber($threads_require_approval) }} {{ __('Threads require approval') }}
                        </a>
                      @endif

                      <div class="p-2">
                        {{-- <div class="mt-4 mb-b border-t dark:border-gray-700"></div> --}}
                        {{-- <div class="p-2 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Categories') }}</div> --}}
                        @if (($settings['faqs'] ?? null) && request()->routeIs('faqs.*') && $faqCategoriesMenu)
                          <div class="rounded-md bg-white dark:bg-gray-900 shadow">
                            <div class="border-b dark:border-gray-700 rounded-t-md px-4 py-4 sm:px-6">
                              <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h3>
                            </div>
                            <div class="pt-2 pb-4">
                              <x-multi-menus :menus="$faqCategoriesMenu" route="faqs.list" />
                            </div>
                          </div>
                        @elseif (($settings['knowledgebase'] ?? null) && request()->routeIs('knowledgebase.*') && $kbCategoriesMenu)
                          <div class="rounded-md bg-white dark:bg-gray-900 shadow">
                            <div class="border-b dark:border-gray-700 rounded-t-md px-4 py-4 sm:px-6">
                              <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h3>
                            </div>
                            <div class="pt-2 pb-4">
                              <x-multi-menus :menus="$kbCategoriesMenu" route="knowledgebase.category" />
                            </div>
                          </div>
                        @elseif ($categoriesMenu)
                          <div class="rounded-md bg-white dark:bg-gray-900 shadow">
                            <div class="border-b dark:border-gray-700 rounded-t-md px-4 py-4 sm:px-6">
                              <h3 class="text-lg font-medium leading-6 text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h3>
                            </div>
                            <div class="pt-2 pb-4">
                              <x-multi-menus :menus="$categoriesMenu" route="threads" />
                            </div>
                          </div>
                        @endif
                      </div>

                      @if (
                          $logged_in_user?->canAny([
                              'settings',
                              'read-faqs',
                              'read-articles',
                              'read-roles',
                              'read-users',
                              'read-badges',
                              'read-categories',
                              'read-custom-fields',
                              'read-knowledgebase',
                          ]))
                        {{-- <div class="mt-4 mb-b border-t dark:border-gray-700"></div> --}}
                        <div class="p-2 text-sm font-medium text-gray-500 dark:text-gray-400">{{ __('Manage') }}</div>
                        @if (($settings['knowledgebase'] ?? false) && $logged_in_user?->can('read-knowledgebase'))
                          <a href="{{ route('knowledgebase') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Knowledge Base') }}</a>
                        @endif
                        @if (($settings['faqs'] ?? false) && $logged_in_user?->can('read-faqs'))
                          <a href="{{ route('faqs') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Frequently Asked Questions') }}</a>
                        @endif
                        @if (($settings['articles'] ?? false) && $logged_in_user?->can('read-articles'))
                          <a href="{{ route('articles') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Articles') }}</a>
                        @endif
                        @if ($logged_in_user?->can('read-badges'))
                          <a href="{{ route('badges') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Badges') }}</a>
                        @endif
                        @if ($logged_in_user?->can('read-categories'))
                          <a href="{{ route('categories') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Categories') }}</a>
                        @endif
                        @if ($logged_in_user?->can('read-custom-fields'))
                          <a href="{{ route('custom_fields') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Custom Fields') }}</a>
                        @endif
                        @if ($logged_in_user?->can('read-users'))
                          <a href="{{ route('users') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Users') }}</a>
                        @endif
                        @if ($logged_in_user?->can('read-roles'))
                          <a href="{{ route('roles') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Roles') }}</a>
                        @endif
                        @if ($logged_in_user?->can('settings'))
                          <a href="{{ route('settings.general') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700 ">{{ __('Settings') }}</a>
                        @endif
                      @endif
                    </div>
                  </div>

                  @auth
                    <div class="pt-4 pb-2">
                      <div class="flex items-center px-5">
                        @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                          <div class="flex-shrink-0 ltr:mr-3 rtl:ml-3">
                            <img class="h-10 w-10 rounded-full" src="{{ Auth::user()->profile_photo_url }}"
                              alt="{{ Auth::user()->name }}">
                          </div>
                        @endif

                        <div class="">
                          <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                          <div class="text-sm font-medium text-gray-500 dark:text-gray-400">{{ Auth::user()->email }}</div>
                        </div>

                        @if ($logged_in_user && $unreadCount)
                          <a href="{{ route('notifications') }}"
                            class="flex items-center justify-center rounded-full py-2 px-4 text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-800 hover:text-gray-500 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-blue-500">
                            <span class="text-sm font-bold ltr:mr-2 rtl:ml-2">{{ $unreadCount }}</span>
                            <span class="sr-only">{{ __('View notifications') }}</span>
                            <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                              stroke="currentColor" aria-hidden="true">
                              <path stroke-linecap="round" stroke-linejoin="round"
                                d="M14.857 17.082a23.848 23.848 0 005.454-1.31A8.967 8.967 0 0118 9.75v-.7V9A6 6 0 006 9v.75a8.967 8.967 0 01-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 01-5.714 0m5.714 0a3 3 0 11-5.714 0" />
                            </svg>
                          </a>
                        @endif
                      </div>

                      <div class="mt-3 space-y-1 px-2 text-gray-900 hover:text-gray-800 dark:text-gray-100 dark:hover:text-gray-200">
                        <a href="{{ route('threads.create') }}"
                          class="mt-6 block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ __('Add New Thread') }}
                        </a>
                        <a href="{{ route('threads', ['by' => $logged_in_user?->username]) }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ __('My Threads') }}
                        </a>
                        <a href="{{ route('threads', ['favorites_of' => $logged_in_user?->username]) }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ __('My Favorites') }}
                        </a>

                        <div class="my-2 border-t dark:border-gray-700"></div>
                        <a href="{{ route('profile.show') }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ __('Profile') }}
                        </a>

                        <a href="{{ route('extra.profile') }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ __('Extra Profile') }}
                        </a>

                        @if (!$logged_in_user?->disabled_messages)
                          <a href="{{ route('conversations') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                            {{ __('Messages') }}
                          </a>
                        @endif

                        @if ($logged_in_user?->can('invitations'))
                          <a href="{{ route('invitations') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                            {{ __('Invitations') }}
                          </a>
                        @endif

                        @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                          <a href="{{ route('api-tokens.index') }}"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                            {{ __('API Tokens') }}
                          </a>
                        @endif

                        <div class="my-2 border-t dark:border-gray-700"></div>
                        <form method="POST" action="{{ route('logout') }}" x-data>
                          @csrf
                          <a href="{{ route('logout') }}" @click.prevent="$root.submit();"
                            class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">{{ __('Log Out') }}</a>
                        </form>
                      </div>
                    </div>
                  @else
                    <div class="p-2">
                      <a href="{{ route('login') }}"
                        class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                        {{ __('Login') }}
                      </a>
                      @if (($settings['registration'] ?? null) == 1)
                        <a href="{{ route('register') }}"
                          class="block rounded-md px-3 py-2 text-base font-medium hover:bg-gray-100 dark:hover:bg-gray-700">
                          {{ __('Register') }}
                        </a>
                      @endif
                    </div>
                  @endauth
                </div>
              </div>
            </div>
          </div>

          <div x-data="{
              theme: window.localStorage.theme || 'system',
              toggleMode() {
                  this.theme = this.theme == 'light' ? 'dark' : 'light';
                  document.documentElement.setAttribute('data-theme', this.theme);
              }
          }" class="hidden lg:flex lg:items-center">
            @if (!($settings['theme'] ?? null))
              <button type="button" @click="toggleMode()"
                class="bg-gray-200 dark:bg-gray-700 relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:border-transparent focus:rounded-full focus:ring-0">
                <span class="sr-only">{{ __('Theme toggle') }}</span>
                <span
                  class="translate-x-0 {{ ($settings['rtl'] ?? null) == 1 ? 'dark:-translate-x-5' : 'dark:translate-x-5' }} pointer-events-none relative inline-block h-5 w-5 transform rounded-full bg-white dark:bg-black shadow ring-0 transition duration-200 ease-in-out">
                  <span :class="theme == 'dark' ? 'opacity-0 ease-out duration-100' : 'opacity-100 ease-in duration-200'"
                    class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                      class="h-3 w-3 text-yellow-500 dark:text-yellow-300">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                    </svg>
                  </span>

                  <span :class="theme == 'dark' ? 'opacity-100 ease-in duration-200' : 'opacity-0 ease-out duration-100'"
                    class="absolute inset-0 flex h-full w-full items-center justify-center transition-opacity">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                      class="h-3 w-3 text-blue-500 dark:text-blue-300">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                    </svg>
                  </span>
                </span>
              </button>
            @endif

            @auth
              @if (
                  $logged_in_user?->canAny([
                      'settings',
                      'read-faqs',
                      'read-articles',
                      'read-roles',
                      'read-users',
                      'read-badges',
                      'read-categories',
                      'read-custom-fields',
                      'read-knowledgebase',
                  ]))
                <div x-data="{ show: false }" class="relative ltr:ml-4 rtl:mr-4" @click.away="show = false">
                  <button type="button" @click="show = !show"
                    class="text-gray-700 dark:text-gray-300 group px-4 py-2 inline-flex items-center rounded-full font-medium hover:text-gray-900 dark:hover:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 hover:bg-gray-50 dark:hover:bg-gray-800 text-sm"
                    aria-expanded="false">
                    <span>{{ __('Manage') }}</span>
                    <svg
                      class="text-gray-400 ltr:ml-2 rtl:mr-2 h-5 w-5 transition duration-150 ease-in-out group-hover:text-gray-500 dark:group-hover:text-gray-300"
                      xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path fill-rule="evenodd"
                        d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                        clip-rule="evenodd" />
                    </svg>
                  </button>

                  <div x-show="show" style="display: none" x-transition:enter="transition ease-out duration-200"
                    x-transition:enter-start="opacity-0 translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
                    x-transition:leave="transition ease-in duration-150" x-transition:leave-start="opacity-100 translate-y-0"
                    x-transition:leave-end="opacity-0 translate-y-1"
                    class="absolute ltr:right-0 rtl:left-0 z-10 mt-3 w-screen max-w-md transform px-2 sm:px-0 lg:max-w-xl">
                    <div class="overflow-hidden rounded-lg shadow-lg border border-gray-200 dark:border-gray-700">
                      <div class="relative grid gap-6 bg-white dark:bg-gray-700 px-5 py-6 sm:gap-8 sm:p-8 lg:grid-cols-2">
                        @if ($logged_in_user?->can('read-categories'))
                          <a href="{{ route('categories') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Categories') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage forum categories') }}
                            </p>
                          </a>
                        @endif

                        @if (($settings['articles'] ?? null) && $logged_in_user?->can('read-articles'))
                          <a href="{{ route('articles') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Articles') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage site articles') }}</p>
                          </a>
                        @endif

                        @if (($settings['knowledgebase'] ?? null) && $logged_in_user?->can('read-knowledgebase'))
                          <a href="{{ route('knowledgebase') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Knowledge base') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage knowledge base & categories') }}</p>
                          </a>
                        @endif

                        @if (($settings['faqs'] ?? null) && $logged_in_user?->can('read-faqs'))
                          <a href="{{ route('faqs') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Frequently Asked Questions') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage FAQS & categories') }}</p>
                          </a>
                        @endif

                        @if ($logged_in_user?->can('read-badges'))
                          <a href="{{ route('badges') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Badges') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Add badges & assign to users') }}</p>
                          </a>
                        @endif

                        @if ($logged_in_user?->can('read-custom-fields'))
                          <a href="{{ route('custom_fields') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Custom Fields') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Add custom fields to site entities') }}</p>
                          </a>
                        @endif

                        @if ($logged_in_user?->can('read-users'))
                          <a href="{{ route('users') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Users') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage forum users') }}</p>
                          </a>
                        @endif

                        @if ($logged_in_user?->can('read-roles'))
                          <a href="{{ route('roles') }}"
                            class="-m-3 rounded-lg p-3 transition duration-150 ease-in-out hover:bg-gray-50 dark:hover:bg-gray-800">
                            <p class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Roles') }}</p>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">{{ __('Manage user roles') }}</p>
                          </a>
                        @endif
                      </div>
                      @if ($logged_in_user?->can('settings'))
                        <div class="bg-gray-50 dark:bg-gray-800 p-5 sm:p-8">
                          <a href="{{ route('settings.general') }}"
                            class="-m-3 flow-root rounded-md p-3 transition duration-150 ease-in-out hover:bg-gray-100 dark:hover:bg-gray-900">
                            <span class="text-base font-medium text-gray-900 dark:text-gray-100">{{ __('Settings') }}</span>
                            <span class="mt-1 block text-sm text-gray-500 dark:text-gray-400">
                              {{ __('Set icon, logo and manage all other forum settings') }}
                            </span>
                          </a>
                        </div>
                      @endif
                    </div>
                  </div>
                </div>
              @endif

              <!-- Profile dropdown -->
              <div class="relative ltr:ml-4 rtl:mr-4 flex-shrink-0">

                <x-jet-dropdown align="{{ ($settings['rtl'] ?? null) == 1 ? 'left' : 'right' }}" width="48">
                  <x-slot name="trigger">
                    <button type="button"
                      class="flex items-center rounded-full px-4 p-2 bg-white dark:bg-gray-900 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 hover:bg-gray-50 dark:hover:bg-gray-800"
                      id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                      <span class="sr-only">{{ __('Open user menu') }}</span>

                      @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                        <img class="h-8 w-8 rounded-full object-cover ltr:mr-2 rtl:ml-2 ltr:-ml-1.5 rtl:-mr-1.5"
                          src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                      @endif

                      {{ Auth::user()->name }}

                      <svg class="ltr:ml-2 ltr:-mr-0.5 rtl:mr-2 rtl:-ml-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"
                        fill="currentColor">
                        <path fill-rule="evenodd"
                          d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                          clip-rule="evenodd" />
                      </svg>

                    </button>
                  </x-slot>

                  <x-slot name="content">
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      {{ __('Threads') }}
                    </div>

                    <x-jet-dropdown-link href="{{ route('threads.create') }}">
                      {{ __('Add New Thread') }}
                    </x-jet-dropdown-link>

                    <x-jet-dropdown-link href="{{ route('threads', ['by' => $logged_in_user?->username]) }}">
                      {{ __('My Threads') }}
                    </x-jet-dropdown-link>

                    <x-jet-dropdown-link href="{{ route('threads', ['favorites_of' => $logged_in_user?->username]) }}">
                      {{ __('My Favorites') }}
                    </x-jet-dropdown-link>

                    <div class="border-t border-gray-100 dark:border-gray-800"></div>
                    <div class="block px-4 py-2 text-xs text-gray-400">
                      {{ __('Manage Account') }}
                    </div>

                    <x-jet-dropdown-link href="{{ route('profile.show') }}">
                      {{ __('Profile') }}
                    </x-jet-dropdown-link>

                    <x-jet-dropdown-link href="{{ route('extra.profile') }}">
                      {{ __('Extra Profile') }}
                    </x-jet-dropdown-link>

                    @if (!$logged_in_user?->disabled_messages)
                      <x-jet-dropdown-link href="{{ route('conversations') }}">
                        {{ __('Messages') }}
                      </x-jet-dropdown-link>
                    @endif

                    @if ($logged_in_user?->can('invitations'))
                      <x-jet-dropdown-link href="{{ route('invitations') }}">
                        {{ __('Invitations') }}
                      </x-jet-dropdown-link>
                    @endif

                    @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                      <x-jet-dropdown-link href="{{ route('api-tokens.index') }}">
                        {{ __('API Tokens') }}
                      </x-jet-dropdown-link>
                    @endif

                    <div class="border-t border-gray-100 dark:border-gray-800"></div>
                    <form method="POST" action="{{ route('logout') }}" x-data>
                      @csrf

                      <x-jet-dropdown-link href="{{ route('logout') }}" @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                      </x-jet-dropdown-link>
                    </form>
                  </x-slot>
                </x-jet-dropdown>
              </div>
            @else
              <span class="ml-4 flex items-center gap-2">
                @if (($settings['registration'] ?? null) == 1)
                  <a href="{{ route('register') }}"
                    class="relative inline-flex items-center rounded-md bg-white dark:bg-gray-800 px-4 py-2 text-sm font-medium text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-700 focus:z-10 focus:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-primary-500">{{ __('Register') }}</a>
                @endif
                <a href="{{ route('login') }}"
                  class="relative -ml-px inline-flex items-center rounded-md bg-blue-600 px-4 py-2 text-sm font-medium text-white hover:bg-blue-700 focus:z-10 focus:bg-blue-500 focus:outline-none focus:ring-1 focus:ring-primary-500">{{ __('Login') }}</a>
              </span>
            @endauth
          </div>
        </div>
      </div>
    </div>

    <div x-show="searching" class="relative z-10 flex items-center justify-center" style="display: none">
      <div x-show="searching" class="fixed inset-0 bg-gray-500 bg-opacity-25 backdrop-blur-sm transition-opacity" x-show="open"
        x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
        x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
        @click="closeSearchConsole()">
      </div>

      <button x-show="searching" type="button" class="fixed top-2 right-2 md:top-4 md:right-4 z-20 text-xs font-bold flex items-center"
        @click="closeSearchConsole()">
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="w-6 h-6">
          <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
        </svg> <span class="hidden sm:block text-gray-500">(<span class="mx-px">ESC</span>)</span>
      </button>
      <div @keyup.escape="closeSearchConsole()" :class="result && result.length ? 'py-10 md:py-12' : 'py-10 md:py-20'"
        class="fixed inset-0 z-10 overflow-y-auto px-4 sm:px-6 flex items-start justify-center">
        @if ($settings['search_backdrop'] ?? false)
          <div x-show="searching" class="fixed inset-0 bg-gray-500 bg-opacity-25 backdrop-blur-sm transition-opacity" x-show="open"
            x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="ease-in duration-300" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0"
            @click="closeSearchConsole()"></div>
        @endif
        <div x-show="searching" x-transition
          class="mx-auto max-w-xl w-full transform divide-y divide-gray-100 dark:divide-gray-700 overflow-hidden rounded-xl bg-white dark:bg-gray-900 shadow-2xl ring-1 ring-black ring-opacity-5 transition-all">
          <form class="relative">
            <svg class="pointer-events-none absolute top-3.5 left-4 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor"
              aria-hidden="true">
              <path fill-rule="evenodd"
                d="M9 3.5a5.5 5.5 0 100 11 5.5 5.5 0 000-11zM2 9a7 7 0 1112.452 4.391l3.328 3.329a.75.75 0 11-1.06 1.06l-3.329-3.328A7 7 0 012 9z"
                clip-rule="evenodd" />
            </svg>
            <input type="text" id="search-console" x-model.debounce.500ms="search" autocomplete="off"
              class="h-12 w-full border-0 bg-transparent px-11 focus:ring-0 sm:text-sm" placeholder="{{ _('Search') }}...">
            <svg x-show="showAnimation" width="38" height="38" viewBox="0 0 38 38" xmlns="http://www.w3.org/2000/svg"
              class="pointer-events-none absolute top-3.5 right-4 h-5 w-5 text-gray-400" stroke="currentColor">
              <g fill="none" fill-rule="evenodd">
                <g transform="translate(1 1)" stroke-width="2">
                  <circle stroke-opacity=".5" cx="18" cy="18" r="18" />
                  <path d="M36 18c0-9.94-8.06-18-18-18">
                    <animateTransform attributeName="transform" type="rotate" from="0 18 18" to="360 18 18" dur="1s"
                      repeatCount="indefinite" />
                  </path>
                </g>
              </g>
            </svg>
          </form>

          <ul x-show="result.length" class="scroll-py-2 overflow-y-auto py-2 text-sm" style="max-height: calc(100vh - 150px)">
            <template x-for="(res, index) in result" :key="index">
              <li>
                <a x-bind:href="res.url"
                  class="w-full select-none px-4 py-2 hover:bg-primary-600 hover:text-white flex items-center justify-between">
                  <span class="grow truncate mr-3" x-text="res.title"></span>
                  <span class="inline-flex items-center rounded-full bg-primary-100 px-2.5 py-0.5 text-xs font-medium text-primary-800"
                    x-text="res.type == 'threads' ? '{{ __('Thread') }}' : (res.type == 'faqs' ? '{{ __('FAQ') }}' : (res.type == 'knowledge_base' ? '{{ __('KB') }}' : res.type))"></span>
                </a>
              </li>
            </template>
          </ul>

          <p class="p-4 text-sm text-gray-500"
            x-show="search != '' && search.length >= '{{ $settings['search_length'] ?? 2 }}' && !result.length">
            {{ __('No result found.') }}</p>
        </div>
      </div>
    </div>
  @endif
</header>
