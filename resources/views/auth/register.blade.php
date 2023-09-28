<x-app-layout>
  <div class="sm:max-w-md mx-auto">
    <x-errors />
  </div>

  <x-jet-authentication-card>
    <div class="mb-4">
      {{ __('Please fill the form to register account.') }}
    </div>

    <form method="POST" action="{{ route('register') }}">
      @csrf

      <div>
        <x-ui-input id="name" :label="__('Name')" name="name" :value="old('name')" required autofocus />
      </div>

      <div class="mt-4">
        <x-ui-input :label="__('Email')" type="email" name="email" :value="old('email')" required />
      </div>

      <div class="mt-4">
        <x-ui-input :label="__('Username')" name="username" :value="old('username')" required />
      </div>

      <div class="mt-4">
        <x-inputs.password :label="__('Password')" name="password" required />
      </div>

      <div class="mt-4">
        <x-inputs.password :label="__('Confirm Password')" name="password_confirmation" required />
      </div>

      @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
        <div class="mt-4">
          <x-jet-label for="terms">
            <div class="inline-flex items-center">
              <x-checkbox name="terms" id="terms" required />

              <div class="ltr:ml-2 rtl:mr-2">
                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                    'terms_of_service' => '<a target="_blank" href="' . route('terms.show') . '" class="link">' . __('Terms of Service') . '</a>',
                    'privacy_policy' => '<a target="_blank" href="' . route('policy.show') . '" class="link">' . __('Privacy Policy') . '</a>',
                ]) !!}
              </div>
            </div>
          </x-jet-label>
        </div>
      @endif

      <div class="flex items-center justify-end mt-4">
        <a class="underline text-sm text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200"
          href="{{ route('login') }}">
          {{ __('Already registered?') }}
        </a>

        <x-jet-button class="ltr:ml-4 rtl:mr-4">
          {{ __('Register') }}
        </x-jet-button>
      </div>
    </form>

    @if (
        ($settings['facebook_login'] ?? null) ||
            ($settings['twitter_login'] ?? null) ||
            ($settings['github_login'] ?? null) ||
            ($settings['google_login'] ?? null))
      <div class="flex flex-wrap items-center justify-start gap-x-6 mt-6 mb-2 rounded-md px-4 py-3 bg-gray-100 dark:bg-gray-800">
        <p>{{ __('Register with') }}</p>
        @if ($settings['facebook_login'] ?? null)
          <a href="{{ route('social.login', 'facebook') }}" class="hover:text-gray-900 dark:hover:text-gray-100">
            <span class="sr-only">Facebook</span>
            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd"
                d="M22 12c0-5.523-4.477-10-10-10S2 6.477 2 12c0 4.991 3.657 9.128 8.438 9.878v-6.987h-2.54V12h2.54V9.797c0-2.506 1.492-3.89 3.777-3.89 1.094 0 2.238.195 2.238.195v2.46h-1.26c-1.243 0-1.63.771-1.63 1.562V12h2.773l-.443 2.89h-2.33v6.988C18.343 21.128 22 16.991 22 12z"
                clip-rule="evenodd" />
            </svg>
          </a>
        @endif

        @if ($settings['github_login'] ?? null)
          <a href="{{ route('social.login', 'github') }}" class="hover:text-gray-900 dark:hover:text-gray-100">
            <span class="sr-only">GitHub</span>
            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path fill-rule="evenodd"
                d="M12 2C6.477 2 2 6.484 2 12.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0112 6.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.202 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.943.359.309.678.92.678 1.855 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0022 12.017C22 6.484 17.522 2 12 2z"
                clip-rule="evenodd" />
            </svg>
          </a>
        @endif

        @if ($settings['github_login'] ?? null)
          <a href="{{ route('social.login', 'google') }}" class="hover:text-gray-900 dark:hover:text-gray-100">
            <span class="sr-only">GitHub</span>
            <svg class="h-9 w-9" fill="currentColor" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 50">
              <path
                d="M 25.996094 48 C 13.3125 48 2.992188 37.683594 2.992188 25 C 2.992188 12.316406 13.3125 2 25.996094 2 C 31.742188 2 37.242188 4.128906 41.488281 7.996094 L 42.261719 8.703125 L 34.675781 16.289063 L 33.972656 15.6875 C 31.746094 13.78125 28.914063 12.730469 25.996094 12.730469 C 19.230469 12.730469 13.722656 18.234375 13.722656 25 C 13.722656 31.765625 19.230469 37.269531 25.996094 37.269531 C 30.875 37.269531 34.730469 34.777344 36.546875 30.53125 L 24.996094 30.53125 L 24.996094 20.175781 L 47.546875 20.207031 L 47.714844 21 C 48.890625 26.582031 47.949219 34.792969 43.183594 40.667969 C 39.238281 45.53125 33.457031 48 25.996094 48 Z" />
            </svg>
          </a>
        @endif

        @if ($settings['twitter_login'] ?? null)
          <a href="{{ route('social.login', 'twitter') }}" class="hover:text-gray-900 dark:hover:text-gray-100">
            <span class="sr-only">Twitter</span>
            <svg class="h-10 w-10" fill="currentColor" viewBox="0 0 24 24" aria-hidden="true">
              <path
                d="M8.29 20.251c7.547 0 11.675-6.253 11.675-11.675 0-.178 0-.355-.012-.53A8.348 8.348 0 0022 5.92a8.19 8.19 0 01-2.357.646 4.118 4.118 0 001.804-2.27 8.224 8.224 0 01-2.605.996 4.107 4.107 0 00-6.993 3.743 11.65 11.65 0 01-8.457-4.287 4.106 4.106 0 001.27 5.477A4.072 4.072 0 012.8 9.713v.052a4.105 4.105 0 003.292 4.022 4.095 4.095 0 01-1.853.07 4.108 4.108 0 003.834 2.85A8.233 8.233 0 012 18.407a11.616 11.616 0 006.29 1.84" />
            </svg>
          </a>
        @endif
      </div>
    @endif
  </x-jet-authentication-card>
</x-app-layout>
