<?php

namespace App\Controllers;

use App\Models\UsuarioModel;
use CodeIgniter\API\ResponseTrait;
use Config\Services;
use Firebase\JWT\JWT;

class AuthController extends BaseController
{
    use ResponseTrait;

    public function __construct()
    {
      helper('secure_password');
    }

    public function login()
    {
      try {
        //code...
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $usuarioModel = new UsuarioModel();

        $where = ['username' => $username, 'password' => $password];
        $validateUsuario = $usuarioModel()->where('username', $username)->first();

        if($validateUsuario == null)
          return $this->failNotFound('Usuario o contraseña invalida');

          if (verifyPassword($password, $validateUsuario['password'])):
            // return $this->respond('Usuario encontrado', $validateUsuario);
            $jwt = $this->generateJWT($validateUsuario);
            return $this->respond(['Token' => $jwt], 201);
          else:
            return $this->failValidationErrors('Contraseña invalida');
          endif;
      } catch (\Exception $ex) {
        //Exception $ex;
        return $this->failServerError('Error en el servidor', $ex->getMessage());
      }
    }

    protected function generateJWT ($usuario)
    {
      $key = Services::getSecretKey();
      $time = time();
      $payload = [
        'aud' => base_url(),
        'iat' => $time,
        'exp' => $time + (60 * 60 * 24),
        'data' => [
          'nombre' => $usuario['nombre'],
          'username' => $usuario['username'],
          'rol' => $usuario['rol_id']
        ]
      ];

      $jwt = JWT::encode($payload, $key, 'HS256');
      return $jwt;
    }
}
