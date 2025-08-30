<?php
// Este é um script para corrigir os selects no arquivo de atualização do perfil
$file = 'c:/Users/Marcos/Documents/sap-tea_laravel/resources/views/alunos/atualiza_perfil_estudante.blade.php';
$content = file_get_contents($file);

// Padrão para encontrar selects que precisam de verificação isset
$patterns = [
    // Padrão para selects com @if($perfil->campo == valor)
    '/<option value="(\d+)" @if\(\$perfil->(\w+) == (\d+)\) selected @endif>([^<]+)<\/option>/' => 
    function($matches) {
        $value = $matches[1];
        $field = $matches[2];
        $compare = $matches[3];
        $text = $matches[4];
        return "<option value=\"$value\" @if(isset(\$perfil->$field) && \$perfil->$field == $compare) selected @endif>$text</option>";
    },
    
    // Padrão para checkboxes com @if($perfil->campo)
    '/<input type="checkbox" name="(\w+)" value="(\d+)" @if\(\$perfil->(\w+)\) checked @endif>/' => 
    function($matches) {
        $name = $matches[1];
        $value = $matches[2];
        $field = $matches[3];
        return "<input type=\"checkbox\" name=\"$name\" value=\"$value\" @if(isset(\$perfil->$field) && \$perfil->$field) checked @endif>";
    },
    
    // Padrão para inputs de texto com {{$perfil->campo}}
    '/<input[^>]*name="(\w+)"[^>]*value=\"\{\{\$perfil->(\w+)\}\}\"[^>]*>/' => 
    function($matches) {
        $name = $matches[1];
        $field = $matches[2];
        return str_replace(
            'value="{{$perfil->' . $field . '}}"', 
            'value="{{ isset($perfil->' . $field . ') ? $perfil->' . $field . ' : '' }}"', 
            $matches[0]
        );
    },
    
    // Padrão para textareas com {{$perfil->campo}}
    '/<textarea[^>]*name="(\w+)"[^>]*>\{\{\$perfil->(\w+)\}\}<\/textarea>/' => 
    function($matches) {
        $name = $matches[1];
        $field = $matches[2];
        return "<textarea name=\"$name\">{{ isset(\$perfil->$field) ? \$perfil->$field : '' }}</textarea>";
    }
];

// Aplicar todas as substituições
$newContent = $content;
foreach ($patterns as $pattern => $replacement) {
    if (is_callable($replacement)) {
        $newContent = preg_replace_callback($pattern, $replacement, $newContent);
    } else {
        $newContent = preg_replace($pattern, $replacement, $newContent);
    }
}

// Salvar o arquivo apenas se houver alterações
if ($newContent !== $content) {
    file_put_contents($file, $newContent);
    echo "Arquivo atualizado com sucesso!\n";
} else {
    echo "Nenhuma alteração necessária.\n";
}
?>
