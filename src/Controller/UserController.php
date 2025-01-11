<?php

namespace App\Controller;

use App\Service\JWTService;
use App\DTO\Request\User\CreateUserDTO;
use App\DTO\Request\User\UpdateUserDTO;
use App\DTO\Response\User\UserResponseDTO;
use App\Enum\UserRole;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    public function __construct(private UserService $userService, private JWTService $jwtService) {}

    #[Route('/signup', methods: ['POST'])]
    public function createUser(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        $check = $this->findByUsername($data['username']);
        if ($check) {
            return $this->json(['message' => 'Username đã tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        $check1 = $this->findByEmail($data['email']);
        if ($check1) {
            return $this->json(['message' => 'Email đã tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new CreateUserDTO(
            username: $data['username'],
            password: $data['password'],
            email: $data['email'],
            phone: $data['phone'] ?? null,
            address: $data['address'] ?? null,
            role: UserRole::USER->value
        );

        $user = $this->userService->createUser($dto);
        return $this->json(new UserResponseDTO($user), Response::HTTP_CREATED);
    }

    #[Route('/users/bulk', methods: ['GET'])]
    public function getAllUsers(Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $users = $this->userService->getAllUsers();
        $response = [];

        foreach ($users as $user) {
            $response[] = (new UserResponseDTO($user))->toArray();
        }

        return $this->json($response);
    }

    private const USER_ROUTE = '/users/{id}';

    #[Route(self::USER_ROUTE, methods: ['GET'])]
    public function getUserById(Request $request ,int $id): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return $this->json(['message' => 'Không tìm thấy người dùng'], Response::HTTP_NOT_FOUND);
        }

        return $this->json((new UserResponseDTO($user))->toArray());
    }

    #[Route('/users', methods: ['GET'])]
    public function getMe(Request $request): JsonResponse
    {
        list($token,$check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_FORBIDDEN);
        }
        $id = $this->jwtService->getIdFromToken($token);
        $user = $this->userService->getUserById($id);

        if (!$user) {
            return $this->json(['message' => 'Không tìm thấy người dùng'], Response::HTTP_NOT_FOUND);
        }

        return $this->json((new UserResponseDTO($user))->toArray());
    }

    #[Route(self::USER_ROUTE, methods: ['PATCH'])]
    public function updateUser(int $id, Request $request): JsonResponse
    {
        [$token, $isAuthenized] = $this->checkAuthor($request);
        if (!$isAuthenized) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_FORBIDDEN);
        }

        $isAdmin = $this->checkAdminRole($request);
        $userId = $this->jwtService->getIdFromToken($token);

        if (!$isAdmin && $userId != $id) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->getUserById($id);
        $role = (isset($data['role']) && $data['role'] !== '' ) ? UserRole::from($data['role']) : $user->getRole();
        $phone = (isset($data['phone']) && $data['phone'] !== '' ) ? $data['phone'] : $user->getPhone();
        $address = (isset($data['address']) && $data['address'] !== '' ) ? $data['address'] : $user->getAddress();
        $username = (isset($data['username']) && $data['username'] !== '' ) ? $data['username'] : $user->getUsername();
        $password = isset($data['password']) ? $data['password'] : $user->getPassword();
        $email = (isset($data['email']) && $data['email'] !== '' ) ? $data['email'] : $user->getEmail();
        $newUsername = $this->findByUsername($username);
        if ($newUsername && $newUsername->getId() != $id) {
            return $this->json(['message' => 'Username đã tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        $newEmail = $this->findByEmail($email);
        if ($newEmail && $newEmail->getId() != $id) {
            return $this->json(['message' => 'Email đã tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        if (!$isAdmin) {
            $role = $user->getRole();
        }
        if ($role !== 'User' && $role !== 'Admin' && $role !== 'Silver' && $role !== 'Gold') {
            return $this->json(['message' => 'Role không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new UpdateUserDTO(
            username: $username,
            password: $password,
            email: $email,
            phone: $phone,
            address: $address,
            role: $role->value
        );

        $user = $this->userService->updateUser($id, $dto);

        return $this->json(new UserResponseDTO($user));
    }

    #[Route(self::USER_ROUTE, methods: ['DELETE'])]
    public function deleteUser(int $id, Request $request): JsonResponse
    {
        list($token, $isAuthenized) = $this->checkAuthor($request);
        if (!$isAuthenized) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_FORBIDDEN);
        }

        $isAdmin = $this->checkAdminRole($request);
        $userId = $this->jwtService->getIdFromToken($token);

        if (!$isAdmin && $userId != $id) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $this->userService->deleteUser($id);

        return $this->json(['message' => 'Xóa người dùng thành công'], Response::HTTP_OK);
    }

    #[Route('/login', methods: ['POST'])]
    public function login(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (empty($data['username']) || empty($data['password'])) {
            return $this->json(['message' => 'Username or password không được để trống'], Response::HTTP_BAD_REQUEST);
        }
        $user = $this->userService->getUserByUsername($data['username']);
        if ( !$user || !$this->userService->isPasswordValid($user, $data['password']) ) {
            return $this->json(['message' => 'Tài khoản hoặc mật khẩu không đúng'], Response::HTTP_BAD_REQUEST);
        }

        $userData = [
            'id' => $user->getId(),
            'username' => $user->getUsername(),
            'role' => $user->getRole()
        ];

        // Tạo JWT token
        $token = $this->jwtService->createToken($userData);

        // Trả về token
        return $this->json([
            'message' => 'Đăng nhập thành công',
            'token' => $token,
        ]);
    }

    private function checkAdminRole(Request $request)
    {
       // Tách token từ header
       list($token, $check) = $this->checkAuthor($request);

       // Kiểm tra role
       if (!$this->jwtService->isAdmin($token)) {
            $check = false;
       }
       return $check;
    }
    

    private function checkAuthor(Request $request){
        $authorizationHeader = $request->headers->get('Authorization');
        $check = true;
       // Kiểm tra nếu không có header hoặc header không đúng định dạng
       if (!$authorizationHeader || !preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
            $check = false;
       }
       if (!$check) {
            $token = null;
            return [$token, $check];
        }
       return [$matches[1], $check];
    }
}
