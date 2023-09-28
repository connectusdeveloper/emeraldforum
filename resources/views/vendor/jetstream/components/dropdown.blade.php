@props(['align' => 'right', 'width' => '48', 'contentClasses' => 'bg-white dark:bg-gray-700 py-1', 'dropdownClasses' => ''])

@php
  switch ($align) {
      case 'left':
          $alignmentClasses = 'origin-top-left left-0';
          break;
      case 'top':
          $alignmentClasses = 'origin-top';
          break;
      case 'none':
      case 'false':
          $alignmentClasses = '';
          break;
      case 'right':
      default:
          $alignmentClasses = 'origin-top-right right-0';
          break;
  }

  switch ($width) {
      case '40':
          $widthClass = 'w-40';
      case '48':
          $widthClass = 'w-48';
      case '56':
          $widthClass = 'w-56';
      case '64':
          $widthClass = 'w-64';
      default:
          $widthClass = 'w-48';
          break;
  }
@endphp

<div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
  <div @click="open = ! open">
    {{ $trigger }}
  </div>

  <div x-show="open" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="transform opacity-0 scale-95"
    x-transition:enter-end="transform opacity-100 scale-100" x-transition:leave="transition ease-in duration-75"
    x-transition:leave-start="transform opacity-100 scale-100" x-transition:leave-end="transform opacity-0 scale-95"
    class="absolute z-10 mt-2 origin-top-right rounded-md shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none {{ $widthClass }} {{ $alignmentClasses }} {{ $dropdownClasses }}"
    style="display: none;" @click="open = false">
    <div class="rounded-md ring-1 ring-black ring-opacity-5 {{ $contentClasses }}">
      {{ $content }}
    </div>
  </div>
</div>
