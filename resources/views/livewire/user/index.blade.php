<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6">
    <x-jet-action-section>
      <x-slot name="title">
        {{ __('Users') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Please review the list.') }}
          </div>
          <div class="mt-6 flex gap-4">
            <a href="{{ route('users') }}" class="link hover:underline">{{ __('List All') }}</a>
            <a href="{{ route('users', ['active' => 'no']) }}" class="link hover:underline">{{ __('List Inactive') }}</a>
            <a href="{{ route('users', ['banned' => 'yes']) }}" class="link hover:underline">{{ __('List Banned') }}</a>
          </div>
          @if (auth()->user()->can('create-users'))
            <x-link class="mt-6" :href="route('users.create')">{{ __('Add User') }}</x-link>
          @endif
        </div>
      </x-slot>

      <x-slot name="content">
        @if ($users->isEmpty())
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
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-bold sm:pl-6">{{ __('User') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">{{ __('Badges') }}</th>
                        {{-- <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">{{ __('Active') }}</th> --}}
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                      @foreach ($users as $user)
                        <tr @class([
                            'bg-red-50 dark:bg-red-950' => $user->banned,
                            'bg-yellow-50 dark:bg-yellow-700' => !$user->active,
                        ])>
                          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                              @if ($user->image)
                                <div class="ltr:mr-4 rtl:ml-4 h-10 w-10 flex-shrink-0">
                                  <img class="h-10 w-10 rounded-full" src="{{ $user->image }}" alt="">
                                </div>
                              @endif
                              <div class="">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $user->displayName }}</div>
                                <div>
                                  <span class="text-gray-500 dark:text-gray-400">{{ __('Username') }}:</span> {{ $user->username }}
                                </div>
                                <div><span class="text-gray-500 dark:text-gray-400">{{ __('Email') }}:</span> {{ $user->email }}</div>
                                <div class="flex flex-wrap mt-2 gap-2">
                                  @if ($user->roles->count())
                                    <span
                                      class="inline-flex items-center rounded-full bg-blue-100 px-2.5 py-0.5 text-xs font-medium text-blue-800">
                                      {{ __('Role') }}: <span class="font-bold ml-1">{{ $user->roles->pluck('name')->first() }}</span>
                                    </span>
                                  @endif
                                  @if ($user->email_verified_at)
                                    <span
                                      class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Verified') }}</span>
                                  @else
                                    <span
                                      class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Unverified') }}</span>
                                  @endif
                                  @if ($user->active)
                                    <span
                                      class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Active') }}</span>
                                  @else
                                    <span
                                      class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Inactive') }}</span>
                                  @endif
                                  @if ($user->banned)
                                    <span
                                      class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Banned') }}</span>
                                  @endif
                                </div>
                              </div>
                            </div>
                          </td>
                          <td class="whitespace-nowrap px-3 py-4 text-sm ">
                            <div class="flex flex-wrap gap-2 items-center">
                              @forelse ($user->badges as $badge)
                                @if ($badge->image)
                                  <img class="inline-block h-8 w-8 rounded-full shadow shadow-gray-300 dark:shadow-gray-700"
                                    src="{{ $badge->image }}" alt="{{ $badge->name }}" x-data x-tooltip.raw="{{ $badge->name }}">
                                @elseif ($badge->css_class)
                                  <div
                                    class="inline-block h-8 w-8 rounded-full shadow shadow-gray-300 dark:shadow-gray-700 flex-shrink-0 {{ $badge->css_class }}"
                                    x-data x-tooltip.raw="{{ $badge->name }}"></div>
                                @endif
                              @empty
                              @endforelse
                              @if (!$user->banned)
                                <button type="button" x-data x-tooltip.raw="{{ __('Assign') }}"
                                  x-on:click="$wireui.confirmDialog({
                                  id: 'custom',
                                  icon: 'info',
                                  accept: {
                                    label: 'Add',
                                    execute: () => {
                                      @this.assignBadges('{{ $user->id }}');
                                    }
                                  },
                                  reject: { label: '{{ __('Close') }}'}
                                })"
                                  class="inline-flex h-8 w-8 items-center justify-center rounded-full bg-gray-100 dark:hover:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-200 dark:bg-gray-800 shadow shadow-gray-300 dark:shadow-gray-700">
                                  <x-icon name="plus" class="w-5 h-5" />
                                </button>
                              @endif
                            </div>
                          </td>
                          {{-- <td class="whitespace-nowrap px-3 py-4 text-sm ">
                            @if ($user->active)
                              <span
                                class="inline-flex rounded-full bg-green-100 px-2 text-xs font-semibold leading-5 text-green-800">{{ __('Active') }}</span>
                            @else
                              <span
                                class="inline-flex rounded-full bg-red-100 px-2 text-xs font-semibold leading-5 text-red-800">{{ __('Inactive') }}</span>
                            @endif
                          </td> --}}
                          <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            @if (auth()->user()->can('update-users'))
                              <a href="{{ route('users.edit', $user) }}" class="link">{{ __('Edit') }}<span class="sr-only">,
                                  {{ $user->displayName }}</span></a>
                            @endif
                            @if (auth()->user()->can('delete-users'))
                              <button
                                x-on:confirm="{
                                icon: 'error',
                                style: 'inline',
                                iconColor: 'text-red-700',
                                iconBackground: 'bg-red-100 rounded-full p-2',
                                title: '{{ __('Delete :x', ['x' => $user->displayName]) }}',
                                description: '{{ __('Are you sure to delete the record?') }}',
                                accept: {
                                    label: '{{ __('Yes, delete') }}',
                                    method: 'removeRecord',
                                    params: '{{ $user->id }}',
                                }
                            }"
                                class="text-red-500 hover:text-red-700 ltr:ml-3 rtl:mr-3">{{ __('Delete') }}<span class="sr-only">,
                                  {{ $user->displayName }}</span></button>
                            @endif
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  @if ($users->hasPages())
                    <div class="border dark:border-gray-700"></div>
                    <div class="w-full bg-white dark:bg-gray-900 min-w-full p-6">
                      {{ $users->links() }}
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
  <x-dialog id="custom" :title="__('Add Badge')" :description="__('Please search the badge to assign it to user.')">
    <div class="mt-4"></div>
    <x-ui-select :label="__('Badges')" :placeholder="__('Search badge by name')" multiselect :options="$badges" option-value="id" option-label="name" :empty-message="__('No badge added yet')"
      wire:model.defer="user_badges" />
  </x-dialog>
</div>
