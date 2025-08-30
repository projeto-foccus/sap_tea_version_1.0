<?php
// Script para corrigir a ação do formulário no arquivo de atualização do perfil
$file = 'c:/Users/Marcos/Documents/sap-tea_laravel/resources/views/alunos/atualiza_perfil_estudante.blade.php';
$content = file_get_contents($file);

// Substituir a ação do formulário para usar a URL direta
$newContent = preg_replace(
    '/<form method="POST" action=\"\{\{ route\(\'atualiza\\.perfil\\.estudante\'\, \[\'id\' => \$aluno->alu_id\]\) \}\}\">/',
    '<form method="POST" action="{{ url(\'/atualizaperfil/\' . $aluno->alu_id) }}">',
    $content
);

// Salvar o arquivo apenas se houver alterações
if ($newContent !== $content) {
    file_put_contents($file, $newContent);
    echo "Ação do formulário atualizada com sucesso!\n";
} else {
    echo "Nenhuma alteração necessária.\n";
}
?>
