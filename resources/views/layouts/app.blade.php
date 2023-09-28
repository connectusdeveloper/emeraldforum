<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full" dir="{{ $settings['rtl'] == 1 ? 'rtl' : 'ltr' }}">
@props(['showAd' => false])

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  @if ($settings['icon'] ?? null)
    <link rel="icon" href="{{ storage_url($settings['icon']) }}">
  @endif

  <title>
    {{ ($title ?? (trim($settings['title'] ?? '') ?: 'Home')) . ' · ' . ($settings['name'] ?? config('app.name', 'Simple Forum')) }}
  </title>
  @if (isset($metaTags))
    {{ $metaTags }}
  @endif
  <script>
    @if ($settings['theme'] ?? null)
      document.documentElement.classList.add('{{ $settings['theme'] }}');
    @else
      let mediaQuery = window.matchMedia('(prefers-color-scheme: dark)');

      function updateTheme(savedTheme) {
        let theme = 'system';
        try {
          if (!savedTheme) {
            savedTheme = window.localStorage.theme;
          }
          if (savedTheme == 'dark') {
            theme = 'dark';
            document.documentElement.classList.add('dark');
          } else if (savedTheme == 'light') {
            theme = 'light';
            document.documentElement.classList.remove('dark');
          } else if (mediaQuery.matches) {
            document.documentElement.classList.add('dark');
            localStorage.setItem('theme', 'dark');
          } else {
            document.documentElement.classList.remove('dark');
            localStorage.setItem('theme', 'light');
          }
        } catch {
          theme = 'light';
          document.documentElement.classList.remove('dark');
        }
        return theme;
      }

      document.documentElement.setAttribute('data-theme', updateTheme());

      new MutationObserver(([{
        oldValue
      }]) => {
        let newValue = document.documentElement.getAttribute('data-theme');
        if (newValue !== oldValue) {
          try {
            window.localStorage.setItem('theme', newValue);
          } catch {}
          updateTheme(newValue);
        }
      }).observe(document.documentElement, {
        attributeFilter: ['data-theme'],
        attributeOldValue: true
      });

      window.addEventListener('storage', updateTheme);
      mediaQuery.addEventListener('change', updateTheme);
    @endif
    window.Locale = '{{ app()->getLocale() }}';
    // window.addEventListener('DOMContentLoaded', function() {
    //   setTimeout(function() {
    //     document.getElementById('app-loading').style.display = 'none';
    //   }, 250);
    // });
  </script>

  <!-- Fonts -->
  <link rel="stylesheet" href="https://fonts.bunny.net/css2?family=Nunito:wght@400;600;700&display=swap">

  <!-- Scripts -->
  @wireUiScripts
  @vite(['resources/css/app.css', 'resources/js/app.js'])

  <!-- Styles -->
  @livewireStyles
  @stack('styles')
  
  <link rel="stylesheet" href="<?= URL::to('/assets/css/style.css'); ?>">
</head>

<body class="h-full min-h-screen font-sans antialiased bg-gray-100 dark:bg-gray-800 text-gray-700 dark:text-gray-300">
  @if (!$logged_in_user?->hasRole('super') && ($settings['mode'] ?? null) == 'Maintenance')
    @include('livewire.maintenance')
  @else
    <x-jet-banner id="top-banner" />

    <div class="">
      <x-navigation-menu />
      {{-- @livewire('navigation-menu') --}}

      <!-- Top Ad -->
      @if ($showAd && $settings['top_ad_code'] ?? null)
        <div class="flex justify-center mx-auto max-w-7xl sm:px-6 lg:px-8 mt-8">
          {{ str($settings['top_ad_code'])->toHtmlString() }}
        </div>
      @endif

      <!-- Page Heading -->
      @isset($header)
        <header class="header" id="header">
          <div class="max-w-7xl mx-auto pt-8 px-4 sm:px-6 lg:px-8">
            {{ $header }}
          </div>
        </header>
      @endisset

      <!-- Page Content -->
      <main class="bg-gray-100 dark:bg-gray-800 h-full min-h-full">
        <div class="pt-8" x-cloak x-data="{
            mh: 300,
            init() {
                window.addEventListener('DOMContentLoaded', () => {
                    this.mh = document.body.offsetHeight - (document.getElementById('main-nav')?.offsetHeight || 64) - (document.getElementById('footer')?.offsetHeight || 216) - 32;
                });
            }
        }" :style="{ 'min-height': mh + 'px' }">
          {{ $slot }}
        </div>

        <!-- Bottom Ad -->
        @if ($showAd && $settings['bottom_ad_code'] ?? null)
          <div class="flex justify-center mx-auto max-w-7xl sm:px-6 lg:px-8 mt-8">
            {{ str($settings['bottom_ad_code'])->toHtmlString() }}
          </div>
        @endif

        <x-footer />
      </main>
    </div>

    <x-notifications zIndex="z-[2500]" />
    <x-dialog blur="sm" align="center" />
    @livewireScripts
    @stack('modals')
    @include('cookie-consent::index')
    @stack('scripts')

    @if (session('error') || session('message') || session('info'))
      <script>
        window.addEventListener('DOMContentLoaded', (event) => {
          const eventNotification = new CustomEvent('wireui:notification', {
            bubbles: true,
            detail: {
              "options": {
                "icon": "{{ session('error') ? 'error' : (session('message') ? 'success' : 'info') }}",
                "title": "{{ session('error') ? 'Error!' : (session('message') ? 'Success!' : 'Info!') }}",
                "description": "{{ session('error') ?: session('message') ?: session('info') }}"
                // "title": "{{ session('error') ?: session('message') ?: session('info') }}"
              }
            }
          });
          document.dispatchEvent(eventNotification);
        });
      </script>
      @php
        session()->forget('info');
        session()->forget('error');
        session()->forget('message');
      @endphp
    @endif

    <script>
      document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre').forEach((el) => {
          hljs.highlightElement(el);
        });
      });
    </script>
  @endif

  @if ($settings['footer_code'] ?? null)
    {{ str($settings['footer_code'])->toHtmlString() }}
  @endif
</body>

</html>
