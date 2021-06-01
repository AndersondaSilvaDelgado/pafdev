<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../dbutil/OCIAPEX.class.php');
require_once('../model/dao/AjusteDataHoraDAO.class.php');
/**
 * Description of FotoDAO
 *
 * @author anderson
 */
class FotoDAO extends OCIAPEX {
    //put your code here
    
    public function verifFoto($idForm, $foto, $base) {

        $select = " SELECT "
                . " COUNT(*) AS QTDE "
                . " FROM "
                . " PAF_FOTO "
                . " WHERE "
                . " DESCR LIKE 'FOTO_" . $foto->idFoto. "'"
                . " AND "
                . " DTHR_CEL = TO_DATE('" . $foto->dthrFoto . "','DD/MM/YYYY HH24:MI') "
                . " AND "
                . " FORMULARIO_ID = " . $idForm;

        $this->Conn = parent::getConn($base);
        $stid = oci_parse($this->Conn, $select);
        oci_execute($stid);

        while ($row = oci_fetch_array($stid, OCI_ASSOC+OCI_RETURN_NULLS)) {
            foreach ($row as $item) {
                $v = $item[0];
            }
        }

        return $v;
    }

    public function insFoto($idForm, $foto, $base) {

        $ajusteDataHoraDAO = new AjusteDataHoraDAO();

        $sql = "INSERT INTO PAF_FOTO ("
                . " FORMULARIO_ID "
                . " , FOTO "
                . " , DESCR "
                . " , DTHR "
                . " , DTHR_CEL "
                . " , DTHR_TRANS "
                . " ) "
                . " VALUES ("
                . " " . $idForm
                . " , empty_blob() "
                . " , 'FOTO_" . $foto->idFoto . "'"
                . " , " . $ajusteDataHoraDAO->dataHoraGMT($foto->dthrFoto, $base)
                . " , TO_DATE('" . $foto->dthrFoto . "','DD/MM/YYYY HH24:MI') "
                . " , SYSDATE"
                . " ) RETURNING foto INTO :foto";

        $this->OCI = parent::getConn($base);
        
        $result = oci_parse($this->OCI, $sql);
        $blob = oci_new_descriptor($this->OCI, OCI_D_LOB);
        oci_bind_by_name($result, ":foto", $blob, -1, OCI_B_BLOB);
        oci_execute($result, OCI_DEFAULT) or die ("Unable to execute query");

        if(!$blob->save(base64_decode($foto->foto))) {
            oci_rollback($this->OCI);
        }
        else {
            oci_commit($this->OCI);
        }

        oci_free_statement($result);
        $blob->free();
        
    }
    
}
