<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SAP-TEA - @yield('title', 'Página Inicial')</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('css/index_responsivo.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    @yield('styles')
</head>
<body>
    <!-- Barra horizontal -->
    <div class="horizontal-bar">
        <div class="logo">Supergando TEA</div>
        <div class="menu">
            <a href="#"><i class="fa-solid fa-user"></i> MINHA CONTA</a>
        </div>
    </div>

    <div class="main-container">
        <!-- Sidebar -->
        <div class="sidebar">
            <div class="menu-logo">
                <img src="{{ asset('img/logo_sap.png') }}" alt="Logo">
            </div>
            
            <div class="user-welcome">
                <div class="user-name">Olá, {{ Auth::guard('funcionario')->user()->func_nome ?? 'Usuário' }}!</div>
                <div class="user-email">{{ Auth::guard('funcionario')->user()->email_func ?? '' }}</div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="logout-btn">Sair</button>
                </form>
            </div>

            <div class="welcome-block">
                <div class="welcome-title">Bem-vindo(a)!</div>
                <div class="welcome-text">Utilize o menu para acessar as funcionalidades.</div>
            </div>

            <ul>
                {{--
                <li>
                    <a href="{{ route('rotina.monitoramento.inicial') }}" class="menu-link" data-page="Monitoramento do estudante">
                        <i class="fa-solid fa-clipboard-list"></i> Monitoramento do estudante
                    </a>
                </li>
                --}}
                <li>
                    <a href="#" class="menu-toggle sondagem" data-page="Sondagem Pedagógica">
                        <i class="fa-solid fa-school"></i> Sondagem Pedagógica ⬇
                    </a>
                    <ul class="submenu">
                       <li><a href="{{ route('eixos.alunos', ['fase' => 'inicial']) }}" class="menu-link" data-page="Sondagem Inicial">.1 Inicial</a></li>
                        <li><a href="{{ route('eixos.alunos', ['fase' => 'continuada1']) }}" class="menu-link" data-page="Sondagem 1ª Cont.">.2 Continuada</a></li>
                        <li><a href="{{ route('eixos.alunos', ['fase' => 'continuada2']) }}" class="menu-link" data-page="Sondagem 2ª Cont.">.3 Continuada</a></li>
                        <li><a href="{{ route('eixos.alunos', ['fase' => 'final']) }}" class="menu-link" data-page="Sondagem Final">.4 Final</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle" data-page="Rotina e Monitoramento">
                        <i class="fa-solid fa-school"></i> Rotina e Monitoramento de Aplicação das Atividades ⬇
                    </a>
                    <ul class="submenu_escola">
                        <li><a href="{{ route('rotina.monitoramento.inicial') }}" class="menu-link" data-page="Rotina Inicial">.1 Inicial</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.2 Continuada</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.3 Continuada</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.4 Final</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle" data-page="Indicativo de Atividades">
                        <i class="fa-solid fa-tasks"></i> Indicativo de Atividades ⬇
                    </a>
                    <ul class="submenu_escola">
                        <li><a href="{{ route('familia.inicial.lista') }}" class="menu-link" data-page="Perfil Inicial">.1 Inicial</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.2 Continuada</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.3 Continuada</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.4 Final</a></li>
                    </ul>
                </li>
                <li>
                    <a href="#" class="menu-toggle" data-page="Perfil Familia">
                        <i class="fa-solid fa-users"></i> Perfil Família ⬇
                    </a>
                    <ul class="submenu_escola">
                        <li><a href="{{ route('familia.inicial.lista') }}" class="menu-link" data-page="Perfil Inicial">.1 Inicial</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.2 Continuada</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.3 Continuada</a></li>
                        <li><a href="#" class="disabled" title="Em breve">.4 Final</a></li>
                    </ul>
                </li>
                <li>
                    <a href="{{ route('perfil.estudante') }}" class="menu-link" data-page="Perfil do Estudante">
                        <i class="fa-solid fa-graduation-cap"></i> Perfil do Estudante
                    </a>
                </li>
                <!--
                <li>
                    <a href="{{ route('visualizar.perfil', ['id' => 1]) }}" class="menu-link" data-page="Atualizar Perfil">
                        <i class="fa-solid fa-user-edit"></i>  Perfil
                    </a>
                </li>
                -->
            </ul>

            <h3 style="margin:20px 15px 10px;">Foccus - Cadastros</h3>
            <ul>
                <li>
                    <a href="{{ route('foccus.xampp') }}" class="menu-link" data-page="Gerenciamento Foccus">
                        <i class="fa-solid fa-building-columns"></i> Gerenciamento
                    </a>
                </li>
            </ul>

            <h3 style="margin:20px 15px 10px;">Download de Materiais</h3>
EM            <ul>
                <li>
                    <a href="{{ route('download.material', ['tipo' => 'como-eu-sou']) }}" class="menu-link">
                        <i class="fa-solid fa-user"></i> Eu como sou
                    </a>
                </li>
                <li>
                    <a href="{{ route('download.material', ['tipo' => 'emocionometro']) }}" class="menu-link">
                        <i class="fa-solid fa-heart"></i> Emocionômetro
                    </a>
                </li>
                <li>
                    <a href="{{ route('download.material', ['tipo' => 'rede-ajuda']) }}" class="menu-link">
                        <i class="fa-solid fa-users"></i> Minha Rede de Ajuda
                    </a>
                </li>
                <li>
                    <a href="{{ route('download.material', ['tipo' => 'turma-supergando']) }}" class="menu-link">
                        <i class="fa-solid fa-people-group"></i> Turma Supergando
                    </a>
                </li>
                <li>
                    <a href="#" id="assistirVideoLink" class="menu-link text-primary">
                        <i class="fa-solid fa-circle-play"></i> Assistir vídeo de boas-vindas
                    </a>
                </li>
            </ul>
            <div style="font-size:13px;color:#555;margin:15px;">
                <i class="fa-solid fa-circle-info"></i> Clique em um dos materiais acima para acessar e baixar os arquivos no Google Drive.<br>
                <span id="assistirVideoLink" style="color:#1976d2;cursor:pointer;">Ou <b>assista o vídeo de boas-vindas</b> a qualquer momento!</span>
            </div>
        </div>

        <!-- Área de conteúdo principal -->
        <div class="content-area">
            <!-- Breadcrumbs -->
            <div class="breadcrumbs">
                <a href="{{ route('index') }}"><i class="fa-solid fa-home"></i> Início</a>
                <span class="separator">/</span>
                <span id="current-page">@yield('title', 'Página Inicial')</span>
            </div>

            <!-- Mensagem de boas-vindas SAP-TEA (fixa apenas na home) -->
            @if (request()->routeIs('index'))
                <div class="welcome-video-container">
                    <p class="welcome-title">
                        Caro(a) Professor(a), bem-vindo(a) ao Supergando TEA Digital!
                    </p>
                    <p class="welcome-text">
                        Esta é uma ferramenta do <b>Programa Supergando TEA</b>, criada para acompanhar o desenvolvimento do estudante com TEA por meio do mapeamento, monitoramento e emissão de relatórios que orientam suas ações pedagógicas, integrando um projeto de intervenção personalizado, contínuo e gradual.
                    </p>
                </div>
            @endif

            <!-- Área onde o conteúdo dos formulários será carregado -->
            <div id="main-content">
                @yield('content')

                <!-- Vídeo de boas-vindas (apenas na home) -->
                @if (request()->routeIs('index'))
                <div id="welcome-video-block" class="video-container">
                    <button id="close-video-btn" type="button" class="close-video-btn">⨉ Fechar vídeo</button>
                    <div class="video-wrapper">
                        <video id="videoPlayerInline" controls playsinline>
                            <source src="{{ asset('videos/exemplo.mp4') }}" type="video/mp4" />
                            Seu navegador não suporta o elemento de vídeo.
                        </video>
                    </div>
                </div>
                @endif

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    var closeBtn = document.getElementById('close-video-btn');
                    var videoBlock = document.getElementById('welcome-video-block');
                    var video = document.getElementById('videoPlayerInline');
                    
                    // Identificador único para o usuário logado (usando ID do funcionário)
                    var userId = '{{ Auth::guard("funcionario")->user()->id ?? "guest" }}';
                    var videoKey = 'video_seen_' + userId;
                    
                    // Verificar se o usuário já viu o vídeo
                    if (sessionStorage.getItem(videoKey)) {
                        videoBlock.style.display = 'none';
                        return; // Não mostrar o vídeo se já foi visto
                    }
                    
                    // Função para pausar o vídeo agressivamente
                    function pauseVideo() {
                        video.pause();
                        video.currentTime = 0;
                        video.src = '';
                        video.load();
                        video.removeAttribute('src');
                        video.innerHTML = '';
                    }
                    
                    // Fechar vídeo com botão
                    closeBtn.addEventListener('click', function() {
                        videoBlock.style.display = 'none';
                        sessionStorage.setItem(videoKey, 'true'); // Marcar como visto
                        pauseVideo();
                    });
                });
                </script>


        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle para submenus
            const menuToggles = document.querySelectorAll('.menu-toggle');
            menuToggles.forEach(toggle => {
                toggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    this.classList.toggle('active');
                    const submenu = this.nextElementSibling;
                    if (submenu) {
                        submenu.classList.toggle('active');
                    }
                });
            });

            // Atualizar breadcrumb e menu ativo baseado na página atual
            const currentPath = window.location.pathname;
            const menuLinks = document.querySelectorAll('.menu-link');
            menuLinks.forEach(link => {
                if (link.getAttribute('href') === currentPath) {
                    link.classList.add('active');
                    const pageName = link.getAttribute('data-page') || link.textContent.trim();
                    document.getElementById('current-page').textContent = pageName;
                    // Expandir submenu pai se existir
                    const parentToggle = link.closest('li').previousElementSibling;
                    if (parentToggle && parentToggle.classList.contains('menu-toggle')) {
                        parentToggle.classList.add('active');
                        parentToggle.nextElementSibling.classList.add('active');
                    }
                }
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function isBootstrapLoaded() {
            return typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined';
        }
        function showWelcomeVideo() {
            let videoSeen = sessionStorage.getItem('video_seen');
            if (!videoSeen) {
                if (isBootstrapLoaded()) {
                    setTimeout(function() {
                        var modal = new bootstrap.Modal(document.getElementById('videoModal'));
                        if (modal) {
                            modal.show();
                        }
                    }, 2000);
                    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
                        sessionStorage.setItem('video_seen', 'true');
                    });
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function() {
            if (document.getElementById('videoModal')) {
                showWelcomeVideo();
                // Permitir abrir o vídeo a qualquer momento pelo link
                var link = document.getElementById('assistirVideoLink');
                if (link) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        var modal = new bootstrap.Modal(document.getElementById('videoModal'));
                        modal.show();
                    });
                }
            }
        });
    </script>
    @yield('scripts')

</body>
</html>
