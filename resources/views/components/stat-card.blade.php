@props([
    'href' => null,
    'icon' => null,
    'value' => 0,
    'label' => '',
    'color' => 'blue', // blue, green, red, yellow, purple
    'delay' => 0
])

@php
$colors = [
    'blue' => ['bg' => 'bg-blue-500/15', 'text' => 'text-blue-400', 'border' => 'border-blue-500/30'],
    'green' => ['bg' => 'bg-emerald-500/15', 'text' => 'text-emerald-400', 'border' => 'border-emerald-500/30'],
    'red' => ['bg' => 'bg-rose-500/15', 'text' => 'text-rose-400', 'border' => 'border-rose-500/30'],
    'yellow' => ['bg' => 'bg-amber-500/15', 'text' => 'text-amber-400', 'border' => 'border-amber-500/30'],
    'purple' => ['bg' => 'bg-violet-500/15', 'text' => 'text-violet-400', 'border' => 'border-violet-500/30'],
];
$style = $colors[$color] ?? $colors['blue'];
@endphp

<a {{ $attributes->merge([
    'href' => $href,
    'class' => "group relative flex items-center gap-4 p-5 rounded-2xl border border-white/10 bg-slate-800/50 
                hover:border-{$style['border']} hover:bg-slate-700/30 hover:-translate-y-0.5 
                transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-{$style['text']} focus:ring-offset-2 focus:ring-offset-slate-900",
    'style' => "transition-delay: {$delay}ms"
]) }}>
    
    {{-- Ícone --}}
    <div class="flex-shrink-0 w-12 h-12 rounded-xl {{ $style['bg'] }} flex items-center justify-center">
        {{ $icon ?? '<x-icons.users class="w-6 h-6 '. $style['text'] .'" />' }}
    </div>
    
    {{-- Conteúdo --}}
    <div class="flex-1 min-w-0">
        <span class="block text-2xl font-bold text-white count-up" data-value="{{ $value }}">
            {{ number_format($value) }}
        </span>
        <span class="text-sm text-slate-400">{{ $label }}</span>
    </div>
    
    {{-- Indicador de clique --}}
    <x-icons.chevron-right class="w-5 h-5 text-slate-500 group-hover:text-amber-400 group-hover:translate-x-0.5 transition-all opacity-0 group-hover:opacity-100" />
    
    {{-- Efeito de brilho no hover --}}
    <span class="absolute inset-0 rounded-2xl bg-gradient-to-r from-transparent via-white/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity pointer-events-none" />
</a>