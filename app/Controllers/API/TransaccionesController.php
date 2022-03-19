<?php

namespace App\Controllers\API;

use App\Models\ClienteModel;
use App\Models\CuentaModel;
use App\Models\TransaccionModel;
use CodeIgniter\RESTful\ResourceController;

class TransaccionesController extends ResourceController
{
    public function __construct()
    { $this->model = $this->setModel(new TransaccionModel());
      $this->model = $this->setModel(new CuentaModel());
      $this->model = $this->setModel(new ClienteModel());
      // inyeccion de la libreria como servvicio nombrado validation
      $this->validation = \Config\Services::validation();
    }

    public function index()
    {
      try {

      if ( $transaccion = $this->model->findAll() ) {
        return $this->respond($transaccion, 200);

        $transaccion->paginate(10);
        $paginador = $this->model->pager;
        $paginador->setPath('api/transaccions');
        return $this->respond($paginador);
      } else {
        return $this->failNotFound( 'No se encontraron transaccions' );
      }
    } catch (\Exception $e) {
      return $this->failServerError('Error en el servidor', $e->getMessage() );
    }

    }

    public function create()
    {
      try {
      $data = $this->request->getJSON(true);

        if ($this->validation->run($data, 'transaccion_validation') == false) {
          return $this->fail($this->validation->getErrors());

        } else {

          $this->model->insert($data);
          return $this->respondCreated([
          'status' => 'created',
          'message' => 'transaccion creado',
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
            'msg' => 'El transaccion se encontro correctamente',
            'transaccion' => $id
          ],200);

        } else {
          return $this->respond(
          ['error' => 'No se puede encontrar el transaccion'],
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
            'error' => 'No se puede editar el transaccion'
          ], 500);

        } else {

          $transaccion = $this->model->find($id);
          if ( $transaccion ) {
            $transaccion = $this->request->getJSON();
            if ( $this->model->update($id, $transaccion) ) {
              return $this->respond([
                'msg' => 'El transaccion se edito correctamente',
                'transaccion' => $transaccion],
                200);
            } else {
              return $this->respond(
                ['error' => 'No se puede editar el transaccion'],
                500);
            }
          } else {
            return $this->respond(
              ['error' => 'El transaccion no existe!!'],
              500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }

    public function getTrasaccionesByCliente ($id = null)
    {
      try {
        //code...
        $modelCliente = new ClienteModel();
        if ( $id == null ) {
          return $this->failVAlidationErrors('No se encontro un Id valido');

          $cliente = $modelCliente->find($id);
          if ( $cliente == null ) {
            return $this->failNotFound('No se encontro el cliente con el Id: '.$id);

            $transacciones = $this->model->TransaccionesPorCliente($id);
            return $this->respond($transacciones);
          }
        }
      } catch (\Exception $ex) {
        //Exception $ex;
        return $this->failServerError('Error en el servidor', $ex->getMessage());
      }
    }

    private function actualizarFondoCuenta ($tipoTransaccionId, $monto, $cuentaId)
    {
      $modelCuenta = new CuentaModel();
      $cuenta = $modelCuenta->find($cuentaId);

      switch ($tipoTransaccionId) {
        case 1:
          $cuenta['fondo'] += $monto;
          break;
        case 2:
          $cuenta['fondo'] -= $monto;
          break;
        default:
          return $this->failNotFound( 'No se encontro el tipo de transaccion' );
          break;
      }
      if ($modelCuenta->update($cuentaId, $cuenta)) {
        return array ('Transaccionn Exitosa' => true, 'Nuevo Fonndo' => $cuenta['fondo']);
      } else {
        return array ('Transaccionn Exitosa' => false, 'Nuevo Fonndo' => $cuenta['fondo']);
      }
    }


    public function delete($id = null)
    {
      try {
        //code...
        if ( $id == null ) {
          return $this->respond(
            ['error' => 'No se puede eliminar el transaccion'],
            500);
        } else {
          $transaccion = $this->model->find($id);
          if ( $transaccion ) {
            if ( $this->model->delete($id) ) {
              return $this->respond([
                'msg' => 'El transaccion se elimino correctamente',
                'transaccion' => $transaccion
              ], 200);

            } else {
              return $this->respond([
                'error' => 'No se puede eliminar el transaccion'
              ], 500);
            }
          } else {
            return $this->respond([
              'error' => 'El transaccion no existe!!'
            ], 500);
          }
        }
      } catch (\Exception $e) {
        //Exception $e;
        return $this->failServerError('Error en el servidor', $e->getMessage());
      }
    }

}
