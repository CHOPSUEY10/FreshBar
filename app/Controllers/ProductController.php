<?php

namespace App\Controllers;

use App\Models\ProductModel;

class ProductController extends BaseController
{
    public function index()
    {
        $model = new ProductModel();

        return view('products/index', [
            'title' => 'Data Produk',
            'products' => $model->orderBy('id', 'DESC')->findAll(),
        ]);
    }

    public function create()
    {
        return view('products/form', [
            'title' => 'Tambah Produk',
            'product' => null,
        ]);
    }

    public function store()
    {
        $model = new ProductModel();

        $model->insert([
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'unit' => $this->request->getPost('unit'),
            'shelf_life_days' => $this->request->getPost('shelf_life_days'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to(site_url('admin/products'))->with('success', 'Produk berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $model = new ProductModel();

        return view('products/form', [
            'title' => 'Edit Produk',
            'product' => $model->find($id),
        ]);
    }

    public function update($id)
    {
        $model = new ProductModel();

        $model->update($id, [
            'name' => $this->request->getPost('name'),
            'type' => $this->request->getPost('type'),
            'unit' => $this->request->getPost('unit'),
            'shelf_life_days' => $this->request->getPost('shelf_life_days'),
            'description' => $this->request->getPost('description'),
        ]);

        return redirect()->to(site_url('admin/products'))->with('success', 'Produk berhasil diperbarui.');
    }

    public function delete($id)
    {
        $model = new ProductModel();
        $model->delete($id);

        return redirect()->to(site_url('admin/products'))->with('success', 'Produk berhasil dihapus.');
    }
}