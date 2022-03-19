<?php

namespace App\Controllers\API;


use App\Models\TipoTransaccionModel;
use CodeIgniter\RESTful\ResourceController;

class TipoTransaccionesController extends ResourceController
{
    public function __construct()
    { $this->model = $this->setModel(new TipoTransaccionModel());
      // inyeccion de la libreria como servvicio nombrado validation
      $this->validation = \Config\Services::validation();
    }

    public function index()
    {
      try {

      if ( $tipotransaccion = $this->model->findAll() ) {
        return $this->respond($tipotransaccion, 200);

        $tipotransaccion->paginate(10);
        $paginador = $this->model->pager;
        $paginador->setPath('api/tipotransaccions');
        return $this->respond($paginador);
      } else {
        return $this->failNotFound( 'No se encontraron tipo transacciones' );
      }
    } catch (\Exception $e) {
      return $this->failServerError('Error en el servidor', $e->getMessage() );
    }

    }

    public function create()
    {
      try {
      $data = $this->request->getJSON(true);

        if ($this->validation->run($data, 'tipotransaccion_validation') == false) {
          return $this->fail($this->validation->getErrors());

        } else {

          $this->model->insert($data);
          return $this->respondCreated([
          'status' => 'created',
          'message' => 'tipo transaccion creado',
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
            'msg' => 'El tipo transaccion se encontro correctamente',
            'tipotransaccion' => $id
          ],200);

        } else {
          return $this->respond(
          ['error' => 'No se puede encontrar el tipo transaccion'],
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
            'error' => 'No se puede editar el tipo transaccion'
          ], 500);

        } else {

          $tipotransaccion = $this->model->find($id);
          if ( $tipotransaccion ) {
            $tipotransaccion = $this->request->getJSON();
            if ( $this->model->update($id, $tipotransaccion) ) {
              return $this->respond([
                'msg' => 'El tipo transaccion se edito correctamente',
                'tipotransaccion' => $tipotransaccion],
                200);
            } else {
              return $this->respond(
                ['error' => 'No se puede editar el tipo transaccion'],
                500);
            }
          } else {
            return $this->respond(
              ['error' => 'El tipo transaccion no existe!!'],
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
            ['error' => 'No se puede eliminar el tipo transaccion'],
            500);
        } else {
          $tipotransaccion = $this->model->find($id);
          if ( $tipotransaccion ) {
            if ( $this->model->delete($id) ) {
              return $this->respond([
                'msg' => 'El tipo transaccion se elimino correctamente',
                'tipotransaccion' => $tipotransaccion
              ], 200);

            } else {
              return $this->respond([
                'error' => 'No se puede eliminar el tipo transaccion'
              ], 500);
            }
          } else {
            return $this->respond([
              'error' => 'El tipo transaccion no existe!!'
            ], 500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }

}
