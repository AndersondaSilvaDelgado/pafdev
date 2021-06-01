<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');
/**
 * Description of LogErroDAO
 *
 * @author anderson
 */
class LogErroDAO extends ConnAPEX {


    public function verifLogErro($erro, $base) {

        $select = " SELECT "
                    . " COUNT(*) AS QTDE "
                . " FROM "
                    . " PAF_LOG_ERRO "
                . " WHERE "
                    . " DTHR_CEL = TO_DATE('" . $erro->dthr . "','DD/MM/YYYY HH24:MI')"
                    . " AND "
                    . " NRO_APARELHO = " . $erro->nroAparelho
                    . " AND "
                    . " CEL_ID = " . $erro->idLog;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $v = $item['QTDE'];
        }

        return $v;
    }

    public function insLogErro($erro, $base) {
        
        $result = $this->dadoAtual($erro->nroAparelho, $base);
        foreach ($result as $item) {
            $flag = $item['FLAG_LOG_ERRO'];
        }

        if($flag == 1){
        
            $sql = "INSERT INTO PAF_LOG_ERRO ("
                    . " CEL_ID "
                    . " , NRO_APARELHO "
                    . " , DTHR_CEL "
                    . " , DTHR_TRANS "
                    . " , ERRO "
                    . " ) "
                    . " VALUES ("
                    . " " . $erro->idLog
                    . " , " . $erro->nroAparelho
                    . " , TO_DATE('" . $erro->dthr . "','DD/MM/YYYY HH24:MI')"
                    . " , SYSDATE "
                    . " , ? "
                    . " )";

            $this->Create = $this->Conn->prepare($sql);
            $this->Create->bindParam(1, $erro->exception, PDO::PARAM_STR, 32000);
            $this->Create->execute();

            $sql = " DELETE "
                    . " FROM "
                        . " PAF_LOG_ERRO "
                    . " WHERE "
                        . " NRO_APARELHO = " . $erro->nroAparelho
                        . " AND "
                        . " DTHR_TRANS < ADD_MONTHS(SYSDATE, -2)";

            $this->Create = $this->Conn->prepare($sql);
            $this->Create->execute();

        }
        
    }
    
    public function dadoAtual($nroAparelho, $base) {

        $select = " SELECT "
                    . " A.VERSAO_NOVA "
                    . " , A.VERSAO_ATUAL "
                    . " , A.FLAG_LOG_ENVIO "
                    . " , A.FLAG_LOG_ERRO "
                . " FROM "
                    . " PCQ_ATUALIZACAO A "
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
