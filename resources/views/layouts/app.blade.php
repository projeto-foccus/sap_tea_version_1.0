<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de Alunos')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/perfil_estudante.css') }}">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .main-content {
            margin-left: 250px;
            padding: 20px;
            min-height: 100vh;
            transition: all 0.3s;
        }
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
        }
    </style>
    @stack('styles')
</head>
<body>
    <div class="d-flex">
        <!-- Sidebar -->
        <div class="bg-dark text-white" style="width: 250px; min-height: 100vh; position: fixed;">
            <div class="p-3">
                <a href="{{ url('/') }}" class="d-flex align-items-center text-white text-decoration-none">
                    <span class="fs-4">Sistema</span>
                </a>
            </div>
            <hr>
            <ul class="nav nav-pills flex-column mb-auto px-3">
                <li class="nav-item">
                    <a href="{{ route('dashboard') }}" class="nav-link text-white">
                        Dashboard
                    </a>
                </li>
                <!-- Adicione mais itens do menu conforme necessário -->
            </ul>
            <hr>
            <div class="dropdown px-3 pb-3">
                @auth('funcionario')
                    <a href="#" class="d-flex align-items-center text-white text-decoration-none dropdown-toggle" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                        <strong>{{ auth('funcionario')->user()->func_nome }}</strong>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-dark text-small shadow" aria-labelledby="dropdownUser1">
                        <li><a class="dropdown-item" href="#">Perfil</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="#" onclick="event.preventDefault(); sessionStorage.clear(); document.getElementById('logout-form').submit();">Sair</a></li>
                    </ul>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                @endauth
            </div>
        </div>

        <!-- Conteúdo principal -->
        <div class="main-content w-100">
            <div class="container-fluid">
                @yield('content')
            </div>
        </div>
    </div>
    <div id="logout-message" style="display:none;position:fixed;z-index:100000;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.8);justify-content:center;align-items:center;color:white;font-size:2em;text-align:center;">
        Sua sessão expirou por inatividade.<br><br>Por segurança, você foi desconectado.<br><br>
        <button onclick="window.location.href='/login'" style="margin-top:24px;font-size:1em;padding:10px 28px;">Fazer login novamente</button>
    </div>
    <script>
    // Comunicação global de logout entre abas
    (function() {
        const LOGOUT_CHANNEL = 'sap-tea-logout';
        function broadcastLogout() {
            if ('BroadcastChannel' in window) {
                new BroadcastChannel(LOGOUT_CHANNEL).postMessage('logout');
            } else {
                localStorage.setItem(LOGOUT_CHANNEL, 'logout-' + Date.now());
            }
        }
        function listenLogout() {
            if ('BroadcastChannel' in window) {
                const bc = new BroadcastChannel(LOGOUT_CHANNEL);
                bc.onmessage = function(ev) {
                    if (ev.data === 'logout') {
                        if (window.location.pathname !== '/login') {
                            window.location.href = '/login';
                        }
                    }
                };
            } else {
                window.addEventListener('storage', function(ev) {
                    if (ev.key === LOGOUT_CHANNEL && ev.newValue && window.location.pathname !== '/login') {
                        window.location.href = '/login';
                    }
                });
            }
        }
        // Adiciona broadcast no logout manual
        window.sapTeaLogoutBroadcast = broadcastLogout;
        listenLogout();
    })();
    // Logout automático após 20 minutos de inatividade
    (function() {
        var timeout = 20 * 60 * 1000; // 20 minutos em ms
        var logoutUrl = '/logout';
        var logoutMessage = document.getElementById('logout-message');
        var timer;
        function resetTimer() {
            clearTimeout(timer);
            timer = setTimeout(autoLogout, timeout);
    // Cria overlay de bloqueio
    function showBlockOverlay() {
        if (!document.getElementById('block-overlay')) {
            const overlay = document.createElement('div');
            overlay.id = 'block-overlay';
            overlay.style.position = 'fixed';
            overlay.style.top = 0;
            overlay.style.left = 0;
            overlay.style.width = '100vw';
            overlay.style.height = '100vh';
            overlay.style.background = 'rgba(0,0,0,0.92)';
            overlay.style.zIndex = 99999;
            overlay.style.display = 'flex';
            overlay.style.flexDirection = 'column';
            overlay.style.justifyContent = 'center';
            overlay.style.alignItems = 'center';
            overlay.innerHTML = '<div style="color:white;font-size:2em;text-align:center;padding:32px;background:rgba(30,30,30,0.95);border-radius:12px;max-width:90vw;">O sistema já está aberto em outra aba.<br><br>Use apenas uma aba do navegador para evitar perda de dados e confusão.<br><br><button id="reload-btn" style="margin-top:24px;font-size:1em;padding:10px 28px;">Tentar novamente</button></div>';
            document.body.appendChild(overlay);
            document.getElementById('reload-btn').onclick = function() {
                location.reload();
            };
        }
    }
    // Comunicação entre abas usando BroadcastChannel (moderno) ou localStorage (fallback)
    let bc = null;
    let gotPing = false;
    function sendPing() {
        if (bc) {
            bc.postMessage('sap-tea-ping');
        } else {
            localStorage.setItem(CHANNEL, 'ping-' + Date.now());
        }
    }
    function sendPong() {
        if (bc) {
            bc.postMessage('sap-tea-pong');
        } else {
            localStorage.setItem(CHANNEL, 'pong-' + Date.now());
        }
    }
    function setupChannel() {
        if ('BroadcastChannel' in window) {
            bc = new BroadcastChannel(CHANNEL);
            bc.onmessage = function(e) {
                if (e.data === 'sap-tea-ping') {
                    sendPong();
                } else if (e.data === 'sap-tea-pong') {
                    gotPing = true;
                }
            };
        } else {
            window.addEventListener('storage', function(event) {
                if (event.key === CHANNEL) {
                    if (event.newValue && event.newValue.startsWith('ping-')) {
                        sendPong();
                    } else if (event.newValue && event.newValue.startsWith('pong-')) {
                        gotPing = true;
                    }
                }
            });
        }
    }
    setupChannel();
    // Ao carregar, envia ping e espera resposta
    window.addEventListener('DOMContentLoaded', function() {
        gotPing = false;
        sendPing();
        setTimeout(function() {
            if (gotPing) {
                // Existe outra aba aberta
                showBlockOverlay();
                isBlocked = true;
            } else {
                // Não há outra aba, segue normalmente
                isBlocked = false;
            }
        }, 500);
    });
    // Se outra aba abrir, bloqueia esta
    if (bc) {
        bc.onmessage = function(e) {
            if (e.data === 'sap-tea-ping') {
                sendPong();
            } else if (e.data === 'sap-tea-pong') {
                gotPing = true;
            }
        };
    }
})();
</script>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    @stack('scripts')
    
    <script>
        // Ativar tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Ativar popovers
        var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
        var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
            return new bootstrap.Popover(popoverTriggerEl);
        });
    </script>
</body>
</html>
.