<?php

namespace App\Controllers\API;


use App\Models\rolModel;
use CodeIgniter\RESTful\ResourceController;

class rolesController extends ResourceController
{
    public function __construct()
    { $this->model = $this->setModel(new rolModel());
      // inyeccion de la libreria como servvicio nombrado validation
      $this->validation = \Config\Services::validation();
    }

    public function index()
    {
      try {

      if ( $rol = $this->model->findAll() ) {
        return $this->respond($rol, 200);

        $rol->paginate(10);
        $paginador = $this->model->pager;
        $paginador->setPath('api/rols');
        return $this->respond($paginador);
      } else {
        return $this->failNotFound( 'No se encontraron rols' );
      }
    } catch (\Exception $e) {
      return $this->failServerError('Error en el servidor', $e->getMessage() );
    }

    }

    public function create()
    {
      try {
      $data = $this->request->getJSON(true);

        if ($this->validation->run($data, 'rol_validation') == false) {
          return $this->fail($this->validation->getErrors());

        } else {

          $this->model->insert($data);
          return $this->respondCreated([
          'status' => 'created',
          'message' => 'rol creado',
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
            'msg' => 'El rol se encontro correctamente',
            'rol' => $id
          ],200);

        } else {
          return $this->respond(
          ['error' => 'No se puede encontrar el rol'],
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
            'error' => 'No se puede editar el rol'
          ], 500);

        } else {

          $rol = $this->model->find($id);
          if ( $rol ) {
            $rol = $this->request->getJSON();
            if ( $this->model->update($id, $rol) ) {
              return $this->respond([
                'msg' => 'El rol se edito correctamente',
                'rol' => $rol],
                200);
            } else {
              return $this->respond(
                ['error' => 'No se puede editar el rol'],
                500);
            }
          } else {
            return $this->respond(
              ['error' => 'El rol no existe!!'],
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
            ['error' => 'No se puede eliminar el rol'],
            500);
        } else {
          $rol = $this->model->find($id);
          if ( $rol ) {
            if ( $this->model->delete($id) ) {
              return $this->respond([
                'msg' => 'El rol se elimino correctamente',
                'rol' => $rol
              ], 200);

            } else {
              return $this->respond([
                'error' => 'No se puede eliminar el rol'
              ], 500);
            }
          } else {
            return $this->respond([
              'error' => 'El rol no existe!!'
            ], 500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }

}
