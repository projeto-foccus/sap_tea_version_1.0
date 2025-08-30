// Script para salvar linha individual do eixo comunicacao/linguagem via AJAX
// Inclua no blade após a tabela de comunicacao

document.addEventListener('DOMContentLoaded', function() {
    function getCsrfToken() {
        let token = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
        if (!token) {
            token = document.querySelector('input[name="_token"]')?.value;
        }
        return token;
    }

    document.querySelectorAll('.btn-salvar-linha').forEach(btn => {
        btn.addEventListener('click', async function() {
            const tr = this.closest('tr');
            const idx = this.dataset.idx;
            const eixo = this.dataset.eixo;
            tr.querySelectorAll('.msg-erro-linha').forEach(e => e.remove());
            this.disabled = true;
            this.textContent = 'Salvando...';

            // Coleta dados da linha
            const cod_atividade = tr.querySelector('input[name$="[cod_atividade]"]')?.value;
            const data_inicial = tr.querySelector('input[type="date"]')?.value;
            const sim_inicial = tr.querySelector('input.sim-checkbox')?.checked;
            const nao_inicial = tr.querySelector('input.nao-checkbox')?.checked;
            const observacoes = tr.querySelector('textarea')?.value || '';

            // Validação
            if (!data_inicial || (!sim_inicial && !nao_inicial) || (sim_inicial && nao_inicial)) {
                const erro = document.createElement('div');
                erro.className = 'msg-erro-linha';
                erro.style.color = 'red';
                erro.textContent = 'Preencha a data e marque apenas SIM ou NÃO.';
                tr.querySelector('td:last-child').appendChild(erro);
                this.disabled = false;
                this.textContent = 'Salvar linha';
                return;
            }

            // Monta payload igual ao esperado pelo backend (array do eixo)
            const payload = {
                aluno_id: document.querySelector('input[name="aluno_id"]')?.value,
                comunicacao: [
                    {
                        cod_atividade,
                        data_inicial,
                        sim_inicial: sim_inicial ? 1 : 0,
                        nao_inicial: nao_inicial ? 1 : 0,
                        observacoes: observacoes || ''
                    }
                ]
            };

            try {
                const resp = await fetch('MONITORAMENTO_SALVAR_ROUTE', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    body: JSON.stringify(payload)
                });
                if (!resp.ok) throw new Error('Erro ao salvar no servidor');
                // Remove a linha da tabela
                tr.remove();
            } catch (e) {
                const erro = document.createElement('div');
                erro.className = 'msg-erro-linha';
                erro.style.color = 'red';
                erro.textContent = 'Erro ao salvar: ' + (e.message || 'Tente novamente.');
                tr.querySelector('td:last-child').appendChild(erro);
                this.disabled = false;
                this.textContent = 'Salvar linha';
            }
        });
    });
});
