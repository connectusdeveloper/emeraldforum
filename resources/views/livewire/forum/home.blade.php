<div>
  <x-slot name="title">
    {{ $category?->id ? $category->title : $settings['title'] ?? ($settings['name'] ?? config('app.name', 'Simple Forum')) }}
  </x-slot>
  <x-slot name="metaTags">
    <meta name="description" content="{{ $category?->id ? $category->description : $settings['description'] ?? '' }}" />
  </x-slot>

  @once
    @push('scripts')
      <script>
        window.addEventListener('DOMContentLoaded', (event) => {
          Livewire.on('page-changed', () => {
            window.scrollTo({
              top: 0,
              behavior: 'smooth'
            });
          });
          // Livewire.on('thread-deleted', () => {
          //   window.location.reload();
          // });
        });
      </script>
    @endpush
  @endonce

  <div class="relative isolate overflow-hidden shadow bg-white dark:bg-gray-900 sm:rounded-lg mb-6 py-6">
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
    <div class="mx-auto max-w-7xl px-6">
      <div class="mx-auto max-w-2xl lg:mx-0">

        <h1 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-white sm:text-4xl">
          {{ $category?->id ? $category->name : ($settings['name'] ?? null ?: config('app.name', 'Simple Forum')) }}
          @if ($sorting == 'trending')
            <span class="font-light text-2xl">({{ __('Trending') }})</span>
          @endif
        </h1>
        <h3 class="mt-4 text-lg leading-relaxed">
          {{ $category?->id ? $category->description : $settings['description'] ?? '' }}
        </h3>
      </div>

      @unless ($category?->id)
        <div class="mx-auto mt-8 flex flex-wrap just gap-6 lg:mx-0">
          @if ($logged_in_user?->can('approve-threads') && request()->require_approval == 'yes')
            <button
              x-on:confirm="{
            icon: 'question',
            style: 'inline',
            iconColor: 'text-primary-700',
            iconBackground: 'bg-primary-100 rounded-full p-2',
            title: '{{ __('Approve Threads') }}',
            description: '{{ __('Are you sure to approve all the records?') }}',
            accept: {
              label: '{{ __('Yes, approve all') }}',
              method: 'approve',
            }
          }"
              class="flex gap-x-4 rounded-lg bg-primary-100 dark:bg-primary-800 px-5 py-3 hover:bg-primary-50 dark:hover:bg-primary-700">
              <span>{{ __('Approve all threads') }}</span>
            </button>
          @endif
          @if ($settings['faqs'])
            <a href="{{ route('faqs.list') }}"
              class="flex gap-x-4 rounded-lg bg-gray-100 dark:bg-gray-800 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-5 flex-none text-blue-400">
                <path fill-rule="evenodd"
                  d="M2.25 12c0-5.385 4.365-9.75 9.75-9.75s9.75 4.365 9.75 9.75-4.365 9.75-9.75 9.75S2.25 17.385 2.25 12zm11.378-3.917c-.89-.777-2.366-.777-3.255 0a.75.75 0 01-.988-1.129c1.454-1.272 3.776-1.272 5.23 0 1.513 1.324 1.513 3.518 0 4.842a3.75 3.75 0 01-.837.552c-.676.328-1.028.774-1.028 1.152v.75a.75.75 0 01-1.5 0v-.75c0-1.279 1.06-2.107 1.875-2.502.182-.088.351-.199.503-.331.83-.727.83-1.857 0-2.584zM12 18a.75.75 0 100-1.5.75.75 0 000 1.5z"
                  clip-rule="evenodd" />
              </svg>
              <div class="text-base leading-7">
                <h3 class="font-semibold">{{ __('FAQs') }}</h3>
                {{-- <p class="mt-2 text-gray-300">Consectetur vel non. Rerum ut consequatur nobis unde. Enim est quo corrupti consequatur.</p> --}}
              </div>
            </a>
          @endif

          @if ($settings['knowledgebase'])
            <a href="{{ route('knowledgebase.list') }}"
              class="flex gap-x-4 rounded-lg bg-gray-100 dark:bg-gray-800 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg class="h-7 w-5 flex-none text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                aria-hidden="true">
                <path fill-rule="evenodd"
                  d="M7.171 4.146l1.947 2.466a3.514 3.514 0 011.764 0l1.947-2.466a6.52 6.52 0 00-5.658 0zm8.683 3.025l-2.466 1.947c.15.578.15 1.186 0 1.764l2.466 1.947a6.52 6.52 0 000-5.658zm-3.025 8.683l-1.947-2.466c-.578.15-1.186.15-1.764 0l-1.947 2.466a6.52 6.52 0 005.658 0zM4.146 12.83l2.466-1.947a3.514 3.514 0 010-1.764L4.146 7.171a6.52 6.52 0 000 5.658zM5.63 3.297a8.01 8.01 0 018.738 0 8.031 8.031 0 012.334 2.334 8.01 8.01 0 010 8.738 8.033 8.033 0 01-2.334 2.334 8.01 8.01 0 01-8.738 0 8.032 8.032 0 01-2.334-2.334 8.01 8.01 0 010-8.738A8.03 8.03 0 015.63 3.297zm5.198 4.882a2.008 2.008 0 00-2.243.407 1.994 1.994 0 00-.407 2.243 1.993 1.993 0 00.992.992 2.008 2.008 0 002.243-.407c.176-.175.31-.374.407-.585a2.008 2.008 0 00-.407-2.243 1.993 1.993 0 00-.585-.407z"
                  clip-rule="evenodd" />
              </svg>
              <div class="text-base leading-7">
                <h3 class="font-semibold">{{ __('Knowledge base') }}</h3>
              </div>
            </a>
          @endif

          @if ($settings['articles'] ?? null)
            <a href="{{ route('articles.list') }}"
              class="flex gap-x-4 rounded-lg bg-gray-100 dark:bg-gray-800 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="h-7 w-5 flex-none text-blue-400">
                <path fill-rule="evenodd"
                  d="M5.625 1.5c-1.036 0-1.875.84-1.875 1.875v17.25c0 1.035.84 1.875 1.875 1.875h12.75c1.035 0 1.875-.84 1.875-1.875V12.75A3.75 3.75 0 0016.5 9h-1.875a1.875 1.875 0 01-1.875-1.875V5.25A3.75 3.75 0 009 1.5H5.625zM7.5 15a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5A.75.75 0 017.5 15zm.75 2.25a.75.75 0 000 1.5H12a.75.75 0 000-1.5H8.25z"
                  clip-rule="evenodd" />
                <path
                  d="M12.971 1.816A5.23 5.23 0 0114.25 5.25v1.875c0 .207.168.375.375.375H16.5a5.23 5.23 0 013.434 1.279 9.768 9.768 0 00-6.963-6.963z" />
              </svg>
              <div class="text-base leading-7">
                <h3 class="font-semibold">{{ __('Articles') }}</h3>
              </div>
            </a>
          @endif

          @if ($settings['contact'] ?? null)
            <a href="{{ route('contact') }}"
              class="flex gap-x-4 rounded-lg bg-gray-100 dark:bg-gray-800 px-5 py-3 hover:bg-gray-50 dark:hover:bg-gray-700">
              <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                class="h-7 w-5 flex-none text-blue-400">
                <path stroke-linecap="round"
                  d="M16.5 12a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zm0 0c0 1.657 1.007 3 2.25 3S21 13.657 21 12a9 9 0 10-2.636 6.364M16.5 12V8.25" />
              </svg>

              <div class="text-base leading-7">
                <h3 class="font-semibold">{{ __('Contact us') }}</h3>
              </div>
            </a>
          @endif
        </div>
      @endunless
    </div>
  </div>



  <div class="px-4 sm:px-0">
    <div class="sm:hidden">
      <label for="sel-sorting" class="sr-only">{{ __('Sorting') }}</label>
      <select id="sel-sorting" wire:model="sorting"
        class="block w-full rounded-md border-gray-300 dark:border-gray-700 text-base font-bold text-gray-900 dark:text-gray-100 shadow-sm focus:border-blue-500 focus:ring-blue-500 dark:bg-gray-800">
        <option value="latest">{{ __('Recent') }}</option>
        <option value="likes">{{ __('Most Liked') }}</option>
        <option value="replies">{{ __('Most Replies') }}</option>
      </select>
    </div>
    <div class="hidden sm:block">
      <nav class="isolate flex rounded-lg shadow">
        <label @class([
            'group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-gray-900 py-4 px-6 text-sm font-bold text-center focus:z-10 rtl:rounded-r-lg ltr:rounded-l-lg',
            $sorting == 'latest'
                ? 'text-gray-900 dark:text-gray-100 border-b-2 border-primary-500'
                : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700' => true,
        ])>
          <span>{{ __('Recent') }}</span>
          <input type="radio" wire:model="sorting" value="latest" class="sr-only">
        </label>

        <label @class([
            'group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-gray-900 py-4 px-6 text-sm font-bold text-center focus:z-10',
            $sorting == 'likes'
                ? 'text-gray-900 dark:text-gray-100 border-b-2 border-primary-500'
                : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700 border-r border-l dark:border-gray-800' => true,
        ])>
          <span>{{ __('Most Liked') }}</span>
          <input type="radio" wire:model="sorting" value="likes" class="sr-only">
        </label>

        <label @class([
            'group relative min-w-0 flex-1 overflow-hidden bg-white dark:bg-gray-900 py-4 px-6 text-sm font-bold text-center focus:z-10 rtl:rounded-l-lg ltr:rounded-r-lg',
            $sorting == 'replies'
                ? 'text-gray-900 dark:text-gray-100 border-b-2 border-primary-500'
                : 'cursor-pointer hover:bg-gray-50 dark:hover:bg-gray-700' => true,
        ])>
          <span>{{ __('Most Answers') }}</span>
          <input type="radio" wire:model="sorting" value="replies" class="sr-only">
        </label>
      </nav>
    </div>
  </div>

  <div class="mt-6">
    <h1 class="sr-only">
      {{ $sorting == 'likes' ? __('Sort by most likes') : ($sorting == 'replies' ? __('Sort by most replies') : __('Recent threads')) }}
    </h1>
    <ul role="list" class="space-y-6">
      @forelse ($threads as $thread)
        <li>
          @livewire('forum.thread-overview', ['settings' => $settings, 'thread' => $thread, 'category' => $category], key($thread->id))
        </li>
      @empty
        <li>
          <div class="flex items-center justify-center text-2xl font-thin h-32 text-gray-500">
            {{ __('No thread to display') }}
          </div>
        </li>
      @endforelse
    </ul>

    @if ($threads->hasPages())
      <div class="mt-6"></div>
      <div class="w-fulln sm:rounded-lg bg-white dark:bg-gray-900 min-w-full p-6">
        {{ $threads->links() }}
      </div>
    @endif
  </div>
</div>
