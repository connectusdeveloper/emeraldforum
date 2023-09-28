<div {{ $attributes->merge(['class' => 'lg:grid lg:grid-cols-3 lg:gap-6']) }}>
  <x-jet-section-title>
    <x-slot name="title">{{ $title }}</x-slot>
    <x-slot name="description">{{ $description }}</x-slot>
  </x-jet-section-title>

  <div class="mt-5 lg:mt-0 lg:col-span-2">
    <div class="px-4 py-5 lg:p-6 bg-white dark:bg-gray-900 shadow md:rounded-lg">
      {{ $content }}
    </div>
  </div>
</div>
