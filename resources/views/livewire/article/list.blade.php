<x-slot name="metaTags">
  <meta name="description"
    content="{{ __('Please review articles, if you cannot find the answer you are looking for? Reach out to our support team by opening forum :thread.', ['thread' => __('thread')]) }}" />
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
      {{ __('Articles') }}
    </h2>
    <p class="mt-4 text-base leading-7">
      {{ str(__('Please review articles, if you cannot find the answer you are looking for? Reach out to our support team by opening forum :thread.', ['thread' => '<a href="' . route('threads.create') . '" class="font-semibold link">' . __('thread') . '</a>']))->toHtmlString() }}
    </p>
    <div class="mt-8">
      {{-- <div
        class="border dark:border-gray-700 divide divide-gray-200 dark:divide-gray-700 rounded-lg overflow-hidden bg-gray-100 dark:bg-gray-700 shadow sm:grid sm:grid-cols-2 sm:gap-px">
        @forelse ($articles as $article)
          <a href="{{ route('articles.show', $article->slug) }}"
            class="block bg-white dark:bg-gray-900bg-white dark:bg-gray-900 hover:bg-gray-100 dark:hover:bg-gray-800 p-6">
            <h3 class="text-lg font-medium">
              {{ $article->title }}
            </h3>
            <p class="mt-2 text-sm">
              {{ $article->description }}
            </p>
          </a>
        @empty
        @endforelse
        @if ($articles->count() % 2 != 0)
          <div class="bg-white dark:bg-gray-900 self-stretch"></div>
        @endif
      </div> --}}

      <dl class="flex flex-col gap-y-12 sm:gap-x-6 divide-y dark:divide-gray-700">
        @forelse ($articles as $article)
          @php
            $socialLink = new \SocialLinks\Page([
                'url' => route('articles.show', $article->slug),
                'title' => $article->title,
                'text' => $article->description,
                'image' => $article->image,
                // 'icon' => $settings['icon'] ?? '',
                // 'twitterUser' => '@twitterUser',
            ]);
          @endphp
          <div class="-m-6 p-6 block even:bg-gray-50 dark:odd:bg-gray-900 odd:bg-white dark:even:bg-gray-950">
            <div class="flex items-start gap-x-4 gap-y-2">
              @if ($article->image)
                <div class="block mt-1 w-32 shrink-0">
                  <a href="{{ route('articles.show', $article->slug) }}">
                    <img src="{{ $article->image }}" alt="" class="w-32 rounded" />
                  </a>
                </div>
              @endif
              <div class="grow w-full block">
                <dt class="text-base font-bold leading-7 text-gray-900 dark:text-gray-100 relative">
                  <a href="{{ route('articles.show', $article->slug) }}">
                    {{ $article->id }}.
                    {{ $article->title }}
                  </a>
                  <div class="absolute top-0 right-0">
                    <div x-data="{ show: false }" @click.away="show = false" class="relative inline-flex items-center">
                      <span class="inline-flex items-center text-sm">
                        <button type="button" @click="show = true"
                          class="inline-flex ltr:space-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
                          <svg class="h-5 w-5 rtl:ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                            aria-hidden="true">
                            <path
                              d="M13 4.5a2.5 2.5 0 11.702 1.737L6.97 9.604a2.518 2.518 0 010 .792l6.733 3.367a2.5 2.5 0 11-.671 1.341l-6.733-3.367a2.5 2.5 0 110-3.475l6.733-3.366A2.52 2.52 0 0113 4.5z" />
                          </svg>
                          <span class="text-gray-900 dark:text-gray-300">{{ __('Share') }}</span>
                        </button>
                      </span>

                      <div x-show="show" style="display: none"
                        class="absolute ltr:right-0 rtl:left-0 top-full mb-2 z-10 mt-1 w-64 md:w-96 ltr:origin-bottom-left rtl:origin-bottom-right rounded-md bg-white dark:bg-gray-700 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none overflow-hidden">
                        <div class="grid grid-cols-2 md:grid-cols-3 p-1 gap-1">
                          <a href="{{ $socialLink->blogger->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Blogger
                          </a>
                          <a href="{{ $socialLink->facebook->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Facebook
                          </a>
                          <a href="{{ $socialLink->linkedin->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            LinkedIn
                          </a>
                          <a href="{{ $socialLink->pinterest->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Pinterest
                          </a>
                          <a href="{{ $socialLink->pocket->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Pocket
                          </a>
                          <a href="{{ $socialLink->reddit->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Reddit
                          </a>
                          <a href="{{ $socialLink->stumbleupon->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Stumbleupon
                          </a>
                          <a href="{{ $socialLink->telegram->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Telegram
                          </a>
                          <a href="{{ $socialLink->tumblr->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Tumblr
                          </a>
                          <a href="{{ $socialLink->twitter->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Twitter
                          </a>
                          <a href="{{ $socialLink->vk->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            VK
                          </a>
                          <a href="{{ $socialLink->whatsapp->shareUrl }}"
                            class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
                            Whatsapp
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                </dt>
                <dd class="mt-2 text-base text-justify">
                  <a href="{{ route('articles.show', $article->slug) }}">
                    {{ parse_markdown($article->description) }}
                  </a>
                </dd>
              </div>
            </div>
          </div>
        @empty
        @endforelse
      </dl>
    </div>
  </div>
  @if ($articles->hasPages())
    <div class="border dark:border-gray-700"></div>
    <div class="w-full bg-white dark:bg-gray-900 rounded-b-lg min-w-full p-6">
      {{ $articles->links() }}
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
