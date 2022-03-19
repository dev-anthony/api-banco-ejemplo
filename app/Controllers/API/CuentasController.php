<?php

namespace App\Controllers\API;


use App\Models\CuentaModel;
use CodeIgniter\RESTful\ResourceController;

class cuentasController extends ResourceController
{
    public function __construct()
    { $this->model = $this->setModel(new CuentaModel());
      // inyeccion de la libreria como servvicio nombrado validation
      $this->validation = \Config\Services::validation();
    }

    public function index()
    {
      try {

      if ( $cuenta = $this->model->findAll() ) {
        return $this->respond($cuenta, 200);

        $cuenta->paginate(10);
        $paginador = $this->model->pager;
        $paginador->setPath('api/cuentas');
        return $this->respond($paginador);
      } else {
        return $this->failNotFound( 'No se encontraron cuentas' );
      }
    } catch (\Exception $e) {
      return $this->failServerError('Error en el servidor', $e->getMessage() );
    }

    }

    public function create()
    {
      try {
      $data = $this->request->getJSON(true);
        if ($this->validation->run($data, 'cuenta_validation') == false) {
          return $this->fail($this->validation->getErrors());

        } else {

          $this->model->insert($data);
          return $this->respondCreated([
          'status' => 'created',
          'message' => 'cuenta creado',
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
            'msg' => 'El cuenta se encontro correctamente',
            'cuenta' => $id
          ],200);

        } else {
          return $this->respond(
          ['error' => 'No se puede encontrar el cuenta'],
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
            'error' => 'No se puede editar el cuenta'
          ], 500);

        } else {

          $cuenta = $this->model->find($id);
          if ( $cuenta ) {
            $cuenta = $this->request->getJSON();
            if ( $this->model->update($id, $cuenta) ) {
              return $this->respond([
                'msg' => 'El cuenta se edito correctamente',
                'cuenta' => $cuenta],
                200);
            } else {
              return $this->respond(
                ['error' => 'No se puede editar el cuenta'],
                500);
            }
          } else {
            return $this->respond(
              ['error' => 'El cuenta no existe!!'],
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
            ['error' => 'No se puede eliminar el cuenta'],
            500);
        } else {
          $cuenta = $this->model->find($id);
          if ( $cuenta ) {
            if ( $this->model->delete($id) ) {
              return $this->respond([
                'msg' => 'El cuenta se elimino correctamente',
                'cuenta' => $cuenta
              ], 200);

            } else {
              return $this->respond([
                'error' => 'No se puede eliminar el cuenta'
              ], 500);
            }
          } else {
            return $this->respond([
              'error' => 'El cuenta no existe!!'
            ], 500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }

}
