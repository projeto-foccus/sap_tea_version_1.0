// Função para validar o formulário de monitoramento
function validarFormularioMonitoramento() {
    // Array para armazenar códigos de linhas incompletas
    let linhasIncompletas = [];
    
    // Verificar todas as linhas da tabela
    document.querySelectorAll('tr').forEach(row => {
        const dataInput = row.querySelector('input[type="date"]');
        const simCheckbox = row.querySelector('.sim-checkbox');
        const naoCheckbox = row.querySelector('.nao-checkbox');
        
        // Se a linha tem data e checkbox
        if (dataInput && (simCheckbox || naoCheckbox)) {
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
              '\n\nPor favor, complete os dados ou remova as marcações.');
        return false;
    }
    
    return true;
}

// Função para preparar os dados do formulário
function prepararDadosFormulario(form) {
    // Preparar dados de comunicação
    const comunicacaoData = {};
    let comunicacaoPreenchido = false;
    
    // Agrupar todos os inputs por índice
    const comunicacaoGroups = {};
    document.querySelectorAll('input[name^="comunicacao["]').forEach(input => {
        const match = input.name.match(/comunicacao\[(\\d+)\]\[([^\]]+)\]/);
        if (match) {
            const idx = match[1];
            const campo = match[2];
            if (!comunicacaoGroups[idx]) comunicacaoGroups[idx] = [];
            comunicacaoGroups[idx].push(input);
        }
    });
    
    // Verificar cada grupo se tem data e checkbox preenchidos
    Object.keys(comunicacaoGroups).forEach(idx => {
        const inputs = comunicacaoGroups[idx];
        let temData = false;
        let temCheckbox = false;
        
        inputs.forEach(input => {
            if (input.type === 'date' && input.value !== '') {
                temData = true;
            }
            if (input.type === 'checkbox' && input.checked) {
                temCheckbox = true;
            }
        });
        
        // Só considera válido se tiver data E checkbox
        if (temData && temCheckbox) {
            comunicacaoData[idx] = {};
            inputs.forEach(input => {
                const fieldMatch = input.name.match(/comunicacao\[(\\d+)\]\[([^\]]+)\]/);
                const campo = fieldMatch[2];
                
                if (input.type === 'checkbox') {
                    comunicacaoData[idx][campo] = input.checked ? '1' : '0';
                } else {
                    comunicacaoData[idx][campo] = input.value;
                }
            });
            comunicacaoPreenchido = true;
        }
    });
    
    // Preparar dados de comportamento
    const comportamentoData = {};
    let comportamentoPreenchido = false;
    
    // Agrupar todos os inputs por índice
    const comportamentoGroups = {};
    document.querySelectorAll('input[name^="comportamento["]').forEach(input => {
        const match = input.name.match(/comportamento\[(\\d+)\]\[([^\]]+)\]/);
        if (match) {
            const idx = match[1];
            const campo = match[2];
            if (!comportamentoGroups[idx]) comportamentoGroups[idx] = [];
            comportamentoGroups[idx].push(input);
        }
    });
    
    // Verificar cada grupo se tem data e checkbox preenchidos
    Object.keys(comportamentoGroups).forEach(idx => {
        const inputs = comportamentoGroups[idx];
        let temData = false;
        let temCheckbox = false;
        
        inputs.forEach(input => {
            if (input.type === 'date' && input.value !== '') {
                temData = true;
            }
            if (input.type === 'checkbox' && input.checked) {
                temCheckbox = true;
            }
        });
        
        // Só considera válido se tiver data E checkbox
        if (temData && temCheckbox) {
            comportamentoData[idx] = {};
            inputs.forEach(input => {
                const fieldMatch = input.name.match(/comportamento\[(\\d+)\]\[([^\]]+)\]/);
                const campo = fieldMatch[2];
                
                if (input.type === 'checkbox') {
                    comportamentoData[idx][campo] = input.checked ? '1' : '0';
                } else {
                    comportamentoData[idx][campo] = input.value;
                }
            });
            comportamentoPreenchido = true;
        }
    });
    
    // Preparar dados de socioemocional
    const socioData = {};
    let socioPreenchido = false;
    
    // Agrupar todos os inputs por índice
    const socioGroups = {};
    document.querySelectorAll('input[name^="socioemocional["]').forEach(input => {
        const match = input.name.match(/socioemocional\[(\\d+)\]\[([^\]]+)\]/);
        if (match) {
            const idx = match[1];
            const campo = match[2];
            if (!socioGroups[idx]) socioGroups[idx] = [];
            socioGroups[idx].push(input);
        }
    });
    
    // Verificar cada grupo se tem data e checkbox preenchidos
    Object.keys(socioGroups).forEach(idx => {
        const inputs = socioGroups[idx];
        let temData = false;
        let temCheckbox = false;
        
        inputs.forEach(input => {
            if (input.type === 'date' && input.value !== '') {
                temData = true;
            }
            if (input.type === 'checkbox' && input.checked) {
                temCheckbox = true;
            }
        });
        
        // Só considera válido se tiver data E checkbox
        if (temData && temCheckbox) {
            socioData[idx] = {};
            inputs.forEach(input => {
                const fieldMatch = input.name.match(/socioemocional\[(\\d+)\]\[([^\]]+)\]/);
                const campo = fieldMatch[2];
                
                if (input.type === 'checkbox') {
                    socioData[idx][campo] = input.checked ? '1' : '0';
                } else {
                    socioData[idx][campo] = input.value;
                }
            });
            socioPreenchido = true;
        }
    });
    
    // Criar campos hidden para enviar os dados JSON (apenas se houver dados)
    if (comunicacaoPreenchido) {
        const comunicacaoInput = document.createElement('input');
        comunicacaoInput.type = 'hidden';
        comunicacaoInput.name = 'comunicacao';
        comunicacaoInput.value = JSON.stringify(comunicacaoData);
        form.appendChild(comunicacaoInput);
    }
    
    if (comportamentoPreenchido) {
        const comportamentoInput = document.createElement('input');
        comportamentoInput.type = 'hidden';
        comportamentoInput.name = 'comportamento';
        comportamentoInput.value = JSON.stringify(comportamentoData);
        form.appendChild(comportamentoInput);
    }
    
    if (socioPreenchido) {
        const socioInput = document.createElement('input');
        socioInput.type = 'hidden';
        socioInput.name = 'socioemocional';
        socioInput.value = JSON.stringify(socioData);
        form.appendChild(socioInput);
    }
}
