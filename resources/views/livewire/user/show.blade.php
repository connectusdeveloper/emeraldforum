<x-slot name="title">
  {{ __('Profile of :user ', ['user' => $user->displayName]) }}
</x-slot>
<x-slot name="metaTags">
  <meta name="description" content="{{ $user->meta['signature'] ?? '' }}">
</x-slot>

<div class="flex flex-col gap-6">
  <section aria-labelledby="profile-overview-title">
    <div class="overflow-hidden sm:rounded-lg bg-white dark:bg-gray-900 shadow">
      <h2 class="sr-only" id="profile-overview-title">{{ __('Profile Overview') }}</h2>
      <div class="bg-white dark:bg-gray-900 p-6">
        <div class="sm:flex sm:items-start sm:justify-between">
          <div class="w-full sm:flex sm:items-start sm:justify-between sm:space-x-5">
            <div class="flex-shrink-0">
              <img class="mx-auto h-16 w-16 object-cover rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->displayName }}">
            </div>
            <div class="flex-1 mt-4 text-center sm:mt-0 sm:pt-1 sm:text-left">
              {{-- <p class="text-sm font-medium text-gray-600"></p> --}}
              <div class="flex-1 min-w-max ltr:mr-4 rtl:ml-4">
                <p class="text-xl font-bold text-gray-900 dark:text-gray-100 sm:text-2xl">{{ $user->displayName }}</p>
                <p class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ '@' . $user->username }}</p>
                @if ($user->meta_data['bio'] ?? null)
                  <p class="mt-2 text-sm font-medium text-gray-600 dark:text-gray-400">{{ $user->meta_data['bio'] }}</p>
                @endif
              </div>
              <div class="mt-4 flex justify-center sm:justify-start gap-2">
                @forelse ($user->badges as $badge)
                  @if ($badge->image)
                    <img class="inline-block h-8 w-8 rounded-full shadow shadow-gray-300 dark:shadow-gray-700"
                      src="{{ storage_url($badge->image) }}" alt="{{ $badge->name }}" x-data x-tooltip.raw="{{ $badge->name }}">
                  @elseif ($badge->css_class)
                    <div
                      class="inline-block h-8 w-8 rounded-full shadow shadow-gray-300 dark:shadow-gray-700 flex-shrink-0 {{ $badge->css_class }}"
                      x-data x-tooltip.raw="{{ $badge->name }}"></div>
                  @endif
                @empty
                @endforelse
              </div>
            </div>
            <div class="flex flex-col gap-2 ltr:justify-end rtl:justify-start">
              <div class="flex justify-center ltr:sm:justify-end rtl:sm:justify-start gap-1 text-sm font-medium">
                <span class="text-gray-600 dark:text-gray-400">{{ __('Member since') }}</span>
                <span class="">{{ $user->created_at->diffForHumans() }}</span>
              </div>
              <div class="flex justify-center ltr:sm:justify-end rtl:sm:justify-start gap-1 text-sm font-medium">
                <span class="text-gray-600 dark:text-gray-400">{{ __('Created') }}</span>
                <span class="">{{ shortNumber($user->threads()->count()) }} {{ __('Threads') }}</span>
              </div>
              <div class="flex justify-center ltr:sm:justify-end rtl:sm:justify-start gap-1 text-sm font-medium">
                <span class="text-gray-600 dark:text-gray-400">{{ __('Posted') }}</span>
                <span class="">{{ shortNumber($user->replies()->count()) }} {{ __('Replies') }}</span>
              </div>
              @if (
                  ($user->meta_data['facebook_link'] ?? null) ||
                      ($user->meta_data['instagram_link'] ?? null) ||
                      ($user->meta_data['twitter_link'] ?? null) ||
                      ($user->meta_data['linkedin_link'] ?? null) ||
                      ($user->meta_data['github_link'] ?? null) ||
                      ($user->meta_data['dribbble_link'] ?? null))
                <div class="mt-4 flex flex-wrap items-center gap-x-4 gap-y-2">
                  @if ($user->meta_data['facebook_link'] ?? null)
                    <a target="_blank" href="{{ $user->meta_data['facebook_link'] }}" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Facebook</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                          d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                          clip-rule="evenodd" />
                      </svg>
                    </a>
                  @endif

                  @if ($user->meta_data['instagram_link'] ?? null)
                    <a target="_blank" href="{{ $user->meta_data['instagram_link'] }}" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Instagram</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                          d="M12.315 2c2.43 0 2.784.013 3.808.06 1.064.049 1.791.218 2.427.465a4.902 4.902 0 011.772 1.153 4.902 4.902 0 011.153 1.772c.247.636.416 1.363.465 2.427.048 1.067.06 1.407.06 4.123v.08c0 2.643-.012 2.987-.06 4.043-.049 1.064-.218 1.791-.465 2.427a4.902 4.902 0 01-1.153 1.772 4.902 4.902 0 01-1.772 1.153c-.636.247-1.363.416-2.427.465-1.067.048-1.407.06-4.123.06h-.08c-2.643 0-2.987-.012-4.043-.06-1.064-.049-1.791-.218-2.427-.465a4.902 4.902 0 01-1.772-1.153 4.902 4.902 0 01-1.153-1.772c-.247-.636-.416-1.363-.465-2.427-.047-1.024-.06-1.379-.06-3.808v-.63c0-2.43.013-2.784.06-3.808.049-1.064.218-1.791.465-2.427a4.902 4.902 0 011.153-1.772A4.902 4.902 0 015.45 2.525c.636-.247 1.363-.416 2.427-.465C8.901 2.013 9.256 2 11.685 2h.63zm-.081 1.802h-.468c-2.456 0-2.784.011-3.807.058-.975.045-1.504.207-1.857.344-.467.182-.8.398-1.15.748-.35.35-.566.683-.748 1.15-.137.353-.3.882-.344 1.857-.047 1.023-.058 1.351-.058 3.807v.468c0 2.456.011 2.784.058 3.807.045.975.207 1.504.344 1.857.182.466.399.8.748 1.15.35.35.683.566 1.15.748.353.137.882.3 1.857.344 1.054.048 1.37.058 4.041.058h.08c2.597 0 2.917-.01 3.96-.058.976-.045 1.505-.207 1.858-.344.466-.182.8-.398 1.15-.748.35-.35.566-.683.748-1.15.137-.353.3-.882.344-1.857.048-1.055.058-1.37.058-4.041v-.08c0-2.597-.01-2.917-.058-3.96-.045-.976-.207-1.505-.344-1.858a3.097 3.097 0 00-.748-1.15 3.098 3.098 0 00-1.15-.748c-.353-.137-.882-.3-1.857-.344-1.023-.047-1.351-.058-3.807-.058zM12 6.865a5.135 5.135 0 110 10.27 5.135 5.135 0 010-10.27zm0 1.802a3.333 3.333 0 100 6.666 3.333 3.333 0 000-6.666zm5.338-3.205a1.2 1.2 0 110 2.4 1.2 1.2 0 010-2.4z"
                          clip-rule="evenodd" />
                      </svg>
                    </a>
                  @endif

                  @if ($user->meta_data['twitter_link'] ?? null)
                    <a target="_blank" href="{{ $user->meta_data['twitter_link'] }}" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Twitter</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path
                          d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
                      </svg>
                    </a>
                  @endif

                  @if ($user->meta_data['linkedin_link'] ?? null)
                    <a target="_blank" href="{{ $user->meta_data['linkedin_link'] }}" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">LinkedIn</span>
                      <svg class="h-5 w-5" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 310 310">
                        <g>
                          <path
                            d="M72.16,99.73H9.927c-2.762,0-5,2.239-5,5v199.928c0,2.762,2.238,5,5,5H72.16c2.762,0,5-2.238,5-5V104.73 C77.16,101.969,74.922,99.73,72.16,99.73z" />
                          <path
                            d="M41.066,0.341C18.422,0.341,0,18.743,0,41.362C0,63.991,18.422,82.4,41.066,82.4 c22.626,0,41.033-18.41,41.033-41.038C82.1,18.743,63.692,0.341,41.066,0.341z" />
                          <path
                            d="M230.454,94.761c-24.995,0-43.472,10.745-54.679,22.954V104.73c0-2.761-2.238-5-5-5h-59.599 c-2.762,0-5,2.239-5,5v199.928c0,2.762,2.238,5,5,5h62.097c2.762,0,5-2.238,5-5v-98.918c0-33.333,9.054-46.319,32.29-46.319 c25.306,0,27.317,20.818,27.317,48.034v97.204c0,2.762,2.238,5,5,5H305c2.762,0,5-2.238,5-5V194.995 C310,145.43,300.549,94.761,230.454,94.761z" />
                        </g>
                      </svg>
                    </a>
                  @endif

                  @if ($user->meta_data['github_link'] ?? null)
                    <a target="_blank" href="{{ $user->meta_data['github_link'] }}" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">GitHub</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                          d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                          clip-rule="evenodd" />
                      </svg>
                    </a>
                  @endif

                  @if ($user->meta_data['dribbble_link'] ?? null)
                    <a target="_blank" href="{{ $user->meta_data['dribbble_link'] }}" class="text-gray-400 hover:text-gray-500">
                      <span class="sr-only">Dribbble</span>
                      <svg class="h-6 w-6" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path fill-rule="evenodd"
                          d="M12 2C6.48 2 2 6.48 2 12s4.48 10 10 10c5.51 0 10-4.48 10-10S17.51 2 12 2zm6.605 4.61a8.502 8.502 0 011.93 5.314c-.281-.054-3.101-.629-5.943-.271-.065-.141-.12-.293-.184-.445a25.416 25.416 0 00-.564-1.236c3.145-1.28 4.577-3.124 4.761-3.362zM12 3.475c2.17 0 4.154.813 5.662 2.148-.152.216-1.443 1.941-4.48 3.08-1.399-2.57-2.95-4.675-3.189-5A8.687 8.687 0 0112 3.475zm-3.633.803a53.896 53.896 0 013.167 4.935c-3.992 1.063-7.517 1.04-7.896 1.04a8.581 8.581 0 014.729-5.975zM3.453 12.01v-.26c.37.01 4.512.065 8.775-1.215.25.477.477.965.694 1.453-.109.033-.228.065-.336.098-4.404 1.42-6.747 5.303-6.942 5.629a8.522 8.522 0 01-2.19-5.705zM12 20.547a8.482 8.482 0 01-5.239-1.8c.152-.315 1.888-3.656 6.703-5.337.022-.01.033-.01.054-.022a35.318 35.318 0 011.823 6.475 8.4 8.4 0 01-3.341.684zm4.761-1.465c-.086-.52-.542-3.015-1.659-6.084 2.679-.423 5.022.271 5.314.369a8.468 8.468 0 01-3.655 5.715z"
                          clip-rule="evenodd" />
                      </svg>
                    </a>
                  @endif
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>

      <div class="border-t dark:border-gray-700 bg-gray-50 dark:bg-black/50 flex justify-between flex-wrap p-4 gap-x-4 gap-y-3">
        <div class="flex justify-center w-full xl:w-auto flex-wrap gap-x-4 gap-y-3">
          <x-link href="{{ route('threads', ['by' => $user->username]) }}">
            {{ __('Threads') }}
          </x-link>
          <x-link href="{{ route('threads', ['favorites_of' => $user->username]) }}">
            {{ __('Favorites') }}
          </x-link>
          @if ($logged_in_user && !($user->meta_data['disable_messages'] ?? null) && $logged_in_user->id != $user->id)
            <x-button primary type="button" class="self-stretch rounded-md"
              @click="$dispatch('show-message-modal', { user: '{{ $user->toJson() }}' })">
              {{ __('Message') }}
            </x-button>
          @endif
        </div>
        <div class="flex justify-center w-full xl:w-auto flex-wrap gap-x-4 gap-y-3">
          @if ($logged_in_user && $logged_in_user?->id != $user->id)
            @livewire('forum.follow', ['user' => $user])
          @endif
          <x-link href="{{ route('followers', $user->username) }}">
            {{ $user->followers_count ?? 0 }} {{ __('Followers') }}
          </x-link>
          <x-link href="{{ route('followings', $user->username) }}">
            {{ $user->followings_count ?? 0 }} {{ __('Followings') }}
          </x-link>
        </div>
      </div>
    </div>
  </section>

  <section aria-labelledby="timeline-title" class="lg:col-span-1 lg:col-start-3">
    <div class="bg-white dark:bg-gray-900 px-4 py-5 overflow-hidden shadow sm:rounded-lg sm:px-6">
      <h2 id="timeline-title" class="text-lg font-medium text-gray-900 dark:text-gray-100">{{ __('Timeline') }}</h2>

      <!-- Activity Feed -->
      <div class="mt-6 flow-root">
        <ul role="list" class="-mb-8">
          @forelse ($activities as $activity)
            <li>
              <div class="relative pb-8">
                <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200 dark:bg-gray-700" aria-hidden="true"></span>
                <div class="relative flex space-x-3">
                  <div>
                    <span @class([
                        'h-8 w-8 rounded-full  flex items-center justify-center',
                        'bg-gray-500' =>
                            $activity->event == 'replied' || $activity->event == 'updated-reply',
                        'bg-orange-500' => $activity->event == 'flagged',
                        'bg-primary-500' => $activity->event == 'liked',
                        'bg-orange-500' => $activity->event == 'disliked',
                        'bg-negative-500' => $activity->event == 'deleted',
                        'bg-green-500' =>
                            $activity->event == 'created' ||
                            $activity->event == 'favorited' ||
                            $activity->event == 'accepted-answer',
                    ])>
                      @if ($activity->event == 'created')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-5 h-5 text-white">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                      @elseif ($activity->event == 'replied' || $activity->event == 'updated-reply')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-5 h-5 text-white">
                          <path stroke-linecap="round" stroke-linejoin="round" d="M9 15L3 9m0 0l6-6M3 9h12a6 6 0 010 12h-3" />
                        </svg>
                      @elseif ($activity->event == 'accepted-answer')
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6 text-white">
                          <path fill-rule="evenodd"
                            d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                            clip-rule="evenodd" />
                        </svg>
                      @elseif ($activity->event == 'favorited')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-5 h-5 text-white">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                        </svg>
                      @elseif ($activity->event == 'liked')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-5 h-5 text-white">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                        </svg>
                      @elseif ($activity->event == 'disliked')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-5 h-5 text-white">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M7.5 15h2.25m8.024-9.75c.011.05.028.1.052.148.591 1.2.924 2.55.924 3.977a8.96 8.96 0 01-.999 4.125m.023-8.25c-.076-.365.183-.75.575-.75h.908c.889 0 1.713.518 1.972 1.368.339 1.11.521 2.287.521 3.507 0 1.553-.295 3.036-.831 4.398C20.613 14.547 19.833 15 19 15h-1.053c-.472 0-.745-.556-.5-.96a8.95 8.95 0 00.303-.54m.023-8.25H16.48a4.5 4.5 0 01-1.423-.23l-3.114-1.04a4.5 4.5 0 00-1.423-.23H6.504c-.618 0-1.217.247-1.605.729A11.95 11.95 0 002.25 12c0 .434.023.863.068 1.285C2.427 14.306 3.346 15 4.372 15h3.126c.618 0 .991.724.725 1.282A7.471 7.471 0 007.5 19.5a2.25 2.25 0 002.25 2.25.75.75 0 00.75-.75v-.633c0-.573.11-1.14.322-1.672.304-.76.93-1.33 1.653-1.715a9.04 9.04 0 002.86-2.4c.498-.634 1.226-1.08 2.032-1.08h.384" />
                        </svg>
                      @elseif ($activity->event == 'deleted')
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                          stroke="currentColor" class="w-5 h-5 text-white">
                          <path stroke-linecap="round" stroke-linejoin="round"
                            d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                        </svg>
                      @endif
                    </span>
                  </div>
                  <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                    <div>
                      <p class="text-sm text-gray-500 dark:text-gray-400">
                        @if ($activity->event == 'deleted')
                        @elseif ($activity->event == 'replied' || $activity->event == 'updated-reply')
                          {{ $activity->description }}
                          @if ($activity->subject?->thread?->slug)
                            <a href="{{ route('threads.show', $activity->subject->thread->slug) }}"
                              class="font-medium text-gray-900 dark:text-gray-100">
                              {{ $activity->subject->thread->title }}
                            </a>
                          @endif
                        @else
                          {{ $activity->description }}
                          @if ($activity->subject?->slug)
                            <a href="{{ route('threads.show', $activity->subject->slug) }}"
                              class="font-medium text-gray-900 dark:text-gray-100">
                              {{ $activity->subject->title }}
                            </a>
                          @endif
                        @endif
                      </p>
                    </div>
                    <div class="whitespace-nowrap text-right text-sm text-gray-500 dark:text-gray-400">
                      <time datetime="2020-09-20">{{ $activity->created_at->diffForHumans() }}</time>
                    </div>
                  </div>
                </div>
                @if ($activity->event == 'deleted')
                @elseif ($activity->event == 'replied' || $activity->event == 'updated-reply')
                  @if ($activity->subject?->thread?->slug)
                    <a href="{{ route('threads.show', $activity->subject->thread->slug) }}"
                      class="block ml-12 mt-4 border dark:border-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 dark:hover:bg-black/20">
                      <h4 class="font-bold">{{ $activity->subject->thread->title }}</h4>
                      <p>{{ $activity->subject->thread->description }}</p>
                    </a>
                  @endif
                @elseif($activity->subject?->slug)
                  <a href="{{ route('threads.show', $activity->subject->slug) }}"
                    class="block ml-12 mt-4 border dark:border-gray-700 px-4 py-2 rounded-md hover:bg-gray-50 dark:hover:bg-black/20">
                    <h4 class="font-bold">{{ $activity->subject->title }}</h4>
                    <p>{{ $activity->subject->description }}</p>
                  </a>
                @endif
              </div>
            </li>
          @empty
          @endforelse
        </ul>
        @if ($activities->hasPages())
          <div class="mt-6"></div>
          <div class="relative w-full sm:rounded-lg bg-white dark:bg-gray-900 min-w-full -mt-6 pt-6">
            {{ $activities->links() }}
          </div>
        @endif
      </div>
    </div>
  </section>
</div>
