<!-- Modal do Vídeo -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="videoModalLabel">Bem-vindo ao Sistema SAP-TEA</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
            </div>
            <div class="modal-body">
                <div class="ratio ratio-16x9">
                    <video id="videoPlayer" controls playsinline>
                        <source src="{{ asset('videos/exemplo.mp4') }}" type="video/mp4">
                        Seu navegador não suporta o elemento de vídeo.
                    </video>
                </div>
                <div class="mt-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="dontShowAgain">
                        <label class="form-check-label" for="dontShowAgain">
                            Não mostrar este vídeo novamente
                        </label>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
            </div>
        </div>
    </div>
</div>

<style>
    .ratio-16x9 {
        background: #000;
    }
    video {
        border-radius: 4px;
    }
    .modal-content {
        border-radius: 8px;
    }
</style>

<script>
// Verifica se o usuário já viu o vídeo
let videoSeen = localStorage.getItem('video_seen');

// Se o vídeo não foi visto ainda
if (!videoSeen) {
    // Mostra o modal após 2 segundos
    setTimeout(function() {
        var modal = new bootstrap.Modal(document.getElementById('videoModal'));
        modal.show();
    }, 2000);

    // Quando o modal é fechado
    document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
        // Pausar o vídeo quando fechar o modal
        var video = document.getElementById('videoPlayer');
        if (video) {
            video.pause();
        }
        
        // Se o usuário marcou para não mostrar novamente
        if (document.getElementById('dontShowAgain').checked) {
            localStorage.setItem('video_seen', 'true');
        }
    });
}
</script>
