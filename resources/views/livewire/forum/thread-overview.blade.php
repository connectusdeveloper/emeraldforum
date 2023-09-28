<article @class([
    'px-4 py-6 shadow sm:rounded-lg sm:p-6',
    'bg-primary-100 dark:bg-primary-700' =>
        $thread->approved == 1 &&
        ($thread->sticky == 1 ||
            ($category->id && $thread->sticky_category == 1)),
    'bg-white dark:bg-gray-900' =>
        $thread->approved == 1 &&
        ($thread->sticky != 1 ||
            ($category->id && $thread->sticky_category != 1)),
    'bg-yellow-100 dark:bg-yellow-900' =>
        $thread->approved != 1 || $thread->flag,
])>
  <div>
    <div class="flex ltr:space-x-3">
      <div x-data class="flex-shrink-0 rtl:ml-3" wire:key="$thread->user->id">
        <template x-ref="template">
          <x-profile-overview :user="$thread->user" hide-follow />
        </template>
        <a x-tooltip="{content: () => $refs.template.innerHTML, allowHTML: true, interactive: true, placement: '{{ $settings['rtl'] ?? null ? 'bottom-end' : 'bottom-start' }}', theme: 'light-border'}"
          href="{{ route('users.show', $thread->user->username) }}">
          <img class="h-10 w-10 rounded-full object-cover" src="{{ $thread->user->profile_photo_url }}" alt="{{ $thread->user->displayName }}">
        </a>
      </div>
      <div class="min-w-0 flex-1">
        <p class="text-sm font-bold text-gray-900 dark:text-gray-100">
          <a href="{{ route('users.show', $thread->user->username) }}">{{ $thread->user->displayName }}</a>
        </p>
        <p class="text-sm text-gray-500 dark:text-gray-400">
          @php
            $catrgory_links = [];
            foreach ($thread->categories->reverse() as $thread_category) {
                $catrgory_links[] =
                    '<a href="' .
                    route('threads', $thread_category->slug) .
                    '"
                  class="font-bold hover dark:hover:text-gray-300">' .
                    $thread_category->name .
                    '</a>';
            }
            $catrgory_links = implode(', ', $catrgory_links);
          @endphp
          {{ str(
              __('Started :at in :categories', [
                  'categories' => $catrgory_links,
                  'at' => $thread->created_at->diffForHumans(),
              ]),
          )->toHtmlString() }}
        </p>
      </div>
      <div class="flex flex-shrink-0 self-start">
        @if (!$thread->approved)
          @if ($logged_in_user && $logged_in_user->can('approve-threads'))
            <button type="button" @click="show = true" wire:click="approve"
              class="flex items-center rounded-full p-2 link text-sm font-bold -my-2 lrt:mr-2 rtl:ml-2">
              {{ __('Approve') }}
            </button>
          @endif
        @endif
        <x-thread-actions :thread="$thread" />
      </div>
    </div>
  </div>
  <a href="{{ route('threads.show', $thread->slug) }}"
    class="block rounded-lg border border-transparent hover:border-gray-200 dark:hover:border-gray-800 py-3 px-4 mt-3 -mb-3 -mx-4 hover:bg-gray-50/50 dark:hover:bg-black/50">
    <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100">
      {{ $thread->title }}
    </h2>
    <div class="mt-2 space-y-4 text-gray-700 dark:text-gray-300">
      <p>
        {{ $thread->description }}
      </p>
    </div>
  </a>
  @if ($thread->flag && $logged_in_user && $logged_in_user->can('approve-threads'))
    <div class="mt-4 bg-red-100 dark:bg-red-800 px-4 py-2 rounded-md">
      <p class="text-sm ltr:text-right rtl:text-left">
        {{ str(
            __('This thread is flagged by :user.', [
                'user' =>
                    '<a href="' .
                    route('users.show', $thread->user->username) .
                    '" class="font-bold hover dark:hover:text-gray-300">' .
                    $thread->flag->user->displayName .
                    '</a>',
            ]),
        )->toHtmlString() }}
      </p>
      <div class="font-bold">{{ __('Reason') }}:</div>
      <p>{{ $thread->flag->reason }}</p>
      <div class="mt-4 flex gap-x-4 gap-y-2">
        <button
          x-on:confirm="{
            icon: 'error',
            style: 'inline',
            iconColor: 'text-red-700',
            iconBackground: 'bg-red-100 rounded-full p-2',
            title: '{{ __('Remove Flag') }}',
            description: '{{ __('Are you sure to delete the record?') }}',
            accept: {
              label: '{{ __('Yes, remove flag') }}',
              method: 'removeFlag',
              params: '{{ $thread->id }}',
            }
          }"
          class="text-gray-700 dark:text-gray-300 border dark:border-red-700 hover:bg-gray-50 dark:hover:bg-red-900 rounded-md flex p-2 text-sm">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="ltr:mr-3 rtl:ml-3 w-5 h-5 text-gray-400">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
          </svg>
          <span>{{ __('Remove Flag') }}</span>
        </button>
        <button
          x-on:confirm="{
            icon: 'error',
            style: 'inline',
            iconColor: 'text-red-700',
            iconBackground: 'bg-red-100 rounded-full p-2',
            title: '{{ __('Delete :x', ['x' => $thread->title]) }}',
            description: '{{ __('Are you sure to delete the record?') }}',
            accept: {
              label: '{{ __('Yes, delete') }}',
              method: 'remove',
              params: '{{ $thread->id }}',
            }
          }"
          class="text-gray-700 dark:text-gray-300 border dark:border-red-700 hover:bg-gray-50 dark:hover:bg-red-900 rounded-md flex p-2 text-sm">
          <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="ltr:mr-3 rtl:ml-3 w-5 h-5 text-gray-400">
            <path fill-rule="evenodd"
              d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
              clip-rule="evenodd" />
          </svg>
          <span>{{ __('Delete Thread') }}</span>
        </button>
      </div>
    </div>
  @endif
  <x-thread-badges :thread="$thread" />
</article>
