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
        $rules = [
            'name'           => 'required|max_length[100]',
            'type'           => 'permit_empty|max_length[50]',
            'unit'           => 'permit_empty|max_length[20]',
            'price'          => 'required|integer|greater_than_equal_to[0]',
            'shelf_life_days'=> 'required|integer|greater_than[0]',
            'description'    => 'permit_empty',
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', implode(' ', $this->validator->getErrors()));
        }

        $model = new ProductModel();

        $model->update($id, [
            'name'            => $this->request->getPost('name'),
            'type'            => $this->request->getPost('type'),
            'unit'            => $this->request->getPost('unit'),
            'price'           => (int) $this->request->getPost('price'),
            'shelf_life_days' => (int) $this->request->getPost('shelf_life_days'),
            'description'     => $this->request->getPost('description'),
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