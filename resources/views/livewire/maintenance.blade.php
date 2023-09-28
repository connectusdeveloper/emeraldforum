<x-slot name="title">{{ __('Maintenance Mode') }} {{ ' - ' . ($settings['shop_name'] ?? '') }}</x-slot>

<div class="relative min-h-screen bg-gray-50 dark:bg-gray-800 overflow-hidden">
  <div class="relative min-h-screen flex items-center justify-center py-6">
    <main class="mx-auto max-w-7xl px-4">
      <div class="text-center">
        <h1
          class="text-3xl tracking-tight font-extrabold text-gray-900 dark:text-gray-100 sm:text-4xl md:text-5xl flex flex-col justify-center gap-6">
          <span class="block xl:inline">{{ site_config('name') ?? config('app.name') }}</span>
          {{-- <span class="block text-blue-600 xl:inline">({{ __('Maintenance Mode') }})</span> --}}
        </h1>
        <p class="mt-3 max-w-md mx-auto text-base text-gray-500 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
          {{ __('Site is under maintenance, please visit us after few days.') }}
        </p>
      </div>
    </main>
  </div>
</div>
