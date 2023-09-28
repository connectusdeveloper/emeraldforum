<div>
  <x-slot name="title">
    {{ __('Messages') }}
  </x-slot>
  @if ($conversations->isEmpty())
    <div class="flex items-center justify-center mx-auto max-w-3xl sm:px-6 lg:grid lg:max-w-7xl lg:gap-8 lg:px-8 min-h-[250px]"
      style="height: calc(100vh - 340px)">
      <h3 class="text-2xl text-gray-500">{{ __('No conversations to display.') }}</h3>
    </div>
  @else
    <div class="mx-auto max-w-3xl sm:px-6 lg:grid lg:max-w-7xl lg:grid-cols-12 lg:gap-8 lg:px-8 min-h-[500px]"
      style="height: calc(100vh - 150px)">
      <aside class="hidden lg:col-span-4 lg:block bg-white dark:bg-gray-900 shadow rounded-lg overflow-y-auto">
        <div class="relative">
          <div class="py-3">
            {{-- <h3 class="text-xs font-semibold uppercase text-gray-400 mb-1">Users</h3> --}}
            <div class="divide-y dark:divide-gray-700">
              @forelse ($conversations as $conversation)
                {{-- <button type="button" wire:click="selectConversation('{{ $conversation->id }}')" --}}
                <a href="{{ route('conversations', ['conversation' => $conversation->id]) }}" @class([
                    'block w-full px-5 py-3 focus:outline-none ltr:text-left rtl:text-right',
                    'bg-gray-200 dark:bg-gray-950' =>
                        $selected && $selected?->id == $conversation->id,
                ])>
                  @if ($logged_in_user?->id != $conversation->receiver->id)
                    <div class="flex items-start gap-4">
                      <img class="rounded-full items-start flex-shrink-0 w-12 h-12" src="{{ $conversation->receiver->profile_photo_url }}"
                        alt="{{ $conversation->receiver->name }}" />
                      <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $conversation->receiver->name }}</h4>
                        <div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1">{{ $conversation->lastMessage->body }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                          {{ $conversation->lastMessage->created_at->diffForHumans() }}
                        </div>
                      </div>
                    </div>
                  @else
                    <div class="flex items-start gap-4">
                      <img class="rounded-full items-start flex-shrink-0 w-12 h-12" src="{{ $conversation->user->profile_photo_url }}"
                        alt="{{ $conversation->user->name }}" />
                      <div>
                        <h4 class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $conversation->user->name }}</h4>
                        <div class="text-sm text-gray-600 dark:text-gray-400 line-clamp-1">{{ $conversation->lastMessage->body }}</div>
                        <div class="text-sm text-gray-600 dark:text-gray-400">
                          {{ $conversation->lastMessage->created_at->diffForHumans() }}
                        </div>
                      </div>
                    </div>
                  @endif
                </a>
              @empty
              @endforelse
            </div>
          </div>
      </aside>
      <main
        class="col-span-12 lg:col-span-8 flex flex-col items-center justify-center w-full shadow bg-white dark:bg-gray-900 sm:rounded-lg">
        <div class="flex flex-col flex-grow w-full rounded-lg overflow-hidden">
          {{-- @if (!$selected?->id)
            <p class="p-4">{{ __('Please select a user to load messages.') }}</p>
          @endif --}}
          @if ($messages->isNotEmpty())
            <div id="messages-con" class="relative flex flex-col flex-grow h-0 p-4 overflow-y-auto min-h-[432px]" x-data="{
                page: 0,
                show: false,
                messages: [],
                loading: false,
                observer: null,
                currentOffset: 0,
                selected_id: null,
                triggerElement: null,
                data: {{ json_encode($messages) }},
                isObserverPolyfilled: false,
                messagesCon: document.getElementById('messages-con'),
                async init(elementId) {
                    const ctx = this;
                    this.page = 0;
                    this.messages = [];
                    this.currentOffset = 0;
                    this.selected_id = {{ $selected->id }};
                    this.data = {{ json_encode($messages) }};
                    @if ($selected && $selected?->id) this.getPage(); @endif
                    this.messagesCon = document.getElementById('messages-con');
                    {{-- this.getPage(); --}}
            
                    {{-- Livewire.on('conversation-changed', () => {
                        this.page = 0;
                        this.currentOffset = 0;
                        this.selected_id = {{ $selected->id }};
                        this.data = {{ json_encode($messages) }};
                        this.getPage();
                    }); --}}
            
                    Livewire.on('message-sent', () => {
                        this.page = 0;
                        this.messages = [];
                        this.currentOffset = 0;
                        this.selected_id = {{ $selected->id }};
                        this.data = {{ json_encode($messages) }};
                        this.getPage();
                    });
            
                    {{-- this.show = true; --}}
                    this.triggerElement = document.querySelector(elementId ? elementId : '#infinite-scroll-trigger');
                    if (this.triggerElement) {
                        if (!('IntersectionObserver' in window) ||
                            !('IntersectionObserverEntry' in window) ||
                            !('isIntersecting' in window.IntersectionObserverEntry.prototype) ||
                            !('intersectionRatio' in window.IntersectionObserverEntry.prototype)) {
                            this.isObserverPolyfilled = true;
                            window.alpineInfiniteScroll = {
                                scrollFunc() {
                                    var messagesCon = document.getElementById('messages-con');
                                    var position = ctx.triggerElement.getBoundingClientRect();
                                    if (position.top < messagesCon.innerHeight && position.bottom >= 0) {
                                        ctx.getPage();
                                    }
                                }
                            }
                            window.addEventListener('scroll', window.alpineInfiniteScroll.scrollFunc)
                        } else {
                            this.observer = new IntersectionObserver(function(entries) {
                                if (entries[0].isIntersecting === true) { ctx.getPage(); }
                            }, { threshold: [0] })
                            this.observer.observe(this.triggerElement);
                        }
                    }
                },
                async getPage() {
                    if (!this.loading && this.page < this.data.last_page) {
                        this.loading = true;
                        {{-- setTimeout(async () => { --}}
                        this.page++;
                        this.currentOffset = this.messagesCon.scrollHeight;
                        let url = `/conversations/${this.selected_id}/messages`;
                        {{-- let url = {{ route('conversations.show', ['conversation' => $selected->id]) }}; --}}
                        let res = await fetch(url, {
                            headers: {
                                'Accept': 'application/json',
                                'Content-Type': 'application/json',
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-Token': '{{ csrf_token() }}'
                            },
                            method: 'post',
                            credentials: 'same-origin',
                            body: JSON.stringify({ page: this.page })
                        });
                        if (res.ok) {
                            let messages = await res.json();
                            messages.map(m => this.messages.unshift(m));
                            if (this.page == 1) {
                                setTimeout(() => {
                                    this.messagesCon.scrollTop = this.messagesCon.scrollHeight + 1000;
                                }, 10);
                            } else {
                                setTimeout(() => {
                                    this.messagesCon.scrollTop = this.messagesCon.scrollHeight - this.currentOffset;
                                }, 10);
                            }
                        }
                        this.loading = false;
            
                        if (this.data.last_page && this.page >= this.data.last_page) {
                            if (this.isObserverPolyfilled) {
                                window.removeEventListener('scroll', window.alpineInfiniteScroll.scrollFunc)
                            } else {
                                this.observer.unobserve(this.triggerElement);
                            }
                            try {
                                this.triggerElement.parentNode.removeChild(this.triggerElement);
                            } catch (e) {}
                        }
                        {{-- }, 2000); --}}
                    }
                }
            }">
              {{-- x-init="$watch('selected_id', value => console.log(value))"> --}}
              {{-- <div x-show="loading" class="bg-gray-500 opacity-50 absolute inset-0 flex items-end justify-center"></div> --}}
              <div class="flex flex-col flex-grow justify-end">
                <div class="h-8 mb-4 w-full" id="infinite-scroll-trigger">
                  <div x-show="show && data.current_page != data.last_page" class="flex flex-col items-center justify-center gap-2">
                    <svg class="animate-spin -ml-1 mr-3 h-5 w-5 " xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2">
                      </circle>
                      <path class="opacity-75" fill="currentColor"
                        d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                      </path>
                    </svg>
                    <span>{{ __('Loading') }}...</span>
                  </div>
                </div>

                <template x-for="message in messages">
                  <div>
                    <template x-if="message.user.id == '{{ $selected->user->id }}'">
                      <div class="flex w-full mt-2 space-x-3 max-w-lg">
                        <img
                          class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 dark:bg-gray-600 ring-2 ring-gray-200 dark:ring-gray-700"
                          :src="message.user.profile_photo_url" :alt="message.user.display_name">
                        <div class="flex flex-col items-start gap-1">
                          <div class="inline-block bg-gray-200 dark:bg-gray-800 p-3 rounded-r-lg rounded-bl-lg">
                            <p class="text-sm" x-text="message.body"></p>
                          </div>
                          <span class="text-xs text-gray-500 leading-none" x-text="message.time"></span>
                        </div>
                      </div>
                    </template>
                    <template x-if="message.user.id != '{{ $selected->user->id }}'">
                      <div class="flex w-full mt-2 space-x-3 max-w-lg ml-auto justify-end">
                        <div class="flex flex-col items-start gap-1">
                          <div class="inline-block bg-primary-600 text-white p-3 rounded-l-lg rounded-br-lg">
                            <p class="text-sm" x-text="message.body"></p>
                          </div>
                          <span class="text-xs text-gray-500 leading-none" x-text="message.time"></span>
                        </div>
                        <img
                          class="flex h-10 w-10 items-center justify-center rounded-full bg-gray-400 dark:bg-gray-600 ring-2 ring-gray-200 dark:ring-gray-700"
                          :src="message.user.profile_photo_url" :alt="message.user.display_name">
                      </div>
                    </template>
                  </div>
                </template>
              </div>
            </div>
          @endif

          <div class="bg-gray-100 dark:bg-gray-950 p-4">
            @if ($selected && $selected->id)
              {{-- <x-ui-input placeholder="Type your message…" /> --}}
              <div>
                <label for="email" class="sr-only">{{ __('Message') }}</label>
                <div class="flex rounded-md shadow-sm">
                  <div class="relative flex flex-grow items-stretch focus-within:z-10">
                    <input type="text" wire:model.defer="message" id="chat-message" autocomplete="none"
                      class="block w-full rounded-none rounded-l-md border-0 py-1.5 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 placeholder:text-gray-400 focus:ring-2 focus:ring-inset focus:ring-primary-600 dark:focus:ring-primary-600 sm:text-sm sm:leading-6 dark:bg-gray-800"
                      placeholder="{{ __('Type your message here') }}">
                  </div>
                  <button type="button" wire:click="reply"
                    class="relative -ml-px inline-flex items-center gap-x-1.5 rounded-r-md px-3 py-2 text-sm font-semibold text-gray-950 dark:text-gray-100 ring-1 ring-inset ring-gray-300 dark:ring-gray-700 hover:bg-gray-200 dark:hover:bg-gray-900">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" <svg
                      class="-ml-0.5 h-5 w-5 text-gray-400" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                      <path stroke-linecap="round" stroke-linejoin="round"
                        d="M6 12L3.269 3.126A59.768 59.768 0 0121.485 12 59.77 59.77 0 013.27 20.876L5.999 12zm0 0h7.5" />
                    </svg>
                    {{ __('Send') }}
                  </button>
                </div>
              </div>
            @else
              <p>{{ __('Please select a user to load messages.') }}</p>
            @endif
          </div>
        </div>
      </main>
    </div>
  @endif
</div>
