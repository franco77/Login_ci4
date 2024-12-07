<?php

use App\Models\SettingsModel;

if (!function_exists('url')) {

    function url($url = '')
    {
        return site_url($url);
    }
}


if (!function_exists('assets_url')) {

    function assets_url($url = '')
    {
        return base_url('assets/' . $url);
    }
}


if (!function_exists('admin_assets_url')) {

    function admin_assets($url = '')
    {
        return assets_url('admin/' . $url);
    }
}



if (!function_exists('getUserData')) {
    /**
     * Obtiene un dato específico de la sesión del usuario
     *
     * @param string $field Campo a obtener (username, id, role_id, etc.)
     * @return mixed Valor del campo solicitado o null si no está disponible
     */
    function getUserData(string $field = '')
    {
        $session = \Config\Services::session();

        // Verificar si hay una sesión activa
        if (!$session->get('isLoggedIn')) {
            return null;
        }

        // Si no se especifica un campo, retornar todos los datos de sesión
        if (empty($field)) {
            return [
                'id' => $session->get('id'),
                'username' => $session->get('username'),
                'avatar' => $session->get('avatar'),
                'role_id' => $session->get('role_id')
            ];
        }

        // Retornar el campo específico si existe
        return $session->get($field);
    }
}

if (!function_exists('isLoggedIn')) {
    /**
     * Verifica si el usuario está autenticado
     *
     * @return bool
     */
    function isLoggedIn()
    {
        $session = \Config\Services::session();
        return $session->get('isLoggedIn') === true;
    }
}

if (!function_exists('logout')) {
    /**
     * Cierra la sesión del usuario
     */
    function logout()
    {
        $session = \Config\Services::session();
        $session->destroy();
    }
}





if (!function_exists('get_setting')) {
    /**
     * Obtener valor de configuración
     * 
     * @param string $key Clave de configuración
     * @param mixed $default Valor por defecto si no existe
     * @return mixed
     */
    function get_setting(string $key, $default = null)
    {
        $settingsModel = new SettingsModel();
        $setting = $settingsModel->where('key', $key)->first();

        return $setting ? $setting['value'] : $default;
    }
}


if (!function_exists('snake_to_words')) {
    /**
     * Convierte una cadena en formato snake_case a formato de palabras con espacios y capitalización.
     *
     * @param string $text La cadena en formato snake_case.
     * @return string La cadena convertida a formato de palabras.
     */
    function snake_to_words(string $text): string
    {
        // Reemplaza guiones bajos con espacios y convierte a mayúsculas las palabras
        return ucwords(str_replace('_', ' ', $text));
    }
}
