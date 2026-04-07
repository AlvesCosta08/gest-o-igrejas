@props([
    'name',
    'label' => null,
    'type' => 'text',
    'value' => null,
    'placeholder' => null,
    'required' => false,
    'disabled' => false,
    'readonly' => false,
    'autocomplete' => null,
    'pattern' => null,
    'maxlength' => null,
    'min' => null,
    'max' => null,
    'step' => null,
    'help' => null,
    'icon' => null,
    'mask' => null, // Ex: 'cpf', 'cep', 'telefone'
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
        
        <input
            type="{{ $type }}"
            name="{{ $name }}"
            id="{{ $id }}"
            value="{{ old($name, $value) }}"
            placeholder="{{ $placeholder }}"
            {{ $required ? 'required' : '' }}
            {{ $disabled ? 'disabled' : '' }}
            {{ $readonly ? 'readonly' : '' }}
            {{ $autocomplete ? "autocomplete=\"$autocomplete\"" : '' }}
            {{ $pattern ? "pattern=\"$pattern\"" : '' }}
            {{ $maxlength ? "maxlength=\"$maxlength\"" : '' }}
            {{ $min ? "min=\"$min\"" : '' }}
            {{ $max ? "max=\"$max\"" : '' }}
            {{ $step ? "step=\"$step\"" : '' }}
            {{ $mask ? "data-mask=\"$mask\"" : '' }}
            class="w-full px-3 py-2 @if($icon)pl-10 @endif border {{ $errorClass }} rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:border-transparent transition-colors {{ $attributes->get('class', '') }}"
            aria-required="{{ $ariaRequired }}"
            {{ $attributes->except(['class', 'wrapperClass']) }}
        >
    </div>

    @error($name)
        <p class="text-red-500 text-xs mt-1" role="alert">{{ $message }}</p>
    @enderror

    @if($help && !$errors->has($name))
        <p class="text-xs text-gray-500 mt-1">{{ $help }}</p>
    @endif
</div>

@push('scripts')
@if($mask)
<script>
document.addEventListener('DOMContentLoaded', function() {
    const input = document.getElementById('{{ $id }}');
    const mask = '{{ $mask }}';
    
    if (!input) return;
    
    const masks = {
        'cpf': function(v) {
            v = v.replace(/\D/g, '');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})/, '$1-$2');
            return v;
        },
        'cep': function(v) {
            v = v.replace(/\D/g, '');
            return v.replace(/(\d{5})(\d{3})/, '$1-$2');
        },
        'telefone': function(v) {
            v = v.replace(/\D/g, '');
            if (v.length <= 10) {
                return v.replace(/(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
            }
            return v.replace(/(\d{2})(\d{5})(\d{0,4})/, '($1) $2-$3');
        },
        'data': function(v) {
            v = v.replace(/\D/g, '');
            return v.replace(/(\d{2})(\d{2})(\d{0,4})/, '$1/$2/$3');
        }
    };
    
    if (masks[mask]) {
        input.addEventListener('input', function(e) {
            e.target.value = masks[mask](e.target.value);
        });
    }
});
</script>
@endif
@endpush