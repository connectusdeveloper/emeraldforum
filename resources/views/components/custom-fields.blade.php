@props(['model', 'custom_fields' => [], 'extra_attributes' => []])

@if ($custom_fields->isNotEmpty())
  <div class="col-span-6 grid grid-cols-6 gap-6">
    @forelse ($custom_fields as $custom_field)
      @if ($custom_field->type == 'Text')
        <!-- {{ $custom_field->name }} -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-input :label="__($custom_field->name)" wire:model.defer="extra_attributes.{{ $custom_field->name }}" />
        </div>
      @elseif ($custom_field->type == 'Secret')
        <!-- {{ $custom_field->name }} -->
        <div class="col-span-6 sm:col-span-3">
          <x-inputs.password :label="__($custom_field->name)" wire:model.defer="extra_attributes.{{ $custom_field->name }}" />
        </div>
      @elseif ($custom_field->type == 'Date')
        <!-- {{ $custom_field->name }} -->
        <div class="col-span-6 sm:col-span-3">
          <x-datetime-picker without-time :label="__($custom_field->name)" wire:model.defer="extra_attributes.{{ $custom_field->name }}" />
        </div>
      @elseif ($custom_field->type == 'Textarea')
        <!-- {{ $custom_field->name }} -->
        <div class="col-span-6">
          <x-textarea :label="__($custom_field->name)" wire:model.defer="extra_attributes.{{ $custom_field->name }}" />
        </div>
      @elseif ($custom_field->type == 'Select')
        <!-- {{ $custom_field->name }} -->
        <div class="col-span-6 sm:col-span-3">
          <x-ui-native-select without-time :label="__($custom_field->name)" wire:model.defer="extra_attributes.{{ $custom_field->name }}">
            <option>{{ __('Select') }}</option>
            @php
              $options = $custom_field->optionsArray();
            @endphp
            @foreach ($options as $option)
              <option value="{{ $option }}">{{ $option }}</option>
            @endforeach
          </x-ui-native-select>
        </div>
      @elseif ($custom_field->type == 'Checkbox')
        <!-- {{ $custom_field->name }} -->
        <div class="col-span-6 sm:col-span-3">
          <x-jet-label for="body" value="{{ __($custom_field->name) }}" />
          <div class="mt-3 flex flex-wrap gap-x-6 gap-y-4">
            @php
              $options = $custom_field->optionsArray();
            @endphp
            @foreach ($options as $option)
              @php
                $id = str()->random();
              @endphp
              <label for="{{ $id }}" class="inline-flex items-center">
                <x-checkbox id="{{ $id }}" wire:model.defer="extra_attributes.{{ $custom_field->name }}.{{ $option }}" />
                <span class="ltr:ml-2 rtl:mr-2 text-sm text-gray-600">{{ $option }}</span>
              </label>
            @endforeach
          </div>
          <x-jet-input-error for="extra_attributes.{{ $custom_field->name }}" class="mt-3" />
        </div>
      @elseif ($custom_field->type == 'Radio')
        <!-- {{ $custom_field->name }} -->
        <div class="col-span-6 sm:col-span-3">
          <x-jet-label for="body" value="{{ __($custom_field->name) }}" />
          <div class="mt-3 flex flex-wrap gap-x-6 gap-y-4">
            @php
              $options = $custom_field->optionsArray();
            @endphp
            @foreach ($options as $option)
              @php
                $id = str()->random();
              @endphp
              <label for="{{ $id }}"
                class="inline-flex items-center @error('extra_attributes.{{ $custom_field->name }}') text-red-600 @enderror">
                {{-- <x-radio id="{{ $id }}" wire:model.defer="extra_attributes.{{ $custom_field->name }}" /> --}}
                <input type="radio" id="{{ $id }}" value="{{ $option }}" name="{{ $custom_field->name }}"
                  wire:model.defer="extra_attributes.{{ $custom_field->name }}"
                  class="rounded-full border-gray-300 dark:border-gray-700 text-blue-600 shadow-sm focus:border-blue-300 focus:ring focus:ring-blue-200 focus:ring-opacity-50 dark:bg-gray-600">
                <span class="ltr:ml-2 rtl:mr-2 text-sm text-gray-600">{{ $option }}</span>
              </label>
            @endforeach
          </div>
          <x-jet-input-error for="extra_attributes.{{ $custom_field->name }}" class="mt-3" />
        </div>
      @endif
    @endforeach
  </div>
@endif
