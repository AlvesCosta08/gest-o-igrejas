@props([
    'name',
    'label' => null,
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'rows' => 4,
    'help' => null,
])

@php
    $errorClass = $errors->has($name) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500';
    $id = $attributes->get('id', $name);
@endphp

<div class="{{ $attributes->get('wrapperClass', '') }}">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}
        @if($required) <span class="text-red-500">*</span> @endif
    </label>
    @endif

    <textarea
        name="{{ $name }}"
        id="{{ $id }}"
        rows="{{ $rows }}"
        placeholder="{{ $placeholder }}"
        {{ $required ? 'required' : '' }}
        class="w-full px-3 py-2 border {{ $errorClass }} rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:border-transparent transition-colors resize-vertical {{ $attributes->get('class', '') }}"
        {{ $attributes->except(['class', 'wrapperClass']) }}
    >{{ old($name, $value) }}</textarea>

    @error($name)
        <p class="text-red-500 text-xs mt-1" role="alert">{{ $message }}</p>
    @enderror

    @if($help && !$errors->has($name))
        <p class="text-xs text-gray-500 mt-1">{{ $help }}</p>
    @endif
</div>