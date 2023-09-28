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
        Livewire.on('updated-reply', () => {
          document.getElementById('reply-form').scrollIntoView();
        });
        Livewire.hook('element.updated', (el, component) => {
          document.querySelectorAll('#thread-replies pre').forEach((el) => {
            hljs.highlightElement(el);
          });
        });
      });
    </script>
  @endpush
@endonce
<x-slot name="title">
  {{ $thread->title }}
</x-slot>
<x-slot name="metaTags">
  <meta name="description" content="{{ $thread->description }}">
  @if ($thread->noindex && $thread->nofollow)
    <meta name="robots" content="noindex,nofollow">
  @elseif ($thread->noindex)
    <meta name="robots" content="noindex">
  @elseif ($thread->nofollow)
    <meta name="robots" content="nofollow">
  @endif
</x-slot>

<div
  class="bg-white dark:bg-gray-900 shadow md:rounded-md mx-auto max-w-3xl sm:-mx-6 md:mx-0 px-4 sm:px-6 lg:px-8 py-6 xl:grid xl:max-w-7xl xl:grid-cols-3">
  {{-- <div class="xl:col-span-2 xl:border-r xl:border-gray-200 dark:xl:border-gray-700 xl:pr-8"> --}}
  <div class="col-span-3">
    <div>
      <div class="flex items-start justify-between gap-x-4 border-b dark:border-gray-700 pb-4">
        <div class="flex-1">
          <h1 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
            {{ $thread->title }}
          </h1>
          <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">
            @php
              $catrgory_links = [];
              foreach ($thread->categories->reverse() as $category) {
                  $catrgory_links[] =
                      '<a href="' .
                      route('threads', $category->slug) .
                      '"
                  class="font-bold hover dark:hover:text-gray-300">' .
                      $category->name .
                      '</a>';
              }
              $catrgory_links = implode(', ', $catrgory_links);
            @endphp
            {{ str(
                __('Started :at by :user in :categories', [
                    'categories' => $catrgory_links,
                    'at' => $thread->created_at->diffForHumans(),
                    'user' =>
                        '<a href="' .
                        route('users.show', $thread->user->username) .
                        '" class="font-bold hover dark:hover:text-gray-300">' .
                        $thread->user->displayName .
                        '</a>',
                ]),
            )->toHtmlString() }}
          </p>
        </div>
        <div class="flex flex-col justify-end gap-x-4 gap-y-2">
          <x-thread-actions :thread="$thread" />
          {{-- @if ($thread->flag)
            <svg x-data x-tooltip.raw="{{ __('Flagged') }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
              class="w-5 h-5 text-orange-600">
              <path
                d="M3.5 2.75a.75.75 0 00-1.5 0v14.5a.75.75 0 001.5 0v-4.392l1.657-.348a6.449 6.449 0 014.271.572 7.948 7.948 0 005.965.524l2.078-.64A.75.75 0 0018 12.25v-8.5a.75.75 0 00-.904-.734l-2.38.501a7.25 7.25 0 01-4.186-.363l-.502-.2a8.75 8.75 0 00-5.053-.439l-1.475.31V2.75z" />
            </svg>
          @endif --}}
        </div>
      </div>

      <div class="-mt-3 pb-4">
        <x-thread-badges :thread="$thread" sharePosition="top-full ltr:right-0 rtl:left-0 mt-2" />
      </div>

      @if ($thread->flag)
        <div
          class="mt-3 -mb-3 py-3 px-4 border-t dark:border-gray-700 text-orange-600 dark:text-orange-200 bg-orange-100 dark:bg-orange-800">
          {{ __('This record is flagged, pending review.') }}
        </div>
      @endif

      <h2 class="sr-only">{{ $thread->description }}</h2>

      <div class="mt-3 pt-6 border-t dark:border-gray-700">
        <h2 class="sr-only">{{ __('Body') }}</h2>
        <div class="prose dark:prose-invert max-w-none">
          {{ parse_markdown($thread->body) }}
        </div>
      </div>

      @if ($thread->tags->isNotEmpty())
        <div class="mt-8">
          {{ __('Tags') }}: <span
            class="font-bold">{{ str(
                $thread->tags->pluck('name')->transform(function ($tag) {
                        return '<a href="' . route('threads', ['tag' => $tag]) . '">' . $tag . '</a>';
                    })->implode(', '),
            )->toHtmlString() }}</span>
        </div>
      @endif

      @if ($thread->extra_attributes->isNotEmpty())
        <div class="mt-8 rounded-md border dark:border-gray-800 px-4 py-5 sm:px-6">
          <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            @foreach ($thread->extra_attributes as $key => $value)
              <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-600 dark:text-gray-400">{{ $key }}</dt>
                <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">{{ $value }}</dd>
              </div>
            @endforeach
          </dl>
        </div>
      @endif

      @if (($settings['signature'] ?? null) && ($thread->user->meta['signature'] ?? null))
        <div class="mt-6">
          <div class="relative">
            <div class="absolute inset-0 flex items-center" aria-hidden="true">
              <div class="w-full border-t dark:border-gray-700"></div>
            </div>
            <div class="relative flex justify-start">
              <span class="bg-white dark:bg-gray-900 pr-3 text-xs font-semibold leading-6 text-gray-500">{{ __('Signature') }}</span>
            </div>
          </div>
          <div class="pt-2 text-sm">{{ str($thread->user->meta['signature'])->toHtmlString() }}</div>
        </div>
      @endif
    </div>
    <section aria-labelledby="activity-title" class="mt-8 xl:mt-10">
      <div>
        <div class="divide-y divide-gray-200 dark:divide-gray-700">
          @if ($replies->count())
            <div class="pb-2">
              <h2 id="activity-title" class="text-lg font-bold text-gray-900 dark:text-gray-100">
                {{ $thread->replies_count }} {{ __('Replies') }}
              </h2>
            </div>
          @endif
          <div class="pt-6">
            <div class="flow-root">
              <ul role="list" class="" id="thread-replies">
                @if ($thread->acceptedReply)
                  <li class="-mt-6 -mx-4 sm:-mx-6 lg:-mx-8 p-8 bg-green-100 dark:bg-green-700">
                    @livewire('forum.reply', ['reply' => $thread->acceptedReply, 'last' => false, 'showAccept' => $logged_in_user?->id == $thread->user_id], key('accepted-' . $thread->acceptedReply->id))
                  </li>
                @endif
                @forelse ($replies as $reply)
                  @if ($thread->acceptedReply?->id != $reply->id)
                    <li class="-mt-12 -mx-4 sm:-mx-6 lg:-mx-8 p-8 odd:bg-white dark:odd:bg-gray-900 even:bg-gray-50 dark:even:bg-gray-950">
                      @livewire('forum.reply', ['reply' => $reply, 'last' => $loop->last, 'showAccept' => $logged_in_user?->id == $thread->user_id], key($reply->id))
                    </li>
                  @endif
                @empty
                  <li class="-mt-12 -mx-4 sm:-mx-6 lg:-mx-8 p-8 odd:bg-white dark:odd:bg-gray-900 even:bg-gray-50 dark:even:bg-gray-950">
                    <div class="flex items-center justify-center mb-8 text-gray-500">
                      {{ __('No one is replied to this thread yet. Be first to reply!') }}
                    </div>
                  </li>
                @endforelse
                @if ($replies->hasPages())
                  <li
                    class="relative -mt-8 -mx-4 sm:-mx-6 lg:-mx-8 px-8 py-6 bg-white dark:bg-gray-900 border-t-2 border-b-2 dark:border-gray-700">
                    <div class="relative w-full min-w-full">
                      {{ $replies->links() }}
                    </div>
                    {{-- <div class="border-t dark:border-gray-700 mb-6"></div> --}}
                  </li>
                @else
                  <li class="relative -my-12 -mx-4 sm:-mx-6 lg:-mx-8 px-8 py-6 bg-white dark:bg-gray-900 border-t-2 dark:border-gray-700">
                  </li>
                @endif
              </ul>
            </div>
            @if ($thread->flag && ($settings['hide_flagged'] ?? null))
              <div
                class="relative -mt-12 -mx-4 sm:-mx-6 lg:-mx-8 px-8 pt-6 text-center text-gray-500 border-t-2 dark:border-gray-700 bg-white dark:bg-gray-900">
                {{ __('This thread is under review.') }}
              </div>
            @elseif($this->thread->approved)
              <div id="reply-form-con">
                @auth
                  <div id="reply-form" class="relative mt-8">
                    <div class="flex gap-x-3">
                      <div class="flex-shrink-0">
                        <div class="relative">
                          <img
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 dark:bg-gray-600 ring ring-white dark:ring-gray-800"
                            src="{{ $logged_in_user?->profile_photo_url }}" alt="{{ $logged_in_user?->name }}">
                        </div>
                      </div>
                      <div class="min-w-0 flex-1">
                        <form wire:submit.prevent="reply" autocomplete="off">
                          <div class="grid grid-cols-1 sm:grid-cols-6 gap-6">
                            <div class="col-span-6 small-editor -mt-1">
                              <label for="reply-body" class="sr-only">{{ __('Reply') }}</label>
                              {{-- <x-textarea id="reply-body" :placeholder="__('Type your reply to thread here')" wire:model.defer="form.body" /> --}}
                              <x-editor wire:model="form.body" id="reply-body" property="body" property_of="form" :model="$form['body'] ?? ''" />
                            </div>
                            <!-- Custom Fields -->
                            <x-custom-fields model="Reply" :custom_fields="$custom_fields" :extra_attributes="$thread->extra_attributes" />
                          </div>
                          <div class="mt-6 flex items-center justify-end gap-x-4">
                            <x-jet-action-message class="ltr:mr-3 rtl:ml-3" on="saved">
                              {{ __('Saved.') }}
                            </x-jet-action-message>
                            <button type="submit"
                              class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-bold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2">{{ __('Reply') }}</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @elseif($settings['guest_reply'] ?? false)
                  <div id="reply-form" class="relative mt-8">
                    <div class="flex gap-x-3">
                      <div class="flex-shrink-0">
                        <div class="relative">
                          <span
                            class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 dark:bg-gray-600 ring ring-white dark:ring-gray-800 text-sm">GU</span>
                        </div>
                      </div>
                      <div class="min-w-0 flex-1">
                        <form wire:submit.prevent="reply" autocomplete="off">
                          <div class="small-editor -mt-1">
                            <label for="reply-body" class="sr-only">{{ __('Reply') }}</label>
                            {{-- <x-textarea id="reply-body" :placeholder="__('Type your reply to thread here')" wire:model.defer="form.body" /> --}}
                            <x-editor wire:model="form.body" id="reply-body" property="body" property_of="form" :model="$form['body'] ?? ''" />
                            <div class="mt-6 grid grid-cols-2 gap-6">
                              <div>
                                <x-ui-input :placeholder="__('Name')" wire:model.defer="form.guest_name" />
                              </div>
                              <div>
                                <x-ui-input type="email" :placeholder="__('Email')" wire:model.defer="form.guest_email" />
                              </div>
                            </div>
                            <div class="mt-6 grid grid-cols-1 sm:grid-cols-6 gap-6">
                              <!-- Custom Fields -->
                              <x-custom-fields model="Reply" :custom_fields="$custom_fields" :extra_attributes="$thread->extra_attributes" />
                            </div>
                          </div>
                          <div class="mt-6 flex items-center justify-end gap-x-4">
                            <x-jet-action-message class="ltr:mr-3 rtl:ml-3" on="saved">
                              {{ __('Saved.') }}
                            </x-jet-action-message>
                            <button type="submit"
                              class="inline-flex items-center justify-center rounded-md border border-transparent bg-blue-600 hover:bg-blue-700 px-4 py-2 text-sm font-bold text-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-700 focus:ring-offset-2">{{ __('Reply') }}</button>
                          </div>
                        </form>
                      </div>
                    </div>
                  </div>
                @else
                  <div class="relative mt-8">
                    <div class="flex items-center gap-x-3">
                      {{-- <div class="flex-shrink-0">
                      <a href="{{ route('login') }}" class="relative">
                        <spam
                          class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 dark:bg-gray-600 ring ring-white dark:ring-gray-800" />
                      </a>
                    </div> --}}
                      <a href="{{ route('login') }}" class="min-w-0 flex- font-bold">
                        {{ __('Please login to reply.') }}
                      </a>
                    </div>
                  </div>
                @endauth
              </div>
            @else
              <p class="mt-6 text-yellow-600 dark:text-yellow-200">{{ __('Thread is not approved yet.') }}</p>
            @endif
          </div>
        </div>
      </div>
    </section>
  </div>
</div>
