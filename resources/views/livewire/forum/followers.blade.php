<div>
  <x-slot name="title">
    {{ __('Followers of :user', ['user' => $user->displayName]) }}
  </x-slot>
  <x-slot name="metaTags">
    <meta name="description" content="" />
  </x-slot>

  @if ($followers->isEmpty())
    <div class="text-center py-12">
      <h3 class="mt-2 text-xl font-semibold text-gray-900 dark:text-gray-100">{{ __('No Followers') }}</h3>
      <p class="mt-1">{{ __(':user does not have any followers yet.', ['user' => $user->displayName]) }}</p>
      <div class="mt-6">
        @if (auth()->user() && auth()->user()->id != $user->id)
          @livewire('forum.follow', ['user' => $user])
        @endif
      </div>
    </div>
  @else
    <div class="relative isolate overflow-hidden shadow bg-white dark:bg-gray-900 sm:rounded-lg mb-6 py-6">
      <div class="mx-auto max-w-7xl px-6 lg:px-8">
        <div class="mx-auto max-w-2xl lg:mx-0">
          <h1 class="text-xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-2xl">
            {{ __('Followers of :user', ['user' => $user->displayName]) }}
          </h1>
          {{-- <h3 class="mt-4 text-lg leading-relaxed"></h3> --}}
        </div>
      </div>
    </div>

    <ul role="list" class="grid grid-cols-1 gap-6 sm:grid-cols-2">
      @foreach ($followers as $follower)
        <li class="col-span-1 rounded-lg bg-white dark:bg-gray-900 shadow p-2">
          <x-profile-overview :user="$follower" less="1" />
        </li>
      @endforeach
    </ul>
  @endif
</div>
