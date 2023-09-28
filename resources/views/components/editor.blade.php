<div>
  @if (($settings['editor'] ?? null) == 'html')
    <x-tinymce {{ $attributes }} />
  @else
    <x-simpledme {{ $attributes }} />
  @endif
</div>
