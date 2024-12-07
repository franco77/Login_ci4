<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;

use App\Models\SettingsModel;

class Settings extends BaseController
{
    protected $settingsModel;

    public function __construct()
    {
        $this->settingsModel = new SettingsModel();
    }

    public function index()
    {
        $settings = $this->settingsModel->findAll();
        return view('admin/settings/edit', ['settings' => $settings, 'title' => 'Ajustes']);
    }

    public function updateSettings()
    {
        // Verificar que sea una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Acceso no autorizado']);
        }

        try {
            $settingsData = $this->request->getPost();

            // Iniciar transacción para asegurar consistencia
            $this->db->transStart();

            foreach ($settingsData as $key => $value) {
                if ($value !== null && $value !== '') {
                    $this->settingsModel->updateSetting($key, $value);
                }
            }

            // Confirmar transacción
            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Configuraciones actualizadas correctamente',
            ]);
        } catch (\Exception $e) {
            // Revertir transacción en caso de error
            $this->db->transRollback();
            log_message('error', 'Error al actualizar configuraciones: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }


    public function uploadLogo()
    {
        // Verificar que sea una solicitud AJAX
        if (!$this->request->isAJAX()) {
            return $this->response->setStatusCode(404)
                ->setJSON(['status' => 'error', 'message' => 'Acceso no autorizado']);
        }

        try {
            $file = $this->request->getFile('logo');

            if ($file && $file->isValid() && !$file->hasMoved()) {
                // Validar tipo MIME permitido
                $allowedTypes = ['image/png', 'image/jpeg', 'image/jpg'];
                if (!in_array($file->getMimeType(), $allowedTypes)) {
                    throw new \RuntimeException("El archivo {$file->getName()} tiene un formato no permitido.");
                }

                // Generar un nombre único y mover el archivo
                $newName = $file->getRandomName();
                $file->move(FCPATH . 'uploads/settings/', $newName);

                // Guardar la ruta del archivo en la base de datos
                $this->settingsModel->updateSetting('logo', $newName);

                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Logo subido correctamente',
                ]);
            } else {
                throw new \RuntimeException('No se pudo subir el archivo');
            }
        } catch (\Exception $e) {
            log_message('error', 'Error al subir el logo: ' . $e->getMessage());

            return $this->response->setJSON([
                'status' => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }
}
