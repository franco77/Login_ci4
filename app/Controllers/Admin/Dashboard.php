<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;

class Dashboard extends BaseController
{
    public function __construct() {}


    public function index()
    {
        $data = ['title' => 'Dashboard'];
        return view('admin/dashboard/dashboard', $data);
    }
}
