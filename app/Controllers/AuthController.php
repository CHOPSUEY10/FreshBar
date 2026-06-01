<?php

namespace App\Controllers;

use App\Models\UserModel;

class AuthController extends BaseController
{
    public function login()
    {
        if (session()->get('logged_in')) {
            return redirect()->to(site_url('dashboard'));
        }

        if (strtolower($this->request->getMethod()) === 'post') {
            $username = trim($this->request->getPost('username'));
            $password = trim($this->request->getPost('password'));

            if ($username === '' || $password === '') {
                return redirect()->back()
                    ->withInput()
                    ->with('error', 'Username dan password wajib diisi.');
            }

            $userModel = new UserModel();

            $user = $userModel
                ->where('username', $username)
                ->first();

            if ($user && password_verify($password, $user['password'])) {
                session()->set([
                    'user_id'   => $user['id'],
                    'name'      => $user['name'],
                    'username'  => $user['username'],
                    'role'      => $user['role'],
                    'logged_in' => true,
                ]);

                return view('animations/login_success', [
                    'title'       => 'Freshbar',
                    'redirectUrl' => site_url('dashboard'),
                ]);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', 'Username atau password salah.');
        }

        return view('auth/login', [
            'title' => 'Login Freshbar',
        ]);
    }

    public function logout()
    {
        session()->destroy();

        return view('animations/logout_success', [
            'title'       => 'Freshbar',
            'redirectUrl' => site_url('login'),
        ]);
    }
}