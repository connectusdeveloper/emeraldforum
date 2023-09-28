@props(['url'])
<tr>
  <td class="header">
    <a href="{{ $url }}" style="display: inline-block;">
      <?php 
	  /*
	  @if ($settings['logo'] ?? null)
        <img src="{{ asset($settings['logo']) }}" class="logo" alt="Laravel Logo">
      @else
        {{ $slot }}
      @endif
	  */
	  ?>
		<h3>Emerald Isle Homes</h3>
    </a>
  </td>
</tr>
