<div>
  @once
    @push('scripts')
      <script>
        window.addEventListener('DOMContentLoaded', (event) => {
          Livewire.on('page-changed', () => {
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          });
        });
      </script>
    @endpush
  @endonce

  <div class="relative isolate overflow-hidden shadow bg-white dark:bg-gray-900 sm:rounded-lg mb-6 py-6">
    <div class="mx-auto max-w-7xl px-6 lg:px-8">
      <div class="flex items-center justify-between">
        <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-2xl">
          {{ __('Notifications') }} @if ($unread_count)
            ({{ $unread_count }}
            {{ __('Unread') }})
          @endif
        </h1>
        @if ($notifications->count())
          <div class="flex items-center gap-x-4 gap-y-2">
            @if ($unreadCount)
              <x-button primary type="button"
                x-on:confirm="{
            icon: 'question',
            style: 'inline',
            iconColor: 'text-primary-700',
            iconBackground: 'bg-primary-100 rounded-full p-2',
            title: '{{ __('Mark all as read') }}',
            description: '{{ __('Are you sure to mark all the records as read?') }}',
            accept: {
              label: '{{ __('Mark all as read') }}',
              method: 'updateAll',
            }
          }">
                {{ __('Mark all as read') }}</x-button>
            @endif
            <x-button negative type="button"
              x-on:confirm="{
            icon: 'error',
            style: 'inline',
            iconColor: 'text-red-700',
            iconBackground: 'bg-red-100 rounded-full p-2',
            title: '{{ __('Delete :x', ['x' => __('Notifications')]) }}',
            description: '{{ __('Are you sure to delete all the records?') }}',
            accept: {
              label: '{{ __('Yes, delete') }}',
              method: 'removeAll',
            }
          }">
              {{ __('Delete All') }}</x-button>
          </div>
        @endif
      </div>
    </div>
  </div>

  <div class="relative isolate">
    <div class="mx-auto max-w-7xl">
      @if ($notifications->isNotEmpty())
        <ul class="flex flex-col gap-6">
          @foreach ($notifications as $notification)
            <li>
              <div class="relative overflow-hidden shadow bg-white dark:bg-gray-900 sm:rounded-lg hover:bg-gray-50 dark:hover:bg-black/50">
                @if (!$notification->read_at)
                  <div
                    class="absolute right-0 top-0 bg-primary-100 dark:bg-primary-700 rounded-bl-lg text-center py-1 px-4 text-xs font-bold">
                    {{ __('Unread') }}
                  </div>
                @endif
                <div class="p-4 sm:p-6">
                  <button type="button" wire:click="view('{{ $notification->id }}')"
                    class="ltr:text-left rtl:text-right font-medium link">{{ $notification->data['title'] }}</button>
                  <p class="mt-2 text-sm">
                    {{ $notification->data['text'] }}
                  </p>
                  <div class="mt-4 flex items-center gap-2">
                    <p class="text-sm">
                      {{ $notification->created_at->diffForHumans() }}
                    </p>
                    <button type="button" wire:click="view('{{ $notification->id }}')"
                      class="inline-flex items-center rounded-full bg-primary-50 dark:bg-primary-600 px-2.5 py-1 text-xs font-semibold text-primary-800 dark:text-primary-200 shadow-sm hover:bg-primary-200 dark:hover:bg-primary-700">
                      {{ __('View') }}
                    </button>
                    @if ($notification->read_at)
                      <button type="button" wire:click="unread('{{ $notification->id }}')"
                        class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-600 px-2.5 py-1 text-xs font-semibold text-gray-800 dark:text-gray-200 shadow-sm hover:bg-gray-200 dark:hover:bg-gray-700">
                        {{ __('Mark as Unread') }}
                      </button>
                    @else
                      <button type="button" wire:click="update('{{ $notification->id }}')"
                        class="inline-flex items-center rounded-full bg-gray-100 dark:bg-gray-600 px-2.5 py-1 text-xs font-semibold text-gray-800 dark:text-gray-200 shadow-sm hover:bg-gray-200 dark:hover:bg-gray-700">
                        {{ __('Mark as Read') }}
                      </button>
                    @endif
                    <button type="button"
                      x-on:confirm="{
                      icon: 'error',
                      style: 'inline',
                      iconColor: 'text-red-700',
                      iconBackground: 'bg-red-100 rounded-full p-2',
                      title: '{{ __('Delete :x', ['x' => __('Notification')]) }}',
                      description: '{{ __('Are you sure to delete the record?') }}',
                      accept: {
                        label: '{{ __('Yes, delete') }}',
                        method: 'remove',
                        params: '{{ $notification->id }}',
                      }
                    }"
                      class="inline-flex items-center rounded-full bg-red-100 dark:bg-red-600 px-2.5 py-1 text-xs font-semibold text-red-800 dark:text-red-200 shadow-sm hover:bg-red-200 dark:hover:bg-red-700">
                      {{ __('Delete') }}
                    </button>
                  </div>
                </div>
              </div>
            </li>
          @endforeach
        </ul>
        @if ($notifications->hasPages())
          <div class="mt-6"></div>
          <div class="w-fulln sm:rounded-lg bg-white dark:bg-gray-900 min-w-full p-6">
            {{ $notifications->links() }}
          </div>
        @endif
      @else
        <div class="w-fulln sm:rounded-lg bg-white dark:bg-gray-900 min-w-full p-6 text-gray-500">
          {{ __('There is no notifications to display.') }}
        </div>
      @endif
    </div>
  </div>
</div>
