<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'Sistema de Gestão de Filiados'))</title>
    
    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    
    {{-- Font Awesome --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    {{-- Bootstrap 5 --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    {{-- Vite / Styles --}}
    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    {{-- Estilos inline para o layout --}}
    <style>
        body { font-family: 'Figtree', sans-serif; }
        .navbar-brand { font-weight: 600; }
        .dropdown-toggle::after { display: none; }
        .user-avatar-mini {
            width: 32px; height: 32px; border-radius: 50%;
            background: linear-gradient(135deg, #6366f1, #8b5cf6);
            display: flex; align-items: center; justify-content: center;
            color: white; font-weight: 600; font-size: 0.75rem;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-50 dark:bg-gray-900">
    
    {{-- Navigation --}}
    <nav class="navbar navbar-expand-lg navbar-light bg-white dark:bg-gray-800 border-bottom border-gray-200 dark:border-gray-700 sticky-top">
        <div class="container-fluid px-4">
            
            {{-- Brand --}}
            <a class="navbar-brand d-flex align-items-center gap-2" href="{{ route('dashboard') }}">
                <i class="fas fa-church text-primary"></i>
                <span class="fw-bold">{{ config('app.name', 'Sistema') }}</span>
            </a>
            
            {{-- Toggler Mobile --}}
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            {{-- Navbar Links --}}
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('dashboard') ? 'active fw-semibold' : '' }}" href="{{ route('dashboard') }}">
                            <i class="fas fa-home me-1"></i>Dashboard
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('filiados.*') ? 'active fw-semibold' : '' }}" href="{{ route('filiados.index') }}">
                            <i class="fas fa-users me-1"></i>Filiados
                        </a>
                    </li>
                    @if(auth()->check() && auth()->user()->isAdmin())
                    <li class="nav-item">
                        <a class="nav-link {{ request()->routeIs('congregacoes.*') ? 'active fw-semibold' : '' }}" href="{{ route('congregacoes.index') }}">
                            <i class="fas fa-building me-1"></i>Congregações
                        </a>
                    </li>
                    @endif
                </ul>
                
                {{-- User Menu (Bootstrap Dropdown puro - sem componentes Blade) --}}
                <div class="d-flex align-items-center gap-3">
                    
                    {{-- Notificações (opcional) --}}
                    <button class="btn btn-link text-gray-600 dark:text-gray-300 position-relative" title="Notificações">
                        <i class="fas fa-bell"></i>
                        @php $notificacoes = 0; @endphp
                        @if($notificacoes > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger" style="font-size: 0.65rem;">
                            {{ $notificacoes }}
                        </span>
                        @endif
                    </button>
                    
                    {{-- Dropdown do Usuário --}}
                    <div class="dropdown">
                        <button class="btn btn-link text-gray-700 dark:text-gray-200 dropdown-toggle d-flex align-items-center gap-2 p-0" 
                                type="button" 
                                data-bs-toggle="dropdown" 
                                aria-expanded="false">
                            <div class="user-avatar-mini">
                                {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                            </div>
                            <span class="d-none d-md-inline text-start lh-1">
                                <small class="d-block text-muted" style="font-size: 0.7rem;">Olá,</small>
                                <strong style="font-size: 0.9rem;">{{ auth()->user()->name }}</strong>
                            </span>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end shadow border-0 mt-2" style="min-width: 200px;">
                            
                            {{-- Link Perfil (com verificação segura de rota) --}}
                            @if(Route::has('profile.edit'))
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('profile.edit') }}">
                                    <i class="fas fa-user text-muted"></i>
                                    <span>Meu Perfil</span>
                                </a>
                            </li>
                            @elseif(Route::has('filiados.show'))
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('filiados.show', auth()->user()->filiado ?? auth()->id()) }}">
                                    <i class="fas fa-user text-muted"></i>
                                    <span>Meu Perfil</span>
                                </a>
                            </li>
                            @else
                            <li>
                                <span class="dropdown-item text-muted disabled d-flex align-items-center gap-2">
                                    <i class="fas fa-user"></i>
                                    <span>Perfil</span>
                                </span>
                            </li>
                            @endif
                            
                            {{-- Configurações (opcional) --}}
                            @if(Route::has('settings'))
                            <li>
                                <a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('settings') }}">
                                    <i class="fas fa-cog text-muted"></i>
                                    <span>Configurações</span>
                                </a>
                            </li>
                            @endif
                            
                            <li><hr class="dropdown-divider my-1"></li>
                            
                            {{-- Logout --}}
                            <li>
                                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item d-flex align-items-center gap-2 text-danger" onclick="event.preventDefault(); this.closest('form').submit();">
                                        <i class="fas fa-sign-out-alt"></i>
                                        <span>Sair</span>
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                    
                </div>
            </div>
        </div>
    </nav>

    {{-- Page Content --}}
    <main class="py-4">
        @yield('content')
    </main>

    {{-- Footer (opcional) --}}
    <footer class="border-top border-gray-200 dark:border-gray-700 py-3 mt-auto">
        <div class="container px-4 text-center text-muted small">
            &copy; {{ date('Y') }} {{ config('app.name', 'Sistema') }}. Todos os direitos reservados.
        </div>
    </footer>

    {{-- Scripts --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts')
    
    {{-- Script para fechar dropdown ao clicar fora (mobile) --}}
    <script>
        document.addEventListener('click', function(e) {
            const dropdowns = document.querySelectorAll('.dropdown-menu.show');
            dropdowns.forEach(menu => {
                const toggle = menu.previousElementSibling;
                if (toggle && !toggle.contains(e.target) && !menu.contains(e.target)) {
                    const bsDropdown = bootstrap.Dropdown.getInstance(toggle);
                    if (bsDropdown) bsDropdown.hide();
                }
            });
        });
    </script>
</body>
</html>