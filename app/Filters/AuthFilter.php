<?php
namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;

class AuthFilter implements FilterInterface
{
  use ResponseTrait;

  // implementacion de before y after
  public function before(RequestInterface $request, $arguments = null)
  {
    // code...
    // echo "Antes de la peticion";
    try {
      //code...
      $key = Services::getSecretKey();
      $authHeader = $request->getServer('HTTP_AUTHORIZATION');

      if ($authHeader == null) {
        return Services::response()->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED)->setBody(json_encode([
          'error' => 'No se encontro el token'
        ]));

        $arr = explode(' ', $authHeader);
        $jwt = $arr[1];

        JWT::decode($jwt, $key, array('HS256'));
      }
    } catch (\Exception $ex) {
      //Exception $ex;
      return Services::response()->setStatusCode(ResponseInterface::HTTP_INTERNAL_SERVER_ERROR)->setBody(json_encode([
        'error' => 'Error en el servidor', 'message' => $ex->getMessage()
      ]));
    }
  }

  public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
  {
    // se ejecuta despues de la peticion
  }

}
