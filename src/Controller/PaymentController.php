<?php

namespace App\Controller;

use App\DTO\Request\Payment\CreatePaymentDTO;
use App\DTO\Response\Payment\PaymentResponseDTO;
use App\Service\PaymentService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;
use App\Service\UserService;
class PaymentController extends AbstractController
{
    private const PAYMENT_ROUTE = '/payments/{id}';
    public function __construct(
        private PaymentService $paymentService,
        private JWTService $jWTService,
        private UserService $userService,
    ) {}

    #[Route('/payments', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $user = $this->userService->getUserById((int)$data['userId']);
        if (!$user) {
            return $this->json(['message' => 'User not found'], Response::HTTP_NOT_FOUND);
        }
        if ($data['paymentMethod'] !== 'Credit Card' && $data['paymentMethod'] !== 'Debit Card' && $data['paymentMethod'] !== 'PayPal' && $data['paymentMethod'] !== 'Cash') {
            return $this->json(['message' => 'Phương thức thanh toán không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new CreatePaymentDTO(userId: $user, bookingId: $data['bookingId'], paymentMethod: $data['paymentMethod']);
        $payment = $this->paymentService->createPayment($dto);

        return $this->json((new PaymentResponseDTO($payment))->toArray(), Response::HTTP_CREATED);
    }
    #[Route('/payments/bulk', methods: ['GET'])]
    public function bulkRead(Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $admin = $this->checkAdminRole($request);
        $userId = $this->jWTService->getIdFromToken($token);
        $payments = $this->paymentService->getAllPayments();
        $response = [];
        if ($admin){
            foreach ($payments as $payment) {
                $response[] = (new PaymentResponseDTO($payment))->toArray();
            }
            return $this->json($response);
        }
        foreach ($payments as $payment) {
            if ($payment->getUserId() == $userId) {
                $response[] = (new PaymentResponseDTO($payment))->toArray();
            }
        }
        return $this->json($response);
    }
    #[Route(self::PAYMENT_ROUTE, methods: ['GET'])]
    public function read(int $id, Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        $userId = $this->jWTService->getIdFromToken($token);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $admin = $this->checkAdminRole($request);
        $payment = $this->paymentService->getPaymentById($id);

        if (!$payment) {
            return $this->json(['message' => 'Payment not found'], Response::HTTP_NOT_FOUND);
        }
        if ($admin || $payment->getUser()->getId() === $userId) {
            return $this->json((new PaymentResponseDTO($payment))->toArray());
        }
        return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
    }
    #[Route(self::PAYMENT_ROUTE, methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $this->paymentService->deletePayment($id);

        return $this->json(['message' => 'Payment deleted successfully'], Response::HTTP_OK);
    }

    private function checkAdminRole(Request $request)
    {
       // Tách token từ header
       list($token, $check) = $this->checkAuthor($request);

       // Kiểm tra role
       if (!$this->jWTService->isAdmin($token)) {
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
