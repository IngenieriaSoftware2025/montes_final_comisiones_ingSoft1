<?php
// crea nombre de espacio Model
namespace Model;
// Importa la clase ActiveRecord del nombre de espacio Model
use Model\ActiveRecord;
// Crea la clase de instancia Usuario y hereda las funciones de ActiveRecord
class Usuarios extends ActiveRecord {

    public static $tabla = 'jemg_usuarios';
    public static $columnasDB = [
        'usuario_nom1',
        'usuario_ape1',
        'usuario_tel',
        'usuario_direc',
        'usuario_dpi',
        'usuario_token',
        'usuario_fecha_creacion',
        'usuario_fotografia',
        'usuario_situacion'
    ];

    public static $idTabla = 'usuario_id';
    public $usuario_id;
    public $usuario_nom1;
    public $usuario_ape1;
    public $usuario_tel;
    public $usuario_direc;
    public $usuario_dpi;
    public $usuario_token;
    public $usuario_fecha_creacion;
    public $usuario_fotografia;
    public $usuario_situacion;

    public function __construct($args = []){
        $this->usuario_id = $args['usuario_id'] ?? null;
        $this->usuario_nom1 = $args['usuario_nom1'] ?? '';
        $this->usuario_ape1 = $args['usuario_ape1'] ?? '';
        $this->usuario_tel = $args['usuario_tel'] ?? 0;
        $this->usuario_direc = $args['usuario_direc'] ?? '';
        $this->usuario_dpi = $args['usuario_dpi'] ?? '';
        $this->usuario_token = $args['usuario_token'] ?? '';
        $this->usuario_fecha_creacion = $args['usuario_fecha_creacion'] ?? date('Y-m-d');
        $this->usuario_fotografia = $args['usuario_fotografia'] ?? null;
        $this->usuario_situacion = $args['usuario_situacion'] ?? 1;
    }

}