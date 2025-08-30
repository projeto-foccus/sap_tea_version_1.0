@extends('index')

@section('title', 'Perfil do Estudante - Família')

@section('content')
<div class="container">
<style>
.linha-eixo-comunicacao,
.linha-eixo-comunicacao > th,
.linha-eixo-comunicacao > td {
    background-color: #7EC3EA !important;
    color: #003366 !important;
}
.linha-eixo-comportamento .card-header,
.linha-eixo-socio .card-header {
    font-weight: bold;
}
.linha-eixo-comportamento,
.linha-eixo-comportamento > th,
.linha-eixo-comportamento > td {
    background-color: #FFD591 !important;
    color: #7a5b00 !important;
}
.linha-eixo-socio,
.linha-eixo-socio > th,
.linha-eixo-socio > td {
    background-color: #A2F5C8 !important;
    color: #006644 !important;
}

    .header-container {
        background-color: #f4f4f4;
        padding: 15px;
        margin-bottom: 20px;
        border-bottom: 3px solid #d9534f;
        border-radius: 5px;
    }
    .header-title {
        text-align: center;
        margin: 10px 0;
        font-weight: bold;
        color: #d9534f;
        font-size: 22px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .logo-container {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
        padding: 0 15px;
    }
    .logo-container img {
        max-height: 80px;
        object-fit: contain;
    }
    .student-info {
        margin-top: 20px;
    }
    .student-info p {
        margin-bottom: 10px;
    }
    .student-info input {
        border: 1px solid #ddd;
        padding: 5px 10px;
        border-radius: 4px;
        background-color: #f9f9f9;
    }
    .student-info input:read-only {
        background-color: #f5f5f5;
    }
</style>
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">{{ __('Perfil Família - Indicativo Inicial') }}</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if(session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif
                    
                    <div class="header-container">
    <div class="logo-container">
        <img src="{{ asset('img/logo_sap.png') }}" alt="Logo SAP">
        <img src="{{ asset('img/logo_tea.png') }}" alt="Logo TEA">
    </div>
    <h1 class="header-title">PERFIL FAMÍLIA - INDICATIVO INICIAL</h1>
    <div class="student-info">
                        @if(isset($aluno))
                            <p>Escola: <input type="text" style="width: 300px;" value="{{ optional(optional(optional($aluno->matriculas)->first())->turma)->escola->esc_razao_social ?? '-' }}" readonly></p>
                            <p>Nome do estudante: <input type="text" style="width: 250px;" value="{{ $aluno->alu_nome ?? '-' }}" readonly></p>
                            <p>Data de Nascimento: <input type="date" value="{{ $aluno->alu_dtnasc ? (is_string($aluno->alu_dtnasc) ? Illuminate\Support\Carbon::parse($aluno->alu_dtnasc)->format('Y-m-d') : $aluno->alu_dtnasc->format('Y-m-d')) : '' }}" readonly>
                                Idade: <input value="{{ $aluno->alu_dtnasc ? (is_string($aluno->alu_dtnasc) ? Illuminate\Support\Carbon::parse($aluno->alu_dtnasc)->age . ' anos' : $aluno->alu_dtnasc->age . ' anos') : '-' }}" readonly type="text" min="0" style="width: 80px;"></p>
                            <p>RA: <input type="text" style="width: 150px;" value="{{ $aluno->alu_ra ?? '-' }}" readonly>
                                Turma: <input value="{{ optional(optional($aluno->matriculas)->first())->turma->tur_nome ?? '-' }}" type="text" style="width: 120px;" readonly>
                                Segmento: <input type="text" style="width: 200px;" value="{{ optional(optional(optional($aluno->matriculas)->first())->modalidade)->tipo->desc_modalidade ?? '-' }}" readonly></p>
                            <p>Série: <input type="text" style="width: 200px;" value="{{ optional(optional($aluno->matriculas)->first())->turma->serie->ser_nome ?? '-' }}" readonly>
                                Período: <input type="text" style="width: 120px;" value="{{ optional(optional($aluno->matriculas)->first())->turma->periodo->per_nome ?? '-' }}" readonly></p>
                        @else
                            <div class="alert alert-warning">
                                Não foi possível carregar os dados do aluno.
                            </div>
                        @endif

                        {{-- Eixo: Comunicação/Linguagem --}}
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card linha-eixo-comunicacao">
                                    <div class="card-header">
                                        <h5 class="card-title">Eixo: Comunicação/Linguagem</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 style="font-weight: bold;">Atividades</h6>
                                        <ul class="list-group mb-4">
                                            @foreach($atividades_comunicacao as $atividade)
                                                <li class="list-group-item">{{ $atividade }}</li>
                                            @endforeach
                                        </ul>
                                        <h6 style="font-weight: bold;">Habilidades</h6>
                                        <ul class="list-group">
                                            @foreach($habilidades_comunicacao as $habilidade)
                                                <li class="list-group-item">{{ $habilidade }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bloco Eixo Comportamento -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card linha-eixo-comportamento">
                                    <div class="card-header">
                                        <h5 class="card-title">Eixo: Comportamento</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 style="font-weight: bold;">Atividades</h6>
                                        <ul class="list-group mb-4">
                                            @foreach($atividades_comportamento as $atividade)
                                                <li class="list-group-item">{{ $atividade }}</li>
                                            @endforeach
                                        </ul>
                                        <h6 style="font-weight: bold;">Habilidades</h6>
                                        <ul class="list-group">
                                            @foreach($habilidades_comportamento as $habilidade)
                                                <li class="list-group-item">{{ $habilidade }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Bloco Eixo Interação/Socioemocional -->
                        <div class="row mt-4">
                            <div class="col-md-12">
                                <div class="card linha-eixo-socio">
                                    <div class="card-header">
                                        <h5 class="card-title">Eixo: Interação/Socioemocional</h5>
                                    </div>
                                    <div class="card-body">
                                        <h6 style="font-weight: bold;">Atividades</h6>
                                        <ul class="list-group mb-4">
                                            @foreach($atividades_socioemocional as $atividade)
                                                <li class="list-group-item">{{ $atividade }}</li>
                                            @endforeach
                                        </ul>
                                        <h6 style="font-weight: bold;">Habilidades</h6>
                                        <ul class="list-group">
                                            @foreach($habilidades_socioemocional as $habilidade)
                                                <li class="list-group-item">{{ $habilidade }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
