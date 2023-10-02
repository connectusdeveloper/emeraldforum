<div class="relative pb-12">
  <span
    class="absolute top-5 left-5 -ml-px h-full w-0.5 {{ $reply->accepted ? 'bg-green-200 dark:bg-green-600' : 'bg-gray-200 dark:bg-gray-700' }}"
    aria-hidden="true"></span>
  <div class="relative flex items-start space-x-3">
    <div x-data class="relative">
      @if ($reply->user)
        <template x-ref="template">
          <x-profile-overview :user="$reply->user" hide-follow />
        </template>
        <a x-tooltip="{content: () => $refs.template.innerHTML, allowHTML: true, interactive: true, placement: '{{ $settings['rtl'] ?? null ? 'bottom-end' : 'bottom-start' }}', theme: 'light-border'}"
          href="{{ route('users.show', $reply->user->username) }}" class="font-bold text-gray-900 dark:text-gray-100">
          <img
            class="flex h-10 w-10 object-cover items-center justify-center rounded-full bg-gray-400 dark:bg-gray-600 ring-2 {{ $reply->accepted ? 'ring-green-200 dark:ring-green-600' : 'ring-gray-200 dark:ring-gray-700' }}"
            src="{{ $reply->user?->profile_photo_url }}" alt="{{ $reply->user?->display_name }}">
        </a>
      @else
        <img
          class="flex h-10 w-10 object-cover items-center justify-center rounded-full bg-gray-400 dark:bg-gray-600 ring-2 ring-gray-200 dark:ring-gray-700"
          src="https://ui-avatars.com/api/?name=G&color=7F9CF5&background=EBF4FF" alt="{{ $reply->guest_name }}">
      @endif
    </div>
    <div class="min-w-0 flex-1">
      <div class="block sm:flex sm:items-start sm:justify-between sm:gap-x-4 sm:gap-y-2">
        <div>
          <div class="text-sm">
            @if ($reply->user)
              <a href="{{ route('users.show', $reply->user->username) }}" class="font-bold text-gray-900 dark:text-gray-100">
                {{ $reply->user?->display_name }}
              </a>
            @else
              {{ $reply->guest_name }}
            @endif
          </div>
          <p class="mt-0.5 text-sm">
            {{ __('Replied :at', ['at' => $reply->created_at->diffForHumans()]) }}
          </p>
        </div>
        <div class="{{ $reply->flag ? 'mt-2' : '-mt-2' }}">
          @if (!$reply->flag)
            <div class="flex flex-wrap items-center justify-end gap-x-4 gap-y-2">
              <span class="inline-flex items-center text-sm">
                <button type="button" wire:click="like" x-data x-tooltip.raw="{{ __('Like') }}"
                  class="inline-flex ltr:gap-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-5 h-5 rtl:ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M6.633 10.5c.806 0 1.533-.446 2.031-1.08a9.041 9.041 0 012.861-2.4c.723-.384 1.35-.956 1.653-1.715a4.498 4.498 0 00.322-1.672V3a.75.75 0 01.75-.75A2.25 2.25 0 0116.5 4.5c0 1.152-.26 2.243-.723 3.218-.266.558.107 1.282.725 1.282h3.126c1.026 0 1.945.694 2.054 1.715.045.422.068.85.068 1.285a11.95 11.95 0 01-2.649 7.521c-.388.482-.987.729-1.605.729H13.48c-.483 0-.964-.078-1.423-.23l-3.114-1.04a4.501 4.501 0 00-1.423-.23H5.904M14.25 9h2.25M5.904 18.75c.083.205.173.405.27.602.197.4-.078.898-.523.898h-.908c-.889 0-1.713-.518-1.972-1.368a12 12 0 01-.521-3.507c0-1.553.295-3.036.831-4.398C3.387 10.203 4.167 9.75 5 9.75h1.053c.472 0 .745.556.5.96a8.958 8.958 0 00-1.302 4.665c0 1.194.232 2.333.654 3.375z" />
                  </svg>
                  <span class="text-gray-900 dark:text-gray-300">{{ shortNumber($reply->up_votes) }}</span>
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
                  <span class="text-gray-900 dark:text-gray-300">{{ shortNumber($reply->down_votes) }}</span>
                  <span class="sr-only">{{ __('dislikes') }}</span>
                </button>
              </span>
              @if ($showAccept)
                @if ($reply->accepted)
                  <span class="inline-flex items-center text-sm">
                    <span type="button" wire:click="accept" x-data x-tooltip.raw="{{ __('Accepted Answer') }}"
                      class="inline-flex ltr:gap-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor"
                        class="w-5 h-5 rtl:ml-2 text-green-700 dark:text-green-300">
                        <path fill-rule="evenodd"
                          d="M8.603 3.799A4.49 4.49 0 0112 2.25c1.357 0 2.573.6 3.397 1.549a4.49 4.49 0 013.498 1.307 4.491 4.491 0 011.307 3.497A4.49 4.49 0 0121.75 12a4.49 4.49 0 01-1.549 3.397 4.491 4.491 0 01-1.307 3.497 4.491 4.491 0 01-3.497 1.307A4.49 4.49 0 0112 21.75a4.49 4.49 0 01-3.397-1.549 4.49 4.49 0 01-3.498-1.306 4.491 4.491 0 01-1.307-3.498A4.49 4.49 0 012.25 12c0-1.357.6-2.573 1.549-3.397a4.49 4.49 0 011.307-3.497 4.49 4.49 0 013.497-1.307zm7.007 6.387a.75.75 0 10-1.22-.872l-3.236 4.53L9.53 12.22a.75.75 0 00-1.06 1.06l2.25 2.25a.75.75 0 001.14-.094l3.75-5.25z"
                          clip-rule="evenodd" />
                      </svg>
                      <span class="sr-only">{{ __('Accepted Answer') }}</span>
                    </span>
                  </span>
                @else
                  <span class="inline-flex items-center text-sm">
                    <button type="button" wire:click="accept" x-data x-tooltip.raw="{{ __('Accept Answer') }}"
                      class="inline-flex ltr:gap-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                        class="w-5 h-5 rtl:ml-2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                          d="M9 12.75L11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 01-1.043 3.296 3.745 3.745 0 01-3.296 1.043A3.745 3.745 0 0112 21c-1.268 0-2.39-.63-3.068-1.593a3.746 3.746 0 01-3.296-1.043 3.745 3.745 0 01-1.043-3.296A3.745 3.745 0 013 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 011.043-3.296 3.746 3.746 0 013.296-1.043A3.746 3.746 0 0112 3c1.268 0 2.39.63 3.068 1.593a3.746 3.746 0 013.296 1.043 3.746 3.746 0 011.043 3.296A3.745 3.745 0 0121 12z" />
                      </svg>
                      <span class="sr-only">{{ __('accept') }}</span>
                    </button>
                  </span>
                @endif
              @endif
            </div>
          @endif

          <div class="mt-4 flex flex-wrap items-center justify-end gap-x-4 gap-y-2">
            @if ($logged_in_user && !$reply->flag)
              <span x-data class="inline-flex items-center text-sm">
                <button type="button"
                  @click="window.dispatchEvent(new CustomEvent('quoteReply', { detail: '{{ str_replace(["\r", "\n"], '', nl2br($reply->body)) }}' }))"
                  x-data x-tooltip.raw="{{ __('Quote') }}"
                  class="inline-flex ltr:gap-x-2 text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-5 h-5 rtl:ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M8.625 9.75a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H8.25m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0H12m4.125 0a.375.375 0 11-.75 0 .375.375 0 01.75 0zm0 0h-.375m-13.5 3.01c0 1.6 1.123 2.994 2.707 3.227 1.087.16 2.185.283 3.293.369V21l4.184-4.183a1.14 1.14 0 01.778-.332 48.294 48.294 0 005.83-.498c1.585-.233 2.708-1.626 2.708-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0012 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z" />
                  </svg>
                  <span class="sr-only">{{ __('Quote') }}</span>
                </button>
              </span>
            @endif

            @if ($settings['flag_option'] ?? null)
              @livewire('forum.flag', ['record' => $reply, 'icon' => true], key($reply->id))
            @endif

            @if ($reply->canEdit())
              <button type="button" wire:click="editNow()" x-data x-tooltip.raw="{{ __('Edit') }}"
                class="flex text-sm text-primary-400 hover:text-primary-500 dark:hover:text-primary-300">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-5 h-5 rtl:ml-2">
                  <path
                    d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
                  <path
                    d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
                </svg>
              </button>
            @endif
            @if ($reply->canDelete() && !$reply->flag)
              <span class="inline-flex items-center text-sm">
                <button type="button"
                  x-on:confirm="{
                  icon: 'error',
                  style: 'inline',
                  iconColor: 'text-red-700',
                  iconBackground: 'bg-red-100 rounded-full p-2',
                  title: '{{ __('Delete :x', ['x' => $reply->title]) }}',
                  description: '{{ __('Are you sure to delete the record?') }}',
                  accept: {
                    label: '{{ __('Yes, delete') }}',
                    method: 'remove',
                    params: '{{ $reply->id }}',
                  }
                }"
                  x-data x-tooltip.raw="{{ __('Delete') }}"
                  class="inline-flex ltr:gap-x-2 text-red-400 hover:text-red-500 dark:hover:text-red-300">
                  <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                    class="w-5 h-5 rtl:ml-2">
                    <path stroke-linecap="round" stroke-linejoin="round"
                      d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" />
                  </svg>
                  <span class="sr-only">{{ __('delete') }}</span>
                </button>
              </span>
            @endif
          </div>
        </div>
      </div>
      <div class="mt-6 text-sm prose dark:prose-invert max-w-none">
        @if ($edit)
          <x-textarea id="reply-body" wire:model.defer="body" />
          <div class="flex gap-4 items-center justify-end mt-3">
            <button type="button" wire:click="$toggle('edit')" class="rounded-md py-2 px-4">{{ __('Cancel') }}</button>
            <button type="button" wire:click="update"
              class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-bold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2">{{ __('Update') }}</button>
          </div>
        @else
          {{ parse_markdown($reply->body) }}
        @endif

        @if ($reply->extra_attributes->isNotEmpty())
          <div class="mt-8 rounded-md border dark:border-gray-800 px-4 py-5 sm:px-6">
            <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
              @foreach ($reply->extra_attributes as $key => $value)
                <div class="sm:col-span-1">
                  <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $key }}</dt>
                  <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $value }}</dd>
                </div>
              @endforeach
            </dl>
          </div>
        @endif

        @if (($settings['signature'] ?? null) && ($reply->user->meta['signature'] ?? null))
          <div class="mt-6">
            <div class="relative">
              <div class="absolute inset-0 flex items-center" aria-hidden="true">
                <div class="w-full border-t dark:border-gray-700"></div>
              </div>
              <div class="relative flex justify-start">
                <span class="bg-white dark:bg-gray-900 pr-3 text-xs font-semibold leading-6 text-gray-500">{{ __('Signature') }}</span>
              </div>
            </div>
            <div class="pt-2 text-sm">{{ str($reply->user->meta['signature'])->toHtmlString() }}</div>
          </div>
        @endif
        {{-- @if ($last)
        <div class="mt-6"></div>
      @else
        <div class="relative mt-6">
          <div class="absolute inset-0 flex items-center" aria-hidden="true">
            <div class="w-full border-t dark:border-gray-800"></div>
          </div>
        </div>
      @endif --}}
      </div>
    </div>
  </div>
