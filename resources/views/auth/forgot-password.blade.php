<x-app-layout>
  <div class="sm:max-w-md mx-auto">
    <x-errors />
  </div>

  <x-jet-authentication-card>
    <div class="mb-4 text-sm">
      {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    @if (session('status'))
      <div class="mb-4 font-medium text-sm text-green-600">
        {{ session('status') }}
      </div>
    @endif

    <form method="POST" action="{{ route('password.email') }}">
      @csrf

      <div class="block">
        <x-ui-input :label="__('Email')" type="email" name="email" :value="old('email')" required autofocus />
      </div>

      <div class="flex items-center justify-end mt-4">
        <x-jet-button>
          {{ __('Email Password Reset Link') }}
        </x-jet-button>
      </div>
    </form>
  </x-jet-authentication-card>
</x-app-layout>
