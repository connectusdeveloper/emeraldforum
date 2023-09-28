<x-app-layout :showAd="true">
  @isset($title)
    <x-slot name="title">{{ $title }}</x-slot>
  @endisset
  @isset($metaTags)
    <x-slot name="metaTags">{{ $metaTags }}</x-slot>
  @endisset
  @isset($header)
    <x-slot name="header">{{ $header }}</x-slot>
  @endisset

  <div class="mx-auto max-w-3xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-12 lg:gap-8 lg:px-8">
    <main class="col-span-12 lg:col-span-8">
      {{ $slot }}
    </main>
    <aside class="hidden lg:col-span-4 lg:block">
      <div class="{{ $settings['sticky_sidebar'] ?? null ? 'sticky top-6' : '' }}  space-y-6">
        <div class="flex flex-col gap-6">
          @auth
            <a href="{{ route('threads.create') }}"
              class="flex items-center justify-between px-6 py-3.5 rounded-lg bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-700 group shadow">
              <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('Add New Thread') }}</h2>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6 text-gray-400 dark:text-gray-600 dark:group-hover:text-gray-300">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v6m3-3H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z" />
              </svg>
            </a>
            <a href="{{ route('threads', ['by' => $logged_in_user?->username]) }}"
              class="flex items-center justify-between px-6 py-3.5 rounded-lg bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-700 group shadow">
              <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('My Threads') }}</h2>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6 text-gray-400 dark:text-gray-600 dark:group-hover:text-gray-300">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 01-.825-.242m9.345-8.334a2.126 2.126 0 00-.476-.095 48.64 48.64 0 00-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0011.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155" />
              </svg>
            </a>

            <a href="{{ route('threads', ['favorites_of' => $logged_in_user?->username]) }}"
              class="flex items-center justify-between px-6 py-3.5 rounded-lg bg-white dark:bg-gray-900 hover:bg-gray-50 dark:hover:bg-gray-700 group shadow">
              <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('My Favorites') }}</h2>
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-6 h-6 text-gray-400 dark:text-gray-600 dark:group-hover:text-gray-300">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
              </svg>
            </a>
          @endauth

          @if ($settings['sidebar_ad_code'] ?? null)
            {{-- <div class="h-80"></div> --}}
            {{ str($settings['sidebar_ad_code'])->toHtmlString() }}
          @endif

          @if (request()->routeIs('faqs.*') && $faqCategoriesMenu)
            <div class="rounded-md bg-white dark:bg-gray-900 shadow">
              <div class="border-b dark:border-gray-700 rounded-t-md px-4 py-4 sm:px-6">
                <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h3>
              </div>
              <div class="pt-2 pb-4">
                <x-multi-menus :menus="$faqCategoriesMenu" route="faqs.list" />
              </div>
            </div>
          @elseif (request()->routeIs('knowledgebase.*') && $kbCategoriesMenu)
            <div class="rounded-md bg-white dark:bg-gray-900 shadow">
              <div class="border-b dark:border-gray-700 rounded-t-md px-4 py-4 sm:px-6">
                <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h3>
              </div>
              <div class="pt-2 pb-4">
                <x-multi-menus :menus="$kbCategoriesMenu" route="knowledgebase.category" />
              </div>
            </div>
          @elseif ($categoriesMenu)
            <div class="rounded-md bg-white dark:bg-gray-900 shadow">
              <div class="border-b dark:border-gray-700 rounded-t-md px-4 py-4 sm:px-6">
                <h3 class="text-lg font-bold leading-6 text-gray-900 dark:text-gray-100">{{ __('Categories') }}</h3>
              </div>
              <div class="pt-2 pb-4">
                <x-multi-menus :menus="$categoriesMenu" route="threads" />
              </div>
            </div>
          @endif

          @if ($settings['tags_cloud'] ?? null)
            <section>
              <div class="rounded-lg bg-white dark:bg-gray-900 shadow p-6">
                <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">{{ _('Tags') }}
                </h2>
                <div class="mt-6 flow-root">
                  <x-tags-cloud :tagsCloud="$tagsCloud" />
                </div>
              </div>
            </section>
          @endif

          @if (
              $top_users->isNotEmpty() &&
                  !request()->routeIs('faqs.*') &&
                  !request()->routeIs('knowledgebase.*') &&
                  !request()->routeIs('articles.*'))
            @if ($settings['top_members'] ?? false)
              <section>
                <div class="rounded-lg bg-white dark:bg-gray-900 shadow">
                  <div class="p-6">
                    <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">{{ _('Who to follow') }}
                    </h2>
                    <div class="mt-6 flow-root">
                      <ul role="list" class="-my-4 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($top_users as $top_user)
                          <li class="flex items-center gap-x-3 py-4">
                            <a class="flex-shrink-0" href="{{ route('users.show', $top_user->username) }}">
                              <img class="h-8 w-8 rounded-full" src="{{ $top_user->profile_photo_url }}" alt="">
                            </a>
                            <div class="min-w-0 flex-1">
                              <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
                                <a href="{{ route('users.show', $top_user->username) }}">{{ $top_user->displayName }}</a>
                              </p>
                              <p class="text-sm text-gray-500 dark:text-gray-400">
                                <a href="{{ route('users.show', $top_user->username) }}"
                                  dir="ltr">{{ '@' . $top_user->username }}</a>
                              </p>
                            </div>
                            <div class="flex-shrink-0">
                              @livewire('forum.follow', ['user' => $top_user])
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                    @if ($settings['member_page'] ?? null)
                      <div class="mt-6">
                        <a href="{{ route('members') }}"
                          class="block w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-center text-sm font-bold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                          {{ __('View all') }}
                        </a>
                      </div>
                    @endif
                  </div>
                </div>
              </section>
            @endif
            @if ($trending_threads->isNotEmpty() && ($settings['trending_threads'] ?? false))
              <section>
                <div class="rounded-lg bg-white dark:bg-gray-900 shadow">
                  <div class="p-6">
                    <h2 class="text-base font-bold text-gray-900 dark:text-gray-100">{{ __('Trending') }}</h2>
                    <div class="mt-6 flow-root">
                      <ul role="list" class="-my-4 divide-y divide-gray-200 dark:divide-gray-700">
                        @foreach ($trending_threads as $tt)
                          <li class="flex gap-x-3 py-4">
                            <a href="{{ route('users.show', $tt->user->username) }}" class="flex-shrink-0">
                              <img class="h-8 w-8 rounded-full" src="{{ $tt->user->profile_photo_url }}"
                                alt="{{ $tt->user->displayName }}">
                            </a>
                            <div class="min-w-0 flex-1">
                              <a href="{{ route('threads.show', $tt->slug) }}" class="text-sm text-gray-800 dark:text-gray-200">
                                <div class="font-bold line-clamp-2">{{ $tt->title }}</div>
                                <div class="text-xs line-clamp-2">{{ $tt->description }}</div>
                              </a>
                              <div class="mt-4 flex gap-x-6 gap-y-2">
                                <span class="inline-flex items-center text-sm">
                                  <a href="{{ route('threads.show', $tt->slug) }}" x-data x-tooltip.raw="{{ __('Replies') }}"
                                    class="inline-flex space-x-2 text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                      aria-hidden="true">
                                      <path fill-rule="evenodd"
                                        d="M10 2c-2.236 0-4.43.18-6.57.524C1.993 2.755 1 4.014 1 5.426v5.148c0 1.413.993 2.67 2.43 2.902.848.137 1.705.248 2.57.331v3.443a.75.75 0 001.28.53l3.58-3.579a.78.78 0 01.527-.224 41.202 41.202 0 005.183-.5c1.437-.232 2.43-1.49 2.43-2.903V5.426c0-1.413-.993-2.67-2.43-2.902A41.289 41.289 0 0010 2zm0 7a1 1 0 100-2 1 1 0 000 2zM8 8a1 1 0 11-2 0 1 1 0 012 0zm5 1a1 1 0 100-2 1 1 0 000 2z"
                                        clip-rule="evenodd" />
                                    </svg>
                                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ shortNumber($tt->replies_count) }}</span>
                                  </a>
                                </span>
                                <span class="inline-flex items-center text-sm">
                                  <a href="{{ route('threads.show', $tt->slug) }}" x-data x-tooltip.raw="{{ __('Views') }}"
                                    class="inline-flex space-x-2 text-gray-400 hover:text-gray-500 dark:text-gray-400 dark:hover:text-gray-300">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                      stroke="currentColor" class="w-5 h-5">
                                      <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
                                      <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <span class="font-bold text-gray-900 dark:text-gray-100">{{ shortNumber($tt->views) }}</span>
                                  </a>
                                </span>
                              </div>
                            </div>
                          </li>
                        @endforeach
                      </ul>
                    </div>
                    <div class="mt-6">
                      <a href="{{ route('threads', ['trending' => 'yes']) }}"
                        class="block w-full rounded-md border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 px-4 py-2 text-center text-sm font-bold text-gray-700 dark:text-gray-300 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700">
                        {{ __('View all') }}
                      </a>
                    </div>
                  </div>
                </div>
              </section>
            @endif
          @endif
        </div>
    </aside>
  </div>
</x-app-layout>
