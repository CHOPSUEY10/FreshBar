<?php

namespace App\Controllers;

use App\Models\UserModel;

class StaffController extends BaseController
{
    public function index()
    {
        $model = new UserModel();

        return view('staff/index', [
            'title' => 'Data Staff Gudang',
            'staffs' => $model->where('role', 'staff')->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('staff/form', [
            'title' => 'Tambah Staff',
            'staff' => null,
        ]);
    }

    public function store()
    {
        $model = new UserModel();

        $model->insert([
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
            'password' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role' => 'staff',
        ]);

        return redirect()->to(site_url('admin/staff'))->with('success', 'Staff berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new UserModel();

        return view('staff/form', [
            'title' => 'Edit Staff',
            'staff' => $model->find($id),
        ]);
    }

    public function update($id)
    {
        $model = new UserModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'username' => $this->request->getPost('username'),
        ];

        if ($this->request->getPost('password')) {
            $data['password'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $model->update($id, $data);

        return redirect()->to(site_url('admin/staff'))->with('success', 'Staff berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new UserModel();
        $model->delete($id);

        return redirect()->to(site_url('admin/staff'))->with('success', 'Staff berhasil dihapus.');
    }
}