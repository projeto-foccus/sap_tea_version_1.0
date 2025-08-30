@extends('index')

@section('content')
<style>
    label {
        font-size: 0.9rem;
        font-weight: 500;
    }
    .form-control {
        font-size: 0.9rem;
    }
    .section-title {
        font-size: 1.1rem;
        font-weight: 600;
        color: #2c3e50;
        margin-bottom: 20px;
        padding-bottom: 10px;
        border-bottom: 2px solid #f0f0f0;
    }
    .form-section {
        border: 1px solid #e0e0e0;
        border-radius: 8px;
        padding: 25px;
        margin-bottom: 30px;
        background-color: #fff;
        box-shadow: 0 2px 4px rgba(0,0,0,0.05);
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        width: 100%;
    }
    .section-title {
        background-color: #f8f9fa;
        padding: 10px 15px;
        margin: 0 -20px 20px -20px;
        border-left: 4px solid #007bff;
        font-weight: 600;
        color: #333;
    }
    .form-group {
        margin-bottom: 1.2rem;
    }
    .form-control {
        border: 1px solid #ced4da;
        border-radius: 4px;
        padding: 8px 12px;
    }
    .form-control:focus {
        border-color: #80bdff;
        box-shadow: 0 0 0 0.2rem rgba(0,123,255,.25);
    }
    .checkbox-group {
        display: flex;
        flex-wrap: wrap;
        gap: 15px;
        margin: 10px 0;
    }
    .checkbox-group label {
        margin-left: 5px;
        font-weight: normal;
    }
    .readonly-value {
        padding: 8px 12px;
        background-color: #f8f9fa;
        border: 1px solid #e9ecef;
        border-radius: 4px;
        min-height: 38px;
        display: flex;
        align-items: center;
    }
    .mode-toggle {
        position: fixed;
        top: 70px;
        right: 20px;
        z-index: 1000;
    }
    .btn-mode {
        padding: 8px 15px;
        border-radius: 4px;
        font-weight: 500;
        transition: all 0.3s;
    }
    .btn-edit {
        background-color: #ffc107;
        border-color: #ffc107;
        color: #212529;
    }
    .btn-save {
        background-color: #28a745;
        border-color: #28a745;
        color: white;
    }
    .btn-cancel {
        background-color: #6c757d;
        border-color: #6c757d;
        color: white;
        margin-right: 10px;
    }
</style>

<div class="container" style="max-width: 1200px; width: 95%; margin: 30px auto; padding: 0 15px;">
    <div class="alert alert-info" style="background-color: #e7f4ff; border-left: 4px solid #007bff; color: #0056b3; padding: 15px; margin-bottom: 25px; border-radius: 4px;">
        <p style="margin: 0; font-size: 1.15em; line-height: 1.7; font-weight: 500;">
            Este documento deve ser atualizado regularmente, considerando os progressos e novas demandas. Os dados
            devem ser tratados de forma confidencial e utilizados exclusivamente para o planejamento de ações que
            promovam a inclusão e o desenvolvimento dos estudantes.
        </p>
    </div>
    
    <!-- Botões de modo -->
    <div class="mode-toggle">
        @if($modo == 'visualizar')
            <a href="{{ route('gerar.pdf', ['aluno_id' => $aluno->alu_id]) }}" class="btn btn-mode btn-danger" style="margin-right:10px;" target="_blank">
                <i class="fas fa-file-pdf"></i> Gerar PDF
            </a>
            <a href="{{ route('editar.perfil', ['id' => $aluno->alu_id]) }}" class="btn btn-mode btn-edit">
                <i class="fas fa-edit"></i> Editar Perfil
            </a>
        @elseif($modo == 'editar')
            <button type="submit" form="perfilForm" class="btn btn-mode btn-save">
                <i class="fas fa-save"></i> Salvar Alterações
            </button>
            <a href="{{ route('perfil.estudante.independente') }}" class="btn btn-mode btn-cancel">
                <i class="fas fa-times"></i> Cancelar
            </a>
        @endif
    </div>
    
    @if($modo == 'editar')
        <form method="POST" action="{{ route('atualiza.perfil.estudante', $aluno->alu_id) }}" id="perfilForm" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" name="is_confirmed" id="is_confirmed" value="0">
            <input type="hidden" name="fk_id_aluno" id="aluno_id_hidden" value="{{$aluno->alu_id }}">
    @endif
    
    <h2>Perfil do Estudante</h2>
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif
    
    <!-- Barra de progresso -->
    <div class="progress-container">
        <div class="progress-bar" id="progressBar"></div>
    </div>
    
    <!-- Abas de etapas -->
    <div class="step-tabs" style="display:flex;flex-wrap:nowrap;gap:3px;justify-content:flex-start;margin-bottom:18px;overflow-x:auto;">
        <button type="button" class="step-tab active" data-step="1" style="height: 42px; font-size: 0.8em; padding: 4px 4px; min-width:90px; white-space: normal; line-height: 1.1; text-align: center;">Dados Pessoais</button>
        <button type="button" class="step-tab" data-step="2" style="height: 42px; font-size: 0.8em; padding: 4px 4px; min-width:90px; white-space: normal; line-height: 1.1; text-align: center;">I - Perfil do Estudante</button>
        <button type="button" class="step-tab" data-step="3" style="height: 42px; font-size: 0.8em; padding: 4px 4px; min-width:90px; white-space: normal; line-height: 1.1; text-align: center;">II - Personalidade</button>
        <button type="button" class="step-tab" data-step="4" style="height: 42px; font-size: 0.8em; padding: 4px 4px; min-width:90px; white-space: normal; line-height: 1.1; text-align: center;">III - Comunicação</button>
        <button type="button" class="step-tab" data-step="5" style="height: 42px; font-size: 0.8em; padding: 4px 4px; min-width:90px; white-space: normal; line-height: 1.1; text-align: center;">IV - Preferências</button>
        <button type="button" class="step-tab" data-step="6" style="height: 42px; font-size: 0.8em; padding: 4px 4px; min-width:90px; white-space: normal; line-height: 1.1; text-align: center;">V - Informações da Família</button>
        <button type="button" class="step-tab" data-step="7" style="height: 42px; font-size: 0.8em; padding: 4px 4px; min-width:90px; white-space: normal; line-height: 1.1; text-align: center;">Cadastro de Profissionais</button>
    </div>
    
    <!-- Etapa 1: Dados Pessoais -->
    <div class="step-content form-section active" data-step="1">
        <div class="section-title">Dados Pessoais do estudante</div>
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-6">
                <label>Nome do estudante:</label>
                <div class="readonly-value">{{ $aluno->alu_nome ?? 'Não informado' }}</div>
            </div>
            <div class="form-group col-md-3">
                <label>Data de Nascimento:</label>
                <div class="readonly-value">
                    @if(!empty($aluno->alu_dtnasc))
                        {{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->format('d/m/Y') }}
                    @else
                        Não informado
                    @endif
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Idade:</label>
                <div class="readonly-value">
                    @if(!empty($aluno->alu_dtnasc))
                        {{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->age }} anos
                    @else
                        Não informado
                    @endif
                </div>
            </div>
        </div>
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-3">
                <label>RA:</label>
                <div class="readonly-value">{{ $aluno->alu_ra ?? 'Não informado' }}</div>
            </div>
            <div class="form-group col-md-3">
                <label>Matrícula:</label>
                <div class="readonly-value">
                    @if(isset($aluno->matriculas) && $aluno->matriculas->isNotEmpty())
                        {{ $aluno->matriculas->first()->numero_matricula ?? 'Não informado' }}
                    @else
                        Não informado
                    @endif
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Turma:</label>
                <div class="readonly-value">
                    @if(isset($aluno->matriculas) && $aluno->matriculas->isNotEmpty() && isset($aluno->matriculas->first()->turma))
                        {{ $aluno->matriculas->first()->turma->tur_nome ?? 'Não informado' }}
                    @else
                        Não informado
                    @endif
                </div>
            </div>
            <div class="form-group col-md-3">
                <label>Período:</label>
                <div class="readonly-value">
                    @if(isset($aluno->matriculas) && $aluno->matriculas->isNotEmpty())
                        {{ $aluno->matriculas->first()->periodo ?? 'Não informado' }}
                    @else
                        Não informado
                    @endif
                </div>
            </div>
        </div>
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-4">
                <label>Escola:</label>
                <div class="readonly-value">
                    @if(isset($aluno->matriculas) && $aluno->matriculas->isNotEmpty() && isset($aluno->matriculas->first()->turma) && isset($aluno->matriculas->first()->turma->escola))
                        {{ $aluno->matriculas->first()->turma->escola->esc_razao_social ?? 'Não informado' }}
                    @else
                        Não informado
                    @endif
                </div>
            </div>
            <div class="form-group col-md-4">
                <label>Série/Ano:</label>
                <div class="readonly-value">
                    @if(isset($aluno->matriculas) && $aluno->matriculas->isNotEmpty() && isset($aluno->matriculas->first()->serie))
                        {{ $aluno->matriculas->first()->serie->serie_desc ?? 'Não informado' }}
                    @else
                        Não informado
                    @endif
                </div>
            </div>
            <div class="form-group col-md-4">
                <label>Modalidade:</label>
                <div class="readonly-value">
                    @if(isset($aluno->matriculas) && $aluno->matriculas->isNotEmpty() && isset($aluno->matriculas->first()->modalidade))
                        {{ $aluno->matriculas->first()->modalidade->desc_modalidade ?? 'Não informado' }}
                    @else
                        Não informado
                    @endif
                </div>
            </div>
        </div>
        <div class="section-title" style="background-color: #ff8c00; margin-top: 15px;">DADOS DO RESPONSÁVEL</div>
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-8">
                <label>Nome do Responsável:</label>
                <div class="readonly-value">{{ $aluno->alu_nome_resp ?? 'Não informado' }}</div>
            </div>
            <div class="form-group col-md-4">
                <label>Tipo de Parentesco:</label>
                <div class="readonly-value">{{ $aluno->alu_tipo_parentesco ?? 'Não informado' }}</div>
            </div>
        </div>
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-6">
                <label>Telefone:</label>
                <div class="readonly-value">{{ $aluno->alu_tel_resp ?? 'Não informado' }}</div>
            </div>
            <div class="form-group col-md-6">
                <label>E-mail:</label>
                <div class="readonly-value">{{ $aluno->alu_email_resp ?? 'Não informado' }}</div>
            </div>
        </div>

    </div>
    
    <!-- Etapa 2: Perfil do Estudante -->
    <div class="step-content form-section" data-step="2">
        <div class="section-title">Perfil do Estudante</div>
        <!-- Diagnóstico/Laudo -->
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-4">
                <label>Possui diagnóstico/laudo?</label>
                @if($modo == 'editar')
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="diag_laudo" id="diag_laudo_sim" value="1" {{ $perfil->diag_laudo == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="diag_laudo_sim">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="diag_laudo" id="diag_laudo_nao" value="0" {{ $perfil->diag_laudo == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="diag_laudo_nao">Não</label>
                        </div>
                    </div>
                @else
                    <div class="readonly-value">{{ $perfil->diag_laudo == 1 ? 'Sim' : 'Não' }}</div>
                @endif
            </div>
            <div class="form-group col-md-4">
                <label>Data do laudo:</label>
                @if($modo == 'editar')
                    <input type="date" name="data_laudo" class="form-control" value="{{ $perfil->data_laudo ?? '' }}">
                @else
                    <div class="readonly-value">{{ $perfil->data_laudo ?? 'Não informado' }}</div>
                @endif
            </div>
            <div class="form-group col-md-4">
                <label>CID:</label>
                @if($modo == 'editar')
                    <input type="text" name="cid" class="form-control" value="{{ $perfil->cid ?? '' }}" maxlength="20">
                @else
                    <div class="readonly-value">{{ $perfil->cid ?? 'Não informado' }}</div>
                @endif
            </div>
        </div>
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-6">
                <label>Nome do médico:</label>
                @if($modo == 'editar')
                    <input type="text" name="nome_medico" class="form-control" value="{{ $perfil->nome_medico ?? '' }}" maxlength="255">
                @else
                    <div class="readonly-value">{{ $perfil->nome_medico ?? 'Não informado' }}</div>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label>Nível de suporte:</label>
                @if($modo == 'editar')
                    <select name="nivel_suporte" class="form-control">
                        <option value="1" {{ $perfil->nivel_suporte == 1 ? 'selected' : '' }}>Nível 1 - Exige pouco apoio</option>
                        <option value="2" {{ $perfil->nivel_suporte == 2 ? 'selected' : '' }}>Nível 2 - Exige apoio substancial</option>
                        <option value="3" {{ $perfil->nivel_suporte == 3 ? 'selected' : '' }}>Nível 3 - Exige apoio muito substancial</option>
                    </select>
                @else
                    <div class="readonly-value">
                        @if($perfil->nivel_suporte == 1)
                            Nível 1 - Exige pouco apoio
                        @elseif($perfil->nivel_suporte == 2)
                            Nível 2 - Exige apoio substancial
                        @elseif($perfil->nivel_suporte == 3)
                            Nível 3 - Exige apoio muito substancial
                        @else
                            Não informado
                        @endif
                    </div>
                @endif
            </div>
        </div>
        
        <!-- Medicação -->
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-4">
                <label>Faz uso de medicamento?</label>
                @if($modo == 'editar')
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="uso_medicamento" id="uso_medicamento_sim" value="1" {{ $perfil->uso_medicamento == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="uso_medicamento_sim">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="uso_medicamento" id="uso_medicamento_nao" value="0" {{ $perfil->uso_medicamento == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="uso_medicamento_nao">Não</label>
                        </div>
                    </div>
                @else
                    <div class="readonly-value">{{ $perfil->uso_medicamento == 1 ? 'Sim' : 'Não' }}</div>
                @endif
            </div>
            <div class="form-group col-md-8">
                <label>Quais medicamentos?</label>
                @if($modo == 'editar')
                    <input type="text" name="quais_medicamento" class="form-control" value="{{ $perfil->quais_medicamento ?? '' }}" maxlength="255">
                @else
                    <div class="readonly-value">{{ $perfil->quais_medicamento ?? 'Não informado' }}</div>
                @endif
            </div>
        </div>
        
        <!-- Profissional de Apoio -->
        <div class="row custom-row-gap align-items-end">
            <div class="form-group col-md-6">
                <label>Necessita de profissional de apoio?</label>
                @if($modo == 'editar')
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="nec_pro_apoio" id="nec_pro_apoio_sim" value="1" {{ $perfil->nec_pro_apoio == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="nec_pro_apoio_sim">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="nec_pro_apoio" id="nec_pro_apoio_nao" value="0" {{ $perfil->nec_pro_apoio == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="nec_pro_apoio_nao">Não</label>
                        </div>
                    </div>
                @else
                    <div class="readonly-value">{{ $perfil->nec_pro_apoio == 1 ? 'Sim' : 'Não' }}</div>
                @endif
            </div>
            <div class="form-group col-md-6">
                <label>Possui profissional de apoio?</label>
                @if($modo == 'editar')
                    <div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="prof_apoio" id="prof_apoio_sim" value="1" {{ $perfil->prof_apoio == 1 ? 'checked' : '' }}>
                            <label class="form-check-label" for="prof_apoio_sim">Sim</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="prof_apoio" id="prof_apoio_nao" value="0" {{ $perfil->prof_apoio == 0 ? 'checked' : '' }}>
                            <label class="form-check-label" for="prof_apoio_nao">Não</label>
                        </div>
                    </div>
                @else
                    <div class="readonly-value">{{ $perfil->prof_apoio == 1 ? 'Sim' : 'Não' }}</div>
                @endif
            </div>
        </div>
        
        <!-- Momentos de Apoio -->
        <div class="form-group">
            <label>Em quais momentos da rotina esse profissional se faz necessário?</label>
            @if($modo == 'editar')
                <div class="checkbox-group">
                    <div class="form-check">
                        <input type="checkbox" id="loc_01" name="loc_01" value="1" {{ $perfil->loc_01 == 1 ? 'checked' : '' }}>
                        <label for="loc_01">Locomoção</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="hig_02" name="hig_02" value="1" {{ $perfil->hig_02 == 1 ? 'checked' : '' }}>
                        <label for="hig_02">Higiene</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="ali_03" name="ali_03" value="1" {{ $perfil->ali_03 == 1 ? 'checked' : '' }}>
                        <label for="ali_03">Alimentação</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="com_04" name="com_04" value="1" {{ $perfil->com_04 == 1 ? 'checked' : '' }}>
                        <label for="com_04">Comunicação</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="out_05" name="out_05" value="1" {{ $perfil->out_05 == 1 ? 'checked' : '' }}>
                        <label for="out_05">Outros</label>
                    </div>
                </div>
            @else
                <div class="readonly-value">
                    @php
                        $momentos = [];
                        if($perfil->loc_01 == 1) $momentos[] = 'Locomoção';
                        if($perfil->hig_02 == 1) $momentos[] = 'Higiene';
                        if($perfil->ali_03 == 1) $momentos[] = 'Alimentação';
                        if($perfil->com_04 == 1) $momentos[] = 'Comunicação';
                        if($perfil->out_05 == 1) $momentos[] = 'Outros';
                    @endphp
                    {{ count($momentos) > 0 ? implode(', ', $momentos) : 'Nenhum momento selecionado' }}
                </div>
            @endif
        </div>
        
        <!-- Outros Momentos -->
        <div class="form-group">
            <label>Outros momentos:</label>
            @if($modo == 'editar')
                <textarea name="out_momentos" class="form-control" rows="3" maxlength="65535">{{ $perfil->out_momentos ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $perfil->out_momentos ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <!-- Atendimento Especializado -->
        <div class="form-group">
            <label>Atendimento Educacional Especializado:</label>
            @if($modo == 'editar')
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="at_especializado" id="at_especializado_sim" value="1" {{ $perfil->at_especializado == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="at_especializado_sim">Sim</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="at_especializado" id="at_especializado_nao" value="0" {{ $perfil->at_especializado == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="at_especializado_nao">Não</label>
                    </div>
                </div>
            @else
                <div class="readonly-value">
                    @if(isset($perfil->at_especializado) && $perfil->at_especializado == 1)
                        Sim
                    @elseif(isset($perfil->at_especializado) && $perfil->at_especializado == 0)
                        Não
                    @else
                        Não informado
                    @endif
                </div>
            @endif
        </div>
    </div>
    
    <!-- Etapa 3: Personalidade -->
    <div class="step-content form-section" data-step="3">
        <div class="section-title">II - Personalidade</div>
        <div class="form-group">
            <label>Características principais:</label>
            @if($modo == 'editar')
                <textarea name="carac_principal" class="form-control" rows="3" maxlength="65535">{{ $personalidade->carac_principal ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $personalidade->carac_principal ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Interesses relacionados às características principais:</label>
            @if($modo == 'editar')
                <textarea name="inter_princ_carac" class="form-control" rows="3" maxlength="65535">{{ $personalidade->inter_princ_carac ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $personalidade->inter_princ_carac ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>O que gosta de fazer quando está livre:</label>
            @if($modo == 'editar')
                <textarea name="livre_gosta_fazer" class="form-control" rows="3" maxlength="65535">{{ $personalidade->livre_gosta_fazer ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $personalidade->livre_gosta_fazer ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>O que o deixa feliz:</label>
            @if($modo == 'editar')
                <textarea name="feliz_est" class="form-control" rows="3" maxlength="65535">{{ $personalidade->feliz_est ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $personalidade->feliz_est ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>O que o deixa triste:</label>
            @if($modo == 'editar')
                <textarea name="trist_est" class="form-control" rows="3" maxlength="65535">{{ $personalidade->trist_est ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $personalidade->trist_est ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Objetos de apego:</label>
            @if($modo == 'editar')
                <textarea name="obj_apego" class="form-control" rows="3" maxlength="65535">{{ $personalidade->obj_apego ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $personalidade->obj_apego ?? 'Não informado' }}</div>
            @endif
        </div>
    </div>
    
    <!-- Etapa 4: Comunicação -->
    <div class="step-content form-section" data-step="4">
        <div class="section-title">III - Comunicação</div>
        <div class="form-group">
            <label>Precisa de comunicação alternativa para se expressar?</label>
            @if($modo == 'editar')
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="precisa_comunicacao" id="precisa_comunicacao_sim" value="1" {{ $comunicacao->precisa_comunicacao == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="precisa_comunicacao_sim">Sim</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="precisa_comunicacao" id="precisa_comunicacao_nao" value="0" {{ $comunicacao->precisa_comunicacao == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="precisa_comunicacao_nao">Não</label>
                    </div>
                </div>
            @else
                <div class="readonly-value">
                    @if($comunicacao->precisa_comunicacao === 1)
                        Sim
                    @elseif($comunicacao->precisa_comunicacao === 0)
                        Não
                    @else
                        Não informado
                    @endif
                </div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Entende instruções dadas de forma verbal?</label>
            @if($modo == 'editar')
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="entende_instrucao" id="entende_instrucao_sim" value="1" {{ $comunicacao->entende_instrucao == 1 ? 'checked' : '' }}>
                        <label class="form-check-label" for="entende_instrucao_sim">Sim</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="entende_instrucao" id="entende_instrucao_nao" value="0" {{ $comunicacao->entende_instrucao == 0 ? 'checked' : '' }}>
                        <label class="form-check-label" for="entende_instrucao_nao">Não</label>
                    </div>
                </div>
            @else
                <div class="readonly-value">
                    @if($comunicacao->entende_instrucao === 1)
                        Sim
                    @elseif($comunicacao->entende_instrucao === 0)
                        Não
                    @else
                        Não informado
                    @endif
                </div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Recomendação para instruções (caso não entenda):</label>
            @if($modo == 'editar')
                <textarea name="recomenda_instrucao" class="form-control" rows="3" maxlength="255">{{ $comunicacao->recomenda_instrucao ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $comunicacao->recomenda_instrucao ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <!-- Campo expressao_comunicacao removido pois não existe na tabela comunicacao -->
    </div>
    
    <!-- Etapa 5: Preferências -->
    <div class="step-content form-section" data-step="5">
        <div class="section-title">IV - Preferências, sensibilidade e dificuldades</div>
        <div class="form-group">
            <label>Apresenta sensibilidade:</label>
            @if($modo == 'editar')
                <div class="checkbox-group">
                    <div class="form-check">
                        <input type="checkbox" id="auditivo_04" name="auditivo_04" value="1" {{ $preferencia->auditivo_04 == 1 ? 'checked' : '' }}>
                        <label for="auditivo_04">Auditiva</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="visual_04" name="visual_04" value="1" {{ $preferencia->visual_04 == 1 ? 'checked' : '' }}>
                        <label for="visual_04">Visual</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="tatil_04" name="tatil_04" value="1" {{ $preferencia->tatil_04 == 1 ? 'checked' : '' }}>
                        <label for="tatil_04">Tátil</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="outros_04" name="outros_04" value="1" {{ $preferencia->outros_04 == 1 ? 'checked' : '' }}>
                        <label for="outros_04">Outros</label>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <label>Quais?</label>
                    <input type="text" name="maneja_04" class="form-control" value="{{ $preferencia->maneja_04 ?? '' }}" maxlength="255">
                </div>
            @else
                <div class="readonly-value">
                    <ul class="list-unstyled mb-0">
                        <li>Auditiva: <strong>{{ $preferencia->auditivo_04 == 1 ? 'Sim' : 'Não' }}</strong></li>
                        <li>Visual: <strong>{{ $preferencia->visual_04 == 1 ? 'Sim' : 'Não' }}</strong></li>
                        <li>Tátil: <strong>{{ $preferencia->tatil_04 == 1 ? 'Sim' : 'Não' }}</strong></li>
                        <li>Outros: <strong>{{ $preferencia->outros_04 == 1 ? ($preferencia->maneja_04 ? $preferencia->maneja_04 : 'Sim') : 'Não' }}</strong></li>
                    </ul>
                </div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Alimentos preferidos:</label>
            @if($modo == 'editar')
                <textarea name="alimentos_pref_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->alimentos_pref_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->alimentos_pref_04 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Alimentos que evita:</label>
            @if($modo == 'editar')
                <textarea name="alimento_evita_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->alimento_evita_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->alimento_evita_04 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Contato com o PC (como lida):</label>
            @if($modo == 'editar')
                <textarea name="contato_pc_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->contato_pc_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->contato_pc_04 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Como reage ao contato físico:</label>
            @if($modo == 'editar')
                <textarea name="reage_contato" class="form-control" rows="3" maxlength="65535">{{ $preferencia->reage_contato ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->reage_contato ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Interação na escola:</label>
            @if($modo == 'editar')
                <textarea name="interacao_escola_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->interacao_escola_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->interacao_escola_04 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Interesse nas atividades:</label>
            @if($modo == 'editar')
                <textarea name="interesse_atividade_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->interesse_atividade_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->interesse_atividade_04 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Estratégias que se mostram eficazes:</label>
            @if($modo == 'editar')
                <textarea name="mostram_eficazes_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->mostram_eficazes_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->mostram_eficazes_04 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Como realiza as tarefas:</label>
            @if($modo == 'editar')
                <textarea name="realiza_tarefa_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->realiza_tarefa_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->realiza_tarefa_04 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Preferências de aprendizado:</label>
            @if($modo == 'editar')
                <div class="checkbox-group">
                    <div class="form-check">
                        <input type="checkbox" id="aprende_visual_04" name="aprende_visual_04" value="1" {{ $preferencia->aprende_visual_04 == 1 ? 'checked' : '' }}>
                        <label for="aprende_visual_04">Visual</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="recurso_auditivo_04" name="recurso_auditivo_04" value="1" {{ $preferencia->recurso_auditivo_04 == 1 ? 'checked' : '' }}>
                        <label for="recurso_auditivo_04">Auditivo</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="material_concreto_04" name="material_concreto_04" value="1" {{ $preferencia->material_concreto_04 == 1 ? 'checked' : '' }}>
                        <label for="material_concreto_04">Material Concreto</label>
                    </div>
                    <div class="form-check">
                        <input type="checkbox" id="outro_identificar_04" name="outro_identificar_04" value="1" {{ $preferencia->outro_identificar_04 == 1 ? 'checked' : '' }}>
                        <label for="outro_identificar_04">Outro</label>
                    </div>
                </div>
                <div class="form-group mt-2">
                    <label>Descreva o outro método de aprendizado:</label>
                    <input type="text" name="descricao_outro_identificar_04" class="form-control" value="{{ $preferencia->descricao_outro_identificar_04 ?? '' }}" maxlength="65535">
                </div>
            @else
                <div class="readonly-value">
                    <ul class="list-unstyled mb-0">
                        <li>Visual: <strong>{{ $preferencia->aprende_visual_04 == 1 ? 'Sim' : 'Não' }}</strong></li>
                        <li>Auditivo: <strong>{{ $preferencia->recurso_auditivo_04 == 1 ? 'Sim' : 'Não' }}</strong></li>
                        <li>Material Concreto: <strong>{{ $preferencia->material_concreto_04 == 1 ? 'Sim' : 'Não' }}</strong></li>
                        <li>Outro: <strong>{{ $preferencia->outro_identificar_04 == 1 ? ($preferencia->descricao_outro_identificar_04 ? $preferencia->descricao_outro_identificar_04 : 'Sim') : 'Não' }}</strong></li>
                    </ul>
                </div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Prefere trabalhar em dupla/grupo ou individualmente?</label>
            @if($modo == 'editar')
                <textarea name="prefere_ts_04" class="form-control" rows="3" maxlength="65535">{{ $preferencia->prefere_ts_04 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $preferencia->prefere_ts_04 ?? 'Não informado' }}</div>
            @endif
        </div>
    </div>
    
    <!-- Etapa 6: Informações da Família -->
    <div class="step-content form-section" data-step="6">
        <div class="section-title">V - Informações da família</div>
        <div class="form-group">
            <label>Há expectativas expressas da família em relação ao desempenho e a inclusão do estudante na sala de aula?</label>
            @if($modo == 'editar')
                <textarea name="expectativa_05" class="form-control" rows="3" maxlength="65535">{{ $perfilFamilia->expectativa_05 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $perfilFamilia->expectativa_05 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Quais estratégias a família utiliza para lidar com o estudante em situações de crise?</label>
            @if($modo == 'editar')
                <textarea name="estrategia_05" class="form-control" rows="3" maxlength="65535">{{ $perfilFamilia->estrategia_05 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $perfilFamilia->estrategia_05 ?? 'Não informado' }}</div>
            @endif
        </div>
        
        <div class="form-group">
            <label>Como a família descreve o estudante em situações de crise?</label>
            @if($modo == 'editar')
                <textarea name="crise_esta_05" class="form-control" rows="3" maxlength="65535">{{ $perfilFamilia->crise_esta_05 ?? '' }}</textarea>
            @else
                <div class="readonly-value" style="min-height: 80px;">{{ $perfilFamilia->crise_esta_05 ?? 'Não informado' }}</div>
            @endif
        </div>
    </div>
    
    <!-- Etapa 7: Cadastro de Profissionais -->
    <div class="step-content form-section" data-step="7">
        <div class="section-title">Cadastro de Profissionais</div>
        @if($modo == 'editar')
            {{-- Modo edição permanece igual --}}
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
    <tr style="background-color: #e9ecef;">
        <th style="text-align: center;">Nome do Profissional</th>
        <th style="text-align: center;">Especialidade/Área</th>
        <th style="text-align: center;">Observações</th>
    </tr>
</thead>
<tbody>
    @foreach([0,1,2] as $i)
    <tr>
        <td>
            <input type="text" name="nome_profissional_0{{ $i+1 }}" class="form-control" value="{{ old('nome_profissional_0'.($i+1), $profissionais[$i]->nome_profissional ?? '') }}">
        </td>
        <td><input type="text" name="especialidade_profissional_0{{ $i+1 }}" class="form-control" value="{{ old('especialidade_profissional_0'.($i+1), $profissionais[$i]->especialidade_profissional ?? '') }}"></td>
        <td><input type="text" name="observacoes_profissional_0{{ $i+1 }}" class="form-control" value="{{ old('observacoes_profissional_0'.($i+1), $profissionais[$i]->observacoes_profissional ?? '') }}"></td>
    </tr>
    @endforeach
</tbody>
                </table>
            </div>
        @else
            <div class="table-responsive">
                <table class="table table-bordered">
                    <thead>
                        <tr style="background-color: #e9ecef;">
                            <th style="text-align: center;">Nome do Profissional</th>
                            <th style="text-align: center;">Especialidade/Área</th>
                            <th style="text-align: center;">Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($profissionais as $prof)
                            <tr>
                                <td>{{ $prof->nome_profissional ?? 'Não informado' }}</td>
                                <td>{{ $prof->especialidade_profissional ?? 'Não informado' }}</td>
                                <td>{{ $prof->observacoes_profissional ?? 'Não informado' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center">Nenhum profissional cadastrado</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        @endif
    </div>
    
    @if($modo == 'editar')
        <div class="form-buttons-nav" style="text-align: center; margin-top: 20px;">
            <button type="button" class="btn btn-secondary" id="prevBtn" style="display: none;">Anterior</button>
            <button type="button" class="btn btn-primary" id="nextBtn">Próximo</button>
        </div>
        </form>
    @endif
</div>
@endsection

@section('styles')
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/perfil_estudante.css') }}">
    <link rel="stylesheet" href="{{ asset('css/perfil_estudante_components.css') }}">
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inicializa as abas
            const stepTabs = document.querySelectorAll('.step-tab');
            const stepContents = document.querySelectorAll('.step-content');
            const progressBar = document.getElementById('progressBar');
            const totalSteps = stepTabs.length;
            
            // Função para mudar de aba
            function changeTab(step) {
                // Remove active de todas as abas e conteúdos
                stepTabs.forEach(tab => tab.classList.remove('active'));
                stepContents.forEach(content => content.classList.remove('active'));
                
                // Adiciona active na aba e conteúdo selecionados
                document.querySelector(`.step-tab[data-step="${step}"]`).classList.add('active');
                document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');
                
                // Atualiza a barra de progresso
                const progress = (step / totalSteps) * 100;
                progressBar.style.width = progress + '%';
            }
            
            // Adiciona evento de clique em cada aba
            stepTabs.forEach(tab => {
                tab.addEventListener('click', function() {
                    const step = this.getAttribute('data-step');
                    changeTab(step);
                });
            });
            
            // Inicializa a primeira aba
            changeTab(1);
            
            // Adiciona evento para o formulário
            const perfilForm = document.getElementById('perfilForm');
            if (perfilForm) {
                perfilForm.addEventListener('submit', function(e) {
                    // Garantir que todos os campos estejam presentes mesmo que não preenchidos
                    const camposInt = ['diag_laudo', 'nivel_suporte', 'uso_medicamento', 'nec_pro_apoio', 'prof_apoio',
                                      'loc_01', 'hig_02', 'ali_03', 'com_04', 'out_05'];
                    
                    camposInt.forEach(campo => {
                        if (!perfilForm.querySelector(`[name="${campo}"]`)) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = campo;
                            input.value = '0';
                            perfilForm.appendChild(input);
                        }
                    });
                    
                    // Campos de texto que devem ser enviados mesmo vazios
                    const camposTexto = ['cid', 'nome_medico', 'quais_medicamento', 'out_momentos', 'at_especializado', 'nome_prof_AEE'];
                    
                    camposTexto.forEach(campo => {
                        if (!perfilForm.querySelector(`[name="${campo}"]`)) {
                            const input = document.createElement('input');
                            input.type = 'hidden';
                            input.name = campo;
                            input.value = '';
                            perfilForm.appendChild(input);
                        }
                    });
                    
                    // Adicionar campo update_count se não existir
                    if (!perfilForm.querySelector('[name="update_count"]')) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'update_count';
                        input.value = '1';
                        perfilForm.appendChild(input);
                    }
                });
            }
            // Navegação entre abas com botões Próximo e Anterior
            if ('{{ $modo }}' === 'editar') {
                const prevBtn = document.getElementById('prevBtn');
                const nextBtn = document.getElementById('nextBtn');
                let currentStep = 1;

                function updateNavButtons() {
                    prevBtn.style.display = currentStep > 1 ? 'inline-block' : 'none';
                    nextBtn.style.display = currentStep < totalSteps ? 'inline-block' : 'none';
                }

                function goToStep(step) {
                    if (step < 1 || step > totalSteps) return;
                    currentStep = step;
                    changeTab(currentStep);
                    updateNavButtons();
                }

                if (nextBtn) {
                    nextBtn.addEventListener('click', function() {
                        if (currentStep < totalSteps) {
                            goToStep(currentStep + 1);
                        }
                    });
                }
                if (prevBtn) {
                    prevBtn.addEventListener('click', function() {
                        if (currentStep > 1) {
                            goToStep(currentStep - 1);
                        }
                    });
                }
                // Inicializa os botões na primeira aba
                updateNavButtons();
            }
        });
    </script>
@endsection
