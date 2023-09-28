@props(['menus', 'pad' => false, 'route' => 'threads'])


@foreach ($menus as $menu)
  <div x-data="{ show: false }" @class([
      'transition-all hover:bg-gray-50 dark:hover:bg-black/20',
      'border-dashed border-b dark:border-gray-700 ltr:rounded-bl-md rtl:rounded-br-md' => $pad,
  ]) @click.away="show = false">

    @if ($menu->children?->isNotEmpty())
      <button @click="show = !show"
        class="w-full ltr:text-left rtl:text-right flex items-center justify-between font-bold px-6 py-2.5 hover:bg-gray-100 dark:hover:bg-black/50">
        <span class="flex-1">{{ $menu->name }}</span>

        <x-icon x-show="show" name="chevron-up" class="w-4 h-4 text-gray-500 dark:text-gray-500" />
        <x-icon x-show="!show" name="chevron-down" class="w-4 h-4 text-gray-400 dark:text-gray-600" />
      </button>
    @else
      <a href="{{ route($route, $menu->slug) }}"
        class="flex items-center justify-between font-bold px-6 py-2.5 hover:bg-gray-100 dark:hover:bg-black/50">
        <span class="flex-1">{{ $menu->name }}</span>

        <span
          class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-bold dark:bg-gray-800">{{ $menu->total }}</span>
      </a>
    @endif

    @if ($menu->children)
      <div x-show="show" style="display: none"
        class="transitions-all ltr:origin-top-left rtl:origin-top-right ltr:ml-6 rtl:mr-6 border-dashed ltr:border-l rtl:border-r dark:border-gray-700 ltr:rounded-l-md rtl:rounded-r-md">
        <a href="{{ route($route, $menu->slug) }}"
          class="flex items-center justify-between font-bold px-6 py-2.5 hover:bg-gray-100 dark:hover:bg-black/50 border-dashed border-t border-b dark:border-gray-700 ltr:rounded-tl-md rtl:rounded-tr-md">
          <span class="flex-1">{{ __('All from :category', ['category' => $menu->name]) }}</span>
          <span class="inline-flex items-center rounded-full bg-gray-100 px-2.5 py-0.5 text-xs font-bold dark:bg-gray-800">
            {{ $menu->total }}
            {{-- {{ $menu->children->sum('total') }} --}}
          </span>
        </a>
        <x-multi-menus :menus="$menu->children" :pad="true" :route="$route" />
      </div>
    @endif
  </div>
@endforeach
