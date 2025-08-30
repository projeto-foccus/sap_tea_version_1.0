<?php

namespace App\Enums;

class FaseCadastro
{
    const INICIAL = 'IN';
    const CONTINUADA1 = 'C1';
    const CONTINUADA2 = 'C2';
    const FINAL = 'FI';
    
    /**
     * Retorna a descrição da fase
     * 
     * @param string $fase
     * @return string
     */
    public static function getDescricao($fase)
    {
        $descricoes = [
            self::INICIAL => 'Inicial',
            self::CONTINUADA1 => 'Continuada 1',
            self::CONTINUADA2 => 'Continuada 2',
            self::FINAL => 'Final'
        ];
        
        return $descricoes[$fase] ?? 'Desconhecida';
    }
    
    /**
     * Retorna todas as fases disponíveis
     * 
     * @return array
     */
    public static function todas()
    {
        return [
            self::INICIAL => self::getDescricao(self::INICIAL),
            self::CONTINUADA1 => self::getDescricao(self::CONTINUADA1),
            self::CONTINUADA2 => self::getDescricao(self::CONTINUADA2),
            self::FINAL => self::getDescricao(self::FINAL)
        ];
    }
}
