<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;
    protected $validation;
    protected $imageService;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->validation = \Config\Services::validation();
        $this->imageService = \Config\Services::image();
    }

    public function index()
    {
        $session = session();
        $userId = $session->get('id'); // Usar el método correcto del session
        $data = [
            'user' => $this->userModel->find($userId),
            'title' => 'Perfil',
        ];
        return view('admin/profile/profile', $data);
    }

    public function update()
    {
        $session = session();
        $userId = $session->get('id');

        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|valid_email',
            'phone' => 'required',
            'profile' => 'required',
        ];

        if (!$this->validate($rules)) {
            return $this->response->setJSON([
                'status' => 'error',
                'errors' => $this->validator->getErrors()
            ]);
        }

        $data = [
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'email' => $this->request->getPost('email'),
            'phone' => $this->request->getPost('phone'),
            'address' => $this->request->getPost('address'),
            'profile' => $this->request->getPost('profile'),
        ];

        if ($this->userModel->update($userId, $data)) {
            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Perfil actualizado exitosamente'
            ]);
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'No se pudo actualizar el perfil'
            ]);
        }
    }


    public function updateAvatar()
    {
        $validationRule = [
            'avatar' => [
                'label' => 'Avatar Image',
                'rules' => 'uploaded[avatar]|is_image[avatar]|mime_in[avatar,image/jpg,image/jpeg,image/png]|max_size[avatar,2048]',
            ],
        ];

        if (!$this->validate($validationRule)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $this->validator->getErrors()
            ]);
        }

        $avatar = $this->request->getFile('avatar');
        $session = session();
        $userId = $session->get('id');

        if ($avatar->isValid() && !$avatar->hasMoved()) {
            $newName = $avatar->getRandomName();
            $path = FCPATH . 'uploads/avatars/';
            $avatar->move($path, $newName);

            $tempPath = $path . $newName;
            $this->imageService->withFile($tempPath)
                ->resize(400, 400, true, 'height')
                ->save($tempPath);

            if ($this->userModel->update($userId, ['avatar' => $newName])) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'new_image_url' => base_url('uploads/avatars/' . $newName),
                ]);
            } else {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'No se pudo actualizar el avatar en la base de datos'
                ]);
            }
        } else {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Error al subir la imagen'
            ]);
        }
    }
}
