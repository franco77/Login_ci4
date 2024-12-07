<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table      = 'users';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'first_name', 'last_name', 'email', 'phone', 'address', 'avatar', 'profile', 'password', 'role_id', 'is_active', 'created_at', 'updated_at'];
    protected $searchFields = ['username', 'first_name', 'last_name', 'email', 'phone'];

    public function filter($search = null, $limit = null, $start = null, $orderField = null, $orderDir = null)
    {
        $builder = $this->table($this->table);

        // Validar que $limit y $start sean enteros
        $limit = is_numeric($limit) ? (int)$limit : 10;  // Por defecto, limit = 10
        $start = is_numeric($start) ? (int)$start : 0;   // Por defecto, start = 0

        // Asegurarse de que $orderField y $orderDir no estén vacíos y sean válidos
        $orderField = in_array($orderField, $this->allowedFields) ? $orderField : 'username';
        $orderDir = in_array(strtolower($orderDir), ['asc', 'desc']) ? $orderDir : 'asc';

        // Aplicar filtro de búsqueda
        if ($search) {
            $builder->groupStart();
            foreach ($this->searchFields as $i => $column) {
                if ($i === 0) {
                    $builder->like($column, $search);
                } else {
                    $builder->orLike($column, $search);
                }
            }
            $builder->groupEnd();
        }



        // Muestra datos menores o iguales a las primeras 6 columnas.

        $builder->select('users.*, roles.id AS roleID, roles.name')
            ->join('roles', 'roles.id = users.role_id')
            ->orderBy($orderField, $orderDir)
            ->limit($limit, $start);

        $query = $builder->get()->getResultArray();

        foreach ($query as $index => $value) {
            $query[$index]['address'] = strlen($query[$index]['address']) > 50 ? substr($query[$index]['address'], 0, 50) . '...' : $query[$index]['address'];

            $query[$index]['is_active'] = '<div class="form-group">
            <div class="form-check form-switch">
                 <input class="form-check-input" 
                        type="checkbox" 
                        id="is_active_' . $query[$index][$this->primaryKey] . '" 
                        name="is_active" 
                        data-user-id="' . $query[$index][$this->primaryKey] . '" 
                        value="1" ' .
                ($query[$index]['is_active'] ? 'checked' : '') . '>
                 <label class="form-check-label" for="is_active_' . $query[$index][$this->primaryKey] . '">Activo</label>
             </div>
         </div>';




            $query[$index]['column_bulk'] = '<input type="checkbox" class="bulk-item" value="' . $query[$index][$this->primaryKey] . '">';
            $query[$index]['column_action'] = '<button class="btn btn-sm btn-xs btn-success form-action" item-id="' . $query[$index][$this->primaryKey] . '" purpose="detail"><i class="far fa-eye"></i></button> <button class="btn btn-sm btn-xs btn-warning form-action" purpose="edit" item-id="' . $query[$index][$this->primaryKey] . '"><i class="far fa-edit"></i></button>';
        }
        return $query;
    }

    public function countTotal()
    {
        return $this->table($this->table)
            ->join('roles', 'roles.id = users.role_id')
            ->countAll();
    }

    public function countFilter($search)
    {
        $builder = $this->table($this->table);

        $i = 0;
        foreach ($this->searchFields as $column) {
            if ($search) {
                if ($i == 0) {
                    $builder->groupStart()
                        ->like($column, $search);
                } else {
                    $builder->orLike($column, $search);
                }

                if (count($this->searchFields) - 1 == $i) $builder->groupEnd();
            }
            $i++;
        }

        return $builder->join('roles', 'roles.id = users.role_id')
            ->countAllResults();
    }

    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first(); // Buscar usuario por nombre
    }
}
