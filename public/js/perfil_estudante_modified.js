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
        // Navegação
        this.nextBtn.addEventListener('click', () => this.nextStep());
        this.prevBtn.addEventListener('click', () => this.prevStep());
        
        // Navegação por clique nas abas
        this.stepTabs.forEach(tab => {
            tab.addEventListener('click', (e) => {
                const step = parseInt(e.currentTarget.getAttribute('data-step'));
                if (step < this.currentStep) { // Só permite voltar
                    this.showStep(step);
                }
            });
        });
    }

    async showStep(step) {
        // Esconde todas as etapas
        this.stepContents.forEach(content => content.classList.remove('active'));
        
        // Mostra a etapa atual
        document.querySelector(`.step-content[data-step="${step}"]`).classList.add('active');

        // Atualiza a aba ativa
        this.stepTabs.forEach(tab => tab.classList.remove('active'));
        document.querySelector(`.step-tab[data-step="${step}"]`).classList.add('active');

        // Atualiza visibilidade dos botões
        this.prevBtn.style.display = step === 1 ? 'none' : 'inline-block';
        
        // Configura o botão de próximo/salvar
        if (step === this.totalSteps) {
            this.nextBtn.textContent = 'Salvar';
            this.nextBtn.className = 'btn btn-success';
            this.nextBtn.onclick = (e) => this.salvarFormularioCompleto(e);
        } else {
            this.nextBtn.textContent = 'Próximo';
            this.nextBtn.className = 'btn btn-primary';
            this.nextBtn.onclick = () => this.nextStep();
        }

        this.currentStep = step;
        this.updateProgressBar();
    }

    async nextStep() {
        if (this.currentStep < this.totalSteps) {
            // Simplesmente avança para a próxima etapa sem validação ou confirmação
            console.log(`Avançando da etapa ${this.currentStep} para ${this.currentStep + 1}`);
            this.showStep(this.currentStep + 1);
            return true;
        }
        return false;
    }

    prevStep() {
        if (this.currentStep > 1) {
            this.showStep(this.currentStep - 1);
        }
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
            
            // Log dos dados sendo enviados para depuração
            console.log('Enviando dados do formulário:');
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
            }
            
            // Envia a requisição
            const response = await fetch(this.form.action, {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: formData
            });
            
            // Log da resposta para depuração
            console.log('Status da resposta:', response.status);
            console.log('Headers da resposta:', response.headers);
            
            // Tenta obter o JSON da resposta
            let resultado;
            try {
                resultado = await response.json();
                console.log('Resposta JSON:', resultado);
            } catch (jsonError) {
                console.error('Erro ao processar JSON da resposta:', jsonError);
                const textoResposta = await response.text();
                console.log('Resposta como texto:', textoResposta);
                throw new Error('Erro ao processar resposta do servidor');
            }
            
            if (resultado.success) {
                this.showConfirmation('Dados salvos com sucesso!');
                
                // Marca todas as abas como concluídas
                this.stepTabs.forEach(tab => tab.classList.add('completed'));
            } else {
                throw new Error(resultado.message || 'Erro ao salvar os dados');
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
        quickNavButton.addEventListener('click', async () => {
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
}

// Inicializa o formulário quando o DOM estiver pronto
document.addEventListener('DOMContentLoaded', () => {
    new PerfilEstudanteForm();
});
