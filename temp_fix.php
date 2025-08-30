<?php
// Este é um arquivo temporário para ajudar a corrigir o arquivo blade
$file = 'c:/Users/Marcos/Documents/sap-tea_laravel/resources/views/alunos/atualiza_perfil_estudante.blade.php';
$content = file_get_contents($file);

// Padrão para encontrar o select de diagnóstico/laudo
$pattern = '/<select name="diag_laudo">\s*<option value="1" @if\(\$perfil->diag_laudo == 1\) selected @endif>Sim<\/option>\s*<option value="0" @if\(\$perfil->diag_laudo == 0\) selected @endif>Não<\/option>\s*<\/select>/';

// Substituição com a verificação isset
$replacement = '<select name="diag_laudo">
                        <option value="1" @if(isset($perfil->diag_laudo) && $perfil->diag_laudo == 1) selected @endif>Sim</option>
                        <option value="0" @if(isset($perfil->diag_laudo) && $perfil->diag_laudo == 0) selected @endif>Não</option>
                    </select>';

$newContent = preg_replace($pattern, $replacement, $content);

if ($newContent !== $content) {
    file_put_contents($file, $newContent);
    echo "Arquivo atualizado com sucesso!\n";
} else {
    echo "Nenhuma alteração necessária ou padrão não encontrado.\n";
}
?>
