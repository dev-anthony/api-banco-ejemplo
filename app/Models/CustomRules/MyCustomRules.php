<?php namespace App\Models\CustomRules;

use App\Models\ClienteModel;

class MyCustomRules {
  public function is_valid_cliente(int $id): bool
  {
    // $client = \Config\Database::connect()->table('cliente')->getWhere('id', $id);
    // return $client->getRow() !== null;

    $model = new ClienteModel();
    $cliente = $model->find($id);

    return $cliente == null ? false : true;
  }

}
