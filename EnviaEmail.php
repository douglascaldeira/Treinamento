<?php

class Local_Email_EnviaEmail
{

    //configuração 
    private $smtpServidor = 'smtp.ufv.br'; //configuração de SMTP do servidor
    private $contaServidor = ''; //usuário
    private $senhaServidor = ''; //senha
    private $de = ''; //de quem foi enviado
    private $para = ''; //para quem deve ir
    private $assunto = ''; //assunto da menssagem
    private $mensagem = ''; // corpo da menssagem pode ser em texto puro para a função emailTextoPuro() ou HTML para função emailTextoHtml()
    private $arquivo; //arquivo anexo
    private $autenticacaoSsl = false; //true se o email for enviado com ssl
    private $copias;
    private $copiasOcultas;

    //cria Mail_Transport e checa se � necess�rio autentica��o SSL
    public function criaMailTranport()
    {
        if ($this->autenticacaoSsl) {
            $config = array(
                'auth' => 'login',
                'username' => $this->contaServidor,
                'password' => $this->senhaServidor,
                'ssl' => 'ssl',
                'port' => '465'
            );
            $mailTransport = new Zend_Mail_Transport_Smtp($this->smtpServidor, $config);
        } else {            
            $mailTransport = new Zend_Mail_Transport_Sendmail("-f" . $this->de);
        }
        return $mailTransport;
    }

    /**
     * função de enviar texto puro por email
     * @return string se for enviado com sucesso retorna true caso não de certo o envio retorna um erro
     */
    public function emailTextoPuro()
    {
        try {

            $mailTransport = $this->criaMailTranport();
            $mail = new Zend_Mail('UTF-8');
            $mail->setFrom($this->de);
            $mail->addTo($this->para);
            $mail->addCc($this->copias);
            $mail->addBcc($this->copiasOcultas);
            $mail->setBodyText($this->mensagem);
            $mail->setSubject($this->assunto);
            $mail->send($mailTransport);
            $retorno = TRUE;
            echo $mail->getReturnPath();
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $retorno;        
    }

    public function emailTextoHtml()
    {
        try {

            $mailTransport = $this->criaMailTranport();

            $mail = new Zend_Mail('UTF-8');
            $mail->setFrom($this->de);
            $mail->addCc($this->copias);
            $mail->addBcc($this->copiasOcultas);
            $mail->addTo($this->para);
            $mail->setBodyHtml($this->mensagem);
            $mail->setSubject($this->assunto);
            $mail->send($mailTransport);
            $retorno = TRUE;
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
        return $retorno;        
    }

    public function emailAnexo()
    {
        /* recebe os campos do formulário */
        $arqTmp = $this->arquivo["tmp_name"];
        $arqNome = $this->arquivo["name"];
        $arqType = $this->arquivo["type"];
        try {
            $mailTransport = $this->criaMailTranport();
            $mail = new Zend_Mail('UTF-8');
            $mail->setFrom($this->de);
            $mail->addCc($this->copias);
            $mail->addBcc($this->copiasOcultas);
            $mail->addTo($this->para);
            $mail->setSubject($this->assunto);
            $mail->createAttachment(
                    file_get_contents($arqTmp),
                    $arqType, Zend_Mime::DISPOSITION_INLINE,
                    Zend_Mime::ENCODING_BASE64, $arqNome
                    );
            $mail->setBodyHtml($this->mensagem);
            $mail->send($mailTransport);
        } catch (Exception $e) {
            echo $e->getMessage();
            die;
        }
    }

    /**
     * Envia emails em texto puro para varios endereços
     * @param array $emails Grupo de emsils que serão enviados
     */
    public function emailsTextoPuro(array $emails)
    {
        foreach ($emails as $email) {
            $this->setPara($email);
            $this->emailTextoPuro();
        }
    }

    /**
     * Envia emails em texto HTML para varios endereços
     * @param array $emails Grupo de emsils que serão enviados
     */
    public function emailsTextoHtml(array $emails)
    {
        foreach ($emails as $email) {
            $this->setPara($email);
            $this->emailTextoHtml();
        }
    }

    /**
     * Envia emails em texto HTML para varios endereços com anexo
     * @param array $emails Grupo de emsils que serão enviados
     */
    public function emailsAnexo(array $emails)
    {
        foreach ($emails as $email) {
            $this->setPara($email);
            $this->emailAnexo();
        }
    }

    /**
     * Metodos set
     * @param type $smtpServidor
     */
    public function setSmtpServidor($smtpServidor)
    {
        $this->smtpServidor = $smtpServidor;
    }

    public function setContaServidor($contaServidor)
    {
        $this->contaServidor = $contaServidor;
    }

    public function setSenhaServidor($senhaServidor)
    {
        $this->senhaServidor = $senhaServidor;
    }

    public function setDe($de)
    {
        $this->de = $de;
    }

    public function setPara($para)
    {
        $this->para = $para;
    }

    public function setAssunto($assunto)
    {
        $this->assunto = $assunto;
    }

    public function setMensagem($mensagem)
    {
        $this->mensagem = $mensagem;
    }

    public function setArquivo($arquivo)
    {
        $this->arquivo = $arquivo;
    }

    public function setAutenticacaoSsl($autenticacaoSsl)
    {
        $this->autenticacaoSsl = $autenticacaoSsl;
    }

    public function setCopias($emailCopias)
    {
        $this->copias = $emailCopias;
    }
    
    public function setCopiasOcultas($emailCopiasOcultas)
    {
        $this->copiasOcultas = $emailCopiasOcultas;
    }

    /** metodos get
     *
     */
    public function getSmtpServidor()
    {
        return $this->smtpServidor;
    }

    public function getContaServidor()
    {
        return $this->contaServidor;
    }

    public function getSenhaServidor()
    {
        return $this->senhaServidor;
    }

    public function getDe()
    {
        return $this->de;
    }

    public function getPara()
    {
        return $this->para;
    }

    public function getAssunto()
    {
        return $this->assunto;
    }

    public function getMensagem()
    {
        return $this->mensagem;
    }

    public function getArquivo()
    {
        return $this->arquivo;
    }

}
