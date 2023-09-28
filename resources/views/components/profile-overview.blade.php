@props(['user', 'less' => 0, 'hideFollow' => 0, 'hideView' => 0])
<div class="p-4 {{ $less ? '' : '-mx-[9px] -my-[5px] min-w-[250px] max-w-xs rounded-md shadow-lg' }}">
  <div class="-m-px">
    <div class="flex w-full items-start gap-3">
      <div class="flex-1">
        <div class="flex items-center gap-x-3">
          <a href="{{ route('users.show', $user->username) }}">
            <h3 class="truncate text-sm font-bold text-gray-900 dark:text-gray-100">{{ $user->displayName }}</h3>
          </a>
          {{-- <span class="inline-block flex-shrink-0 rounded-full bg-green-100 px-2 py-0.5 text-xs font-medium text-green-800">{{ '@' . $user->username }}</span> --}}
        </div>
        <p class="text-sm ">{{ '@' . $user->username }}</p>
      </div>
      <a href="{{ route('users.show', $user->username) }}">
        <img class="h-10 w-10 object-cover flex-shrink-0 rounded-full" src="{{ $user->profile_photo_url }}" alt="{{ $user->displayName }}">
      </a>
    </div>
    <div class="mt-2">
      <div>
        <p>{{ $user->meta_data['bio'] ?? '' }}</p>
        <div class="my-4 flex flex-wrap gap-2">
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
        <div class="grid grid-cols-2 gap-1">
          <a href="{{ route('threads', ['by' => $user->username]) }}" class="text-sm font-medium">
            <span class="">{{ shortNumber($user->threads_count) }}</span>
            <span class="text-gray-600 dark:text-gray-400">{{ __('Threads') }}</span>
          </a>
          <p class="text-sm font-medium">
            <span class="">{{ shortNumber($user->replies_count) }}</span>
            <span class="text-gray-600 dark:text-gray-400">{{ __('Replies') }}</span>
          </p>

          <a href="{{ route('followers', $user->username) }}" class="text-sm font-medium">
            <span class="">{{ shortNumber($user->followers_count ?? 0) }}</span>
            <span class="text-gray-600 dark:text-gray-400">{{ __('Followers') }}</span>
          </a>
          <a href="{{ route('followings', $user->username) }}" class="text-sm font-medium">
            <span class="">{{ shortNumber($user->followings_count ?? 0) }}</span>
            <span class="text-gray-600 dark:text-gray-400">{{ __('Followings') }}</span>
          </a>
        </div>
        @if ($logged_in_user && !($user->meta_data['disable_messages'] ?? null) && $logged_in_user->id != $user->id)
          <div class="flex items-stretch gap-x-4 gap-y-2 mt-4">
            <x-button primary type="button" class="w-full py-2.5"
              @click="$dispatch('show-message-modal', { user: '{{ $user->toJson() }}' })">
              {{ __('Send Message') }}
            </x-button>
          </div>
        @endif
        <div class="flex items-stretch gap-x-4 gap-y-2 mt-4">
          @if (!$hideView)
            <x-link href="{{ route('users.show', $user->username) }}" @class(['w-full justify-center' => $hideFollow])>
              {{ __('View Profile') }}
            </x-link>
          @endif
          @if (!$hideFollow && $logged_in_user && $logged_in_user?->id != $user->id)
            @livewire('forum.follow', ['user' => $user])
          @endif
        </div>
      </div>
    </div>
  </div>
</div>
