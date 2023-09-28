<div>
  <x-slot name="title">
    {{ __('Contact us') }}
  </x-slot>
  <x-slot name="metaTags">
    <meta name="description"
      content="{{ __($settings['contact_page'] ?? 'If you have any concerns or want to talk, do reach by filling the form below.') }}">
  </x-slot>

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
    <div class="">
      <h2 class="text-3xl font-bold tracking-tight text-gray-900 dark:text-gray-100">
        {{ __('Contact us') }}
      </h2>
      <p class="mt-2 text-lg leading-8">
        {{ __($settings['contact_page'] ?? 'Please fill the form to send us message.') }}
      </p>
      <div class="mt-6 flex flex-col gap-8 lg:flex-row">
        <form wire:submit.prevent="send" autocomplete="off" class="lg:flex-auto">
          <div class="grid grid-cols-1 gap-y-6 gap-x-8 sm:grid-cols-2">
            <!-- Name -->
            <div class="sm:col-span-2">
              <x-ui-input :label="__('Name')" wire:model.defer="form.name" />
            </div>

            <!-- Email -->
            <div class="">
              <x-ui-input :label="__('Email')" wire:model.defer="form.email" />
            </div>

            <!-- Phone -->
            <div class="">
              <x-ui-input :label="__('Phone')" wire:model.defer="form.phone" />
            </div>

            <!-- Subject -->
            <div class="sm:col-span-2">
              <x-ui-input :label="__('Subject')" wire:model.defer="form.subject" />
            </div>

            <!-- Message -->
            <div class="sm:col-span-2">
              <x-textarea :label="__('Message')" wire:model.defer="form.message" />
            </div>
          </div>
          <div class="mt-6">
            <x-jet-button wire:loading.attr="disabled" wire:target="image">
              {{ __('Send') }}
            </x-jet-button>
          </div>
          {{-- <p class="mt-4 text-sm leading-6 text-gray-500">By submitting this form, I agree to the
            <a href="#" class="font-semibold text-primary-600">privacy&nbsp;policy</a>.
          </p> --}}
        </form>
      </div>
    </div>
  </div>
</div>
