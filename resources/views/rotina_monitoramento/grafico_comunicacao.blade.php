@extends('index')

@section('title', 'Gráfico de Monitoramento - Eixo Comunicação/Linguagem')

{{-- Debug dos dados recebidos --}}
@php
    \Illuminate\Support\Facades\Log::info('Dados recebidos na view:', [
        'aluno' => $aluno ?? null,
        'resultados' => $resultados ?? null,
        'resultadosPorFase' => $resultadosPorFase ?? null,
        'labels' => $labels ?? null,
        'data' => $data ?? null,
        'descricoes' => $descricoes ?? null
    ]);
@endphp

@push('styles')
    <style>
        .chart-container {
            position: relative;
            margin: auto;
            height: 300px;
        }
        .comunicacao-bg {
            background-color: #A1D9F6 !important;
        }
    </style>
@endpush

@push('scripts')
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
@endpush

@section('content')
<div class="container mt-5">
    <div class="card comunicacao-bg">
        <div class="card-header bg-primary text-white">
            <div class="d-flex justify-content-between align-items-center">
                <h4>Gráfico de Monitoramento - Eixo Comunicação/Linguagem</h4>
                <a href="{{ url()->previous() }}" class="btn btn-light btn-sm">Voltar</a>
            </div>
        </div>
        
        <div class="card-body">
            <div class="row mb-4">
                <div class="col-md-6">
                    <h5>Informações do Estudante</h5>
                    <p><strong>Nome:</strong> {{ $aluno->alu_nome ?? 'N/A' }}</p>
                    <p><strong>Matrícula:</strong> {{ $aluno->alu_matricula ?? 'N/A' }}</p>
                    <p><strong>Série:</strong> {{ $aluno->serie_desc ?? 'N/A' }}</p>
                    <p><strong>Período:</strong> {{ $aluno->periodo ?? 'N/A' }}</p>
                </div>
            </div>

            <!-- Gráfico por Propostas -->
            <div class="row mb-5">
                <div class="col-md-12">
                    <div class="card shadow-sm">
                        <div class="card-header bg-warning text-dark">
                            <h5>Distribuição de Atividades por Proposta</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="graficoPropostas"></canvas>
                                <div id="graficoPropostasErro" class="alert alert-danger d-none"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Gráfico por Fase -->
            <div class="row">
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-info text-white">
                            <h5>Distribuição por Fase</h5>
                        </div>
                        <div class="card-body">
                            <div class="chart-container">
                                <canvas id="graficoFases"></canvas>
                                <div id="graficoFasesErro" class="alert alert-danger d-none"></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Tabela de dados -->
                <div class="col-md-6">
                    <div class="card shadow-sm">
                        <div class="card-header bg-light">
                            <h5>Dados Quantitativos</h5>
                        </div>
                        <div class="card-body">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Código</th>
                                        <th>Descrição</th>
                                        <th>Quantidade</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($resultados as $resultado)
                                    <tr>
                                        <td>{{ $resultado->cod_pro_com_lin }}</td>
                                        <td>{{ Str::limit($resultado->desc_pro_com_lin, 40) }}</td>
                                        <td>{{ $resultado->total }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="2">Total</th>
                                        <th>{{ $resultados->sum('total') }}</th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script>
// Função para exibir mensagens de erro
function showError(elementId, message) {
    const element = document.getElementById(elementId);
    if (element) {
        element.textContent = message;
        element.classList.remove('d-none');
    }
}

// Função para criar gráfico de propostas
function criarGraficoPropostas() {
    const ctx = document.getElementById('graficoPropostas').getContext('2d');
    const dadosPropostas = {!! json_encode($resultados) !!};
    
    if (!dadosPropostas || dadosPropostas.length === 0) {
        showError('graficoPropostasErro', 'Nenhum dado disponível para o gráfico de propostas.');
        return null;
    }
    
    const propostasLabels = dadosPropostas.map(p => p.cod_pro_com_lin);
    const propostasData = dadosPropostas.map(p => p.total);
    const propostasDescricoes = dadosPropostas.map(p => p.desc_pro_com_lin || '');
    
    return new Chart(ctx, {
        type: 'bar',
        data: {
            labels: propostasLabels,
            datasets: [{
                label: 'Quantidade de Atividades',
                data: propostasData,
                backgroundColor: 'rgba(54, 162, 235, 0.7)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            },
            plugins: {
                tooltip: {
                    callbacks: {
                        afterLabel: function(context) {
                            const index = context.dataIndex;
                            return propostasDescricoes[index];
                        }
                    }
                },
                legend: {
                    display: false
                }
            }
        }
    });
}

// Função para criar gráfico de fases
function criarGraficoFases() {
    const ctx = document.getElementById('graficoFases').getContext('2d');
    const dadosFases = {!! json_encode($resultadosPorFase) !!};
    
    if (!dadosFases || dadosFases.length === 0) {
        showError('graficoFasesErro', 'Nenhum dado disponível para o gráfico de fases.');
        return null;
    }
    
    const fasesLabels = dadosFases.map(f => {
        if (f.tipo_fase_com_lin === '1') return 'Inicial';
        if (f.tipo_fase_com_lin === '2') return 'Final';
        return 'Não especificado';
    });
    
    const fasesData = dadosFases.map(f => f.total);
    
    return new Chart(ctx, {
        type: 'pie',
        data: {
            labels: fasesLabels,
            datasets: [{
                data: fasesData,
                backgroundColor: [
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(255, 206, 86, 0.7)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(255, 206, 86, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.raw || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = Math.round((value / total) * 100);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
}

// Função para inicializar os gráficos
function initGraficos() {
    console.log('Inicializando gráficos...');
    
    // Verificar se o Chart.js foi carregado
    if (typeof Chart === 'undefined') {
        console.error('Erro: Chart.js não foi carregado corretamente!');
        showError('graficoPropostasErro', 'Erro: A biblioteca de gráficos não foi carregada corretamente. Por favor, recarregue a página.');
        showError('graficoFasesErro', 'Erro: A biblioteca de gráficos não foi carregada corretamente. Por favor, recarregue a página.');
        return;
    }
    
    // Verificar se os elementos canvas existem
    const graficoPropostas = document.getElementById('graficoPropostas');
    const graficoFases = document.getElementById('graficoFases');
    
    if (!graficoPropostas || !graficoFases) {
        console.error('Erro: Elementos do gráfico não encontrados!');
        showError('graficoPropostasErro', 'Erro: Elementos do gráfico não encontrados!');
        showError('graficoFasesErro', 'Erro: Elementos do gráfico não encontrados!');
        return;
    }
    
    // Criar os gráficos
    try {
        criarGraficoPropostas();
        criarGraficoFases();
    } catch (error) {
        console.error('Erro ao criar gráficos:', error);
        showError('graficoPropostasErro', 'Erro ao criar os gráficos: ' + error.message);
    }
}

// Inicializar quando o DOM estiver pronto
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', initGraficos);
} else {
    // DOM já está pronto
    initGraficos();
}
</script>
@endpush
