<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/ConnAPEX.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');
/**
 * Description of PassageiroDAO
 *
 * @author anderson
 */
class FormularioDAO extends ConnAPEX {
    
    public function verifFormulario($formulario, $base) {

        if ($formulario->matricVistoriador == 0) {
            $formulario->matricVistoriador = "MATRIC_VISTORIADOR IS NULL";
        }
        else{
            $formulario->matricVistoriador = "MATRIC_VISTORIADOR = " . $formulario->matricVistoriador;
        }
        
        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PAF_FORMULARIO "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $formulario->dthrForm . "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . $formulario->matricVistoriador;

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

    public function idFormulario($formulario, $base) {

        if ($formulario->matricVistoriador == 0) {
            $formulario->matricVistoriador = "MATRIC_VISTORIADOR IS NULL";
        }
        else{
            $formulario->matricVistoriador = "MATRIC_VISTORIADOR = " . $formulario->matricVistoriador;
        }
        
        $select = " SELECT "
                . " ID AS ID "
                . " FROM "
                . " PAF_FORMULARIO "
                . " WHERE "
                . " DTHR_CEL = TO_DATE('" . $formulario->dthrForm. "','DD/MM/YYYY HH24:MI')"
                . " AND "
                . $formulario->matricVistoriador;

        $this->Conn = parent::getConn($base);
        $this->Read = $this->Conn->prepare($select);
        $this->Read->setFetchMode(PDO::FETCH_ASSOC);
        $this->Read->execute();
        $result = $this->Read->fetchAll();

        foreach ($result as $item) {
            $id = $item['ID'];
        }

        return $id;
    }
    
    public function insFormulario($formulario, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        if ($formulario->latitudeForm == 0.0) {
            $formulario->latitudeForm = 'null';
        }
        
        if ($formulario->longitudeForm == 0.0) {
            $formulario->longitudeForm = 'null';
        }
        
        if ($formulario->descrLocalForm != 'null') {
            $formulario->descrLocalForm = "'" . $formulario->descrLocalForm . "'";
        }
        
        if ($formulario->idAnimal == 0) {
            $formulario->idAnimal = 'null';
        }
        
        if ($formulario->nomeAnimal != 'null') {
            $formulario->nomeAnimal = "'" . $formulario->nomeAnimal . "'";
        }
        
        if ($formulario->matricVistoriador == 0) {
            $formulario->matricVistoriador = 'null';
        }
        
        if ($formulario->observacao != 'null') {
            $formulario->observacao = "'" . $formulario->observacao . "'";
        }
        
        $sql = "INSERT INTO PAF_FORMULARIO ("
                . " DATA_INS "
                . " , NRO_APARELHO "
                . " , LATITUDE "
                . " , LONGITUDE "
                . " , DESCR_LOCALIZACAO "
                . " , ID_ANIMAL "
                . " , DESCR_NOME_ANIMAL "
                . " , MATRIC_VISTORIADOR "
                . " , OBSERVACAO "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " TO_DATE('" . $formulario->dataInsForm . "','DD/MM/YYYY')"
                . " , " . $formulario->nroAparelhoForm
                . " , " . $formulario->latitudeForm
                . " , " . $formulario->longitudeForm
                . " , " . $formulario->descrLocalForm
                . " , " . $formulario->idAnimal
                . " , " . $formulario->nomeAnimal
                . " , " . $formulario->matricVistoriador
                . " , " . $formulario->observacao
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($formulario->dthrForm, $base)
                . " , TO_DATE('" . $formulario->dthrForm . "','DD/MM/YYYY HH24:MI')"
                . " , SYSDATE "
                . " )";

        $this->Conn = parent::getConn($base);
        $this->Create = $this->Conn->prepare($sql);
        $this->Create->execute();

    }
    
}
