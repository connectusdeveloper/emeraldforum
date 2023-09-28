@props(['url'])
<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      @if ($settings['logo'] ?? null)
        <img src="{{ asset($settings['logo']) }}" class="logo" alt="Laravel Logo">
      @else
        {{ $slot }}
      @endif
    </a>
  </td>
</tr>
