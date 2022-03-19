<?php namespace App\Models;

use CodeIgniter\Model;

class UsuarioModel extends Model
{
    protected $table      = 'usuario';
    protected $primaryKey = 'id';

    protected $returnType     = 'array';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['username', 'password', 'rol_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // protected $validationRules    = [
    //   'nombre' => 'required|alpha_space|min_length[3]|max_length[75]',
    //   'apellido' => 'required|alpha_space|min_length[3]|max_length[75]',
    //   'telefono' => 'required|alpha_space|min_length[3]|max_length[8]',
    //   'correo' => 'permit_empty|alpha_numeric_space|valid_email|max_length[85]',
    // ];
    // protected $validationMessages = [
    //   'correo' => ['valid_email' => 'El correo no es válido'],
    // ];
    // protected $skipValidation     = false;
}
