<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;


class PasswordController extends BaseController
{

    public function __construct()
    {
        $this->UsersModel = new UserModel;
    }

    public function forgotPassword()
    {
        return view('forgot_password');
    }

    public function sendResetLink()
    {
        $email = $this->request->getPost('email');

        // Validar el correo electrónico
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return redirect()->back()->with('error', 'Por favor ingrese un correo electrónico válido.');
        }

        $user = $this->UsersModel->findByEmail($email);

        if (!$user) {
            return redirect()->back()->with('error', 'El correo no está registrado.');
        }

        // Generar un token único y seguro
        $token = bin2hex(random_bytes(50));
        $this->UsersModel->saveToken($email, $token);

        $resetLink = base_url("PasswordController/resetPassword/$token");
        $fromEmail = get_setting('logo');
        // Configurar correo electrónico
        $this->email->setTo($email);
        $this->email->setFrom($fromEmail);
        $this->email->setSubject('Restablecimiento de contraseña');
        $this->email->setMessage("Haz clic en el siguiente enlace para restablecer tu contraseña: $resetLink");

        try {
            if ($this->email->send()) {
                return redirect()->back()->with('success', 'Se envió un correo con instrucciones para restablecer la contraseña.');
            } else {
                throw new \RuntimeException('No se pudo enviar el correo.');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al enviar el correo de restablecimiento de contraseña: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Ocurrió un error al enviar el correo. Por favor, inténtelo de nuevo más tarde.');
        }
    }


    public function resetPassword($token)
    {
        $this->UsersModel = new UserModel();
        $resetRequest = $this->UsersModel->findToken($token);

        if (!$resetRequest) {
            return redirect()->to('PasswordController/forgot_password')->with('error', 'Token inválido o expirado.');
        }

        return view('reset_password', ['token' => $token]);
    }

    public function updatePassword()
    {
        $token = $this->request->getPost('token');
        $newPassword = $this->request->getPost('password');
        $confirmPassword = $this->request->getPost('confirm_password');

        // Validar que las contraseñas coincidan
        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'Las contraseñas no coinciden.');
        }

        // Buscar la solicitud de restablecimiento por token
        $resetRequest = $this->UsersModel->findToken($token);

        if (!$resetRequest) {
            return redirect()->to('PasswordController/forgot_password')->with('error', 'Token inválido o expirado.');
        }

        // Obtener al usuario asociado
        $user = $this->UsersModel->findByEmail($resetRequest->email);

        if (!$user) {
            return redirect()->to('PasswordController/forgot_password')->with('error', 'Usuario no encontrado.');
        }

        // Actualizar la contraseña del usuario
        $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

        // Asegúrate de pasar el ID del usuario como primer argumento
        $this->UsersModel->update($user['id'], ['password' => $hashedPassword]);

        // Eliminar el token usado
        $this->UsersModel->deleteToken($token);

        return redirect()->to('/login')->with('success', 'Contraseña actualizada correctamente.');
    }
}
