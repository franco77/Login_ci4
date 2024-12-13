<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;
use App\Models\TodoModel;

class Todo extends BaseController
{

    public function __construct()
    {
        $this->TodoModel = new TodoModel();
    }
    public function index()
    {

        $todos =  $this->TodoModel->findAll();
        $data = [
            'title' => 'Tareas',
            'todos' => $todos
        ];

        return view('admin/todo/index', $data);
    }

    public function add()
    {
        $task = $this->request->getPost('task');
        if ($task) {
            $data = [
                'task' => $task,
                'is_completed' => 0,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $this->TodoModel->save($data);
        }

        return $this->response->setJSON(['status' => 'success', 'task' => $task]);
    }

    public function toggle($id)
    {

        $todo =  $this->TodoModel->find($id);

        if ($todo) {
            $todo['is_completed'] = !$todo['is_completed'];
            $this->TodoModel->save($todo);
        }

        return $this->response->setJSON(['status' => 'success']);
    }

    public function delete($id)
    {

        $this->TodoModel->delete($id);

        return $this->response->setJSON(['status' => 'success']);
    }
}