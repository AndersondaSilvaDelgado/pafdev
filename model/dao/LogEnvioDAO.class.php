<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of LogEnvioDAO
 *
 * @author anderson
 */
class LogEnvioDAO extends Conn {
    //put your code here
    
    public function salvarDados($formulario, $dados, $pagina, $versao, $base) {

        $this->Conn = parent::getConn($base);
        
        $versao = str_replace("_", ".", $versao);
        $pagina = $pagina . '-' . $versao;

        if ($versao >= 1.00) {
        
            $formularioJsonObj = json_decode($formulario);
            $formularioDados = $formularioJsonObj->formulario;

            $nroAparelho = 0;
            $flag = 0;

            foreach ($formularioDados as $form) {

                $nroAparelho = $form->nroAparelhoForm;
                $result = $this->dadoAtual($nroAparelho, $base);
                foreach ($result as $item) {
                    $flag = $item['FLAG_LOG_ENVIO'];
                }

            }

            if($flag == 1){

                $sql = "INSERT INTO PAF_LOG_ENVIO ("
                        . " NRO_APARELHO "
                        . " , DTHR "
                        . " , PAGINA "
                        . " , DADOS "
                        . " ) "
                        . " VALUES ("
                        . " " . $nroAparelho
                        . " , SYSDATE "
                        . " , ? "
                        . " , ? "
                        . " )";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->bindParam(1, $pagina, PDO::PARAM_STR, 30);
                $this->Create->bindParam(2, $dados, PDO::PARAM_STR, 32000);
                $this->Create->execute();

                $sql = " DELETE "
                        . " FROM "
                            . " PAF_LOG_ENVIO "
                        . " WHERE "
                            . " NRO_APARELHO = " . $nroAparelho
                            . " AND "
                            . " DTHR < ADD_MONTHS(SYSDATE, -2)";

                $this->Create = $this->Conn->prepare($sql);
                $this->Create->execute();

            } 
        
        }
        
    }
    
    public function dadoAtual($nroAparelho, $base) {

        $select = " SELECT "
                . " A.VERSAO_NOVA "
                . " , A.VERSAO_ATUAL "
                . " , A.FLAG_LOG_ENVIO "
                . " , A.FLAG_LOG_ERRO "
                . " FROM "
                . " PAF_ATUALIZACAO A "
                . " WHERE "
                . " A.NRO_APARELHO = " . $nroAparelho;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }
    
}
