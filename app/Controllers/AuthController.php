<?php

namespace App\Controllers;

use App\Models\UserModel;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthController extends BaseController
{
    public function login()
    {
        // Pengecekan JWT Token cookie yang sudah ada
        $token = $this->request->getCookie('auth_token');
        if ($token) {
            try {
                $secretKey = env('jwt.secret') ?: 'FreshBarSecretJWTKeyForTokens2026!WithPlentyOfBytesForSecurity';
                $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));
                
                // Token valid, injeksikan ke sesi dan langsung arahkan ke dashboard
                session()->set([
                    'user_id'   => $decoded->user_id,
                    'name'      => $decoded->name,
                    'username'  => $decoded->username,
                    'role'      => $decoded->role,
                    'logged_in' => true,
                ]);
                return redirect()->to(site_url('dashboard'));
            } catch (Exception $e) {
                // Token tidak valid, hapus cookie dan biarkan user mengisi form login
                setcookie('auth_token', '', time() - 3600, '/', '', false, true);
            }
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
                // Definisikan secret key dan masa kedaluwarsa JWT
                $secretKey = env('jwt.secret') ?: 'FreshBarSecretJWTKeyForTokens2026!WithPlentyOfBytesForSecurity';
                $expire = env('jwt.expire') ?: 3600;
                $issuedAt = time();
                $expireTime = $issuedAt + $expire;

                $payload = [
                    'iss' => base_url(),
                    'aud' => base_url(),
                    'iat' => $issuedAt,
                    'exp' => $expireTime,
                    'user_id' => $user['id'],
                    'name' => $user['name'],
                    'username' => $user['username'],
                    'role' => $user['role'],
                ];

                // Encode JWT Token
                $token = JWT::encode($payload, $secretKey, 'HS256');

                // Simpan JWT di HTTP-Only Cookie yang aman (selama masa kedaluwarsa)
                setcookie('auth_token', $token, $expireTime, '/', '', false, true);

                // Set sesi lokal
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

        // Hapus/kedaluwarsakan cookie auth_token JWT
        setcookie('auth_token', '', time() - 3600, '/', '', false, true);

        return view('animations/logout_success', [
            'title'       => 'Freshbar',
            'redirectUrl' => site_url('login'),
        ]);
    }
}