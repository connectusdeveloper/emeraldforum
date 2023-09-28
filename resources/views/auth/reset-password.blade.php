<x-app-layout>
  <div class="sm:max-w-md mx-auto">
    <x-errors />
  </div>

  <x-jet-authentication-card>
    <form method="POST" action="{{ route('password.update') }}">
      @csrf

      <input type="hidden" name="token" value="{{ $request->route('token') }}">

      <div>
        <x-ui-input :label="__('Email')" type="email" name="email" :value="old('email', $request->email)" required />
      </div>

      <div class="mt-4">
        <x-inputs.password :label="__('Password')" name="password" required />
      </div>

      <div class="mt-4">
        <x-inputs.password :label="__('Confirm Password')" name="password_confirmation" required />
      </div>

      <div class="flex items-center justify-end mt-4">
        <x-jet-button>
          {{ __('Reset Password') }}
        </x-jet-button>
      </div>
    </form>
  </x-jet-authentication-card>
</x-app-layout>
