<div>
  <div class="max-w-7xl mx-auto pb-10 sm:px-6">
    <x-jet-action-section>
      <x-slot name="title">
        {{ __('Invitations') }}
      </x-slot>

      <x-slot name="description">
        <div class="">
          <div>
            {{ __('Please review the list.') }}
          </div>

          <x-button primary type="button"
            x-on:click="$wireui.confirmDialog({
              id: 'custom',
              icon: 'info',
              accept: {
                label: 'Invite',
                execute: () => {
                  @this.send();
                }
              },
              reject: { label: '{{ __('Cancel') }}'}
            })"
            class="mt-4 inline-flex items-center justify-center">
            <x-icon name="plus" class="w-5 h-5" />
            {{ __('Invite') }}
          </x-button>
        </div>
      </x-slot>

      <x-slot name="content">
        @if ($invitations->isEmpty())
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
                        <th scope="col" class="py-3.5 pl-4 pr-3 text-left text-sm font-bold sm:pl-6">{{ __('Invitation sent to') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">{{ __('Accepted at') }}</th>
                        <th scope="col" class="px-3 py-3.5 text-left text-sm font-bold">{{ __('Accepted by') }}</th>
                        <th scope="col" class="relative py-3.5 pl-3 pr-4 sm:pr-6">
                          <span class="sr-only">{{ __('Actions') }}</span>
                        </th>
                      </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700 bg-white dark:bg-gray-900">
                      @foreach ($invitations as $invitation)
                        <tr>
                          <td class="whitespace-nowrap py-4 pl-4 pr-3 text-sm sm:pl-6">
                            <div class="flex items-center">
                              <div class="">
                                <div class="font-medium text-gray-900 dark:text-gray-100">{{ $invitation->email }}</div>
                              </div>
                            </div>
                          </td>
                          <td class="px-3 py-4 text-sm max-w-md">
                            {{ $invitation->accepted_at }}
                          </td>
                          <td class="px-3 py-4 text-sm max-w-md">
                            @if ($invitation->acceptedBy)
                              <a href="{{ route('users.show', $invitation->acceptedBy->username) }}">
                                {{ $invitation->acceptedBy->name }}
                              </a>
                            @endif
                          </td>

                          <td class="relative whitespace-nowrap py-4 pl-3 pr-4 text-right text-sm font-medium sm:pr-6">
                            <button
                              x-on:confirm="{
                                icon: 'error',
                                style: 'inline',
                                iconColor: 'text-red-700',
                                iconBackground: 'bg-red-100 rounded-full p-2',
                                title: '{{ __('Delete :x', ['x' => $invitation->name]) }}',
                                description: '{{ __('Are you sure to delete the record?') }}',
                                accept: {
                                    label: '{{ __('Yes, delete') }}',
                                    method: 'removeRecord',
                                    params: '{{ $invitation->id }}',
                                }
                            }"
                              class="text-red-500 hover:text-red-700 ltr:ml-3 rtl:mr-3">{{ __('Delete') }}<span class="sr-only">,
                                {{ $invitation->name }}</span></button>
                          </td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                  @if ($invitations->hasPages())
                    <div class="border dark:border-gray-700"></div>
                    <div class="w-full bg-white dark:bg-gray-900 min-w-full p-6">
                      {{ $invitations->links() }}
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
  <x-dialog id="custom" :title="__('Invite User')" :description="__('Please type email to send invitation.')">
    <div class="mt-4"></div>
    <x-ui-input :placeholder="__('Email')" wire:model.defer="invitation.email" />
  </x-dialog>
</div>
