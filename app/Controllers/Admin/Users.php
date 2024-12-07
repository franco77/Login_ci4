<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use CodeIgniter\API\ResponseTrait;
use App\Models\UserModel;
use App\Models\RoleModel;

class Users extends BaseController
{
   use ResponseTrait;

   public function __construct()
   {
      $this->UsersModel = new UserModel;
      $this->RolesModel = new RoleModel;
   }

   function index()
   {
      $data = [
         'title' => 'Data Users',
         'host' => site_url('admin/users/')
      ];
      echo view('admin/users/list', $data);
   }

   public function data()
   {
      try {
         $request = esc($this->request->getPost());
         $search = $request['search']['value'];
         $limit = $request['length'];
         $start = $request['start'];

         $orderIndex = $request['order'][0]['column'];
         $orderFields = $request['columns'][$orderIndex]['data'];
         $orderDir = $request['order'][0]['dir'];

         $recordsTotal = $this->UsersModel->countTotal();
         $data = $this->UsersModel->filter($search, $limit, $start, $orderFields, $orderDir);
         $recordsFiltered = $this->UsersModel->countFilter($search);

         $callback = [
            'draw' => $request['draw'],
            'recordsTotal' => $recordsTotal,
            'recordsFiltered' => $recordsFiltered,
            'data' => $data
         ];

         return $this->respond($callback);
      } catch (\Exception $e) {
         // return $this->failServerError($e->getMessage());
         return $this->failServerError('Sorry, an error occurred. Please contact the administrator.');
      }
   }

   public function new()
   {
      $data = [
         'actions' => 'new',
         'data_roles' => $this->RolesModel->findAll(),
      ];

      echo view('admin/users/form', $data);
   }

   public function create()
   {
      // Validar y procesar avatar
      $avatar = $this->request->getFile('avatar');
      $avatarFileName = null;

      if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
         // Verificar el tipo de archivo
         $mimeType = $avatar->getMimeType();
         if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
            return $this->respond([
               'status' => 400,
               'error' => 400,
               'messages' => ['avatar' => 'El archivo debe ser una imagen válida (JPEG, PNG, GIF).']
            ], 400);
         }

         // Generar un nombre único para el archivo
         $avatarFileName = $avatar->getRandomName();

         // Verificar las dimensiones de la imagen
         $image = \Config\Services::image()
            ->withFile($avatar->getTempName());
         $width = $image->getWidth();

         if ($width > 600) {
            // Redimensionar si el ancho es mayor a 600 píxeles
            $image->resize(600, 600, true) // Mantener proporción
               ->save(FCPATH . 'uploads/' . $avatarFileName);
         } else {
            // Mover el archivo si no necesita redimensionarse
            $avatar->move(FCPATH . 'uploads/', $avatarFileName);
         }
      }

      // Crear el array de datos del usuario
      $request = [
         'username' => $this->request->getPost('username'),
         'first_name' => $this->request->getPost('first_name'),
         'last_name' => $this->request->getPost('last_name'),
         'email' => $this->request->getPost('email'),
         'phone' => $this->request->getPost('phone'),
         'address' => $this->request->getPost('address'),
         'avatar' => $avatarFileName, // Solo el nombre del archivo
         'profile' => $this->request->getPost('profile'),
         'password' => $this->request->getPost('password'),
         'password_confirm' => $this->request->getPost('password_confirm'),
         'role_id' => $this->request->getPost('role_id'),
         'created_at' => date('Y-m-d'),
      ];

      // Validar los datos ingresados
      $this->rules();

      if (!$this->validation->run($request)) {
         return $this->respond([
            'status' => 400,
            'error' => 400,
            'messages' => $this->validation->getErrors()
         ], 400);
      }

      try {
         // Verificar si las contraseñas coinciden
         if ($request['password'] !== $request['password_confirm']) {
            return $this->respond([
               'status' => 400,
               'error' => 400,
               'messages' => ['password_confirm' => 'Las contraseñas no coinciden.']
            ], 400);
         }

         // Hash de la contraseña
         $request['password'] = password_hash($request['password'], PASSWORD_BCRYPT);

         // Eliminar el campo `password_confirm` antes de guardar en la base de datos
         unset($request['password_confirm']);

         // Insertar los datos en la base de datos
         $insert = $this->UsersModel->insert($request);

         if ($insert) {
            return $this->respondCreated([
               'status' => 201,
               'message' => 'Data created.'
            ]);
         } else {
            return $this->fail($this->UsersModel->errors());
         }
      } catch (\Exception $e) {
         return $this->failServerError('Sorry, an error occurred. Please contact the administrator.');
      }
   }





   public function show($id = null)
   {
      try {
         $data = $this->UsersModel->join('roles', 'roles.id = users.role_id')
            ->find($id);
         if ($data) {
            // De forma predeterminada, solo muestra datos de la tabla principal.

            $table = '<table class="table table-striped table-bordered table-sm">';
            $table .= '<tr><th>Username</th><td>' . $data['username'] . '</td></tr>';
            $table .= '<tr><th>First Name</th><td>' . $data['first_name'] . '</td></tr>';
            $table .= '<tr><th>Last Name</th><td>' . $data['last_name'] . '</td></tr>';
            $table .= '<tr><th>Email</th><td>' . $data['email'] . '</td></tr>';
            $table .= '<tr><th>Phone</th><td>' . $data['phone'] . '</td></tr>';
            $table .= '<tr><th>Address</th><td>' . $data['address'] . '</td></tr>';
            $table .= '<tr><th>Avatar</th><td>' . $data['avatar'] . '</td></tr>';
            $table .= '<tr><th>Profile</th><td>' . $data['profile'] . '</td></tr>';
            $table .= '<tr><th>Password</th><td>' . $data['password'] . '</td></tr>';
            $table .= '<tr><th>Role Id</th><td>' . $data['role_id'] . '</td></tr>';
            $table .= '<tr><th>Created At</th><td>' . $data['created_at'] . '</td></tr>';
            $table .= '<tr><th>Updated At</th><td>' . $data['updated_at'] . '</td></tr>';
            $table .= '</table>';
            return $this->respond($table);;
         } else {
            return $this->failNotFound();
         }
      } catch (\Exception $e) {
         // return $this->failServerError($e->getMessage());
         return $this->failServerError('Sorry, an error occurred. Please contact the administrator.');
      }
   }

   public function edit($id = null)
   {
      try {
         $data = $this->UsersModel->find($id);

         if ($data) {
            $data = [
               'actions' => 'edit',
               'data_roles' => $this->RolesModel->findAll(),
               'data_users' => $data
            ];

            echo view('admin/users/form', $data);
         } else {
            return $this->failNotFound();
         }
      } catch (\Exception $e) {
         // return $this->failServerError($e->getMessage());
         return $this->failServerError('Sorry, an error occurred. Please contact the administrator.');
      }
   }

   public function update($id = null)
   {
      // Buscar al usuario para verificar que existe
      $existingUser = $this->UsersModel->find($id);
      if (!$existingUser) {
         return $this->failNotFound('Usuario no encontrado.');
      }

      // Obtener los datos del formulario
      $password = $this->request->getPost('password');
      $passwordConfirm = $this->request->getPost('password_confirm');

      // Validar y procesar avatar
      $avatar = $this->request->getFile('avatar');
      $avatarFileName = $existingUser['avatar']; // Mantener la imagen existente por defecto

      if ($avatar && $avatar->isValid() && !$avatar->hasMoved()) {
         // Verificar el tipo de archivo
         $mimeType = $avatar->getMimeType();
         if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
            return $this->respond([
               'status' => 400,
               'error' => 400,
               'messages' => ['avatar' => 'El archivo debe ser una imagen válida (JPEG, PNG, GIF).']
            ], 400);
         }

         // Generar un nombre único para el archivo
         $avatarFileName = $avatar->getRandomName();

         // Verificar las dimensiones de la imagen
         $image = \Config\Services::image()
            ->withFile($avatar->getTempName());
         $width = $image->getWidth();

         if ($width > 600) {
            // Redimensionar si el ancho es mayor a 600 píxeles
            $image->resize(600, 600, true) // Mantener proporción
               ->save(FCPATH . 'uploads/' . $avatarFileName);
         } else {
            // Mover el archivo si no necesita redimensionarse
            $avatar->move(FCPATH . 'uploads', $avatarFileName);
         }
      }

      // Crear el array con los datos a actualizar
      $request = [
         'username' => $this->request->getPost('username'),
         'first_name' => $this->request->getPost('first_name'),
         'last_name' => $this->request->getPost('last_name'),
         'email' => $this->request->getPost('email'),
         'phone' => $this->request->getPost('phone'),
         'address' => $this->request->getPost('address'),
         'avatar' => $avatarFileName, // Solo el nombre del archivo
         'profile' => $this->request->getPost('profile'),
         'role_id' => $this->request->getPost('role_id'),
      ];

      // Si se ingresó un nuevo password, verificar y agregarlo al array
      if (!empty($password)) {
         if ($password !== $passwordConfirm) {
            return $this->respond([
               'status' => 400,
               'error' => 400,
               'messages' => ['password_confirm' => 'Las contraseñas no coinciden.']
            ], 400);
         }
         $request['password'] = password_hash($password, PASSWORD_BCRYPT);
      }

      // Validar los datos antes de actualizar
      $this->rules();

      if (!$this->validation->run($request)) {
         return $this->respond([
            'status' => 400,
            'error' => 400,
            'messages' => $this->validation->getErrors()
         ], 400);
      }

      try {
         // Actualizar los datos del usuario
         $update = $this->UsersModel->update($id, $request);

         if ($update) {
            return $this->respondNoContent('Data updated');
         } else {
            return $this->fail($this->UsersModel->errors());
         }
      } catch (\Exception $e) {
         return $this->failServerError('Sorry, an error occurred. Please contact the administrator.');
      }
   }


   public function toggleUserStatus()
   {
      // Recibir datos desde la solicitud AJAX
      $request = \Config\Services::request(); // Obtener la instancia de la solicitud
      $userId = $request->getPost('user_id');
      $isActive = $request->getPost('is_active');

      // Validar los datos recibidos
      if ($userId !== null && $isActive !== null) {
         // Actualizar directamente la base de datos
         $updated = $this->db->table('users') // 'users' es el nombre de la tabla
            ->where('id', $userId) // 'id' es la clave primaria
            ->update(['is_active' => $isActive]);

         if ($updated) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'Estado actualizado correctamente.']);
         } else {
            return $this->response->setJSON(['status' => 'error', 'message' => 'No se pudo actualizar el estado.']);
         }
      } else {
         return $this->response->setJSON(['status' => 'error', 'message' => 'Datos inválidos.']);
      }
   }






   public function delete($id = null)
   {
      try {
         $data = $this->UsersModel->find($id);
         if ($data) {
            $this->UsersModel->delete($id);
            return $this->respondDeleted([
               'status' => 200,
               'message' => 'Data deleted.'
            ]);
         } else {
            return $this->failNotFound();
         }
      } catch (\Exception $e) {
         // return $this->failServerError($e->getMessage());
         return $this->failServerError('Sorry, an error occurred. Please contact the administrator.');
      }
   }

   private function rules()
   {
      $id = $this->request->getPost('id');

      $passwordRules = empty($id) ? 'required|string|max_length[255]' : 'permit_empty|string|max_length[255]';

      $this->validation->setRules([
         'username' => [
            'label' => 'Username',
            'rules' => 'required|is_unique[users.username,id,' . ($id ?? 'NULL') . ']'
         ],
         'first_name' => [
            'label' => 'First Name',
            'rules' => 'required|string|max_length[150]'
         ],
         'last_name' => [
            'label' => 'Last Name',
            'rules' => 'required|string|max_length[150]'
         ],
         'email' => [
            'label' => 'Email',
            'rules' => 'required|is_unique[users.email,id,' . ($id ?? 'NULL') . ']'
         ],
         'phone' => [
            'label' => 'Phone',
            'rules' => 'required|string|max_length[70]'
         ],
         'address' => [
            'label' => 'Address',
            'rules' => 'required|string'
         ],
         'password' => [
            'label' => 'Password',
            'rules' => $passwordRules
         ],
         'role_id' => [
            'label' => 'Role Id',
            'rules' => 'required|numeric'
         ]
      ]);
   }
}
