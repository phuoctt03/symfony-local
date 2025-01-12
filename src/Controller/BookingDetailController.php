<?php

namespace App\Controller;

use App\DTO\Request\BookingDetail\UpdateBookingDetailDTO;
use App\DTO\Response\BookingDetail\BookingDetailResponseDTO;
use App\Service\BookingDetailService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;

class BookingDetailController extends AbstractController
{
    private const BOOKING_DETAIL_ROUTE = '/booking-details/{id}';
    public function __construct(private BookingDetailService $bookingDetailService, private JWTService $jWTService) {}

    #[Route('/booking-details/bulk', methods: ['GET'])]
    public function bulkRead(Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $admin = $this->checkAdminRole($request);
        $userId = $this->jWTService->getIdFromToken($token);
        $bookingDetails = $this->bookingDetailService->getAllBookingDetails();
        $response = [];
        if (!$admin) {
            foreach ($bookingDetails as $bookingDetail) {
                if ($userId === $bookingDetail->getBooking()->getUser()->getId()) {
                    $response[] = (new BookingDetailResponseDTO($bookingDetail))->toArray();
                }
            }
            return $this->json($response);
        }
        foreach ($bookingDetails as $bookingDetail) {
            $response[] = (new BookingDetailResponseDTO($bookingDetail))->toArray();
        }

        return $this->json($response);
    }
    #[Route(self::BOOKING_DETAIL_ROUTE, methods: ['GET'])]
    public function read(int $id, Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $userId = $this->jWTService->getIdFromToken($token);
        $admin = $this->checkAdminRole($request);
        $bookingDetail = $this->bookingDetailService->getBookingDetailById($id);
        if (!$admin && $userId !== $bookingDetail->getBooking()->getUser()->getId()) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        if (!$bookingDetail) {
            return $this->json(['message' => 'Booking Detail not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json((new BookingDetailResponseDTO($bookingDetail))->toArray());
    }
    #[Route(self::BOOKING_DETAIL_ROUTE, methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $bookingDetail = $this->bookingDetailService->getBookingDetailById($id);
        $quantity = (isset($data['quantity']) && $data['quantity'] !== '') ? $data['quantity'] : $bookingDetail->getQuantity();
        $checkInDate = $bookingDetail->getCheckInDate();
        $checkOutDate = $bookingDetail->getCheckOutDate();
        $checkTime = (isset($data['checkInDate']) && $data['checkInDate'] !== 'NaN-NaN-NaN NaN:NaN:NaN' && isset($data['checkOutDate']) && $data['checkOutDate'] !== 'NaN-NaN-NaN NaN:NaN:NaN') ? true : false;
        if ($checkTime) {
            $checkInDate = new \DateTime($data['checkInDate']);
            $checkOutDate = new \DateTime($data['checkOutDate']);
            $now = new \DateTime(date('Y-m-d H:i:s'));
            if ( $checkInDate < $now || $checkOutDate < $now || $checkInDate > $checkOutDate || !$checkInDate || !$checkOutDate) {
                return $this->json(['message' => 'Ngày không hợp lệ'], Response::HTTP_BAD_REQUEST);
            }
        }
        if ( $quantity < 0 ){
            return $this->json(['message' => 'Số lượng phải lớn hơn 0'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new UpdateBookingDetailDTO(
            quantity: $quantity,
            checkInDate: $checkInDate,
            checkOutDate: $checkOutDate
        );

        $bookingDetail = $this->bookingDetailService->updateBookingDetail($id, $dto);

        return $this->json((new BookingDetailResponseDTO($bookingDetail))->toArray());
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
