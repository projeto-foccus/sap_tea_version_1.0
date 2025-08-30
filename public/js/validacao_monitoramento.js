/**
 * Validação para o formulário de monitoramento de alunos
 * Garante que cada linha tenha tanto data quanto checkbox preenchidos
 */

/**
 * Preenche o formulário com dados já cadastrados
 * @param {Object} dados Objeto contendo os dados de monitoramento por eixo
 */
function preencherFormularioComDadosSalvos(dados) {
    console.log('Iniciando preenchimento do formulário com dados salvos:', dados);
    
    if (!dados || Object.keys(dados).length === 0) {
        console.warn('Nenhum dado de monitoramento disponível para preencher o formulário');
        return;
    }
    
    // Verifica se estamos na página do aluno correto
    const alunoIdNoFormulario = document.querySelector('input[name="aluno_id"]')?.value;
    console.log(`ID do aluno no formulário: ${alunoIdNoFormulario}`);
    
    // Exibe informação sobre o aluno no console para debug
    const alunoNome = document.querySelector('.card-title')?.textContent || 'Nome não encontrado';
    console.log(`Preenchendo formulário para o aluno: ${alunoNome}`);
    
    // Limpa qualquer dado anterior que possa estar no formulário
    limparFormulario();
    
    // Eixos disponíveis no formulário
    const eixos = ['comunicacao', 'comportamento', 'socioemocional'];
    let totalLinhasPreenchidas = 0;
    
    // Processar cada eixo
    eixos.forEach(function(eixo) {
        if (dados[eixo]) {
            console.log(`Processando eixo ${eixo}:`, dados[eixo]);
            let linhasEixo = 0;
            
            // Para cada código de atividade nos dados
            Object.keys(dados[eixo]).forEach(function(codAtividade) {
                const atividade = dados[eixo][codAtividade];
                console.log(`Buscando linhas para atividade ${codAtividade} do eixo ${eixo}:`, atividade);
                
                // Verificar se os dados da atividade são válidos
                if (!atividade || typeof atividade !== 'object') {
                    console.warn(`Dados inválidos para atividade ${codAtividade} do eixo ${eixo}`);
                    return;
                }
                
                // Tenta primeiro com seletor específico
                let linhas = document.querySelectorAll(`tr[data-cod-atividade="${codAtividade}"][data-eixo="${eixo}"]`);
                
                // Se não encontrar, tenta uma abordagem alternativa
                if (linhas.length === 0) {
                    console.warn(`Nenhuma linha encontrada com seletor específico para atividade ${codAtividade} do eixo ${eixo}. Tentando abordagem alternativa...`);
                    // Procura por linhas que contenham o código da atividade em qualquer campo
                    const todasLinhas = document.querySelectorAll(`tr[data-eixo="${eixo}"]`);
                    const linhasEncontradas = [];
                    
                    todasLinhas.forEach(linha => {
                        const conteudo = linha.textContent || '';
                        if (conteudo.includes(codAtividade)) {
                            linhasEncontradas.push(linha);
                        }
                    });
                    
                    if (linhasEncontradas.length > 0) {
                        console.log(`Encontradas ${linhasEncontradas.length} linhas alternativas para atividade ${codAtividade}`);
                        linhas = linhasEncontradas;
                    }
                }
                
                console.log(`Encontradas ${linhas.length} linhas para atividade ${codAtividade}`);
                
                // Registra todos os atributos data-* das linhas para debug
                if (linhas.length > 0) {
                    console.log('Atributos data-* da primeira linha encontrada:', Array.from(linhas[0].attributes)
                        .filter(attr => attr.name.startsWith('data-'))
                        .map(attr => `${attr.name}="${attr.value}"`))
                }
                
                linhas.forEach(function(linha) {
                    // Obter o índice da linha para acessar os campos
                    const idx = linha.getAttribute('data-idx');
                    if (!idx) {
                        console.warn('Linha sem atributo data-idx:', linha);
                        return;
                    }
                    
                    console.log(`Preenchendo linha com idx=${idx} para atividade ${codAtividade}`);
                    let camposPreenchidos = 0;
                    
                    // Preencher campo de data - tenta múltiplos seletores
                    let inputData = linha.querySelector(`input[name="${eixo}[${idx}][data_inicial]"]`);
                    if (!inputData) {
                        inputData = linha.querySelector(`input[type="date"]`);
                    }
                    if (!inputData) {
                        // Tenta encontrar qualquer input de data na linha
                        inputData = linha.querySelector('input[type="date"]');
                    }
                    
                    if (inputData && atividade.data_inicial) {
                        console.log(`Preenchendo data: ${atividade.data_inicial}`);
                        // Formata a data no formato YYYY-MM-DD se necessário
                        let dataFormatada = atividade.data_inicial;
                        
                        // Se a data estiver no formato brasileiro DD/MM/YYYY, converte para YYYY-MM-DD
                        if (dataFormatada.includes('/')) {
                            const partes = dataFormatada.split('/');
                            if (partes.length === 3) {
                                dataFormatada = `${partes[2]}-${partes[1].padStart(2, '0')}-${partes[0].padStart(2, '0')}`;
                            }
                        }
                        
                        inputData.value = dataFormatada;
                        camposPreenchidos++;
                        console.log(`Data preenchida com sucesso: ${dataFormatada}`);
                    } else if (!inputData) {
                        console.warn(`Campo de data não encontrado para ${eixo}[${idx}]`);
                    } else if (!atividade.data_inicial) {
                        console.warn(`Dados de data não disponíveis para atividade ${codAtividade}`);
                    }
                    
                    // Marcar checkbox Sim ou Não
                    if (atividade.sim_inicial === '1') {
                        const simCheckbox = linha.querySelector(`.sim-checkbox[data-eixo="${eixo}"][data-idx="${idx}"]`);
                        if (simCheckbox) {
                            console.log('Marcando checkbox SIM');
                            simCheckbox.checked = true;
                            camposPreenchidos++;
                        } else {
                            console.warn(`Checkbox SIM não encontrado para ${eixo}[${idx}]`);
                        }
                    } else if (atividade.nao_inicial === '1') {
                        const naoCheckbox = linha.querySelector(`.nao-checkbox[data-eixo="${eixo}"][data-idx="${idx}"]`);
                        if (naoCheckbox) {
                            console.log('Marcando checkbox NAO');
                            naoCheckbox.checked = true;
                            camposPreenchidos++;
                        } else {
                            console.warn(`Checkbox NAO não encontrado para ${eixo}[${idx}]`);
                        }
                    }
                    
                    // Preencher campo de observações - tenta múltiplos seletores
                    let inputObs = linha.querySelector(`textarea[name="${eixo}[${idx}][observacoes]"]`);
                    if (!inputObs) {
                        inputObs = linha.querySelector(`input[type="text"][name*="observacoes"]`);
                    }
                    if (!inputObs) {
                        // Tenta encontrar qualquer campo de texto na linha que possa ser para observações
                        inputObs = linha.querySelector('textarea') || linha.querySelector('input[type="text"]');
                    }
                    
                    if (inputObs && atividade.observacoes) {
                        console.log(`Preenchendo observações: ${atividade.observacoes}`);
                        inputObs.value = atividade.observacoes;
                        camposPreenchidos++;
                        console.log(`Observações preenchidas com sucesso`);
                    } else if (!inputObs) {
                        console.warn(`Campo de observações não encontrado para ${eixo}[${idx}]`);
                    } else if (!atividade.observacoes) {
                        console.log(`Sem observações para atividade ${codAtividade}`);
                    }
                    
                    if (camposPreenchidos > 0) {
                        linhasEixo++;
                        totalLinhasPreenchidas++;
                        console.log(`Linha ${idx} preenchida com sucesso (${camposPreenchidos} campos)`);
                    } else {
                        console.warn(`Nenhum campo preenchido para linha ${idx}`);
                    }
                });
            });
            
            console.log(`Total de ${linhasEixo} linhas preenchidas para o eixo ${eixo}`);
        } else {
            console.log(`Nenhum dado encontrado para o eixo ${eixo}`);
        }
    });
    
    console.log(`Preenchimento do formulário concluído. Total de ${totalLinhasPreenchidas} linhas preenchidas.`);
}

/**
 * Limpa todos os campos do formulário para evitar dados antigos
 */
function limparFormulario() {
    console.log('Limpando dados antigos do formulário...');
    
    // Limpar todos os campos de data
    document.querySelectorAll('input[type="date"]').forEach(input => {
        input.value = '';
    });
    
    // Desmarcar todos os checkboxes
    document.querySelectorAll('input[type="checkbox"]').forEach(checkbox => {
        checkbox.checked = false;
    });
    
    // Limpar todos os campos de observações
    document.querySelectorAll('textarea').forEach(textarea => {
        textarea.value = '';
    });
    
    console.log('Formulário limpo com sucesso');
}

document.addEventListener('DOMContentLoaded', function() {
    // Preencher o formulário com dados já cadastrados, se existirem
    if (typeof dadosMonitoramento !== 'undefined' && dadosMonitoramento) {
        console.log('Dados de monitoramento encontrados, preenchendo formulário...');
        preencherFormularioComDadosSalvos(dadosMonitoramento);
    } else {
        console.log('Nenhum dado de monitoramento encontrado para preencher o formulário');
    }
    
    // Exclusividade dos checkboxes sim/não
    document.querySelectorAll('.sim-checkbox').forEach(function(simCb) {
        simCb.addEventListener('change', function() {
            if (this.checked) {
                const eixo = this.dataset.eixo;
                const idx = this.dataset.idx;
                const naoCb = document.querySelector('.nao-checkbox[data-eixo="'+eixo+'"][data-idx="'+idx+'"]');
                if (naoCb) naoCb.checked = false;
            }
        });
    });
    
    document.querySelectorAll('.nao-checkbox').forEach(function(naoCb) {
        naoCb.addEventListener('change', function() {
            if (this.checked) {
                const eixo = this.dataset.eixo;
                const idx = this.dataset.idx;
                const simCb = document.querySelector('.sim-checkbox[data-eixo="'+eixo+'"][data-idx="'+idx+'"]');
                if (simCb) simCb.checked = false;
            }
        });
    });

    // Botão salvar com confirmação e validação
    const btnSalvar = document.getElementById('btn-salvar');
    const form = document.getElementById('monitoramentoForm');
    
    if (btnSalvar && form) {
        btnSalvar.addEventListener('click', function(e) {
            e.preventDefault();
            
            // Validar o formulário - verificar se cada linha tem data e checkbox
            let linhasIncompletas = [];
            
            document.querySelectorAll('tr').forEach(row => {
                const dataInput = row.querySelector('input[type="date"]');
                const simCheckbox = row.querySelector('.sim-checkbox');
                const naoCheckbox = row.querySelector('.nao-checkbox');
                
                // Se a linha tem campos de data e checkbox
                if (dataInput && (simCheckbox || naoCheckbox)) {
                    // Se apenas um dos dois está preenchido (data ou checkbox)
                    const temData = dataInput.value !== '';
                    const temCheckbox = (simCheckbox && simCheckbox.checked) || (naoCheckbox && naoCheckbox.checked);
                    
                    // Se tem data mas não tem checkbox ou vice-versa
                    if ((temData && !temCheckbox) || (!temData && temCheckbox)) {
                        const codigo = row.querySelector('td:first-child')?.textContent?.trim();
                        if (codigo) {
                            linhasIncompletas.push(codigo);
                        }
                    }
                }
            });
            
            // Se encontrou linhas incompletas
            if (linhasIncompletas.length > 0) {
                alert('Atenção! As seguintes linhas estão incompletas (falta data ou checkbox):\n\n' + 
                      linhasIncompletas.join('\n') + 
                      '\n\nPara cada linha, é necessário preencher TANTO a data QUANTO marcar um checkbox (Sim ou Não).');
                return;
            }
            
            // Confirmação antes de salvar
            if (confirm('Deseja salvar as alterações?')) {
                // Preparar dados para envio
                const comunicacaoData = {};
                const comportamentoData = {};
                const socioData = {};
                
                // Processar dados de comunicação
                document.querySelectorAll('input[name^="comunicacao["]').forEach(input => {
                    const match = input.name.match(/comunicacao\[(\d+)\]\[([^\]]+)\]/);
                    if (match) {
                        const idx = match[1];
                        const campo = match[2];
                        
                        // Verificar se esta linha tem data e checkbox
                        const row = input.closest('tr');
                        const dataInput = row.querySelector('input[type="date"]');
                        const simCheckbox = row.querySelector('.sim-checkbox');
                        const naoCheckbox = row.querySelector('.nao-checkbox');
                        
                        if (dataInput && dataInput.value !== '' && 
                            ((simCheckbox && simCheckbox.checked) || (naoCheckbox && naoCheckbox.checked))) {
                            
                            if (!comunicacaoData[idx]) comunicacaoData[idx] = {};
                            
                            if (input.type === 'checkbox') {
                                comunicacaoData[idx][campo] = input.checked ? '1' : '0';
                            } else {
                                comunicacaoData[idx][campo] = input.value;
                            }
                        }
                    }
                });
                
                // Processar dados de comportamento
                document.querySelectorAll('input[name^="comportamento["]').forEach(input => {
                    const match = input.name.match(/comportamento\[(\d+)\]\[([^\]]+)\]/);
                    if (match) {
                        const idx = match[1];
                        const campo = match[2];
                        
                        // Verificar se esta linha tem data e checkbox
                        const row = input.closest('tr');
                        const dataInput = row.querySelector('input[type="date"]');
                        const simCheckbox = row.querySelector('.sim-checkbox');
                        const naoCheckbox = row.querySelector('.nao-checkbox');
                        
                        if (dataInput && dataInput.value !== '' && 
                            ((simCheckbox && simCheckbox.checked) || (naoCheckbox && naoCheckbox.checked))) {
                            
                            if (!comportamentoData[idx]) comportamentoData[idx] = {};
                            
                            if (input.type === 'checkbox') {
                                comportamentoData[idx][campo] = input.checked ? '1' : '0';
                            } else {
                                comportamentoData[idx][campo] = input.value;
                            }
                        }
                    }
                });
                
                // Processar dados de socioemocional
                document.querySelectorAll('input[name^="socioemocional["]').forEach(input => {
                    const match = input.name.match(/socioemocional\[(\d+)\]\[([^\]]+)\]/);
                    if (match) {
                        const idx = match[1];
                        const campo = match[2];
                        
                        // Verificar se esta linha tem data e checkbox
                        const row = input.closest('tr');
                        const dataInput = row.querySelector('input[type="date"]');
                        const simCheckbox = row.querySelector('.sim-checkbox');
                        const naoCheckbox = row.querySelector('.nao-checkbox');
                        
                        if (dataInput && dataInput.value !== '' && 
                            ((simCheckbox && simCheckbox.checked) || (naoCheckbox && naoCheckbox.checked))) {
                            
                            if (!socioData[idx]) socioData[idx] = {};
                            
                            if (input.type === 'checkbox') {
                                socioData[idx][campo] = input.checked ? '1' : '0';
                            } else {
                                socioData[idx][campo] = input.value;
                            }
                        }
                    }
                });
                
                // Criar campos hidden para enviar os dados JSON
                if (Object.keys(comunicacaoData).length > 0) {
                    const comunicacaoInput = document.createElement('input');
                    comunicacaoInput.type = 'hidden';
                    comunicacaoInput.name = 'comunicacao';
                    comunicacaoInput.value = JSON.stringify(comunicacaoData);
                    form.appendChild(comunicacaoInput);
                }
                
                if (Object.keys(comportamentoData).length > 0) {
                    const comportamentoInput = document.createElement('input');
                    comportamentoInput.type = 'hidden';
                    comportamentoInput.name = 'comportamento';
                    comportamentoInput.value = JSON.stringify(comportamentoData);
                    form.appendChild(comportamentoInput);
                }
                
                if (Object.keys(socioData).length > 0) {
                    const socioInput = document.createElement('input');
                    socioInput.type = 'hidden';
                    socioInput.name = 'socioemocional';
                    socioInput.value = JSON.stringify(socioData);
                    form.appendChild(socioInput);
                }
                
                // Enviar o formulário
                form.submit();
            }
        });
    }
    
    /**
     * Preenche o formulário com os dados já cadastrados
     * @param {Object} dados - Dados do monitoramento já cadastrados
     */
    function preencherFormularioComDadosSalvos(dados) {
        console.log('Preenchendo formulário com dados salvos:', dados);
        
        // Percorrer cada eixo
        const eixos = ['comunicacao', 'comportamento', 'socioemocional'];
        
        eixos.forEach(function(eixo) {
            if (dados[eixo]) {
                // Para cada atividade no eixo
                Object.keys(dados[eixo]).forEach(function(codAtividade) {
                    const atividade = dados[eixo][codAtividade];
                    
                    // Encontrar a linha correspondente no formulário
                    const linhas = document.querySelectorAll(`tr[data-cod-atividade="${codAtividade}"]`);
                    
                    linhas.forEach(function(linha) {
                        if (linha.getAttribute('data-eixo') === eixo) {
                            const idx = linha.getAttribute('data-idx');
                            
                            // Preencher data
                            const inputData = document.querySelector(`input[name="${eixo}[${idx}][data_inicial]"]`);
                            if (inputData && atividade.data_inicial) {
                                inputData.value = atividade.data_inicial;
                            }
                            
                            // Marcar checkbox Sim ou Não
                            if (atividade.sim_inicial === 1 || atividade.sim_inicial === '1') {
                                const simCheckbox = document.querySelector(`.sim-checkbox[data-eixo="${eixo}"][data-idx="${idx}"]`);
                                if (simCheckbox) simCheckbox.checked = true;
                            } else if (atividade.nao_inicial === 1 || atividade.nao_inicial === '1') {
                                const naoCheckbox = document.querySelector(`.nao-checkbox[data-eixo="${eixo}"][data-idx="${idx}"]`);
                                if (naoCheckbox) naoCheckbox.checked = true;
                            }
                            
                            // Preencher observações
                            const inputObs = document.querySelector(`textarea[name="${eixo}[${idx}][observacoes]"]`);
                            if (inputObs && atividade.observacoes) {
                                inputObs.value = atividade.observacoes;
                            }
                        }
                    });
                });
            }
        });
    }
});
