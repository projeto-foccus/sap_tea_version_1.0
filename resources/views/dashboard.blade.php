@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Bem-vindo ao Dashboard</h1>
        <p>Seu acesso está protegido e funcionando corretamente.</p>

        <!-- Modal do Vídeo de Boas-Vindas -->
        <div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true" data-bs-backdrop="static">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="videoModalLabel">Bem-vindo ao Sistema SAP-TEA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Fechar"></button>
                    </div>
                    <div class="modal-body">
                        <div class="d-flex justify-content-center align-items-center w-100">
                            <div class="ratio ratio-16x9" style="width:100%; max-width:700px;">
                                <video id="videoPlayer" controls playsinline style="width:100%; height:100%; object-fit:cover;">
                                    <source src="{{ asset('videos/exemplo.mp4') }}" type="video/mp4">
                                    Seu navegador não suporta o elemento de vídeo.
                                </video>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

        @push('scripts')
        <script>
            function isBootstrapLoaded() {
                return typeof bootstrap !== 'undefined' && typeof bootstrap.Modal !== 'undefined';
            }
            function showWelcomeVideo() {
                let videoSeen = localStorage.getItem('video_seen');
                if (!videoSeen) {
                    if (isBootstrapLoaded()) {
                        setTimeout(function() {
                            var modal = new bootstrap.Modal(document.getElementById('videoModal'));
                            if (modal) {
                                modal.show();
                            }
                        }, 2000);
                        document.getElementById('videoModal').addEventListener('hidden.bs.modal', function () {
                            localStorage.setItem('video_seen', 'true');
                        });
                    }
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                if (document.getElementById('videoModal')) {
                    showWelcomeVideo();
                }
            });
        </script>
        @endpush
    </div>
@endsection

@push('scripts')
<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
@endpush

