<?php
/**
 * Responsável por formatar nomes com letras maiúsculas no início e com minúsculas nas preposições
 *
 * @author douglas
 */
class Local_Zend_Controller_Action_Helper_FormataNomes {

    //put your code here
    public function formartarNome($nome) {
        try {
            //retira espaços em braco e caracter em HTML
            $nome = strip_tags(trim($nome));

            //divide o nome em vários pedaços
            $arrayNome = explode(' ', $nome);

            //parametros que devem ser iguinorados
            $naoFormatar = array('de', 'do', 'da', 'e', 'a', 'dos', 'das', 'o');

            //inicia a variavel
            $nomeFormatado = '';
           
            //percorre as palavras passadas
            foreach ($arrayNome as $parteDoNome) {
                //toma se como nenhum valor deve ser iguinorado
                $minusculo = FALSE;

                //caminha no array do nome
                foreach ($naoFormatar as $tudoMinusculo) {
                    //verifica se alguma das partes é alguma que deve ser colocada em minúsculo
                    if (strcasecmp($tudoMinusculo, $parteDoNome) == 0) {
                        $minusculo = TRUE;
                        break;
                    }
                }
                
                //se for minusculo  
                if ($minusculo) {
                    //põe em caixa baixa
                    $nomeFormatado .= strtolower($parteDoNome) . ' ';
                } else {
                    //se não põe a primeira letra da palavra em maiuscula e o resto minuscula
                    $nomeFormatado .= ucfirst($parteDoNome) . ' ';
                }
            }
            //caso de um erro na hora da converção
        } catch (Exception $e) {
            die('Não foi possível formatar o nome. Erro: ' . $e->getMessage());
        }

        return trim($nomeFormatado);
    }

}
