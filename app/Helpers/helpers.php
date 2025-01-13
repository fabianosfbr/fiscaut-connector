<?php

if (!function_exists("removeCaracteresEspeciais")) {
    function removeCaracteresEspeciais(string $frase): string {
        // Garante que a string esteja codificada em UTF-8
        $frase = mb_convert_encoding($frase, 'UTF-8', 'UTF-8');

        // Remove acentos e normaliza a string
        $fraseSemAcentos = @iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $frase);

        // Remove tudo que não seja letra, número, espaço ou ponto
        return preg_replace('/[^a-zA-Z0-9\s.]/', '', $fraseSemAcentos);
    }
}
