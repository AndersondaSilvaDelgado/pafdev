<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of OCI
 *
 * @author anderson
 */
class OCIAPEX {
    
    /** @var PDO */
    private static $Connect = null;

    /**
     * Conecta com o banco de dados com o pattern singleton.
     * Retorna um objeto PDO!
     */
    private static function Conectar($base) {
        try {

            if (self::$Connect == null) {
                if ($base == 1) {
                    $tns = "  (DESCRIPTION =
                                (ADDRESS_LIST =
                                  (ADDRESS = (PROTOCOL = TCP)(HOST = stafe-scan)(PORT = 1521))
                                )
                                (CONNECT_DATA =
                                  (SERVER = DEDICATED)
                                  (SERVICE_NAME = apex)
                                )
                              )";
                } elseif ($base == 2) {
                    $tns = " (DESCRIPTION =
                                  (ADDRESS_LIST =
                                    (ADDRESS = (PROTOCOL = TCP)(HOST = 192.168.2.15)(PORT = 1521))
                                    (LOAD_BALANCE = yes)
                                  )
                                  (CONNECT_DATA =
                                    (SERVER = DEDICATED)
                                    (SERVICE_NAME = APEXQA)
                                    (FAILOVER_MODE =
                                      (TYPE = SELECT)
                                      (METHOD = BASIC)
                                      (RETRIES = 180)
                                      (DELAY = 5)
                                    )
                                  )
                                )";
                } elseif ($base == 3) {
                    $tns = "  (DESCRIPTION =
                                    (ADDRESS_LIST =
                                      (ADDRESS = (PROTOCOL = TCP)(HOST = stafe-scan)(PORT = 1521))
                                    )
                                    (CONNECT_DATA =
                                      (SERVER = DEDICATED)
                                      (SERVICE_NAME = apexdev)
                                    )
                                  )";
                }

                self::$Connect = oci_connect('STAFE', 'STA1553', $tns);
                
            }
        } catch (PDOException $e) {
            PHPErro($e->getCode(), $e->getMessage(), $e->getFile(), $e->getLine());
            die;
        }

//        self::$Connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return self::$Connect;
    }

    protected static function getConn($base) {
        return self::Conectar($base);
    }
    
}
