<x-mail::message>
  {{ __('Hello') }} **{{ $user->name }}**,

  {{ __('Thank you for resgistering the account with us. Please click the button below to verify your eemail address.') }}

  <x-mail::button :url="$url">
    Verify Email Address
  </x-mail::button>

  Thanks,<br>
  {{ config('app.name') }}
  {{-- Subcopy --}}
  @isset($url)
    <x-slot:subcopy>
      @lang("If you're having trouble clicking the button, copy and paste the URL below\n" . 'into your web browser:', [
          'url' => $url,
      ]) <span class="break-all">[{{ $url }}]({{ $url }})</span>
    </x-slot:subcopy>
  @endisset
</x-mail::message>
