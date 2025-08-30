<?php
// Script para corrigir os campos de texto no arquivo de atualização do perfil
$file = 'c:/Users/Marcos/Documents/sap-tea_laravel/resources/views/alunos/atualiza_perfil_estudante.blade.php';
$content = file_get_contents($file);

// Lista de campos de texto para corrigir
$textFields = [
    // Dados básicos
    'cid', 'nome_medico', 'data_laudo', 'quais_medicamento', 'out_momentos',
    'nome_prof_AEE', 'carac_principal', 'inter_princ_carac', 'livre_gosta_fazer',
    'feliz_est', 'trist_est', 'obj_apego', 'recomenda_instrucao', 'maneja_04',
    'alimentos_pref_04', 'alimento_evita_04', 'contato_pc_04', 'reage_contato',
    'interacao_escola_04', 'interesse_atividade_04', 'descricao_outro_identificar_04',
    'realiza_tarefa_04', 'mostram_eficazes_04', 'prefere_ts_04', 'expectativa_05',
    'estrategia_05', 'crise_esta_05'
];

// Aplicar correções para cada campo
foreach ($textFields as $field) {
    // Corrigir inputs de texto
    $content = str_replace(
        'name="' . $field . '" value="{{$perfil->' . $field . ' }}"',
        'name="' . $field . '" value="{{ isset($perfil->' . $field . ') ? $perfil->' . $field . ' : '' }}"',
        $content
    );
    
    // Corrigir textareas
    $content = str_replace(
        'name="' . $field . '">{{$perfil->' . $field . ' }}',
        'name="' . $field . '">{{ isset($perfil->' . $field . ') ? $perfil->' . $field . ' : '' }}',
        $content
    );
}

// Salvar o arquivo
file_put_contents($file, $content);
echo "Campos de texto atualizados com sucesso!\n";
?>
