<?php namespace App\Models;

use CodeIgniter\Model;

class CuentaModel extends Model
{
  protected $table = 'cuenta';
  protected $primaryKey = 'id';

  protected $returnType     = 'array';
  protected $useSoftDeletes = false;

  protected $allowedFields = ['moneda', 'fondo', 'id_cliente'];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';


}
