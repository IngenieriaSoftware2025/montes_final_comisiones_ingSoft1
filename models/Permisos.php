<?php

namespace Model;

use Model\ActiveRecord;

class Permisos extends ActiveRecord {
    
    public static $tabla = 'jemg_permisos';
    public static $idTabla = 'permiso_id';
    public static $columnasDB = 
    [
        'app_id',
        'permiso_nombre',
        'permiso_clave',
        'permiso_desc',
        'permiso_fecha',
        'permiso_situacion'
    ];
    
    public $permiso_id;
    public $app_id;
    public $permiso_nombre;
    public $permiso_clave;
    public $permiso_desc;
    public $permiso_fecha;
    public $permiso_situacion;
    
    public function __construct($usuario = [])
    {
        $this->permiso_id = $usuario['permiso_id'] ?? null;
        $this->app_id = $usuario['app_id'] ?? '';
        $this->permiso_nombre = $usuario['permiso_nombre'] ?? '';
        $this->permiso_clave = $usuario['permiso_clave'] ?? 0;
        $this->permiso_desc = $usuario['permiso_desc'] ?? '';
        $this->permiso_fecha = $usuario['permiso_fecha'] ?? '';
        $this->permiso_situacion = $usuario['permiso_situacion'] ?? 1;

    }

}