<div x-data="{
    user: null,
    show: false,
    message: '',
    init() {
        window.addEventListener('show-message-modal', (event) => {
            document.querySelectorAll('[data-tippy-root]').forEach(function(el) {
                el.style.visibility = 'hidden';
            });
            this.show = true;
            this.user = JSON.parse(event.detail.user);
            setTimeout(() => document.getElementById('user-message-textarea').focus(), 50);
        });
        Livewire.on('message-sent', () => {
            this.user = null;
            this.message = '';
            this.show = false;
        });
    },
    send() {
        @this.user_id = this.user.id;
        @this.message = this.message;
        @this.send();
    }
}">
  <div x-show="show" x-transition class="fixed inset-0" style="display: none">
    <div class="relative z-10" aria-labelledby="modal-title" role="dialog" aria-modal="true">
      <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity"></div>


      <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-end justify-center p-4 text-center sm:items-center sm:p-0">
          <div @click.away="show = false"
            class="relative transform overflow-hidden rounded-lg bg-white dark:bg-gray-900 p-4 text-left shadow-xl transition-all sm:my-8 sm:w-full sm:max-w-sm sm:p-6">
            <button type="button" @click="show=false" class="absolute top-2 right-1 w-7 h-7">
              <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-6 h-6">
                <path fill-rule="evenodd"
                  d="M5.47 5.47a.75.75 0 011.06 0L12 10.94l5.47-5.47a.75.75 0 111.06 1.06L13.06 12l5.47 5.47a.75.75 0 11-1.06 1.06L12 13.06l-5.47 5.47a.75.75 0 01-1.06-1.06L10.94 12 5.47 6.53a.75.75 0 010-1.06z"
                  clip-rule="evenodd" />
              </svg>
            </button>
            <h2 class="mb-2 text-lg font-bold">{{ __('To') }}: <span x-text="user?.name"></span></h2>
            <p class="mb-2">{{ __('Message') }}</p>
            <x-textarea id="user-message-textarea" x-model="message" />
            <x-jet-input-error for="message" class="mt-1" />
            <x-button primary type="button" @click="send" class="w-full mt-4">{{ __('Send') }}</x-button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
