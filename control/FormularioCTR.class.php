<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
require_once('../model/dao/FormularioDAO.class.php');
require_once('../model/dao/FotoDAO.class.php');
require_once('../model/dao/LogEnvioDAO.class.php');
/**
 * Description of PassageiroCTR
 *
 * @author anderson
 */
class FormularioCTR {
    
    private $base = 2;
    
    public function salvarDados($versao, $info, $pagina) {

        $formulario = $info['formulario'];
        $foto = $info['foto'];
        
        $dados = $formulario;
        
        $this->salvarLog($formulario, $dados, $pagina, $versao);

        $pagina = $pagina . '-' . $versao;
        $versao = str_replace("_", ".", $versao);

        if ($versao >= 1.00) {

            $cabecJsonObj = json_decode($formulario);
            $fotoJsonObj = json_decode($foto);

            $formularioDados = $cabecJsonObj->formulario;
            $fotoDados = $fotoJsonObj->foto;

            $this->salvarFormulario($formularioDados, $fotoDados);
        }
    }
    
    private function salvarLog($cabec, $dados, $pagina, $versao) {
        $logEnvioDAO = new LogEnvioDAO();
        $logEnvioDAO->salvarDados($cabec, $dados, $pagina, $versao, $this->base);
    }
    
    private function salvarFormulario($formularioDados, $fotoDados) {
        
        $formularioDAO = new FormularioDAO();
        
        foreach ($formularioDados as $formulario) {
            
            $v = $formularioDAO->verifFormulario($formulario, $this->base);
            if ($v == 0) {
                $formularioDAO->insFormulario($formulario, $this->base);
            }
            $idFormBD = $formularioDAO->idFormulario($formulario, $this->base);
            
            $this->salvarFoto($idFormBD, $fotoDados);
            
        }

    }

    private function salvarFoto($idFormBD, $fotoDados) {
        $fotoDAO = new FotoDAO();
        foreach ($fotoDados as $foto) {
            $v = $fotoDAO->verifFoto($idFormBD, $foto, $this->base);
            if ($v == 0) {
                $fotoDAO->insFoto($idFormBD, $foto, $this->base);
            }
        }
    }
    
}
