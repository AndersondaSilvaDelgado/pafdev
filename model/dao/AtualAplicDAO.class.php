<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/Conn.class.php');
/**
 * Description of AtualizaAplicDAO
 *
 * @author anderson
 */
class AtualAplicDAO extends Conn {
    
    /** @var PDOStatement */
    private $Read;

    /** @var PDO */
    private $Conn;

    public function verAtual($aparelho, $base) {

        $select = "SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PAF_ATUALIZACAO "
                . " WHERE "
                . " NRO_APARELHO = " . $aparelho;

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

    public function insAtual($aparelho, $va, $base) {

        $sql = "INSERT INTO PAF_ATUALIZACAO ("
                . " NRO_APARELHO "
                . " , VERSAO_ATUAL "
                . " , VERSAO_NOVA "
                . " , DTHR_ULT_ATUAL "
                . " , FLAG_LOG_ENVIO "
                . " , FLAG_LOG_ERRO "
                . " ) "
                . " VALUES ("
                . " " . $aparelho
                . " , TRIM(TO_CHAR(" . $va . ", '99999999D99')) "
                . " , TRIM(TO_CHAR(" . $va . ", '99999999D99')) "
                . " , SYSDATE "
                . " , 1 "
                . " , 1 "
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function retAtual($aparelho, $base) {

        $select = " SELECT "
                . " VERSAO_NOVA "
                . " , VERSAO_ATUAL "
                . " , FLAG_LOG_ENVIO "
                . " , FLAG_LOG_ERRO "
                . " FROM "
                . " PAF_ATUALIZACAO "
                . " WHERE "
                . " NRO_APARELHO = " . $aparelho;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        return $result;
    }

    public function updAtualNova($aparelho, $va, $base) {

        $sql = "UPDATE PAF_ATUALIZACAO "
                . " SET "
                . " VERSAO_ATUAL = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , VERSAO_NOVA = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , DTHR_ULT_ATUAL = SYSDATE "
                . " , FLAG_LOG_ENVIO = 1 "
                . " , FLAG_LOG_ERRO = 1 "
                . " WHERE "
                . " NRO_APARELHO = " . $aparelho;

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function updAtual($aparelho, $va, $base) {

        $sql = "UPDATE PAF_ATUALIZACAO "
                . " SET "
                . " VERSAO_ATUAL = TRIM(TO_CHAR(" . $va . ", '99999999D99'))"
                . " , DTHR_ULT_ATUAL = SYSDATE "
                . " , FLAG_LOG_ENVIO = 1 "
                . " , FLAG_LOG_ERRO = 1 "
                . " WHERE "
                . " NRO_APARELHO = " . $aparelho;

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();
    }

    public function dataHora($base) {

        $select = " SELECT "
                . " TO_CHAR(SYSDATE, 'DD/MM/YYYY HH24:MI') AS DTHR "
                . " FROM "
                . " DUAL ";

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result1 = $this->Read->fetchAll();

        foreach ($result1 as $item) {
            $dthr = $item['DTHR'];
        }

        return $dthr;
    }
    
}
