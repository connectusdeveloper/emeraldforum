<x-slot name="metaTags">
  <meta name="description"
    content="{{ __('Please review knowledge base, if you cannot find the answer you are looking for? Reach out to our support team by opening forum :thread.', ['thread' => __('thread')]) }}" />
</x-slot>

<div class="bg-white dark:bg-gray-900 sm:rounded-lg shadow relative isolate">
  <svg
    class="absolute inset-0 -z-10 opacity-50 h-full w-full stroke-gray-200 dark:stroke-gray-700 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
    aria-hidden="true">
    <defs>
      <pattern id="83fd4e5a-9d52-42fc-97b6-718e5d7ee527" width="200" height="200" x="50%" y="-64"
        patternUnits="userSpaceOnUse">
        <path d="M100 200V.5M.5 .5H200" fill="none" />
      </pattern>
    </defs>
    <svg x="50%" y="-64" class="overflow-visible fill-gray-50 dark:fill-gray-900">
      <path d="M-100.5 0h201v201h-201Z M699.5 0h201v201h-201Z M499.5 400h201v201h-201Z M299.5 800h201v201h-201Z" stroke-width="0" />
    </svg>
    <rect width="100%" height="100%" stroke-width="0" fill="url(#83fd4e5a-9d52-42fc-97b6-718e5d7ee527)" />
  </svg>
  <div class="p-6">
    <h2 class="text-2xl font-bold leading-10 tracking-tight text-gray-900 dark:text-gray-100">
      {{ __('Knowledge Base') }}
    </h2>
    <p class="mt-4 text-base leading-7">
      {{ str(__('Please review knowledge base, if you cannot find the answer you are looking for? Reach out to our support team by opening forum :thread.', ['thread' => '<a href="' . route('threads.create') . '" class="font-semibold link">' . __('thread') . '</a>']))->toHtmlString() }}
    </p>
    <div class="mt-8">
      <div
        class="border dark:border-gray-700 divide divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 sm:grid sm:grid-cols-2 sm:gap-px">
        @forelse ($categories as $category)
          <div
            class="bg-white dark:bg-gray-900 self-stretch flex flex-col gap-3 {{ $category->knowledgeBase->isNotEmpty() ? 'pb-6' : '' }}">
            <a href="{{ route('knowledgebase.category', $category->slug) }}"
              class="group block hover:text-gray-900 dark:hover:text-gray-100 p-6">
              <h3 class="text-lg group-hover:font-bold">
                {{ $category->name }}
              </h3>
              <p class="mt-2 text-sm">
                {{ $category->description }}
              </p>
            </a>
            @forelse ($category->knowledgeBase as $kb)
              <div class="px-6 py-1 flex gap-1">
                {{ $loop->iteration }}.
                <a href="{{ route('knowledgebase.show', $kb->slug) }}"
                  class="leading-snug hover:text-gray-900 dark:hover:text-gray-100 hover:underline hover:decoration-2 hover:decoration-dotted hover:underline-offset-4 hover:decoration-gray-300 dark:hover:decoration-gray-700">
                  {{ $kb->title }}
                </a>
              </div>
            @empty
            @endforelse
          </div>
        @empty
        @endforelse
        @if ($categories->count() % 2 != 0)
          <div class="bg-white dark:bg-gray-900 self-stretch"></div>
        @endif
      </div>
    </div>
  </div>
  @if ($categories->hasPages())
    <div class="border dark:border-gray-700"></div>
    <div class="w-full bg-white dark:bg-gray-900 rounded-b-lg min-w-full p-6">
      {{ $categories->links() }}
    </div>
  @endif
</div>
@once
  @push('scripts')
    <script>
      window.addEventListener('DOMContentLoaded', (event) => {
        Livewire.hook('element.updated', (el, component) => {
          window.scrollTo({
            top: 0,
            behavior: 'smooth'
          });
        });
      });
    </script>
  @endpush
@endonce
