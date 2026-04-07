@props([
    'status' => 'inativo',
    'icon' => null
])

@php
$config = [
    'ativo' => ['class' => 'bg-emerald-500/10 text-emerald-400 border-emerald-500/20', 'label' => 'Ativo'],
    'inativo' => ['class' => 'bg-rose-500/10 text-rose-400 border-rose-500/20', 'label' => 'Inativo'],
    'transferido' => ['class' => 'bg-amber-500/10 text-amber-400 border-amber-500/20', 'label' => 'Transferido'],
];
$item = $config[$status] ?? $config['inativo'];
@endphp

<span {{ $attributes->merge([
    'class' => "inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-xs font-semibold border {$item['class']}"
]) }}>
    @if($icon) <span class="w-3 h-3">{!! $icon !!}</span> @endif
    {{ $item['label'] }}
</span>