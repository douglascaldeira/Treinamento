<?php

/**
 * 
 *@UC006
 * Aplicativo para lançamento de notas dos alunos do Coluni
 * 
 *
 * HistÃ³rico:
 *  2013-07-05, v1.0, Douglas Paiva Caldeira
 *   VersÃ£o inicial.
 *
 * @category  
 * @copyright Copyright (c) 2010-2013 
 * @license   ?
 * 
 */

/**
 * Classe de envio de emails 
 *
 * @category  lancanotascoluni
 * @copyright Copyright (c) 2010-2013 
 * @license   ?
 */
class EnviaEmail
{

    /**
     * Função de envio de emails em PHP
     * 
     * @param type $emailsDestinatario Destinatario E-mails
     * @param type $arquivo Caminho do arquivo no servidor
     * @param type $assunto Assunto do e-mail
     * @param type $textoEmail Corpo do texto pode ser em HTML
     * @param type $remetente Remetente
     */
    public function enviar($emailsDestinatario, $arquivo, $assunto, $textoEmail, $remetente)
    {
        $assunto = mb_convert_encoding($assunto, "iso-8859-1", mb_detect_encoding($assunto));
        //verifica qual sistema é utilizado no servidor
        if (PHP_OS == "Linux") {
            $quebra_linha = "\n"; //Se for Linux
        } elseif (PHP_OS == "WINNT") {
            $quebra_linha = "\r\n"; // Se for Windows
        } else {
            die("Este script não está preparado para funcionar com o sistema operacional do servidor");
        }

        $inicio_corpo_mensagem = "
		<html>
		<body>
                    <table width='800' border='0' cellpadding='0' cellspacing='0' align='center'>
                        <tr>
                            <td colspan='3'><img src='https://phpdes.dti.ufv.br/dtr_siscore/images/topo.jpg' width='100%' height='100'></td>
			</tr>
			<tr>
                            <td>";

        $final_corpo_mensagem = "</td>
			</tr>
                    </table>
		</body>
		</html>";
        $nome = explode('/', $arquivo);
        //checa se o arquivo existe
        if (file_exists($arquivo) and !empty($arquivo)) {
            //abre o arquivo  
            $fp = fopen($arquivo, "rb");
            //lê o arquivo e passa para a variavel anexo
            $anexo = fread($fp, filesize($arquivo));
            $anexo = base64_encode($anexo);
            //fexa o arquivo
            fclose($fp);

            $anexo = chunk_split($anexo);           

            //cabeçalho
            $headers = "MIME-Version: 1.0" . $quebra_linha;
            $headers .= "From:" . $remetente . $quebra_linha;
            $headers .="Reply-To:" . $remetente . $quebra_linha;
            $boundary = "XYZ-" . date("dmYis") . "-ZYX";
            $headers.= "Content-type: multipart/mixed; boundary=" . $boundary . $quebra_linha;

            $mensagem = "--" . $boundary . $quebra_linha;            
            $mensagem.= 'Content-Type: text/html; charset="iso-8859-1"' . $quebra_linha ;
            $mensagem.= $inicio_corpo_mensagem . $textoEmail . $final_corpo_mensagem . $quebra_linha;
            $mensagem.= "--$boundary" . $quebra_linha;
            $mensagem.= "Content-Type: " . $arquivo["type"] . $quebra_linha;
            $mensagem.= "Content-Disposition: attachment; filename=\"" . $nome[2]  .'"'. $quebra_linha;
            $mensagem.= "Content-Transfer-Encoding: base64" . $quebra_linha . $quebra_linha;
            $mensagem.= "$anexo" . $quebra_linha;
            $mensagem.= "--$boundary--" . $quebra_linha;
        
        //se nao tiver anexo
        }else {
            $headers = 'MIME-Version: 1.0' . $quebra_linha;
            $headers .= 'Content-type: text/html; charset=iso-8859-1' . $quebra_linha;
            $headers .= 'From: ' . $remetente . $quebra_linha;
            $mensagem = $inicio_corpo_mensagem . $textoEmail . $final_corpo_mensagem;
        }
 
        mail($emailsDestinatario, $assunto, $mensagem, $headers);
        
    }
    
    /**
     * Envia email para um array de emails
     * 
     * @param type $emailsDestinatarios array de emails
     * @param type $arquivo Caminho do arquivo
     * @param type $assunto Assunto do e-mail
     * @param type $textoEmail Corpo do textopode conter caracteres em HTML
     * @param type $remetente Remetente
     */
    public function enviarVariosDestinatarios($emailsDestinatarios, $arquivo, $assunto, $textoEmail, $remetente){
        foreach ($emailsDestinatarios as $emailDestinatario){
            $this->enviar($emailDestinatario, $arquivo, $assunto, $textoEmail, $remetente);
        }
    }

}