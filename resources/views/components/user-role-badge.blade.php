@props(['user'])

@php
    $roleConfig = match(true) {
        $user->isAdmin() => ['label' => 'Administrador', 'class' => 'badge-admin', 'icon' => 'fa-shield-alt'],
        $user->isSecretario() => ['label' => 'Secretário', 'class' => 'badge-secretario', 'icon' => 'fa-user-tie'],
        default => ['label' => 'Usuário', 'class' => 'badge-user', 'icon' => 'fa-user'],
    };
@endphp

<span {{ $attributes->merge(['class' => "badge {$roleConfig['class']}"]) }}>
    <i class="fas {{ $roleConfig['icon'] }} me-1"></i>{{ $roleConfig['label'] }}
</span>