@props(['thread', 'sharePosition' => 'md:ltr:right-0 md:rtl:left-0 bottom-full mb-2'])

@php
  $socialLink = new \SocialLinks\Page([
      'url' => route('threads.show', $thread->slug),
      'title' => $thread->title,
      'text' => $thread->description,
      // 'image' => $thread->image,
      // 'icon' => $settings['icon'] ?? '',
      // 'twitterUser' => '@twitterUser',
  ]);
@endphp

<div class="mt-6 w-full flex flex-wrap justify-between sm:gap-x-8 gap-y-2 sm:gap-y-0">
  <div class="w-full flex flex-wrap justify-between items-center gap-x-6 gap-y-2 sm:gap-y-0">
    <div class="flex flex-wrap items-center gap-x-6 gap-y-2 sm:gap-y-0">
      <div class="flex flex-wrap items-center gap-x-6 gap-y-2 sm:gap-y-0">
        @if ($thread->flag)
          <svg x-data x-tooltip.raw="{{ __('Flagged') }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            class="w-5 h-5 text-orange-600">
            <path
              d="M3.5 2.75a.75.75 0 00-1.5 0v14.5a.75.75 0 001.5 0v-4.392l1.657-.348a6.449 6.449 0 014.271.572 7.948 7.948 0 005.965.524l2.078-.64A.75.75 0 0018 12.25v-8.5a.75.75 0 00-.904-.734l-2.38.501a7.25 7.25 0 01-4.186-.363l-.502-.2a8.75 8.75 0 00-5.053-.439l-1.475.31V2.75z" />
          </svg>
        @else
          <span class="inline-flex items-center text-sm">
            <button type="button" wire:click="like" x-data x-tooltip.raw="{{ __('Like') }}"
              class="inline-flex ltr:gap-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 rtl:ml-2">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
              </svg>
              <span class="text-gray-900 dark:text-gray-300">{{ shortNumber($thread->up_votes) }}</span>
              <span class="sr-only">{{ __('likes') }}</span>
            </button>
          </span>
          <span class="inline-flex items-center text-sm">
            <button type="button" wire:click="dislike" x-data x-tooltip.raw="{{ __('Dislike') }}"
              class="inline-flex ltr:gap-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="w-5 h-5 rtl:ml-2 -scale-y-100">
                <path stroke-linecap="round" stroke-linejoin="round"
                  d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
              </svg>
              <span class="text-gray-900 dark:text-gray-300">{{ shortNumber($thread->down_votes) }}</span>
              <span class="sr-only">{{ __('dislikes') }}</span>
            </button>
          </span>
        @endif
        <a href="{{ route('threads.show', $thread->slug) }}" x-data x-tooltip.raw="{{ __('Replies') }}"
          class="inline-flex items-center text-sm">
          <span class="inline-flex ltr:space-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
              class="w-5 h-5 rtl:ml-2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
            </svg>

            <span class="text-gray-900 dark:text-gray-300">{{ shortNumber($thread->replies_count ?? 0) }}</span>
            <span class="sr-only">{{ __('replies') }}</span>
        </a>
        </span>
        <a href="{{ route('threads.show', $thread->slug) }}" x-data x-tooltip.raw="{{ __('Views') }}"
          class="inline-flex items-center text-sm">
          <span class="inline-flex ltr:space-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
              class="w-5 h-5 rtl:ml-2">
              <path stroke-linecap="round" stroke-linejoin="round"
                d="M2.036 12.322a1.012 1.012 0 010-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>

            <span class="text-gray-900 dark:text-gray-300">{{ shortNumber($thread->views) }}</span>
            <span class="sr-only">{{ __('views') }}</span>
          </span>
        </a>
      </div>
      @if ($thread->lastReply)
        <span class="inline-flex ltr:space-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300" x-data
          x-tooltip.raw="{{ __('Last Reply') }}">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 rtl:ml-2">
            <path fill-rule="evenodd"
              d="M14.47 2.47a.75.75 0 011.06 0l6 6a.75.75 0 010 1.06l-6 6a.75.75 0 11-1.06-1.06l4.72-4.72H9a5.25 5.25 0 100 10.5h3a.75.75 0 010 1.5H9a6.75 6.75 0 010-13.5h10.19l-4.72-4.72a.75.75 0 010-1.06z"
              clip-rule="evenodd" />
          </svg>
          <span class="text-gray-700 dark:text-gray-300">
            {{ str(
                __(':user replied :at', [
                    'user' => $thread->lastReply->user
                        ? '<a href="' .
                            route('users.show', $thread->lastReply->user->username) .
                            '" class="font-bold hover dark:hover:text-gray-300">' .
                            $thread->lastReply->user->displayName .
                            '</a>'
                        : '',
                    'at' => $thread->lastReply->created_at->diffForHumans(),
                ]),
            )->toHtmlString() }}
            {{-- <a href="#" class="link font-bold">User</a> replied 3 minutes ago --}}
          </span>
        </span>
      @endif
    </div>

    <div x-data="{ show: false }" @click.away="show = false" class="relative inline-flex items-center">
      <span class="inline-flex items-center text-sm">
        <button type="button" @click="show = true"
          class="inline-flex ltr:space-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
          <svg class="h-5 w-5 rtl:ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path
              d="M13 4.5a2.5 2.5 0 11.702 1.737L6.97 9.604a2.518 2.518 0 010 .792l6.733 3.367a2.5 2.5 0 11-.671 1.341l-6.733-3.367a2.5 2.5 0 110-3.475l6.733-3.366A2.52 2.52 0 0113 4.5z" />
          </svg>
          <span class="text-gray-900 dark:text-gray-300">{{ __('Share') }}</span>
        </button>
      </span>

      <div x-show="show" style="display: none"
        class="absolute {{ $sharePosition }} z-10 w-64 md:w-96 ltr:origin-bottom-left rtl:origin-bottom-right rounded-md bg-white dark:bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
        <div class="grid grid-cols-2 md:grid-cols-3 p-1 gap-1">
          <a href="{{ $socialLink->blogger->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Blogger
          </a>
          <a href="{{ $socialLink->facebook->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Facebook
          </a>
          <a href="{{ $socialLink->linkedin->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            LinkedIn
          </a>
          <a href="{{ $socialLink->pinterest->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Pinterest
          </a>
          <a href="{{ $socialLink->pocket->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Pocket
          </a>
          <a href="{{ $socialLink->reddit->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Reddit
          </a>
          {{-- <a href="{{ $socialLink->telegram->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Telegram
          </a> --}}
          <a href="https://telegram.me/share/url?url={{ rawurlencode(route('threads.show', $thread->slug)) }}&text={{ rawurlencode($thread->description) }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Telegram
          </a>
          <a href="{{ $socialLink->tumblr->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Tumblr
          </a>
          <a href="{{ $socialLink->twitter->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Twitter
          </a>
          <a href="{{ $socialLink->vk->shareUrl }}" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            VK
          </a>
          <a href="{{ $socialLink->whatsapp->shareUrl }}"
            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
            Whatsapp
          </a>
        </div>
      </div>

    </div>
  </div>
