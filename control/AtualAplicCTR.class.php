<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/AtualAplicDAO.class.php');
/**
 * Description of AtualAplicativoCTR
 *
 * @author anderson
 */
class AtualAplicCTR {
    //put your code here
    
    private $base = 2;
    
    public function atualAplic($versao, $info) {

        $versao = str_replace("_", ".", $versao);
        
        $retorno = '';
        
        if($versao >= 1.00){
        
            $atualAplicDAO = new AtualAplicDAO();

            $jsonObj = json_decode($info['dado']);
            $dados = $jsonObj->dados;

            foreach ($dados as $d) {
                $aparelho = $d->nroAparelhoAtual;
                $versaoAtual = $d->versaoAtual;
            }
            
            $retAtualApp = 0;
            $retFlagLogEnvio = 0;
            $retFlagLogErro = 0;
            
            $verif = $atualAplicDAO->verAtual($aparelho, $this->base);
            if ($verif == 0) {
                $atualAplicDAO->insAtual($aparelho, $versaoAtual, $this->base);
            } else {
                $result = $atualAplicDAO->retAtual($aparelho, $this->base);
                foreach ($result as $item) {
                    $versaoNovo = $item['VERSAO_NOVA'];
                    $versaoAtualBD = $item['VERSAO_ATUAL'];
                    $retFlagLogEnvio = $item['FLAG_LOG_ENVIO'];
                    $retFlagLogErro = $item['FLAG_LOG_ERRO'];
                }
                if ($versaoAtual != $versaoAtualBD) {
                    $atualAplicDAO->updAtualNova($aparelho, $versaoAtual, $this->base);
                } else {
                    if ($versaoAtual != $versaoNovo) {
                        $retAtualApp = 1;
                    } else {
                        if (strcmp($versaoAtual, $versaoAtualBD) <> 0) {
                            $atualAplicDAO->updAtual($aparelho, $versaoAtual, $this->base);
                        }
                    }
                }
            }
            $dthr = $atualAplicDAO->dataHora($this->base);
            
            $dado = array("flagAtualApp" => $retAtualApp
                , "flagLogEnvio" => $retFlagLogEnvio, "flagLogErro" => $retFlagLogErro
                , "dthr" => $dthr);

            $retorno = json_encode(array("dados" => array($dado)));
        
        }
        
        return $retorno; 
        
    }
    
}
