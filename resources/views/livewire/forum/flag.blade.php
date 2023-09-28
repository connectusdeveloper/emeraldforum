<div>
  @if ($settings['flag_option'] ?? null)
    @if ($record->flag)
      <div class="w-full text-gray-700 dark:text-gray-300 flex text-sm {{ $icon ? '' : 'px-4 py-2' }}">
        <svg @if ($icon) x-data x-tooltip.raw="{{ __('Flagged') }}" @endif xmlns="http://www.w3.org/2000/svg"
          viewBox="0 0 20 20" fill="currentColor" class="w-5 h-5 text-orange-600 {{ $icon ? '' : 'ltr:mr-3 rtl:ml-3' }}">
          <path
            d="M3.5 2.75a.75.75 0 00-1.5 0v14.5a.75.75 0 001.5 0v-4.392l1.657-.348a6.449 6.449 0 014.271.572 7.948 7.948 0 005.965.524l2.078-.64A.75.75 0 0018 12.25v-8.5a.75.75 0 00-.904-.734l-2.38.501a7.25 7.25 0 01-4.186-.363l-.502-.2a8.75 8.75 0 00-5.053-.439l-1.475.31V2.75z" />
        </svg>
        <span class="{{ $icon ? 'sr-only' : '' }}">{{ __('Flagged') }}</span>
      </div>
    @else
      @auth
        <button
          x-on:click="$wireui.confirmDialog({
        icon: 'warning',
        style: 'inline',
        id: 'flag-{{ $record->id }}',
        reject: { label: '{{ __('Cancel') }}' },
        accept: { label: '{{ __('Report') }}', execute: () => { @this.report(); } },
      })"
          class="w-full flex text-sm text-red-400 hover:text-red-500 dark:hover:text-red-300 {{ $icon ? '' : 'px-4 py-2' }}" @if ($icon)
          x-data x-tooltip.raw="{{ __('Report') }}"
      @endif>
      @if ($icon)
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
          class="w-5 h-5 text-red-400 hover:text-red-500 dark:hover:text-red-300">
          <path stroke-linecap="round" stroke-linejoin="round"
            d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
        </svg>
      @else
        <svg class="h-5 w-5 text-red-400 hover:text-red-500 dark:hover:text-red-300 {{ $icon ? '' : 'ltr:mr-3 rtl:ml-3' }}"
          xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
          <path
            d="M3.5 2.75a.75.75 0 00-1.5 0v14.5a.75.75 0 001.5 0v-4.392l1.657-.348a6.449 6.449 0 014.271.572 7.948 7.948 0 005.965.524l2.078-.64A.75.75 0 0018 12.25v-8.5a.75.75 0 00-.904-.734l-2.38.501a7.25 7.25 0 01-4.186-.363l-.502-.2a8.75 8.75 0 00-5.053-.439l-1.475.31V2.75z" />
        </svg>
      @endif
      <span class="{{ $icon ? 'sr-only' : '' }}">{{ __('Report') }}</span>
      </button>
    @else
      <a href="/login" class="w-full flex text-sm text-red-400 hover:text-red-500 dark:hover:text-red-300 {{ $icon ? '' : 'px-4 py-2' }}">
        @if ($icon)
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
            class="w-5 h-5 text-red-400 hover:text-red-500 dark:hover:text-red-300">
            <path stroke-linecap="round" stroke-linejoin="round"
              d="M3 3v1.5M3 21v-6m0 0l2.77-.693a9 9 0 016.208.682l.108.054a9 9 0 006.086.71l3.114-.732a48.524 48.524 0 01-.005-10.499l-3.11.732a9 9 0 01-6.085-.711l-.108-.054a9 9 0 00-6.208-.682L3 4.5M3 15V4.5" />
          </svg>
        @else
          <svg class="h-5 w-5 text-red-400 hover:text-red-500 dark:hover:text-red-300 {{ $icon ? '' : 'ltr:mr-3 rtl:ml-3' }}"
            xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
            <path
              d="M3.5 2.75a.75.75 0 00-1.5 0v14.5a.75.75 0 001.5 0v-4.392l1.657-.348a6.449 6.449 0 014.271.572 7.948 7.948 0 005.965.524l2.078-.64A.75.75 0 0018 12.25v-8.5a.75.75 0 00-.904-.734l-2.38.501a7.25 7.25 0 01-4.186-.363l-.502-.2a8.75 8.75 0 00-5.053-.439l-1.475.31V2.75z" />
          </svg>
        @endif
        <span class="{{ $icon ? 'sr-only' : '' }}">{{ __('Report') }}</span>
      </a>
    @endauth

    <x-dialog id="flag-{{ $record->id }}" :title="__('Report')" :description="__('Please describe the reason with violated rule')">
      <div class="mt-4">
        <x-textarea :placeholder="__('Let us know the rule violated')" wire:model.defer="reason" />
      </div>
    </x-dialog>
  @endif
  @endif
</div>
