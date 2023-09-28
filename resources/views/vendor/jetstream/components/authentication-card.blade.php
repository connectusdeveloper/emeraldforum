<div x-cloak x-data="{
    mh: 300,
    init() {
        {{-- console.log(document.getElementById('main-nav')?.offsetHeight, document.getElementById('footer')?.offsetHeight); --}}
        window.addEventListener('DOMContentLoaded', () => {
            this.mh = document.body.offsetHeight - (document.getElementById('main-nav')?.offsetHeight || 64) - (document.getElementById('footer')?.offsetHeight || 216) - 32;
        });
    }
}" class="h-full flex flex-col sm:justify-center items-center sm:pt-0" :style="{ 'min-height': mh + 'px' }">
  <div class="w-full sm:max-w-md mt-6 px-6 py-4 bg-white dark:bg-gray-900 shadow-md overflow-hidden sm:rounded-lg">
    {{ $slot }}
  </div>
</div>
