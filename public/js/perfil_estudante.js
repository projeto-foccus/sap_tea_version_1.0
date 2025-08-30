/**
 * Gerenciamento do formulário de perfil do estudante
 * 
 * Este arquivo contém a lógica de navegação entre abas,
 * validação e envio do formulário.
 */

class PerfilEstudanteForm {
    constructor() {
        // Elementos do DOM
        this.form = document.getElementById('perfilForm');
        this.stepTabs = document.querySelectorAll('.step-tab');
        this.stepContents = document.querySelectorAll('.step-content');
        this.prevBtn = document.getElementById('prevBtn');
        this.nextBtn = document.getElementById('nextBtn');
        this.progressBar = document.getElementById('progressBar');
        
        // Estado
        this.currentStep = 1;
        this.totalSteps = this.stepContents.length;
        
        // Inicialização
        this.init();
        
        // Adiciona botão de navegação rápida para a última etapa
        this.addQuickNavigation();
    }

    init() {
        this.setupEventListeners();
        this.loadSweetAlert();
        this.showStep(1);
        
        // Impedir submissão do formulário em abas intermediárias
        this.form.addEventListener('submit', (e) => {
            if (this.currentStep !== this.totalSteps) {
                e.preventDefault();
                console.log('Tentativa de envio do formulário bloqueada - não está na última aba');
                return false;
            }
        });
    }

    setupEventListeners() {
        // Navegação com botões diretos
        document.getElementById('nextBtn').onclick = (e) => {
            e.preventDefault();
            const nextStep = parseInt(this.currentStep) + 1;
            console.log(`Botão Próximo: indo para etapa ${nextStep}`);
            this.showStep(nextStep);
        };
        
        document.getElementById('prevBtn').onclick = (e) => {
            e.preventDefault();
            const prevStep = parseInt(this.currentStep) - 1;
            console.log(`Botão Anterior: indo para etapa ${prevStep}`);
            this.showStep(prevStep);
        };
        
        // Navegação por clique nas abas - permite clicar em qualquer aba
        this.stepTabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                e.preventDefault();
                const step = parseInt(e.currentTarget.getAttribute('data-step'));
                console.log(`Clique na aba ${step}`);
                this.showStep(step);
            });
        });
    }

    async showStep(step) {
        // Garante que step seja um número inteiro válido
        step = parseInt(step);
        
        // Valida se o step está dentro dos limites
        if (isNaN(step) || step < 1 || step > this.totalSteps) {
            console.error(`Etapa inválida: ${step}`);
            return;
        }
        
        console.log(`Mostrando etapa ${step}`);
        
        // Esconde todas as etapas
        this.stepContents.forEach(content => content.classList.remove('active'));
        
        // Mostra a etapa atual
        const targetContent = document.querySelector(`.step-content[data-step="${step}"]`);
        if (targetContent) {
            targetContent.classList.add('active');
        } else {
            console.error(`Conteúdo da etapa ${step} não encontrado`);
            return;
        }

        // Atualiza a aba ativa
        this.stepTabs.forEach(tab => tab.classList.remove('active'));
        const targetTab = document.querySelector(`.step-tab[data-step="${step}"]`);
        if (targetTab) {
            targetTab.classList.add('active');
        } else {
            console.error(`Aba da etapa ${step} não encontrada`);
        }

        // Atualiza visibilidade dos botões
        this.prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        
        // Configura o botão de próximo/salvar
        if (step === this.totalSteps) {
            this.nextBtn.textContent = 'Salvar';
            this.nextBtn.className = 'btn btn-success';
            this.nextBtn.onclick = (e) => {
                e.preventDefault();
                this.salvarFormularioCompleto(e);
            };
        } else {
            this.nextBtn.textContent = 'Próximo';
            this.nextBtn.className = 'btn btn-primary';
            this.nextBtn.onclick = (e) => {
                e.preventDefault();
                const nextStep = parseInt(this.currentStep) + 1;
                console.log(`Botão Próximo: indo para etapa ${nextStep}`);
                this.showStep(nextStep);
            };
        }

        // Atualiza o estado atual
        this.currentStep = step;
        this.updateProgressBar();
        
        console.log(`Etapa atual definida como: ${this.currentStep}`);
    }

    // Método auxiliar para avançar para a próxima etapa
    nextStep() {
        if (this.currentStep < this.totalSteps) {
            const nextStep = parseInt(this.currentStep) + 1;
            console.log(`Avançando para etapa ${nextStep}`);
            this.showStep(nextStep);
            return true;
        }
        return false;
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.showStep(this.currentStep - 1);
        }
    }

    async validarEtapaAtual() {
        // Validação básica - pode ser expandida conforme necessário
        const currentContent = document.querySelector('.step-content.active');
        const requiredFields = currentContent.querySelectorAll('[required]');
        let isValid = true;

        for (const field of requiredFields) {
            if (!field.value.trim()) {
                isValid = false;
                field.classList.add('is-invalid');
            } else {
                field.classList.remove('is-invalid');
            }
        }

        if (!isValid) {
            await Swal.fire({
                icon: 'warning',
                title: 'Campos obrigatórios',
                text: 'Por favor, preencha todos os campos obrigatórios antes de continuar.',
                confirmButtonColor: '#3085d6',
            });
        }

        return isValid;
    }

    async salvarFormularioCompleto(e) {
        e.preventDefault();
        
        const confirmado = await Swal.fire({
            title: 'Salvar alterações?',
            text: 'Deseja salvar todas as alterações feitas no perfil do estudante?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Sim, salvar',
            cancelButtonText: 'Cancelar',
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#6c757d',
        });

        if (!confirmado.isConfirmed) return false;

        const btn = e.target;
        const btnText = btn.innerHTML;
        
        try {
            // Mostra indicador de carregamento
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Salvando...';
            
            // Coleta os dados do formulário
            const formData = new FormData(this.form);
            formData.append('etapa', 'final');
            
            // Garante que o método PUT seja enviado corretamente
            formData.set('_method', 'PUT');
            
            // Garante que o ID do aluno esteja presente
            const alunoId = document.getElementById('aluno_id_hidden').value;
            if (alunoId) {
                formData.append('fk_id_aluno', alunoId);
                formData.append('aluno_id', alunoId); // Adiciona campo alternativo para garantir
            }
            
            // Garante que todos os campos estejam presentes
            this.garantirCamposFormulario(formData);
            
            // Log dos dados sendo enviados para depuração
            console.log('Enviando dados do formulário:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Verificação rápida para campos obrigatórios
            ['loc_01', 'hig_02', 'ali_03', 'com_04', 'out_05'].forEach(campo => {
                const valor = formData.get(campo);
                if (valor === null || valor === 'null' || valor === undefined) {
                    formData.set(campo, '0');
                }
            });
            
            // Envia a requisição com método PUT
            const response = await fetch(this.form.action, {
                method: 'POST', // Mantemos POST aqui porque o FormData será enviado com _method=PUT
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });
            
            console.log('URL da requisição:', this.form.action);
            
            // Log da resposta para depuração
            console.log('Status da resposta:', response.status);
            console.log('Headers da resposta:', response.headers);
            
            // Tenta obter o JSON da resposta - apenas uma vez
            let resultado;
            let textoResposta;
            
            try {
                // Clona a resposta antes de ler o corpo para evitar erro de stream já lido
                const responseClone = response.clone();
                
                try {
                    resultado = await response.json();
                    console.log('Resposta JSON:', resultado);
                } catch (jsonError) {
                    console.error('Erro ao processar JSON da resposta:', jsonError);
                    textoResposta = await responseClone.text();
                    console.log('Resposta como texto:', textoResposta);
                    
                    // Verifica se a resposta contém indicação de sucesso mesmo sendo HTML
                    if (response.ok && (textoResposta.includes('sucesso') || textoResposta.includes('success'))) {
                        resultado = { success: true };
                    } else {
                        throw new Error('Erro ao processar resposta do servidor');
                    }
                }
            } catch (error) {
                console.error('Erro ao processar resposta:', error);
                this.showConfirmation('Erro ao processar resposta do servidor. Verifique o console para mais detalhes.', true);
                throw error;
            }
            
            if (resultado && resultado.success) {
                this.showConfirmation('Dados salvos com sucesso!');
                
                // Marca todas as abas como concluídas
                this.stepTabs.forEach(tab => tab.classList.add('completed'));
                
                // Redireciona após 2 segundos se houver URL de redirecionamento
                if (resultado.redirect) {
                    setTimeout(() => {
                        window.location.href = resultado.redirect;
                    }, 2000);
                }
            } else {
                throw new Error((resultado && resultado.message) || 'Erro ao salvar os dados');
            }
        } catch (error) {
            console.error('Erro ao salvar:', error);
            this.showConfirmation(error.message || 'Erro ao salvar. Por favor, tente novamente.', true);
        } finally {
            // Restaura o botão
            btn.disabled = false;
            btn.innerHTML = btnText;
        }
    }

    showConfirmation(message, isError = false) {
        // Remove mensagens antigas
        const oldAlert = document.querySelector('.alert-message');
        if (oldAlert) oldAlert.remove();

        // Cria a mensagem
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${isError ? 'danger' : 'success'} alert-message`;
        alertDiv.style.position = 'fixed';
        alertDiv.style.top = '20px';
        alertDiv.style.right = '20px';
        alertDiv.style.zIndex = '9999';
        alertDiv.style.minWidth = '300px';
        alertDiv.innerHTML = `
            <div class="d-flex justify-content-between align-items-center">
                <div>${message}</div>
                <button type="button" class="btn-close" onclick="this.parentElement.parentElement.remove()"></button>
            </div>`;
        
        document.body.appendChild(alertDiv);
        
        // Remove a mensagem após 5 segundos
        setTimeout(() => {
            if (alertDiv.parentNode) {
                alertDiv.remove();
            }
        }, 5000);
    }

    updateProgressBar() {
        const progress = ((this.currentStep - 1) / (this.totalSteps - 1)) * 100;
        this.progressBar.style.width = `${progress}%`;
    }
    
    addQuickNavigation() {
        // Cria o botão de navegação rápida
        const quickNavContainer = document.createElement('div');
        quickNavContainer.className = 'quick-nav-container';
        quickNavContainer.style.cssText = 'position: fixed; bottom: 20px; right: 20px; z-index: 1000;';
        
        const quickNavButton = document.createElement('button');
        quickNavButton.type = 'button';
        quickNavButton.className = 'btn btn-info';
        quickNavButton.innerHTML = '<i class="fas fa-save"></i> Ir para Salvar';
        quickNavButton.style.cssText = 'box-shadow: 0 2px 5px rgba(0,0,0,0.2); padding: 8px 15px; border-radius: 4px;';
        
        // Adiciona evento de clique
        quickNavButton.addEventListener('click', () => {
            // Vai diretamente para a última etapa sem confirmação
            this.showStep(this.totalSteps);
        });
        
        quickNavContainer.appendChild(quickNavButton);
        document.body.appendChild(quickNavContainer);
    }

    loadSweetAlert() {
        if (typeof Swal === 'undefined') {
            const sweetScript = document.createElement('script');
            sweetScript.src = 'https://cdn.jsdelivr.net/npm/sweetalert2@11';
            document.head.appendChild(sweetScript);
        }
    }
    
    // Garante que todos os campos importantes estejam presentes no formulário
    garantirCamposFormulario(formData) {
        // Mapeia os checkboxes de momentos_apoio[] para os campos específicos do backend
        this.mapearMomentosApoio(formData);
        
        // Mapeia os campos da aba Personalidade
        this.mapearCamposPersonalidade(formData);
        
        // Mapeia os campos da aba Comunicação
        this.mapearCamposComunicacao(formData);
        
        // Mapeia os campos da aba Preferências
        this.mapearCamposPreferencias(formData);
        
        // Mapeia os campos da aba Informações da Família
        this.mapearCamposFamilia(formData);
        
        // Mapeia os campos da aba Cadastro de Profissionais
        this.mapearCamposProfissionais(formData);
        
        // Campos obrigatórios que devem ter valor zero se não preenchidos
        const camposZero = ['loc_01', 'hig_02', 'ali_03', 'com_04', 'out_05'];
        camposZero.forEach(campo => {
            const valorAtual = formData.get(campo);
            if (!formData.has(campo) || valorAtual === null || valorAtual === 'null' || valorAtual === undefined) {
                formData.set(campo, '0');
            }
        });
        
        // Campos especiais que devem ser string vazia se nulos
        const camposEspeciais = ['out_momentos', 'out_moments'];
        camposEspeciais.forEach(campo => {
            const valor = formData.get(campo);
            if (valor === null || valor === 'null' || valor === undefined) {
                formData.set(campo, '');
            }
        });
        
        // Garante que campos de checkbox estejam presentes mesmo que não selecionados
        ['momentos_apoio', 'sensibilidade', 'como_aprende_melhor'].forEach(field => {
            if (!formData.has(field)) {
                formData.append(field, '');
            }
        });
        
        // Garante que o ID do aluno esteja presente
        if (!formData.has('fk_id_aluno') || !formData.has('aluno_id')) {
            const alunoId = document.getElementById('aluno_id_hidden')?.value;
            
            if (alunoId) {
                formData.set('fk_id_aluno', alunoId);
                formData.set('aluno_id', alunoId);
                console.log('ID do aluno adicionado:', alunoId);
            } else {
                console.error('ID do aluno não encontrado!');
            }
        }
        
        // Adiciona campos de data que podem estar faltando
        if (!formData.has('updated_at')) {
            const dataAtual = new Date().toISOString().slice(0, 19).replace('T', ' ');
            formData.set('updated_at', dataAtual);
        }
    }
    
    // Mapeia os checkboxes de momentos_apoio[] para os campos específicos do backend
    mapearMomentosApoio(formData) {
        // Obtém os valores selecionados dos checkboxes
        const momentosApoio = formData.getAll('momentos_apoio[]') || [];
        
        // Define os valores padrão como '0' (não selecionado)
        formData.set('loc_01', '0');
        formData.set('hig_02', '0');
        formData.set('ali_03', '0');
        formData.set('com_04', '0');
        formData.set('out_05', '0');
        
        // Sempre inicializa out_momentos como string vazia para evitar null
        formData.set('out_momentos', '');
        
        // Mapeia os valores selecionados para os campos específicos
        momentosApoio.forEach(momento => {
            switch(momento) {
                case 'locomocao':
                    formData.set('loc_01', '1');
                    break;
                case 'higiene':
                    formData.set('hig_02', '1');
                    break;
                case 'alimentacao':
                    formData.set('ali_03', '1');
                    break;
                case 'comunicacao':
                    formData.set('com_04', '1');
                    break;
                case 'outros':
                    formData.set('out_05', '1');
                    // Se 'outros' está selecionado, também mapeia o campo de texto
                    const outrosMomentos = formData.get('outros_momentos_apoio');
                    if (outrosMomentos !== null && outrosMomentos !== undefined) {
                        formData.set('out_momentos', outrosMomentos);
                    }
                    break;
            }
        });
        
        // Verifica novamente se out_momentos está definido e não é null
        if (formData.get('out_momentos') === null || formData.get('out_momentos') === undefined) {
            formData.set('out_momentos', '');
        }
        
        // Log para debug
        console.log('Mapeamento de momentos de apoio:', {
            loc_01: formData.get('loc_01'),
            hig_02: formData.get('hig_02'),
            ali_03: formData.get('ali_03'),
            com_04: formData.get('com_04'),
            out_05: formData.get('out_05'),
            out_momentos: formData.get('out_momentos')
        });
    }
    
    // Mapeia os campos da aba Personalidade para os campos esperados pelo backend
    mapearCamposPersonalidade(formData) {
        // Mapeamento entre os campos do formulário e os campos do modelo
        const mapeamento = {
            'principais_caracteristicas': 'carac_principal',
            'areas_interesse': 'inter_princ_carac',
            'atividades_livre': 'livre_gosta_fazer',
            'feliz': 'feliz_est',
            'triste': 'trist_est',
            'objeto_apego': 'obj_apego'
        };
        
        // Realiza o mapeamento dos campos
        Object.entries(mapeamento).forEach(([campoFormulario, campoModelo]) => {
            const valor = formData.get(campoFormulario);
            
            // Se o campo existe no formulário, mapeia para o campo do modelo
            if (valor !== null && valor !== undefined) {
                formData.set(campoModelo, valor);
            } else {
                // Se não existe, inicializa com string vazia para evitar null
                formData.set(campoModelo, '');
            }
            
            // Log para debug
            console.log(`Mapeamento de ${campoFormulario} para ${campoModelo}:`, formData.get(campoModelo));
        });
    }
    
    // Mapeia os campos da aba Comunicação para os campos esperados pelo backend
    mapearCamposComunicacao(formData) {
        // Neste caso, os nomes dos campos já correspondem aos nomes esperados pelo modelo
        // Mas precisamos garantir que os valores sejam tratados corretamente
        
        // Campos booleanos que devem ser '0' ou '1'
        const camposBooleanos = ['precisa_comunicacao', 'entende_instrucao'];
        camposBooleanos.forEach(campo => {
            const valor = formData.get(campo);
            
            // Se o campo não existe ou é nulo, define como '0'
            if (!formData.has(campo) || valor === null || valor === undefined || valor === '') {
                formData.set(campo, '0');
            }
        });
        
        // Campo de texto que deve ser string vazia se nulo
        const valorRecomenda = formData.get('recomenda_instrucao');
        if (valorRecomenda === null || valorRecomenda === undefined) {
            formData.set('recomenda_instrucao', '');
        }
        
        // Log para debug
        console.log('Mapeamento de campos de Comunicação:', {
            precisa_comunicacao: formData.get('precisa_comunicacao'),
            entende_instrucao: formData.get('entende_instrucao'),
            recomenda_instrucao: formData.get('recomenda_instrucao')
        });
    }
    
    // Mapeia os campos da aba Preferências para os campos esperados pelo backend
    mapearCamposPreferencias(formData) {
        // Mapeamento dos campos de texto
        const mapeamentoTexto = {
            'manejo_sensibilidade': 'maneja_04',
            'alimentos_preferidos': 'alimentos_pref_04',
            'alimentos_evita': 'alimento_evita_04',
            'afinidade_escola': 'contato_pc_04',
            'reage_contato': 'reage_contato',
            'ajuda_dificulta_interacao': 'interacao_escola_04',
            'interesses_especificos': 'interesse_atividade_04',
            'gosta_grupo_sozinho': 'prefere_ts_04',
            'estrategias_eficazes': 'mostram_eficazes_04',
            'interesse_tarefa': 'realiza_tarefa_04'
        };
        
        // Mapeia os campos de texto
        Object.entries(mapeamentoTexto).forEach(([campoFormulario, campoModelo]) => {
            const valor = formData.get(campoFormulario);
            
            // Se o campo existe no formulário, mapeia para o campo do modelo
            if (valor !== null && valor !== undefined) {
                formData.set(campoModelo, valor);
            } else {
                // Se não existe, inicializa com string vazia para evitar null
                formData.set(campoModelo, '');
            }
            
            // Log para debug
            console.log(`Mapeamento de ${campoFormulario} para ${campoModelo}:`, formData.get(campoModelo));
        });
        
        // Mapeia o campo seletividade_alimentar para asa_04
        const valorSeletividade = formData.get('seletividade_alimentar');
        if (valorSeletividade !== null && valorSeletividade !== undefined && valorSeletividade !== '') {
            formData.set('asa_04', valorSeletividade);
        } else {
            formData.set('asa_04', '0');
        }
        
        // Mapeia os checkboxes de sensibilidade[] para os campos específicos
        this.mapearSensibilidade(formData);
        
        // Mapeia os checkboxes de como_aprende_melhor[] para os campos específicos
        this.mapearComoAprendeMelhor(formData);
        
        // Log para debug
        console.log('Mapeamento de campos de Preferências concluído');
    }
    
    // Mapeia os checkboxes de sensibilidade[] para os campos específicos
    mapearSensibilidade(formData) {
        // Inicializa os campos com '0'
        formData.set('auditivo_04', '0');
        formData.set('visual_04', '0');
        formData.set('tatil_04', '0');
        formData.set('outros_04', '0');
        
        // Obtém os valores marcados
        const sensibilidades = formData.getAll('sensibilidade[]');
        
        // Mapeia cada valor para o campo correspondente
        if (sensibilidades.includes('auditiva')) {
            formData.set('auditivo_04', '1');
        }
        
        if (sensibilidades.includes('visual')) {
            formData.set('visual_04', '1');
        }
        
        if (sensibilidades.includes('tatil')) {
            formData.set('tatil_04', '1');
        }
        
        if (sensibilidades.includes('outros')) {
            formData.set('outros_04', '1');
        }
        
        // Log para debug
        console.log('Mapeamento de sensibilidade[]:', {
            auditivo_04: formData.get('auditivo_04'),
            visual_04: formData.get('visual_04'),
            tatil_04: formData.get('tatil_04'),
            outros_04: formData.get('outros_04')
        });
    }
    
    // Mapeia os checkboxes de como_aprende_melhor[] para os campos específicos
    mapearComoAprendeMelhor(formData) {
        // Inicializa os campos com '0'
        formData.set('aprende_visual_04', '0');
        formData.set('recurso_auditivo_04', '0');
        formData.set('material_concreto_04', '0');
        formData.set('outro_identificar_04', '0');
        
        // Obtém os valores marcados
        const comoAprende = formData.getAll('como_aprende_melhor[]');
        
        // Mapeia cada valor para o campo correspondente
        if (comoAprende.includes('visual')) {
            formData.set('aprende_visual_04', '1');
        }
        
        if (comoAprende.includes('auditivo')) {
            formData.set('recurso_auditivo_04', '1');
        }
        
        if (comoAprende.includes('concreto')) {
            formData.set('material_concreto_04', '1');
        }
        
        if (comoAprende.includes('outro')) {
            formData.set('outro_identificar_04', '1');
        }
        
        // Campo de texto associado ao checkbox 'outro'
        // Se houver um campo para descrever o 'outro', mapear aqui
        const descricaoOutro = formData.get('descricao_outro_aprendizado');
        if (descricaoOutro !== null && descricaoOutro !== undefined) {
            formData.set('descricao_outro_identificar_04', descricaoOutro);
        } else {
            formData.set('descricao_outro_identificar_04', '');
        }
        
        // Log para debug
        console.log('Mapeamento de como_aprende_melhor[]:', {
            aprende_visual_04: formData.get('aprende_visual_04'),
            recurso_auditivo_04: formData.get('recurso_auditivo_04'),
            material_concreto_04: formData.get('material_concreto_04'),
            outro_identificar_04: formData.get('outro_identificar_04'),
            descricao_outro_identificar_04: formData.get('descricao_outro_identificar_04')
        });
    }
    
    // Mapeia os campos da aba Informações da Família para os campos esperados pelo backend
    mapearCamposFamilia(formData) {
        // Mapeamento dos campos de texto
        const mapeamentoTexto = {
            'expectativas_familia': 'expectativa_05',
            'estrategia_familiar': 'estrategia_05',
            'familia_crise_estresse': 'crise_esta_05'
        };
        
        // Mapeia os campos de texto
        Object.entries(mapeamentoTexto).forEach(([campoFormulario, campoModelo]) => {
            const valor = formData.get(campoFormulario);
            
            // Se o campo existe no formulário, mapeia para o campo do modelo
            if (valor !== null && valor !== undefined) {
                formData.set(campoModelo, valor);
            } else {
                // Se não existe, inicializa com string vazia para evitar null
                formData.set(campoModelo, '');
            }
            
            // Log para debug
            console.log(`Mapeamento de ${campoFormulario} para ${campoModelo}:`, formData.get(campoModelo));
        });
        
        // Log para debug
        console.log('Mapeamento de campos da Família concluído');
    }
    
    // Mapeia os campos da aba Cadastro de Profissionais para os campos esperados pelo backend
    mapearCamposProfissionais(formData) {
        // Array para armazenar os dados dos profissionais
        const profissionais = [];
        
        // Obtém o ID do aluno para associar aos profissionais
        const alunoId = document.getElementById('aluno_id_hidden')?.value || formData.get('aluno_id') || formData.get('fk_id_aluno');
        
        // Processa cada linha de profissional (até 3 linhas no formulário)
        for (let i = 1; i <= 3; i++) {
            const numProfissional = i.toString().padStart(2, '0'); // 01, 02, 03
            
            // Obtém os valores dos campos
            const nome = formData.get(`nome_profissional_${numProfissional}`);
            const especialidade = formData.get(`especialidade_profissional_${numProfissional}`);
            const observacoes = formData.get(`observacoes_profissional_${numProfissional}`);
            
            // Só adiciona ao array se pelo menos o nome do profissional estiver preenchido
            if (nome && nome.trim() !== '') {
                profissionais.push({
                    nome_profissional: nome,
                    especialidade_profissional: especialidade || '',
                    observacoes_profissional: observacoes || '',
                    fk_id_aluno: alunoId // Adiciona o ID do aluno para cada profissional
                });
            }
        }
        
        // Adiciona o array de profissionais ao formData como JSON string
        formData.set('profissionais', JSON.stringify(profissionais));
        
        // Log para debug
        console.log('Profissionais mapeados:', profissionais);
        console.log('Mapeamento de campos de Profissionais concluído');
    }
}

// Inicializa o formulário quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new PerfilEstudanteForm();
});
