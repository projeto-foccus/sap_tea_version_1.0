<?php
// Script simples para corrigir os selects no arquivo de atualização do perfil
$file = 'c:/Users/Marcos/Documents/sap-tea_laravel/resources/views/alunos/atualiza_perfil_estudante.blade.php';
$content = file_get_contents($file);

// 1. Corrigir uso_medicamento
$content = str_replace(
    '<option value="1" @if($perfil->uso_medicamento == 1) selected @endif>Sim</option>',
    '<option value="1" @if(isset($perfil->uso_medicamento) && $perfil->uso_medicamento == 1) selected @endif>Sim</option>',
    $content
);
$content = str_replace(
    '<option value="0" @if($perfil->uso_medicamento == 0) selected @endif>Não</option>',
    '<option value="0" @if(isset($perfil->uso_medicamento) && $perfil->uso_medicamento == 0) selected @endif>Não</option>',
    $content
);

// 2. Corrigir nec_pro_apoio
$content = str_replace(
    '<option value="1" @if($perfil->nec_pro_apoio == 1) selected @endif>Sim</option>',
    '<option value="1" @if(isset($perfil->nec_pro_apoio) && $perfil->nec_pro_apoio == 1) selected @endif>Sim</option>',
    $content
);
$content = str_replace(
    '<option value="0" @if($perfil->nec_pro_apoio == 0) selected @endif>Não</option>',
    '<option value="0" @if(isset($perfil->nec_pro_apoio) && $perfil->nec_pro_apoio == 0) selected @endif>Não</option>',
    $content
);

// 3. Corrigir prof_apoio
$content = str_replace(
    '<option value="1" @if($perfil->prof_apoio == 1) selected @endif>Sim</option>',
    '<option value="1" @if(isset($perfil->prof_apoio) && $perfil->prof_apoio == 1) selected @endif>Sim</option>',
    $content
);
$content = str_replace(
    '<option value="0" @if($perfil->prof_apoio == 0) selected @endif>Não</option>',
    '<option value="0" @if(isset($perfil->prof_apoio) && $perfil->prof_apoio == 0) selected @endif>Não</option>',
    $content
);

// 4. Corrigir at_especializado
$content = str_replace(
    '<option value="1" @if($perfil->at_especializado == 1) selected @endif>Sim</option>',
    '<option value="1" @if(isset($perfil->at_especializado) && $perfil->at_especializado == 1) selected @endif>Sim</option>',
    $content
);
$content = str_replace(
    '<option value="0" @if($perfil->at_especializado == 0) selected @endif>Não</option>',
    '<option value="0" @if(isset($perfil->at_especializado) && $perfil->at_especializado == 0) selected @endif>Não</option>',
    $content
);

// 5. Corrigir precisa_comunicacao
$content = str_replace(
    '<option value="1" @if($perfil->precisa_comunicacao == 1) selected @endif>Sim</option>',
    '<option value="1" @if(isset($perfil->precisa_comunicacao) && $perfil->precisa_comunicacao == 1) selected @endif>Sim</option>',
    $content
);
$content = str_replace(
    '<option value="0" @if($perfil->precisa_comunicacao == 0) selected @endif>Não</option>',
    '<option value="0" @if(isset($perfil->precisa_comunicacao) && $perfil->precisa_comunicacao == 0) selected @endif>Não</option>',
    $content
);

// 6. Corrigir entende_instrucao
$content = str_replace(
    '<option value="1" @if($perfil->entende_instrucao == 1) selected @endif>Sim</option>',
    '<option value="1" @if(isset($perfil->entende_instrucao) && $perfil->entende_instrucao == 1) selected @endif>Sim</option>',
    $content
);
$content = str_replace(
    '<option value="0" @if($perfil->entende_instrucao == 0) selected @endif>Não</option>',
    '<option value="0" @if(isset($perfil->entende_instrucao) && $perfil->entende_instrucao == 0) selected @endif>Não</option>',
    $content
);

// Salvar o arquivo
file_put_contents($file, $content);
echo "Arquivo atualizado com sucesso!\n";
?>
