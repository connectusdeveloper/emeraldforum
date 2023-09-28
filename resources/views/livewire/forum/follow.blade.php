@if ($logged_in_user && $logged_in_user?->id != $user?->id)
  @if ($logged_in_user?->isFollowing($user))
    <button type="button"
      x-on:click="$wireui.confirmDialog({
        title: '{{ __('Unfollow :user?', ['user' => $user->displayName]) }}',
        description: '{{ __('Do you want to unfollow :user', ['user' => $user->displayName]) }}',
        icon: 'question',
      accept: {
        label: '{{ __('Yes') }}',
        execute: () => {
          @this.unfollow();
        }
      },
      reject: { label: '{{ __('No') }}'}
    })"
      class="inline-flex items-center px-4 py-3 bg-orange-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-orange-700 active:bg-orange-900 focus:outline-none focus:border-orange-900 focus:ring focus:ring-orange-300 disabled:opacity-25 transition">
      <span>{{ __('Unfollow') }}</span>
    </button>
  @else
    <button type="button" wire:click="follow"
      class="inline-flex items-center px-4 py-3 bg-blue-700 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-600 active:bg-blue-900 focus:outline-none focus:border-blue-900 focus:ring focus:ring-blue-300 disabled:opacity-25 transition">
      <span>{{ __('Follow') }}</span>
    </button>
  @endif
@endif
