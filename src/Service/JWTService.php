<?php

namespace App\Service;

use Firebase\JWT\JWT;
use \Exception;

class JWTService
{
    private string $secretKey;

    public function __construct(string $secretKey)
    {
        $this->secretKey = $secretKey;
    }

    /**
     * Tạo JWT token
     *
     * @param array $userData Dữ liệu người dùng (ví dụ: username, id)
     * @return string
     */
    public function createToken(array $userData): string
    {
        $issuedAt = time();
        $expirationTime = $issuedAt + 3600;  // Token sẽ hết hạn sau 1 giờ
        $payload = [
            'iat' => $issuedAt,  // Thời điểm phát hành
            'exp' => $expirationTime,  // Thời điểm hết hạn
            'data' => $userData  // Dữ liệu người dùng
        ];

        return JWT::encode($payload, $this->secretKey, 'HS256');
    }

    /**
     * Giải mã JWT token
     *
     * @param string $token
     * @return object|array
     * @throws Exception
     */
    public function decodeToken(string $token)
    {
        try {
            return JWT::decode($token, new \Firebase\JWT\Key($this->secretKey, 'HS256'));
        } catch (Exception $e) {
            throw new Exception('Invalid token: ' . $e->getMessage());
        }
    }

    /**
     * Kiểm tra token có hợp lệ không
     *
     * @param string $token
     * @return bool
     */
    public function isTokenValid(string $token): bool
    {
        try {
            $this->decodeToken($token);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function getRoleFromToken(string $token): string
    {
        $data = $this->decodeToken($token)->data->role;
        return $data;
    }

    public function getIdFromToken(string $token): int
    {
        $data = $this->decodeToken($token)->data->id;
        return $data;
    }

    public function getUsernameFromToken(string $token): string
    {
        $data = $this->decodeToken($token)->data->username;
        return $data;
    }

    /**
     * Check role là admin
     *
     * @param string $token
     * @return bool
     */
    public function isAdmin(string $token): bool
    {
        $role = $this->getRoleFromToken($token);
        if($role == 'Admin')
        {
            return true;
        }
        return false;
    }
}
