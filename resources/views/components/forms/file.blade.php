@props([
    'name',
    'label' => null,
    'accept' => null,
    'help' => null,
    'preview' => false,
    'previewUrl' => null,
    'previewAlt' => 'Pré-visualização',
])

@php
    $errorClass = $errors->has($name) ? 'border-red-500 focus:ring-red-500' : 'border-gray-300 dark:border-gray-600 focus:ring-indigo-500';
    $id = $attributes->get('id', $name);
@endphp

<div class="{{ $attributes->get('wrapperClass', '') }}">
    @if($label)
    <label for="{{ $id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
        {{ $label }}
    </label>
    @endif

    @if($preview && $previewUrl)
    <div class="mb-3">
        <img src="{{ $previewUrl }}" alt="{{ $previewAlt }}" class="w-24 h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
    </div>
    @endif

    <input
        type="file"
        name="{{ $name }}"
        id="{{ $id }}"
        accept="{{ $accept }}"
        @if($preview) onchange="previewImage(this)" @endif
        class="w-full px-3 py-2 border {{ $errorClass }} rounded-lg bg-white dark:bg-gray-700 text-gray-800 dark:text-white focus:ring-2 focus:border-transparent transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-medium file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 dark:file:bg-indigo-900/30 dark:file:text-indigo-300 {{ $attributes->get('class', '') }}"
        {{ $attributes->except(['class', 'wrapperClass']) }}
    >

    @error($name)
        <p class="text-red-500 text-xs mt-1" role="alert">{{ $message }}</p>
    @enderror

    @if($help)
        <p class="text-xs text-gray-500 mt-1">{{ $help }}</p>
    @endif

    @if($preview)
    <div id="{{ $id }}-preview" class="mt-3 hidden">
        <img src="" alt="Pré-visualização" class="w-24 h-24 object-cover rounded-lg border border-gray-200 dark:border-gray-700">
    </div>
    @endif
</div>

@if($preview)
@push('scripts')
<script>
function previewImage(input) {
    const previewContainer = document.getElementById('{{ $id }}-preview');
    const previewImg = previewContainer?.querySelector('img');
    
    if (!previewContainer || !previewImg) return;
    
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        
        reader.onload = function(e) {
            previewImg.src = e.target.result;
            previewContainer.classList.remove('hidden');
        }
        
        reader.readAsDataURL(input.files[0]);
    } else {
        previewContainer.classList.add('hidden');
    }
}
</script>
@endpush
@endif