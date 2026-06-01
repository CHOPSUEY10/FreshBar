<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Exception;

class AuthFilter implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $token = $request->getCookie('auth_token');

        if (!$token) {
            return redirect()->to(site_url('login'))->with('error', 'Silakan login terlebih dahulu.');
        }

        try {
            $secretKey = env('jwt.secret') ?: 'FreshBarSecretJWTKeyForTokens2026!WithPlentyOfBytesForSecurity';
            $decoded = JWT::decode($token, new Key($secretKey, 'HS256'));

            // Injeksikan claims JWT ke sesi untuk kompatibilitas dengan sisa aplikasi
            session()->set([
                'user_id'   => $decoded->user_id,
                'name'      => $decoded->name,
                'username'  => $decoded->username,
                'role'      => $decoded->role,
                'logged_in' => true,
            ]);
        } catch (Exception $e) {
            // Jika token tidak valid atau kedaluwarsa, bersihkan sesi dan hapus cookie
            session()->destroy();
            
            // Hapus cookie auth_token
            setcookie('auth_token', '', time() - 3600, '/', '', false, true);

            return redirect()->to(site_url('login'))->with('error', 'Sesi Anda tidak valid atau telah berakhir. Silakan login kembali.');
        }
    }

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        //
    }
}