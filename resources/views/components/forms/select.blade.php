@props([
    'name',
    'label' => null,
    'options' => [],
    'value' => null,
    'required' => false,
    'disabled' => false,
    'placeholder' => 'Selecione...',
    'help' => null,
    'icon' => null,
])

@php
    $errorClass = $errors->has($name) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500';
    $ariaRequired = $required ? 'true' : 'false';
    $id = $attributes->get('id', $name);
@endphp

<div class="{{ $attributes->get('wrapperClass', '') }}">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500" aria-label="obrigatório">*</span> @endif
    </label>
    @endif

    <div class="relative">
        @if($icon)
        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                {!! $icon !!}
            </svg>
        </div>
        @endif
        
        <select
            name="{{ $name }}"
            id="{{ $id }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            class="w-full px-3 py-2 @if($icon)pl-10 @endif border {{ $errorClass }} rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:border-transparent transition-colors appearance-none {{ $attributes->get('class', '') }}"
            aria-required="{{ $ariaRequired }}"
            {{ $attributes->except(['class', 'wrapperClass']) }}
        >
            @if($placeholder)
                <option value="">{{ $placeholder }}</option>
            @endif
            @foreach($options as $optionValue => $optionLabel)
                @php
                    $optValue = is_numeric($optionValue) ? $optionLabel : $optionValue;
                    $optLabel = is_numeric($optionValue) ? $optionLabel : $optionLabel;
                @endphp
                <option value="{{ $optValue }}" {{ old($name, $value) == $optValue ? 'selected' : '' }}>
                    {{ $optLabel }}
                </option>
            @endforeach
        </select>
        
        <!-- Custom arrow -->
        <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
            </svg>
        </div>
    </div>

    @error($name)
        <p class="text-red-500 text-xs mt-1" role="alert">{{ $message }}</p>
    @enderror

    @if($help && !$errors->has($name))
        <p class="text-xs text-gray-500 mt-1">{{ $help }}</p>
    @endif
</div>