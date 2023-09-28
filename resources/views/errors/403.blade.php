<x-app-layout>
  <x-slot name="title">
    {{ __('403 Forbidden') }}
  </x-slot>

  <x-jet-authentication-card class="-mb-32">
    <div class="text-center">
      <p class="text-base font-semibold text-primary-600">403</p>
      <h1 class="mt-4 text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100 sm:text-5xl">{{ __('Forbidden') }}</h1>
      <p class="mt-6 text-base leading-7 text-gray-600 dark:text-gray-400">
        {{ __("You don't have permission to access this resource.") }}
      </p>
      @if (auth()->user() &&
              auth()->user()->hasRole('super'))
        <p class="mt-2 text-base leading-7 text-gray-600 dark:text-gray-400">{{ $exception->getMessage() }}</p>
      @endif
      <div class="mt-6 mb-4 flex items-center justify-center gap-x-6">
        <a href="{{ route('threads') }}"
          class="rounded-md bg-primary-600 px-4 py-2 text-sm font-semibold text-white shadow-sm hover:bg-primary-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-primary-600">
          {{ __('Go back home') }}
        </a>
      </div>
    </div>
  </x-jet-authentication-card>
</x-app-layout>
