<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6">
    <x-jet-action-section>
      <x-slot name="title">
        {{ __('Custom Fields') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Please review the list.') }}
          </div>
          @if (auth()->user()->can('create-custom-fields'))
            <x-link type="button" class="mt-6" :href="route('custom_fields.create')">{{ __('Add Custom Field') }}</x-link>
          @endif
        </div>
      </x-slot>

      <x-slot name="content">
        @if ($custom_fields->isEmpty())
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
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-bold sm:pl-6">{{ __('Custom Field') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">{{ __('Models') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold"></th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                      @foreach ($custom_fields as $custom_field)
                        <tr>
                          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                              <div class="">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $custom_field->name }}</div>
                                <div><span class="text-gray-500">{{ __('Type') }}:</span> {{ $custom_field->type }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="whitespace-nowrap px-3 py-4 text-sm ">
                            {{ implode(', ', $custom_field->models) }}
                          </td>
                          <td class="whitespace-nowrap px-3 py-4 text-sm ">
                            @if ($custom_field->active)
                              <span
                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Active') }}</span>
                            @else
                              <span
                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Inactive') }}</span>
                            @endif
                            @if ($custom_field->required)
                              <span
                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Required') }}</span>
                            @else
                              <span
                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Not Required') }}</span>
                            @endif
                            {{-- TODO: Private --}}
                            {{-- @if ($custom_field->show)
                              <span
                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Public') }}</span>
                            @else
                              <span
                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Private') }}</span>
                            @endif --}}
                          </td>
                          <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            @if (auth()->user()->can('update-custom-fields'))
                              <a href="{{ route('custom_fields.edit', $custom_field) }}" class="link">{{ __('Edit') }}<span
                                  class="sr-only">,
                                  {{ $custom_field->name }}</span></a>
                            @endif
                            @if (auth()->user()->can('delete-custom-fields'))
                              <button
                                x-on:confirm="{
                                icon: 'error',
                                style: 'inline',
                                iconColor: 'text-red-700',
                                iconBackground: 'bg-red-100 rounded-full p-2',
                                title: '{{ __('Delete :x', ['x' => $custom_field->name]) }}',
                                description: '{{ __('Are you sure to delete the record?') }}',
                                accept: {
                                    label: '{{ __('Yes, delete') }}',
                                    method: 'removeRecord',
                                    params: '{{ $custom_field->id }}',
                                }
                            }"
                                class="text-red-500 hover:text-red-700 ltr:ml-3 rtl:mr-3">{{ __('Delete') }}<span class="sr-only">,
                                  {{ $custom_field->name }}</span></button>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  @if ($custom_fields->hasPages())
                    <div class="border dark:border-gray-700"></div>
                    <div class="w-full bg-white dark:bg-gray-900 min-w-full p-6">
                      {{ $custom_fields->links() }}
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
