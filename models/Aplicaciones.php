<?php

namespace Model;

use Model\ActiveRecord;

class Aplicaciones extends ActiveRecord {
    
    public static $tabla = 'jemg_aplicaciones';
    public static $idTabla = 'app_id';
    public static $columnasDB = 
    [
        'app_nombre_largo',
        'app_nombre_medium',
        'app_nombre_corto',
        'app_fecha_creacion',
        'app_situacion'
    ];
    
    public $app_id;
    public $app_nombre_largo;
    public $app_nombre_medium;
    public $app_nombre_corto;
    public $app_fecha_creacion;
    public $app_situacion;
    
    public function __construct($usuario = [])
    {
        $this->app_id = $usuario['app_id'] ?? null;
        $this->app_nombre_largo = $usuario['app_nombre_largo'] ?? '';
        $this->app_nombre_medium = $usuario['app_nombre_medium'] ?? '';
        $this->app_nombre_corto = $usuario['app_nombre_corto'] ?? 0;
        $this->app_fecha_creacion = $usuario['app_fecha_creacion'] ?? '';
        $this->app_situacion = $usuario['app_situacion'] ?? 1;

    }

}