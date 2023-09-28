<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6">
    <x-jet-action-section>
      <x-slot name="title">
        {{ __('Categories') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Please review the list.') }}
          </div>
          @if (auth()->user()->can('create-categories'))
            <x-link type="button" class="mt-6" :href="route('categories.create')">{{ __('Add Category') }}</x-link>
          @endif
        </div>
      </x-slot>

      <x-slot name="content">
        @if ($categories->isEmpty())
          <p>
            {{ __('There is no data to display') }}
          </p>
        @else
          <div class="-m-6 flex flex-col shadow lg:shadow-none md:rounded-lg">
            <div class="-my-2 mx-0 overflow-x-auto sm:-mx-6 lg:-mx-8">
              <div class="inline-block min-w-full pl-2 sm:pl-0 py-2 align-middle md:px-6 lg:px-8">
                <div class="overflow-hidden md:rounded-lg">
                  <table class="min-w-full divide-y divide-gray-300 dark:divide-gray-800">
                    <thead class="bg-gray-50 dark:bg-black/50">
                      <tr>
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-bold sm:pl-6">{{ __('Category') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">{{ __('Description') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">
                          <span class="sr-only">{{ __('Active') }}</span>
                        </th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                      @foreach ($categories as $category)
                        <tr>
                          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                              @if ($category->image)
                                <div class="ltr:mr-4 rtl:ml-4 h-10 w-10 flex-shrink-0">
                                  <img class="h-10 w-10 rounded-full" src="{{ $category->image }}" alt="">
                                </div>
                              @endif
                              <div class="">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $category->name }}</div>
                                {{-- <div class="">{{ $category->title }}</div> --}}
                                @if ($category->parent)
                                  <div><span class="text-gray-500">{{ __('Child of') }}:</span> {{ $category->parent->name }}</div>
                                @endif
                              </div>
                            </div>
                          </td>
                          <td class="px-3 py-4 text-sm max-w-md">
                            {{ $category->description }}
                          </td>
                          <td class="whitespace-nowrap px-3 py-4 text-sm">
                            @if ($category->active)
                              <span
                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Active') }}</span>
                            @else
                              <span
                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Inactive') }}</span>
                            @endif
                          </td>
                          <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            @if (auth()->user()->can('update-categories'))
                              <a href="{{ route('categories.edit', $category) }}" class="link">{{ __('Edit') }}<span
                                  class="sr-only">,
                                  {{ $category->name }}</span></a>
                            @endif
                            @if (auth()->user()->can('delete-categories'))
                              <button
                                x-on:confirm="{
                                icon: 'error',
                                style: 'inline',
                                iconColor: 'text-red-700',
                                iconBackground: 'bg-red-100 rounded-full p-2',
                                title: '{{ __('Delete :x', ['x' => $category->name]) }}',
                                description: '{{ __('Are you sure to delete the record?') }}',
                                accept: {
                                    label: '{{ __('Yes, delete') }}',
                                    method: 'removeRecord',
                                    params: '{{ $category->id }}',
                                }
                            }"
                                class="text-red-500 hover:text-red-700 ltr:ml-3 rtl:mr-3">{{ __('Delete') }}<span class="sr-only">,
                                  {{ $category->name }}</span></button>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  @if ($categories->hasPages())
                    <div class="border dark:border-gray-700"></div>
                    <div class="w-full bg-white dark:bg-gray-900 min-w-full p-6">
                      {{ $categories->links() }}
                    </div>
                  @endif
                </div>
              </div>
            </div>
          </div>
        @endif
      </x-slot>
    </x-jet-action-section>
  </div>
</div>
