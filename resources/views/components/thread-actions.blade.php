@props(['thread'])

<div x-data="{ show: false }" class="flex flex-shrink-0 self-start">
  <div class="relative inline-block text-left">
    <div>
      <button type="button" @click="show = true"
        class="-m-2 flex items-center rounded-full p-2 text-gray-400 hover:text-gray-600 dark:text-gray-500 dark:hover:text-gray-300">
        <span class="sr-only">{{ __('Open thread actions') }}</span>
        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path
            d="M10 3a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM10 8.5a1.5 1.5 0 110 3 1.5 1.5 0 010-3zM11.5 15.5a1.5 1.5 0 10-3 0 1.5 1.5 0 003 0z" />
        </svg>
      </button>
    </div>

    <div x-show="show" @click.away="show = false" style="display: none"
      class="absolute -top-2 rtl:left-6 ltr:right-6 z-10 mt-2 w-56 origin-top-right rounded-md bg-white dark:bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
      role="menu" aria-orientation="vertical">
      <div class="py-1" role="none">
        <button type="button" wire:click="favorite"
          class="w-full ltr:text-left rtl:text-right text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 flex px-4 py-2 text-sm"
          role="menuitem" tabindex="-1" id="options-menu-0-item-0">
          <svg class="ltr:mr-3 rtl:ml-3 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
            aria-hidden="true">
            <path fill-rule="evenodd"
              d="M10.868 2.884c-.321-.772-1.415-.772-1.736 0l-1.83 4.401-4.753.381c-.833.067-1.171 1.107-.536 1.651l3.62 3.102-1.106 4.637c-.194.813.691 1.456 1.405 1.02L10 15.591l4.069 2.485c.713.436 1.598-.207 1.404-1.02l-1.106-4.637 3.62-3.102c.635-.544.297-1.584-.536-1.65l-4.752-.382-1.831-4.401z"
              clip-rule="evenodd" />
          </svg>
          <span>{{ __('Add to favorites') }}</span>
        </button>

        @livewire('forum.flag', ['record' => $thread], key($thread->id))

        @if ($thread->canEdit())
          <a href="{{ route('threads.edit', $thread->id) }}"
            class="text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 flex px-4 py-2 text-sm" role="menuitem"
            tabindex="-1" id="options-menu-0-item-2">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="ltr:mr-3 rtl:ml-3 w-5 h-5 text-gray-400">
              <path
                d="M21.731 2.269a2.625 2.625 0 00-3.712 0l-1.157 1.157 3.712 3.712 1.157-1.157a2.625 2.625 0 000-3.712zM19.513 8.199l-3.712-3.712-8.4 8.4a5.25 5.25 0 00-1.32 2.214l-.8 2.685a.75.75 0 00.933.933l2.685-.8a5.25 5.25 0 002.214-1.32l8.4-8.4z" />
              <path
                d="M5.25 5.25a3 3 0 00-3 3v10.5a3 3 0 003 3h10.5a3 3 0 003-3V13.5a.75.75 0 00-1.5 0v5.25a1.5 1.5 0 01-1.5 1.5H5.25a1.5 1.5 0 01-1.5-1.5V8.25a1.5 1.5 0 011.5-1.5h5.25a.75.75 0 000-1.5H5.25z" />
            </svg>
            <span>{{ __('Edit thread') }}</span>
          </a>
        @endif
        @if ($thread->canDelete())
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
            class="w-full ltr:text-left rtl:text-right text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-900 flex px-4 py-2 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="ltr:mr-3 rtl:ml-3 w-5 h-5 text-gray-400">
              <path fill-rule="evenodd"
                d="M16.5 4.478v.227a48.816 48.816 0 013.878.512.75.75 0 11-.256 1.478l-.209-.035-1.005 13.07a3 3 0 01-2.991 2.77H8.084a3 3 0 01-2.991-2.77L4.087 6.66l-.209.035a.75.75 0 01-.256-1.478A48.567 48.567 0 017.5 4.705v-.227c0-1.564 1.213-2.9 2.816-2.951a52.662 52.662 0 013.369 0c1.603.051 2.815 1.387 2.815 2.951zm-6.136-1.452a51.196 51.196 0 013.273 0C14.39 3.05 15 3.684 15 4.478v.113a49.488 49.488 0 00-6 0v-.113c0-.794.609-1.428 1.364-1.452zm-.355 5.945a.75.75 0 10-1.5.058l.347 9a.75.75 0 101.499-.058l-.346-9zm5.48.058a.75.75 0 10-1.498-.058l-.347 9a.75.75 0 001.5.058l.345-9z"
                clip-rule="evenodd" />
            </svg>
            <span>{{ __('Delete thread') }}</span>
          </button>
        @endif
      </div>
    </div>
  </div>
</div>
