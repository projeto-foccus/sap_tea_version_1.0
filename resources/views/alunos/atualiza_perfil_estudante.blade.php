@extends('index')

@section('title', 'Atualizar Perfil do Estudante')

@section('styles')
<link rel="stylesheet" href="{{ asset('css/perfil_estudante.css') }}">
<link rel="stylesheet" href="{{ asset('css/atualiza_perfil_estudante.css') }}">
<style>
    /* Evita quebra de página em títulos ao gerar PDF */
    h1, h2, h3, .no-break {
        page-break-inside: avoid;
        break-inside: avoid;
    }
</style>
@endsection

@section('content')
<div class="perfil-container">
    <div class="logo-bg-top">
    <img src="{{ asset('img/logo_sap.png') }}" alt="Logo Transparente Central" class="logo-center">

    </div>
    <h2>I - Perfil do Estudante</h2>

    @if(isset($dados) && count($dados) > 0)
        @php $aluno = $dados[0]; @endphp

        <form method="POST" action="{{ url('/sondagem/atualizaperfil/' . $aluno->alu_id) }}">
            @method('PUT')
            @csrf

            <!-- Dados do aluno selecionado -->
<input type="hidden" name="fk_id_aluno" value="{{ $aluno->alu_id }}">
            
            
            <div class="form-group">
                <label>Nome do estudante:</label>
                <input type="text" name="nome_aluno" value="{{ $aluno->alu_nome }}" readonly>
            </div>
            <div class="form-group">
                        <label>RA do estudante:</label>
                        <input type="text" name="alu_ra" value="{{ $aluno->alu_ra }}" readonly>
                    </div>

            <div class="row">
                <div class="form-group">
                    <label>Ano/Série:</label>
                    <input type="text" value="{{ $aluno->desc_modalidade . '-' . $aluno->desc_serie_modalidade }}" readonly>
                </div>

                <div class="form-group">
                    <label>Data de Nascimento:</label>
                    <input type="text" name="alu_nasc" value="{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->format('d/m/Y') }}" readonly>
                </div>

                <div class="form-group">
                    <label>Idade do estudante:</label>
                    <input type="text" name="alu_idade" value="{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->age }} anos" readonly>
                </div>
            </div>

            <div class="form-group">
                <label>Nome do Professor:</label>
                <input type="text" name="nome_professor" value="{{ $aluno->func_nome }}" readonly>
            </div>

            <!-- Dados adicionais do perfil -->
            @if(isset($perfil))

                <div class="form-group">
                    <label>Possui diagnóstico/laudo?</label>
                    <select name="diag_laudo">
                        <option value="1" @if(isset($perfil->diag_laudo) && $perfil->diag_laudo == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($perfil->diag_laudo) && $perfil->diag_laudo == 0) selected @endif>Não</option>
                    </select>
                </div>

                <!-- Outros campos adicionais -->
                <!-- Exemplo: CID, Médico, Data do Laudo -->
                <div class="row">
                    <div class="form-group">
                        <label>CID:</label>
                        <input type="text" name="cid" value="{{ $perfil->cid }}">
                    </div>
                    <div class="form-group">
                        <label>Médico:</label>
                        <input type="text" name="nome_medico" value="{{ $perfil->nome_medico }}">
                    </div>
                    <div class="form-group">
                        <label>Data do Laudo:</label>
                        <input type="date" name="data_laudo" value="{{ $perfil->data_laudo }}">
                    </div>
                </div>


                <div class="form-group">
                    <label>Nível suporte</label>
                    <select name="nivel_suporte">
                        <option value="1" @if(isset($perfil->nivel_suporte) && $perfil->nivel_suporte == 1) selected @endif>Nível 1 - Exige pouco apoio </option>
                        <option value="2" @if(isset($perfil->nivel_suporte) && $perfil->nivel_suporte == 2) selected @endif>Nível 2 - Exige apoio substancial</option>
                        <option value="3" @if(isset($perfil->nivel_suporte) && $perfil->nivel_suporte == 3) selected @endif>Nível 3 - Exige apoio muito substancial</option>
                    </select>
                </div>

                <div class="form-group">
                <label>Faz uso de medicamento?</label>
                    <select name="uso_medicamento">
                        <option value="1" @if(isset($perfil->uso_medicamento) && $perfil->uso_medicamento == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($perfil->uso_medicamento) && $perfil->uso_medicamento == 0) selected @endif>Não</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Quais?</label>
                    <input type="text" name="quais_medicamento" value="{{ isset($perfil->quais_medicamento) ? $perfil->quais_medicamento : '' }}">
                </div>

                <div class="row">
                    <div class="form-group">
                        <label>Necessita de profissional de apoio em sala?</label>
                        <select name="nec_pro_apoio">
                        <option value="1" @if(isset($perfil->nec_pro_apoio) && $perfil->nec_pro_apoio == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($perfil->nec_pro_apoio) && $perfil->nec_pro_apoio == 0) selected @endif>Não</option>
                        </select>
                    </div>

                    <div class="row">
                    <div class="form-group">
                        <label>O estudante conta com o profissional de apoio?</label>
                        <select name="prof_apoio">
                                <option value="1" @if(isset($perfil->prof_apoio) && $perfil->prof_apoio == 1) selected @endif>Sim</option>
                                <option value="0" @if(isset($perfil->prof_apoio) && $perfil->prof_apoio == 0) selected @endif>Não</option>
                        </select>
                    </div>
                </div>
                </div>   

                <div class="form-group">
                    <label>Em quais momentos da rotina esse profissional se faz necessário?</label>
                    <div class="checkbox-group">
                        <input type="hidden" name="loc_01" value="0">
                        <input type="checkbox" name="loc_01" value="1" @if(isset($perfil->loc_01) && $perfil->loc_01 == 1) checked @endif><label for="loc_01">Locomoção</label>
                        
                        <input type="hidden" name="hig_02" value="0">
                        <input type="checkbox" name="hig_02" value="1" @if(isset($perfil->hig_02) && $perfil->hig_02 == 1) checked @endif><label for="hig_02">Higiene</label>
                        
                        <input type="hidden" name="ali_03" value="0">
                        <input type="checkbox" name="ali_03" value="1" @if(isset($perfil->ali_03) && $perfil->ali_03 == 1) checked @endif><label for="ali_03">Alimentação</label>
                        
                        <input type="hidden" name="com_04" value="0">
                        <input type="checkbox" name="com_04" value="1" @if(isset($perfil->com_04) && $perfil->com_04 == 1) checked @endif><label for="com_04">Comunicação</label>
                        
                        <input type="hidden" name="out_05" value="0">
                        <input type="checkbox" name="out_05" value="1" @if(isset($perfil->out_05) && $perfil->out_05 == 1) checked @endif><label for="out_05">Outros</label>
                    </div>
                    <input type="text" name="out_momentos" placeholder="Quais?" value="{{$perfil->out_momentos }}">
                </div>


                <div class="form-group">
                    <label>O estudante conta com Atendimento Educacional Especializado?</label>
                    <select name="at_especializado">
                        <option value="1" @if(isset($perfil->at_especializado) && $perfil->at_especializado == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($perfil->at_especializado) && $perfil->at_especializado == 0) selected @endif>Não</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Nome do profissional do AEE:</label>
                    <input type="text" name="nome_prof_AEE" value="{{ isset($perfil->nome_prof_AEE) ? $perfil->nome_prof_AEE : '' }}">
                </div>

                <h2> II - Personalidade</h2>

                <div class="form-group">
                    <label>Principais características:</label>
                    <textarea rows="3" name="carac_principal">{{ isset($perfil->carac_principal) ? $perfil->carac_principal : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Principais áreas de interesse (brinquedos, jogos, temas, etc.):</label>
                    <textarea name="inter_princ_carac">{{ isset($perfil->inter_princ_carac) ? $perfil->inter_princ_carac : '' }}</textarea>

                </div>

                <div class="form-group">
                    <label>Gosta de fazer no tempo livre:</label>
                    <textarea name="livre_gosta_fazer">{{ isset($perfil->livre_gosta_fazer) ? $perfil->livre_gosta_fazer : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Deixa o estudante muito feliz:</label>
                    <textarea name="feliz_est">{{ isset($perfil->feliz_est) ? $perfil->feliz_est : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Deixa o estudante muito triste ou desconfortável:</label>
                    <textarea name="trist_est">{{ isset($perfil->trist_est) ? $perfil->trist_est : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Objeto de apego? Qual?</label>
                    <textarea name="obj_apego">{{ isset($perfil->obj_apego) ? $perfil->obj_apego : '' }}</textarea>
                </div>

                <h2 class="comunicacao-section">III - Comunicação</h2>

                <div class="form-group">
                   <label>Precisa de comunicação alternativa para expressar-se?</label>
                   <select name="precisa_comunicacao">
                     <option value="1" @if(isset($perfil->precisa_comunicacao) && $perfil->precisa_comunicacao == 1) selected @endif>Sim</option>
                     <option value="0" @if(isset($perfil->precisa_comunicacao) && $perfil->precisa_comunicacao == 0) selected @endif>Não</option>
                 </select>
                </div>


                <div class="form-group">
                 <label>Entende instruções dadas de forma verbal?</label>
                  <select name="entende_instrucao">
                     <option value="1" @if(isset($perfil->entende_instrucao) && $perfil->entende_instrucao == 1) selected @endif>Sim</option>
                    <option value="0" @if(isset($perfil->entende_instrucao) && $perfil->entende_instrucao == 0) selected @endif>Não</option>
                  </select>
            </div>

                <div class="form-group">
                    <label>Caso não,Como você recomenda dar instruções?</label>
                    <textarea name="recomenda_instrucao">{{ isset($perfil->recomenda_instrucao) ? $perfil->recomenda_instrucao : '' }}</textarea>
                </div>

                <h2>IV - Preferencias, sensibilidade e dificuldades</h2>

                <div class="form-group">
                    <label>Apresenta sensibilidade:</label>
                    <div class="checkbox-group">
                        <input type="hidden" name="s_auditiva" value="0">
                        <input type="hidden" name="s_auditiva" value="0">
<input type="checkbox" name="s_auditiva" value="1" @if(isset($perfil->s_auditiva) && $perfil->s_auditiva) checked @endif><label for="s_auditiva">Auditiva</label>
                        
                        <input type="hidden" name="s_visual" value="0">
                        <input type="hidden" name="s_visual" value="0">
<input type="checkbox" name="s_visual" value="1" @if(isset($perfil->s_visual) && $perfil->s_visual) checked @endif><label for="s_visual">Visual</label>
                        
                        <input type="hidden" name="s_tatil" value="0">
                        <input type="hidden" name="s_tatil" value="0">
<input type="checkbox" name="s_tatil" value="1" @if(isset($perfil->s_tatil) && $perfil->s_tatil) checked @endif><label for="s_tatil">Tátil</label>
                        
                        <input type="hidden" name="s_outros" value="0">
                        <input type="hidden" name="s_outros" value="0">
<input type="checkbox" name="s_outros" value="1" @if(isset($perfil->s_outros) && $perfil->s_outros) checked @endif><label for="s_outros">Outros estímulos</label>
                    </div>
                </div>


                <div class="form-group">
                    <label>Caso sim,Como manejar em sala de aula</label>
                    <textarea name="maneja_04">{{ isset($perfil->maneja_04) ? $perfil->maneja_04 : '' }}</textarea>
                </div>

                <div class="form-group">
                  <label>Apresenta seletividade alimentar?</label>
                    <select name="asa_04">
                        <option value="1" @if(isset($perfil->asa_04) && $perfil->asa_04 == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($perfil->asa_04) && $perfil->asa_04 == 0) selected @endif>Não</option>
                    </select>
                </div>


                <div class="form-group">
                    <label>Alimentos preferidos:</label>
                    <textarea rows="3" name="alimentos_pref_04">{{ isset($perfil->alimentos_pref_04) ? $perfil->alimentos_pref_04 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Alimentos que evita:</label>
                    <textarea name="alimento_evita_04">{{ isset($perfil->alimento_evita_04) ? $perfil->alimento_evita_04 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Com quem tem mais afinidade na escola (professores, colegas)? Identifique</label>
                    <textarea rows="3" name="contato_pc_04">{{ isset($perfil->contato_pc_04) ? $perfil->contato_pc_04 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Como reage no contato com novas pessoas ou situações</label>
                    <textarea rows="3" name="reage_contato">{{ isset($perfil->reage_contato) ? $perfil->reage_contato : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>O que ajuda a sua interação na escola e o que dificulta a sua interação na escola?
                    </label>
                    <textarea rows="3" name="interacao_escola_04">{{ isset($perfil->interacao_escola_04) ? $perfil->interacao_escola_04 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Há interesses específicos ou hiperfoco em algum tema ou atividade?</label>
                    <textarea rows="3" name="interesse_atividade_04">{{ isset($perfil->interesse_atividade_04) ? $perfil->interesse_atividade_04 : '' }}</textarea>
                </div>

                <div class="form-group">
    <label>Como o(a) estudante aprende melhor?</label>
    <div class="checkbox-group">
        <input type="hidden" name="aprende_visual_04" value="0">
<input type="checkbox" name="aprende_visual_04" value="1" @if(isset($perfil->aprende_visual_04) && $perfil->aprende_visual_04) checked @endif><label for="aprende_visual_04">Recurso visual</label>
        <input type="hidden" name="recurso_auditivo_04" value="0">
<input type="checkbox" name="recurso_auditivo_04" value="1" @if(isset($perfil->recurso_auditivo_04) && $perfil->recurso_auditivo_04) checked @endif><label for="recurso_auditivo_04">Recurso auditivo</label>
        <input type="hidden" name="material_concreto_04" value="0">
<input type="checkbox" name="material_concreto_04" value="1" @if(isset($perfil->material_concreto_04) && $perfil->material_concreto_04) checked @endif><label for="material_concreto_04">Material concreto</label>
        <input type="hidden" name="outro_identificar_04" value="0">
<input type="checkbox" name="outro_identificar_04" value="1" @if(isset($perfil->outro_identificar_04) && $perfil->outro_identificar_04) checked @endif><label for="outro_identificar_04">Outro</label>
    </div>

    <div class="form-group">
        <label></label>
        <textarea rows="3" name="descricao_outro_identificar_04">{{ isset($perfil->descricao_outro_identificar_04) ? $perfil->descricao_outro_identificar_04 : '' }}</textarea>
    </div>
</div>


                <div class="form-group">
                    <label>Gosta de atividades em grupo ou prefere trabalhar sozinho?</label>
                    <textarea rows="3" name="realiza_tarefa_04">{{ isset($perfil->realiza_tarefa_04) ? $perfil->realiza_tarefa_04 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Quais estratégias são utilizadas e se mostram eficazes?</label>
                    <textarea rows="3" name="mostram_eficazes_04">{{ isset($perfil->mostram_eficazes_04) ? $perfil->mostram_eficazes_04 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>O que desperta seu interesse para realizar uma tarefa/atividade</label>
                    <textarea rows="3" name="prefere_ts_04">{{ isset($perfil->prefere_ts_04) ? $perfil->prefere_ts_04 : '' }}</textarea>
                </div>


                
                <h2 class="comunicacao-section">V - Informações da família</h2>

                <div class="form-group">
                    <label>Há expectativas expressas da família em relação ao desempenho e a inclusão do estudante na sala de aula?</label>
                    <textarea rows="3" name="expectativa_05">{{ isset($perfil->expectativa_05) ? $perfil->expectativa_05 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Existe alguma estratégia utilizada no contexto familiar que pode ser reaplicada na escola?</label>
                    <textarea rows="3" name="estrategia_05">{{ isset($perfil->estrategia_05) ? $perfil->estrategia_05 : '' }}</textarea>
                </div>

                <div class="form-group">
                    <label>Como a família lida com situações de crise ou estresse do estudante?</label>
                    <textarea rows="3" name="crise_esta_05">{{ isset($perfil->crise_esta_05) ? $perfil->crise_esta_05 : '' }}</textarea>
                </div>

                <h2 class="profissionais-title">Cadastro de Profissionais</h2>
                <table class="table table-bordered profissionais-table">
                    <thead>
                        <tr>
                            <th>Nome do Profissional</th>
                            <th>Especialidade/Área</th>
                            <th>Observações</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td><input type="text" placeholder="Nome do Profissional" class="form-control"></td>
                            <td><input type="text" placeholder="Especialidade/Área" class="form-control"></td>
                            <td><input type="text" placeholder="Observações" class="form-control"></td>
                        </tr>
                        <tr>
                            <td><input type="text" placeholder="Nome do Profissional" class="form-control"></td>
                            <td><input type="text" placeholder="Especialidade/Área" class="form-control"></td>
                            <td><input type="text" placeholder="Observações" class="form-control"></td>
                        </tr>
                        <tr>
                            <td><input type="text" placeholder="Nome do Profissional" class="form-control"></td>
                            <td><input type="text" placeholder="Especialidade/Área" class="form-control"></td>
                            <td><input type="text" placeholder="Observações" class="form-control"></td>
                        </tr>
                        <tr>
                            <td><input type="text" placeholder="Nome do Profissional" class="form-control"></td>
                            <td><input type="text" placeholder="Especialidade/Área" class="form-control"></td>
                            <td><input type="text" placeholder="Observações" class="form-control"></td>
                        </tr>
                        <tr>
                            <td><input type="text" placeholder="Nome do Profissional" class="form-control"></td>
                            <td><input type="text" placeholder="Especialidade/Área" class="form-control"></td>
                            <td><input type="text" placeholder="Observações" class="form-control"></td>
                        </tr>
                    </tbody>
                </table>

            @endif

            <!-- Botões -->
            <div class="button-group no-break" style="text-align:right; margin-bottom:10px;">
                <button type="submit" class="btn btn-primary" id="confirmar-alteracao" onclick="return confirm('Tem certeza que deseja atualizar o perfil do estudante?')">Confirma Alteração</button>
                <a href="{{ route('index') }}" class="btn btn-danger">Cancelar</a>
                <button type="button" class="pdf-button btn btn-warning">Gerar PDF</button>
            </div>
        </form>
    <div class="logo-bg-bottom">
        <img src="{{ asset('img/logo_baixo.png') }}" alt="Logo Inferior" class="logo-img-bottom">
    </div>
    @else
        <!-- Caso nenhum aluno esteja selecionado -->
        <p>Nenhum estudante foi selecionado. Por favor, selecione um estudante para visualizar os dados.</p>
    @endif
</div>

@section('scripts')
<!-- Scripts para geração de PDF -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
<script>
// Verifica se o jQuery já está carregado
function checkJQuery() {
    if (window.jQuery) {
        initPdfGeneration();
    } else {
        // Se não estiver carregado, carrega o jQuery
        const script = document.createElement('script');
        script.src = 'https://code.jquery.com/jquery-3.6.0.min.js';
        script.onload = initPdfGeneration;
        document.head.appendChild(script);
    }
}

function initPdfGeneration() {
    $(document).on('click', '.pdf-button', function(e) {
        e.preventDefault();
        generatePdf(this);
    });
}

function generatePdf(button) {
    const originalText = $(button).text();
    $(button).prop('disabled', true).text('Gerando PDF...');
    if (typeof window.jspdf === 'undefined' || typeof html2canvas === 'undefined') {
        alert('Erro ao carregar as bibliotecas necessárias. Recarregue a página.');
        $(button).prop('disabled', false).text(originalText);
        return;
    }
    try {
        const { jsPDF } = window.jspdf;
        const element = document.querySelector('.perfil-container');
        const options = {
            scale: 1.5, // ajuste fino para evitar cortes abruptos
            useCORS: true,
            allowTaint: true,
            scrollY: 0,
            windowHeight: document.documentElement.offsetHeight
        };
        html2canvas(element, options).then(canvas => {
            const imgData = canvas.toDataURL('image/png');
            const pdf = new jsPDF({ orientation: 'portrait', unit: 'mm', format: 'a4' });
            const imgProps = pdf.getImageProperties(imgData);
            const pdfWidth = pdf.internal.pageSize.getWidth() - 20;
            const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
            let heightLeft = pdfHeight;
            let position = 10;
            const pageHeight = pdf.internal.pageSize.getHeight() - 20;
            while (heightLeft > 0) {
                pdf.addImage(imgData, 'PNG', 10, position, pdfWidth, pdfHeight);
                heightLeft -= pageHeight;
                if (heightLeft > 0) {
                    pdf.addPage();
                    position = heightLeft - pdfHeight;
                }
            }
            let nomeAluno = $(element).find('input[name="nome_aluno"]').val() || 'Estudante';
            nomeAluno = nomeAluno
                ? nomeAluno.normalize('NFD').replace(/[\u0300-\u036f]/g, '').replace(/[^a-zA-Z0-9]/g, '_').replace(/_+/g, '_').replace(/^_+|_+$/g, '')
                : 'Estudante';
            const hoje = new Date();
            const dataAtual = [
                String(hoje.getDate()).padStart(2, '0'),
                String(hoje.getMonth() + 1).padStart(2, '0'),
                hoje.getFullYear()
            ].join('_');
            const nomeArquivo = `PerfilEstudante_${nomeAluno}_${dataAtual}.pdf`;
            pdf.save(nomeArquivo);
        }).catch(error => {
            alert('Ocorreu um erro ao gerar o PDF.');
        }).finally(() => {
            $(button).prop('disabled', false).text(originalText);
        });
    } catch (error) {
        alert('Erro ao processar a geração do PDF.');
        $(button).prop('disabled', false).text(originalText);
    }
}
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', checkJQuery);
} else {
    checkJQuery();
}
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

<!-- Script para redirecionamento após atualização -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Verifica se há mensagem de sucesso
    const successMessage = '{{ session('success') }}';
    if (successMessage) {
        // Redireciona para a página de origem após 2 segundos
        setTimeout(function() {
            window.location.href = '{{ route('perfil.estudante') }}';
        }, 2000);
    }

    // Adiciona validação no submit do formulário
    const form = document.querySelector('form');
    form.addEventListener('submit', function(e) {
        // Debug: Log dos dados do formulário
        const formData = new FormData(form);
        console.log('Dados do formulário:');
        for (let [key, value] of formData.entries()) {
            console.log(key + ': ' + value);
        }

        // Confirmação antes de enviar
        if (!confirm('Tem certeza que deseja atualizar o perfil do estudante?')) {
            e.preventDefault();
            return false;
        }
        
        return true;
    });
});
</script>
@endsection

<style>
    .profissionais-title {
        color: #d35400;
        margin-top: 30px;
        margin-bottom: 15px;
        text-align: left;
        font-size: 1.3rem;
        padding-left: 2px;
    }
    .profissionais-table {
        width: 100%;
        max-width: 100%;
        margin-bottom: 20px;
        border-collapse: collapse;
        margin-left: 0;
    }
    .profissionais-table th, .profissionais-table td {
        border: 1px solid #ccc;
        padding: 8px 6px;
        text-align: left;
        vertical-align: middle;
    }
    .profissionais-table input[type="text"] {
        width: 100%;
        box-sizing: border-box;
        padding: 6px;
        border-radius: 4px;
        border: 1px solid #ccc;
        font-size: 1rem;
    }
    @media (max-width: 700px) {
        .profissionais-table, .profissionais-table thead, 
        .profissionais-table tbody, .profissionais-table th, 
        .profissionais-table td, .profissionais-table tr {
            display: block;
        }
        .profissionais-table th {
            background: #f5f5f5;
            font-weight: bold;
        }
        .profissionais-table td {
            border: none;
            border-bottom: 1px solid #eee;
        }
    }
    .logo-bg-top, .logo-bg-bottom {
        width: 100%;
        background: linear-gradient(90deg, #f5f5f5 0%, #fff 100%);
        display: flex;
        justify-content: center;
        align-items: center;
        padding: 12px 0 8px 0;
        margin-bottom: 10px;
        border-radius: 8px;
    }
    .logo-bg-bottom {
        margin-top: 24px;
        margin-bottom: 0;
    }
    .logo-img-top, .logo-img-bottom {
        max-width: 170px;
        width: 100%;
        height: auto;
        opacity: 0.92;
    }
    @media (max-width: 700px) {
        .logo-img-top, .logo-img-bottom {
            max-width: 120px;
        }
        .logo-bg-top, .logo-bg-bottom {
            padding: 6px 0;
        }
    }
</style>
@endsection

@section('content')
<div class="perfil-container">
    <div class="logo-bg-top">
        <img src="{{ asset('img/logogando.png') }}" alt="Logo Superior" class="logo-img-top">
    </div>
    <h2>I - Perfil do Estudante</h2>

    @if(isset($dados) && count($dados) > 0)
        @php $aluno = $dados[0]; @endphp

        <form method="POST" action="{{ url('/sondagem/atualizaperfil/' . $aluno->alu_id) }}">
            @method('PUT')
            @csrf

            <!-- Dados do aluno selecionado -->
<input type="hidden" name="fk_id_aluno" value="{{ $aluno->alu_id }}">
            
            
            <div class="form-group">
                <label>Nome do estudante:</label>
                <input type="text" name="nome_aluno" value="{{ $aluno->alu_nome }}" readonly>
            </div>

            <div class="row">
                <div class="form-group">
                    <label>Ano/Série:</label>
                    <input type="text" value="{{ $aluno->desc_modalidade . '-' . $aluno->desc_serie_modalidade }}" readonly>
                </div>

                <div class="form-group">
                    <label>Data de Nascimento:</label>
                    <input type="text" name="alu_nasc" value="{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->format('d/m/Y') }}" readonly>
                </div>

                <div class="form-group">
                    <label>Idade do estudante:</label>
                    <input type="text" name="alu_idade" value="{{ \Carbon\Carbon::parse($aluno->alu_dtnasc)->age }} anos" readonly>
                </div>
            </div>

            <div class="form-group">
                <label>Nome do Professor:</label>
                <input type="text" name="nome_professor" value="{{ $aluno->func_nome }}" readonly>
            </div>

            @if(isset($perfil))

                <!-- Seção de Dados do Perfil -->
                <div class="form-group">
                    <label>Possui diagnóstico/laudo?</label>
                    <select name="diag_laudo">
                        <option value="1" @if(isset($perfil->diag_laudo) && $perfil->diag_laudo == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($perfil->diag_laudo) && $perfil->diag_laudo == 0) selected @endif>Não</option>
                    </select>
                </div>

                <!-- Outros campos do formulário -->
                <div class="button-group">
                    <button type="submit" class="btn btn-primary" id="btn-salvar-alteracoes">Salvar Alterações</button>
                    <button type="button" class="btn btn-secondary pdf-button">Gerar PDF</button>
                </div>
            @else
                <p>Nenhum perfil encontrado para este estudante.</p>
            @endif
        </form>
    @else
        <p>Nenhum dado de estudante encontrado.</p>
    @endif
</div>
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Configura o botão de gerar PDF
        const pdfButton = document.querySelector(".pdf-button");
        if (pdfButton) {
            pdfButton.addEventListener("click", function() {
                const element = document.querySelector('.perfil-container');
                
                html2canvas(element, {
                    scale: 1.0,
                    useCORS: true,
                    logging: false
                }).then(canvas => {
                    const imgData = canvas.toDataURL('image/png');
                    const pdf = new jspdf.jsPDF('p', 'mm', 'a4');
                    const imgProps = pdf.getImageProperties(imgData);
                    const pdfWidth = pdf.internal.pageSize.getWidth();
                    const pdfHeight = (imgProps.height * pdfWidth) / imgProps.width;
                    
                    pdf.addImage(imgData, 'PNG', 0, 0, pdfWidth, pdfHeight);
                    pdf.save('perfil_estudante.pdf');
                });
            });
        }
    });
</script>
@endpush
