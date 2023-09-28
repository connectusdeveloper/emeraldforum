<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6">
    <x-jet-action-section>
      <x-slot name="title">
        {{ __('Badges') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Please review the list.') }}
          </div>
          @if (auth()->user()->can('create-badges'))
            <x-link type="button" class="mt-6" :href="route('badges.create')">{{ __('Add Badge') }}</x-link>
          @endif
        </div>
      </x-slot>

      <x-slot name="content">
        @if ($badges->isEmpty())
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
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-bold sm:pl-6">{{ __('Badge') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">{{ __('Active') }}</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                      @foreach ($badges as $badge)
                        <tr>
                          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                              @if ($badge->image)
                                <div class="ltr:mr-4 rtl:ml-4 h-10 w-10 flex-shrink-0">
                                  <img class="h-10 w-10 rounded-full shadow shadow-gray-300 dark:shadow-gray-700"
                                    src="{{ storage_url($badge->image) }}" alt="">
                                </div>
                              @elseif ($badge->css_class)
                                <div
                                  class="ltr:mr-4 rtl:ml-4 h-10 w-10 rounded-full shadow shadow-gray-300 dark:shadow-gray-700 flex-shrink-0 {{ $badge->css_class }}">
                                </div>
                              @endif
                              <div class="">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $badge->name }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="whitespace-nowrap px-3 py-4 text-sm ">
                            @if ($badge->active)
                              <span
                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Active') }}</span>
                            @else
                              <span
                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Inactive') }}</span>
                            @endif
                          </td>
                          <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            @if (auth()->user()->can('assign-badges'))
                              <button type="button" x-data x-tooltip.raw="{{ __('Assign') }}"
                                x-on:click="$wireui.confirmDialog({
                              id: 'custom',
                              icon: 'info',
                              accept: {
                                label: 'Add',
                                execute: () => {
                                  @this.assignBadge('{{ $badge->id }}');
                                }
                              },
                              reject: { label: '{{ __('Close') }}'}
                            })"
                                class="link ltr:mr-2 rtl:ml-2">
                                {{ __('Assign') }}
                              </button>
                            @endif
                            @if (auth()->user()->can('update-badges'))
                              <a href="{{ route('badges.edit', $badge) }}" class="link">{{ __('Edit') }}<span class="sr-only">,
                                  {{ $badge->name }}</span></a>
                            @endif
                            @if (auth()->user()->can('delete-badges'))
                              <button
                                x-on:confirm="{
                                icon: 'error',
                                style: 'inline',
                                iconColor: 'text-red-700',
                                iconBackground: 'bg-red-100 rounded-full p-2',
                                title: '{{ __('Delete :x', ['x' => $badge->name]) }}',
                                description: '{{ __('Are you sure to delete the record?') }}',
                                accept: {
                                    label: '{{ __('Yes, delete') }}',
                                    method: 'removeRecord',
                                    params: '{{ $badge->id }}',
                                }
                            }"
                                class="text-red-500 hover:text-red-700 ltr:ml-3 rtl:mr-3">{{ __('Delete') }}<span class="sr-only">,
                                  {{ $badge->name }}</span></button>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  @if ($badges->hasPages())
                    <div class="border dark:border-gray-700"></div>
                    <div class="w-full bg-white dark:bg-gray-900 min-w-full p-6">
                      {{ $badges->links() }}
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
  <x-dialog id="custom" :title="__('Assign Badge')" :description="__('Please search the users to assign badge.')">
    <div class="mt-4"></div>
    <x-ui-select :label="__('Users')" :placeholder="__('Search user')" multiselect :async-data="route('search.users')" always-fetch="true" option-value="id" option-label="name"
      wire:model.defer="users" />
  </x-dialog>
</div>
