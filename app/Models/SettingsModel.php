<?php

namespace App\Models;

use CodeIgniter\Model;

class SettingsModel extends Model
{
    protected $table            = 'settings';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;

    protected $allowedFields    = ['key', 'value'];

    // Habilitar timestamps
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Obtener un ajuste por su clave
     */
    public function getSetting($key)
    {
        return $this->where('key', $key)->first();
    }

    /**
     * Actualizar o crear un ajuste
     */
    public function updateSetting($key, $value)
    {
        $setting = $this->where('key', $key)->first();

        if ($setting) {
            // Actualizar existente
            return $this->where('key', $key)->set('value', $value)->update();
        } else {
            // Crear nuevo
            return $this->insert([
                'key' => $key,
                'value' => $value
            ]);
        }
    }
}
