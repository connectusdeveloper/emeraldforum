@once
  @push('scripts')
    <script>
      document.addEventListener('DOMContentLoaded', (event) => {
        document.querySelectorAll('pre').forEach((el) => {
          hljs.highlightElement(el);
        });
      });
    </script>
  @endpush
@endonce
<x-slot name="title">
  {{ $faq->question }}
</x-slot>
<x-slot name="metaTags">
  <meta name="description" content="{{ $faq->description }}">
  @if ($faq->noindex && $faq->nofollow)
    <meta name="robots" content="noindex,nofollow">
  @elseif ($faq->noindex)
    <meta name="robots" content="noindex">
  @elseif ($faq->nofollow)
    <meta name="robots" content="nofollow">
  @endif
</x-slot>

<div>
  <div class="bg-white dark:bg-gray-900 rounded-md shadow p-6 relative isolate">
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
    <div class="mb-6">
      <h1 class="text-xl font-bold leading-6 text-gray-900 dark:text-gray-100">
        {{ $faq->question }}
      </h1>
      <div class="text-sm mt-2 flex gap-x-8 gap-y-2">
        <span>
          {{ _('From') }}:
          @forelse (($faq->FaqCategories?->reverse() ?? []) as $category)
            <a href="{{ route('faqs.list', $category->slug) }}">{{ $category->name }}</a>
            {{ $loop->last ? '' : ', ' }}
          @empty
          @endforelse
        </span>
        <span>{{ _('Last Updated') }}: {{ $faq->updated_at->diffForHumans() }}</span>
      </div>
    </div>
    <div class="prose dark:prose-invert max-w-none">{{ parse_markdown($faq->answer) }}</div>
  </div>
</div>
