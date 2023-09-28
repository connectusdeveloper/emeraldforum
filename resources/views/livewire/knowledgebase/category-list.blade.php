<x-slot name="title">
  {{ $k_b_category?->name }}
</x-slot>
<x-slot name="metaTags">
  <meta name="description"
    content="{{ $k_b_category?->description ?? __('Please review knowledge base, if you cannot find the answer you are looking for? Reach out to our support team by opening forum :thread.', ['thread' => 'thread']) }}" />
</x-slot>

<div class="bg-white dark:bg-gray-900 sm:rounded-lg shadow relative isolate">
  <svg
    class="absolute inset-0 -z-10 opacity-30 h-full w-full stroke-gray-200 dark:stroke-gray-700 [mask-image:radial-gradient(100%_100%_at_top_right,white,transparent)]"
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
      @if ($k_b_category->id)
        ({{ $k_b_category->name }})
      @endif
    </h2>
    <p class="mt-0 text-base leading-normal">
      {{ str(__('Please review knowledge base, if you cannot find the answer you are looking for? Reach out to our support team by opening forum :thread.', ['thread' => '<a href="' . route('threads.create') . '" class="font-semibold link">' . __('thread') . '</a>']))->toHtmlString() }}
    </p>
    <div class="mt-8">
      <dl class="flex flex-col gap-y-12 sm:gap-x-6 divide-y dark:divide-gray-700">
        @forelse ($knowledge_base as $kb)
          <div class="-m-6 p-6 block even:bg-gray-50 dark:odd:bg-gray-900 odd:bg-white dark:even:bg-gray-950">
            <dt class="text-base font-bold leading-7 text-gray-900 dark:text-gray-100">
              {{ $kb->id }}.
              {{ $kb->title }}
            </dt>
            <dd class="mt-2 text-base text-justify">
              <div class="prose dark:prose-invert max-w-none">{{ parse_markdown($kb->body) }}</div>
            </dd>
          </div>
        @empty
        @endforelse
      </dl>
    </div>
  </div>
  @if ($knowledge_base->hasPages())
    <div class="border dark:border-gray-700"></div>
    <div class="w-full bg-white dark:bg-gray-900 rounded-b-lg min-w-full p-6">
      {{ $knowledge_base->links() }}
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
