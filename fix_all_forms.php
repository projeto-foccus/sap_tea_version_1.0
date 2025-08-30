<?php
// Script para corrigir todas as ocorrências do formulário no arquivo
$file = 'c:/Users/Marcos/Documents/sap-tea_laravel/resources/views/alunos/atualiza_perfil_estudante.blade.php';
$content = file_get_contents($file);

// Padrão para encontrar o formulário
$pattern = '/<form method=\"POST\" action=\"\{\{ url\(\'\/atualizaperfil\/\' \. \$aluno->alu_id\) \}\}\">/';

// Substituir pelo formulário correto
$replacement = '<form method="POST" action="{{ route(\'atualiza.perfil.estudante\', [\'id\' => $aluno->alu_id]) }}">';

// Aplicar a substituição
$newContent = preg_replace($pattern, $replacement, $content);

// Salvar o arquivo apenas se houver alterações
if ($newContent !== $content) {
    file_put_contents($file, $newContent);
    echo "Formulários atualizados com sucesso!\n";
} else {
    echo "Nenhuma alteração necessária.\n";
}
?>
