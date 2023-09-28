@props(['categories', 'index' => 0])

@php
  $append = '';
  if ($index) {
      for ($i = 0; $i < $index; $i++) {
          $append .= '&nbsp;&nbsp;&nbsp;';
      }
      $append .= '&nbsp;⇢&nbsp;';
  }
@endphp

@foreach ($categories as $sc)
  <option value="{{ $sc->id }}">
    {{ str($append)->toHtmlString() }} {{ $sc->name }}
  </option>
  @if ($sc->children)
    {{-- <optgroup label="{{ $sc->name }}"> --}}
    <x-options :categories="$sc->children" :index="$index + 1" />
    {{-- </optgroup> --}}
  @endif
@endforeach
