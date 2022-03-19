<?php

namespace App\Controllers\API;


use App\Models\usuarioModel;
use CodeIgniter\RESTful\ResourceController;

class usuariosController extends ResourceController
{
    public function __construct()
    { $this->model = $this->setModel(new usuarioModel());
      // inyeccion de la libreria como servvicio nombrado validation
      $this->validation = \Config\Services::validation();
    }

    public function index()
    {
      try {

      if ( $usuario = $this->model->findAll() ) {
        return $this->respond($usuario, 200);

        $usuario->paginate(10);
        $paginador = $this->model->pager;
        $paginador->setPath('api/usuarios');
        return $this->respond($paginador);
      } else {
        return $this->failNotFound( 'No se encontraron usuarios' );
      }
    } catch (\Exception $e) {
      return $this->failServerError('Error en el servidor', $e->getMessage() );
    }

    }

    public function create()
    {
      try {
      $data = $this->request->getJSON(true);

        if ($this->validation->run($data, 'usuario_validation') == false) {
          return $this->fail($this->validation->getErrors());

        } else {

          $this->model->insert($data);
          return $this->respondCreated([
          'status' => 'created',
          'message' => 'usuario creado',
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
            'msg' => 'El usuario se encontro correctamente',
            'usuario' => $id
          ],200);

        } else {
          return $this->respond(
          ['error' => 'No se puede encontrar el usuario'],
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
            'error' => 'No se puede editar el usuario'
          ], 500);

        } else {

          $usuario = $this->model->find($id);
          if ( $usuario ) {
            $usuario = $this->request->getJSON();
            if ( $this->model->update($id, $usuario) ) {
              return $this->respond([
                'msg' => 'El usuario se edito correctamente',
                'usuario' => $usuario],
                200);
            } else {
              return $this->respond(
                ['error' => 'No se puede editar el usuario'],
                500);
            }
          } else {
            return $this->respond(
              ['error' => 'El usuario no existe!!'],
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
            ['error' => 'No se puede eliminar el usuario'],
            500);
        } else {
          $usuario = $this->model->find($id);
          if ( $usuario ) {
            if ( $this->model->delete($id) ) {
              return $this->respond([
                'msg' => 'El usuario se elimino correctamente',
                'usuario' => $usuario
              ], 200);

            } else {
              return $this->respond([
                'error' => 'No se puede eliminar el usuario'
              ], 500);
            }
          } else {
            return $this->respond([
              'error' => 'El usuario no existe!!'
            ], 500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }

}
