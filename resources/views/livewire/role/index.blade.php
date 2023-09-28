<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6">
    <x-jet-action-section>
      <x-slot name="title">
        {{ __('Roles') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Please review the list.') }}
          </div>
          @if (auth()->user()->can('create-roles'))
            <x-link type="button" class="mt-6" :href="route('roles.create')">{{ __('Add Role') }}</x-link>
          @endif
        </div>
      </x-slot>

      <x-slot name="content">
        @if ($roles->isEmpty())
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
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-bold sm:pl-6">{{ __('Role') }}</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                      @foreach ($roles as $role)
                        <tr>
                          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                              <div class="">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $role->name }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            @if ($role->name != 'super')
                              @if (auth()->user()->can('update-roles'))
                                <a href="{{ route('roles.edit', $role) }}" class="link">{{ __('Edit') }}<span class="sr-only">,
                                    {{ $role->name }}</span></a>
                              @endif
                              @if (auth()->user()->can('delete-roles'))
                                <button
                                  x-on:confirm="{
                                icon: 'error',
                                style: 'inline',
                                iconColor: 'text-red-700',
                                iconBackground: 'bg-red-100 rounded-full p-2',
                                title: '{{ __('Delete :x', ['x' => $role->name]) }}',
                                description: '{{ __('Are you sure to delete the record?') }}',
                                accept: {
                                    label: '{{ __('Yes, delete') }}',
                                    method: 'removeRecord',
                                    params: '{{ $role->id }}',
                                }
                            }"
                                  class="text-red-500 hover:text-red-700 ltr:ml-3 rtl:mr-3">{{ __('Delete') }}<span class="sr-only">,
                                    {{ $role->name }}</span></button>
                              @endif
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
            </div>
          </div>
        @endif
      </x-slot>
    </x-jet-action-section>
  </div>
</div>
