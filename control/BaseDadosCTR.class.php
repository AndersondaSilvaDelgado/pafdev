<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/AnimalDAO.class.php');
require_once('../model/dao/ColabDAO.class.php');
/**
 * Description of BaseDadosCTR
 *
 * @author anderson
 */
class BaseDadosCTR {
    
    private $base = 2;
    
    public function dadosAnimal($versao) {

        $versao = str_replace("_", ".", $versao);
        
        $animalDAO = new AnimalDAO();
        
        if($versao >= 1.00){
        
            $dados = array("dados"=>$animalDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
    public function dadosColab($versao) {
        
        $versao = str_replace("_", ".", $versao);
        
        $colabDAO = new ColabDAO();
        
        if($versao >= 1.00){
        
            $dados = array("dados"=>$colabDAO->dados($this->base));
            $json_str = json_encode($dados);

            return $json_str;
        
        }
        
    }
    
}
