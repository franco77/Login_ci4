<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;
use CodeIgniter\Controller;

class LoginController extends Controller
{
    public function __construct()
    {
        $this->UserModel = new UserModel;
        $this->RoleModel = new RoleModel();
    }
    public function index()
    {
        return view('login');
    }

    public function authenticate()
    {
        // Validar los datos de entrada
        $validation = \Config\Services::validation();
        $validation->setRules([
            'username' => 'required|string|max_length[255]',
            'password' => 'required|string|max_length[255]',
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return view('login', ['error' => 'Invalid input data', 'validation' => $validation]);
        }

        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        // Obtener el usuario por nombre de usuario
        $user = $this->UserModel->getUserByUsername($username);

        // Verificar si el usuario existe y la contraseña es correcta
        if ($user && strcasecmp($username, $user['username']) === 0 && password_verify($password, $user['password'])) {
            // Verificar si la cuenta está activa
            if (!$user['is_active']) {
                return view('login', ['error' => 'Account is inactive. Please contact support.']);
            }

            // Iniciar sesión del usuario
            $this->session = \Config\Services::session();
            $this->session->set([
                'id' => $user['id'],
                'username' => $user['username'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'avatar' => $user['avatar'],
                'role_id' => $user['role_id'],
                'isLoggedIn' => true,
            ]);

            // Redirigir según el rol del usuario
            if ($user['role_id'] == 1) {
                return redirect()->to('admin/dashboard');
            } else {
                return redirect()->to('users/dashboard');
            }
        }

        // Credenciales inválidas
        return view('login', ['error' => 'Invalid username or password']);
    }


    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
