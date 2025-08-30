<script>
// Garantir que o JS está carregado
console.log('[scripts_monitoramento] JS carregado');

// Função para carregar atividades já cadastradas
function carregarAtividadesCadastradas() {
    console.log('[scripts_monitoramento] Carregando atividades cadastradas...');
    
    // Obter o ID do aluno
    const alunoInput = document.getElementById('aluno_id_hidden') || document.querySelector('input[name="aluno_id"]');
    let aluno_id = alunoInput ? alunoInput.value : '';
    
    if (!aluno_id) {
        // Extrai o ID da URL, ex: /rotina_monitoramento/cadastrar/52
        const match = window.location.pathname.match(/cadastrar\/(\d+)/);
        if (match) {
            aluno_id = match[1];
            if (alunoInput) alunoInput.value = aluno_id;
        }
    }
    
    if (!aluno_id) {
        console.error('[scripts_monitoramento] ID do aluno não encontrado!');
        return;
    }
    
    // URL para buscar atividades cadastradas
    const url = `/monitoramento/atividades-cadastradas/${aluno_id}`;
    
    fetch(url, {
        method: 'GET',
        headers: {
            'Accept': 'application/json',
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => { throw err; });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            console.log('[scripts_monitoramento] Atividades cadastradas:', data.data);
            
            // Processar cada eixo
            processarAtividadesCadastradas('comunicacao', data.data.com_lin);
            processarAtividadesCadastradas('comportamento', data.data.comportamento);
            processarAtividadesCadastradas('socioemocional', data.data.int_socio);
        } else {
            console.error('[scripts_monitoramento] Erro ao carregar atividades:', data.message);
        }
    })
    .catch(err => {
        console.error('[scripts_monitoramento] Erro na requisição:', err);
    });
}

// Função para processar e marcar atividades já cadastradas
function processarAtividadesCadastradas(eixoFrontend, atividades) {
    if (!atividades || !Array.isArray(atividades) || atividades.length === 0) {
        console.log(`[scripts_monitoramento] Nenhuma atividade cadastrada para o eixo ${eixoFrontend}`);
        return;
    }
    
    console.log(`[scripts_monitoramento] Processando ${atividades.length} atividades do eixo ${eixoFrontend}`);
    
    // Para cada atividade cadastrada
    atividades.forEach(atividade => {
        // Encontrar a linha correspondente no formulário
        const linhas = document.querySelectorAll(`tr[data-eixo="${eixoFrontend}"]`);
        
        linhas.forEach(linha => {
            const codAtividade = linha.getAttribute('data-cod-atividade');
            const linhaFlag = linha.querySelector('input[name$="[flag]"]')?.value || '';
            
            // Verificar tanto o código da atividade quanto o flag para garantir correspondência exata
            if (codAtividade === atividade.cod_atividade && 
                (linhaFlag === '' || linhaFlag === atividade.flag.toString())) {
                console.log(`[scripts_monitoramento] Encontrada atividade cadastrada: ${codAtividade}`);
                
                // Preencher os campos com os dados cadastrados
                const dataInput = linha.querySelector('input[type="date"]');
                if (dataInput) {
                    dataInput.value = atividade.data_monitoramento;
                    dataInput.setAttribute('readonly', true);
                }
                
                // Marcar checkbox sim ou não conforme o valor realizado
                const simInput = linha.querySelector('input[name$="[sim_inicial]"]');
                const naoInput = linha.querySelector('input[name$="[nao_inicial]"]');
                
                if (simInput && naoInput) {
                    if (atividade.realizado === 1 || atividade.realizado === true) {
                        simInput.checked = true;
                        naoInput.checked = false;
                    } else {
                        simInput.checked = false;
                        naoInput.checked = true;
                    }
                    
                    // Desabilitar os checkboxes
                    simInput.disabled = true;
                    naoInput.disabled = true; // Desabilita o checkbox "Não"
                }
                
                // Preencher observações
                const obsInput = linha.querySelector('textarea[name$="[observacoes]"]');
                if (obsInput) {
                    obsInput.value = atividade.observacoes || '';
                    obsInput.setAttribute('readonly', true);
                }
                
                // Atualizar o campo flag (hidden)
                const flagInput = linha.querySelector('input[name$="[flag]"]');
                if (flagInput) {
                    flagInput.value = atividade.flag;
                }
                
                // Desabilitar o botão de salvar e alterar a cor para vermelho
                const botaoSalvar = linha.querySelector('.btn-salvar-linha');
                if (botaoSalvar) {
                    botaoSalvar.disabled = true;
                    botaoSalvar.classList.remove('btn-success');
                    botaoSalvar.classList.add('btn-danger');
                    botaoSalvar.style.width = '100%'; // Aumentar largura para igualar ao botão verde
                    botaoSalvar.innerHTML = 'Cadastrada';
                }
            }
        });
    });
}

// Listener para todos os botões salvar do eixo comunicacao
function adicionarListenersSalvarLinhaGenerico() {
    document.querySelectorAll('button.btn-salvar-linha').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            const eixo = this.getAttribute('data-eixo');
            console.log(`[scripts_monitoramento] Clique no botão salvar atividade (eixo: ${eixo})`, this);
            // Coletar dados da linha
            const linha = this.closest(`tr[data-eixo="${eixo}"]`);
            if (!linha) {
                console.error('Linha não encontrada para salvar.');
                alert('Erro interno: linha não localizada.');
                return;
            }
            // Coleta campos dinâmicos
            const idx = linha.getAttribute('data-idx') || '';
            const cod_atividade = linha.getAttribute('data-cod-atividade') || '';
            const alunoInput = document.getElementById('aluno_id_hidden') || document.querySelector('input[name="aluno_id"]');
            let aluno_id = alunoInput ? alunoInput.value : '';
            if (!aluno_id) {
                // Extrai o ID da URL, ex: /rotina_monitoramento/cadastrar/52
                const match = window.location.pathname.match(/cadastrar\/(\d+)/);
                if (match) {
                    aluno_id = match[1];
                    if (alunoInput) alunoInput.value = aluno_id;
                }
            }
            if (!aluno_id) {
                alert('ID do aluno não encontrado! Não é possível salvar.');
                return;
            }
            // Sempre garantir que o campo hidden está preenchido antes de enviar
            if (alunoInput && alunoInput.value !== aluno_id) {
                alunoInput.value = aluno_id;
            }
            const dataInput = linha.querySelector('input[type="date"]');
            const data_aplicacao = dataInput ? dataInput.value : '';
            // Checagem dinâmica dos checkboxes
            const simInput = linha.querySelector('input[name$="[sim_inicial]"]');
            const sim_inicial = simInput ? (simInput.checked ? 1 : 0) : 0;
            const naoInput = linha.querySelector('input[name$="[nao_inicial]"]');
            const nao_inicial = naoInput ? (naoInput.checked ? 1 : 0) : 0;
            const obsInput = linha.querySelector('textarea[name$="[observacoes]"]');
            const observacoes = obsInput ? obsInput.value : '';
            const flagInput = linha.querySelector('input[name$="[flag]"]');
            let flag = flagInput ? flagInput.value : '';
            flag = flag ? parseInt(flag, 10) : 1;
            const registro_timestamp = Date.now();

            // Validação: exige data_aplicacao e apenas um checkbox marcado (sim OU não)
            if (!data_aplicacao) {
                alert('Por favor, preencha a data de aplicação.');
                return;
            }
            if ((sim_inicial === 1 && nao_inicial === 1) || (sim_inicial === 0 && nao_inicial === 0)) {
                alert('Por favor, marque apenas uma opção: "Sim" OU "Não" para realização da atividade.');
                return;
            }
            
            console.log(`[scripts_monitoramento] Flag encontrado: ${flag}`);
            
            // Monta os dados para envio
            // Payload exatamente como o backend espera
            const payload = {
                aluno_id: aluno_id ? parseInt(aluno_id, 10) : null,
                cod_atividade: cod_atividade || '',
                data_inicial: data_aplicacao || '', // O backend converte para data_aplicacao
                sim_inicial: sim_inicial,
                nao_inicial: nao_inicial,
                observacoes: observacoes || '',
                flag: flag, // Usa o valor exato do flag da linha
                registro_timestamp: registro_timestamp,
                fase_cadastro: "In" // Valor fixo para padronização
            };
            console.log(`[scripts_monitoramento] Payload FINAL para eixo ${eixo}:`, payload);

            // Confirmação antes de salvar
            if (!confirm('Confirma o salvamento desta atividade? Após salvar, não será possível editar.')) {
                console.log('[scripts_monitoramento] Salvamento cancelado pelo usuário');
                return;
            }

            // Monta o objeto para o backend: { eixo: [payload], aluno_id: xxx }
            const dataToSend = {
                aluno_id: aluno_id // Garante que aluno_id está no nível principal
            };
            
            // Verificação adicional para garantir que aluno_id não seja nulo ou vazio
            if (!aluno_id) {
                console.error('[scripts_monitoramento] ERRO: aluno_id está vazio ou nulo!');
                alert('Erro: ID do aluno não encontrado. Não é possível salvar.');
                return;
            } else {
                console.log('[scripts_monitoramento] aluno_id válido:', aluno_id);
            }
            
            // Mapeia o nome do eixo para o formato esperado pelo backend
            let eixoBackend = eixo;
            if (eixo === 'comunicacao') {
                eixoBackend = 'com_lin'; // O backend espera 'com_lin' em vez de 'comunicacao'
            } else if (eixo === 'socioemocional') {
                eixoBackend = 'int_socio'; // O backend espera 'int_socio' em vez de 'socioemocional'
            }
            // O eixo 'comportamento' já tem o mesmo nome no frontend e backend
            
            dataToSend[eixoBackend] = JSON.stringify([payload]); // Converte array para string JSON como o backend espera
            
            console.log('[scripts_monitoramento] Dados para enviar:', dataToSend);
            
            // Vamos criar um FormData diretamente com os dados corretos
            const formData = new FormData();
            
            // Adiciona todos os campos do dataToSend ao FormData
            for (const key in dataToSend) {
                if (Object.prototype.hasOwnProperty.call(dataToSend, key)) {
                    formData.append(key, dataToSend[key]);
                }
            }
            
            // Adiciona o token CSRF - busca em várias fontes possíveis
            const csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') 
                            || document.querySelector('input[name="_token"]')?.value 
                            || '';
            formData.append('_token', csrfToken);
            
            console.log('[scripts_monitoramento] Enviando para backend (FormData):', formData);
            
            fetch('{{ route('monitoramento.salvar') }}', {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    return response.json().then(err => { throw err; });
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Tenta usar o Bootstrap Modal com tratamento de erro
                    try {
                        const sucessoModalElement = document.getElementById('sucessoModal');
                        if (sucessoModalElement) {
                            const sucessoModal = new bootstrap.Modal(sucessoModalElement);
                            sucessoModal.show();
                        } else {
                            alert('Operação realizada com sucesso!');
                        }
                    } catch (error) {
                        console.warn('Erro ao mostrar modal de sucesso:', error);
                        alert('Operação realizada com sucesso!');
                    }

                    // Desabilitar a linha após o sucesso
                    const botaoSalvar = linha.querySelector('.btn-salvar-linha');
                    if (botaoSalvar) {
                        botaoSalvar.disabled = true;
                        botaoSalvar.classList.remove('btn-success');
                        botaoSalvar.classList.add('btn-danger');
                        botaoSalvar.style.width = '100%'; // Aumentar largura para igualar ao botão verde
                        botaoSalvar.innerHTML = 'Cadastrada';
                    }
                    const inputs = linha.querySelectorAll('input, textarea');
                    inputs.forEach(input => { 
                        input.setAttribute('readonly', true);
                        if(input.type === 'checkbox') input.disabled = true;
                    });

                } else {
                    // Lidar com erro de backend (ex: validação falhou)
                    // Tenta usar o modal de erro com tratamento de erro
                    try {
                        const erroModalMsg = document.getElementById('modalDuplicidadeMsg');
                        if (erroModalMsg) {
                            erroModalMsg.textContent = data.message || 'Ocorreu um erro ao salvar.';
                        }
                        
                        const erroModalElement = document.getElementById('modalDuplicidadeMonitoramento');
                        if (erroModalElement) {
                            const erroModal = new bootstrap.Modal(erroModalElement);
                            erroModal.show();
                        } else {
                            alert(data.message || 'Ocorreu um erro ao salvar.');
                        }
                    } catch (error) {
                        console.warn('Erro ao mostrar modal de erro:', error);
                        alert(data.message || 'Ocorreu um erro ao salvar.');
                    }
                }
            })
            .catch(err => {
                console.error('Erro AJAX:', err);
                
                // Tratamento de erro com fallback para alert
                try {
                    const modalMsg = document.getElementById('modalDuplicidadeMsg');
                    if (modalMsg) {
                        modalMsg.textContent = 'Erro na requisição: ' + err.message;
                    }
                    
                    const modalElement = document.getElementById('modalDuplicidadeMonitoramento');
                    if (modalElement) {
                        const modal = new bootstrap.Modal(modalElement);
                        modal.show();
                    } else {
                        alert('Erro na requisição: ' + err.message);
                    }
                } catch (error) {
                    console.warn('Erro ao mostrar modal de erro:', error);
                    alert('Erro na requisição: ' + err.message);
                }
                return;
            });
        });
    });
}

// Chama imediatamente ao carregar o script
if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function() {
        adicionarListenersSalvarLinhaGenerico();
        carregarAtividadesCadastradas(); // Carregar atividades já cadastradas
    });
} else {
    adicionarListenersSalvarLinhaGenerico();
    carregarAtividadesCadastradas(); // Carregar atividades já cadastradas
}


</script>
