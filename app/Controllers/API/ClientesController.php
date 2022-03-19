<?php

namespace App\Controllers\API;


use App\Models\ClienteModel;
use CodeIgniter\RESTful\ResourceController;

class clientesController extends ResourceController
{
    public function __construct()
    { $this->model = $this->setModel(new ClienteModel());
      // inyeccion de la libreria como servvicio nombrado validation
      $this->validation = \Config\Services::validation();
    }

    public function index()
    {
      try {

      if ( $cliente = $this->model->findAll() ) {
        return $this->respond($cliente, 200);

        $cliente->paginate(10);
        $paginador = $this->model->pager;
        $paginador->setPath('api/clientes');
        return $this->respond($paginador);
      } else {
        return $this->failNotFound( 'No se encontraron clientes' );
      }
    } catch (\Exception $e) {
      return $this->failServerError('Error en el servidor', $e->getMessage() );
    }

    }

    public function create()
    {
      try {
      $data = $this->request->getJSON(true);

        if ($this->validation->run($data, 'cliente_validation') == false) {
          return $this->fail($this->validation->getErrors());

        } else {

          $this->model->insert($data);
          return $this->respondCreated([
          'status' => 'created',
          'message' => 'cliente creado',
          'data' => $data,
        ], 201);

        }
      }catch (\Exception $e) {
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }



    public function show($id = null)
    {
      try {
        //code...
        if ( $id = $this->model->find($id) ){
          return $this->respond([
            'msg' => 'El cliente se encontro correctamente',
            'cliente' => $id
          ],200);

        } else {
          return $this->respond(
          ['error' => 'No se puede encontrar el cliente'],
          500);
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor' ,$e->getMessage() );
      }
    }

    public function edit( $id = null )
    {
      try {
        //code...
        if ( $id == null ) {
          return $this->respond([
            'error' => 'No se puede editar el cliente'
          ], 500);

        } else {

          $cliente = $this->model->find($id);
          if ( $cliente ) {
            $cliente = $this->request->getJSON();
            if ( $this->model->update($id, $cliente) ) {
              return $this->respond([
                'msg' => 'El cliente se edito correctamente',
                'cliente' => $cliente],
                200);
            } else {
              return $this->respond(
                ['error' => 'No se puede editar el cliente'],
                500);
            }
          } else {
            return $this->respond(
              ['error' => 'El cliente no existe!!'],
              500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }


    public function delete($id = null)
    {
      try {
        //code...
        if ( $id == null ) {
          return $this->respond(
            ['error' => 'No se puede eliminar el cliente'],
            500);
        } else {
          $cliente = $this->model->find($id);
          if ( $cliente ) {
            if ( $this->model->delete($id) ) {
              return $this->respond([
                'msg' => 'El cliente se elimino correctamente',
                'cliente' => $cliente
              ], 200);

            } else {
              return $this->respond([
                'error' => 'No se puede eliminar el cliente'
              ], 500);
            }
          } else {
            return $this->respond([
              'error' => 'El cliente no existe!!'
            ], 500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }

}
