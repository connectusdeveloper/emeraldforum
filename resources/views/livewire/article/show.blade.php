<div>
  <x-slot name="title">
    {{ $article->title }}
  </x-slot>
  <x-slot name="metaTags">
    <meta name="description" content="{{ $article->description }}">
    @if ($article->noindex && $article->nofollow)
      <meta name="robots" content="noindex,nofollow">
    @elseif ($article->noindex)
      <meta name="robots" content="noindex">
    @elseif ($article->nofollow)
      <meta name="robots" content="nofollow">
    @endif
  </x-slot>

  <div class="bg-white dark:bg-gray-900 rounded-md shadow p-6 relative isolate overflow-hidden">
    @if ($article->image)
      <div class="-mt-6 -mx-6">
        <img src="{{ $article->image }}" alt="" class="min-w-full shrink-0 mb-6 rounded-t-md" />
      </div>
    @endif
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
    <div class="absolute top-2 right-2">
      <div x-data="{ show: false }" @click.away="show = false" class="relative inline-flex items-center">
        <span class="inline-flex items-center text-sm">
          <button type="button" @click="show = true"
            class="inline-flex ltr:space-x-2 text-gray-400 hover:text-gray-500 dark:hover:text-gray-300">
            <svg class="h-5 w-5 rtl:ml-2" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
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
            <a href="{{ $socialLink->vk->shareUrl }}" class="block px-4 py-2 text-sm rounded-md hover:bg-gray-100 dark:hover:bg-gray-800">
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
    <div class="mb-2">
      <h1 class="text-xl font-bold leading-6 text-gray-900 dark:text-gray-100">
        {{ $article->title }}
      </h1>
      <h2 class="text-sm font-bold mt-2 max-w-4xl">{{ $article->description }}</h2>
    </div>
    <div class="prose dark:prose-invert max-w-none">{{ parse_markdown($article->body) }}</div>
  </div>
</div>
