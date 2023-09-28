<span class="flex items-center gap-2">
  @php
    $settings = site_config();
  @endphp
  @if ($settings['logo'] ?? null)
    <img src="{{ storage_url($settings['logo']) }}" alt="{{ $settings['name'] ?? config('app.name') }}" class="h-10" />
  @else
    <x-jet-application-mark class="h-10 w-12" />
    <span class="text-2xl font-extrabold">{{ $settings['name'] ?? config('app.name') }}</span>
  @endif
</span>
