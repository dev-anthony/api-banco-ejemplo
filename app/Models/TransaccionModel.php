<?php namespace App\Models;

use CodeIgniter\Model;

class TransaccionModel extends Model
{
  protected $table = 'transaccion';
  protected $primaryKey = 'id';

  protected $returnType     = 'array';
  protected $useSoftDeletes = false;

  protected $allowedFields = ['cuenta_id', 'tipo_transaccion_id'];

  protected $useTimestamps = true;
  protected $createdField  = 'created_at';
  protected $updatedField  = 'updated_at';

  public function TransaccionesPorCliente($clienteId = null)
  {
    $builder = $this->db->table($this->tabale);
    $builder->select('cuenta.id AS NumeroCuenta, cliente.nombre, cliente.apellido');
    $builder->select('tipo_transaccion.descripcion AS Tipo, transaccion.monto, transaccion.created_at');
    $builder->join('cuenta', 'cuenta.id = transaccion.cuenta_id');
    $builder->join('tipo_transaccion', 'tipo_transaccion.id = transaccion.tipo_transaccion_id');
    $builder->join('cuenta', 'cuenta.id = cuenta.cliente_id');

    $builder->where('cliente.id', $clienteId);

    $query = $builder->get(); //retorna el resultado de la consulta anidada
    return $query->getResult(); //retorna el resultado de las filas
  }


}
