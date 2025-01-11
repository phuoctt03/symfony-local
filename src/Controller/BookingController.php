<?php

namespace App\Controller;

use App\DTO\Request\Booking\CreateBookingDTO;
use App\DTO\Request\Booking\UpdateBookingDTO;
use App\DTO\Request\BookingDetail\CreateBookingDetailDTO;
use App\DTO\Response\Booking\BookingResponseDTO;
use App\DTO\Response\BookingDetail\BookingDetailResponseDTO;
use App\Enum\BookingStatus;
use App\Service\ActivityService;
use App\Service\BookingDetailService;
use App\Service\BookingService;
use App\Service\ComboService;
use App\Service\FlightService;
use App\Service\HotelService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;
use App\Service\PromoService;

class BookingController extends AbstractController
{
    private const BOOKING_ROUTE = '/bookings/{id}';
    public function __construct(private BookingService $bookingService, 
    private JWTService $jWTService, 
    private BookingDetailService $bookingDetailService, 
    private FlightService $flightService,
    private HotelService $hotelService,
    private ActivityService $activityService,
    private ComboService $comboService,
    private PromoService $promoService
    ) {}

    #[Route('/bookings', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $userId = $this->jWTService->getIdFromToken($token);
        $data = json_decode($request->getContent(), true);
        $flight = null;
        $hotel = null;
        $activity = null;
        $combo = null;
        $promo = null;
        $checkInDate = null;
        $checkOutDate = null;
        if ($data['flightId']) {
            $flight = $this->flightService->getFlightById($data['flightId']);
            $flightId = $flight;
        } else {
            $flightId = null;
        }
        if ($data['hotelId']) {
            $hotel = $this->hotelService->getHotelById($data['hotelId']);
            $hotelId = $hotel;
        } else {
            $hotelId = null;
        }
        if ($data['activityId']) {
            $activity = $this->activityService->getActivityById($data['activityId']);
            $activityId = $activity;
        } else {
            $activityId = null;
        }
        if ($data['comboId']) {
            $combo = $this->comboService->getComboById($data['comboId']);
            $comboId = $combo;
        } else {
            $comboId = null;
        }
        if ($data['promoId']) {
            $promo = $this->promoService->getPromoById($data['promoId']);
            $promoId = $promo;
        } else {
            $promoId = null;
        }
        if ($comboId && ($flightId || $hotelId || $activityId)) {
            return $this->json(['message' => 'Combo không thể book cùng lúc với flight, hotel hoặc activity'], Response::HTTP_BAD_REQUEST);
        }
        if (!$comboId && !$flightId && !$hotelId && !$activityId) {
            return $this->json(['message' => 'Phải chọn ít nhất 1 trong các loại: combo, flight, hotel hoặc activity'], Response::HTTP_BAD_REQUEST);
        }
        if ($data['flightId'] && !$flight) {
            return $this->json(['message' => 'Flight không tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        if ($data['hotelId'] && !$hotel) {
            return $this->json(['message' => 'Hotel không tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        if ($data['activityId'] && !$activity) {
            return $this->json(['message' => 'Activity không tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        if ($data['comboId'] && !$combo) {
            return $this->json(['message' => 'Combo không tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        if ($data['promoId'] && !$promo) {
            return $this->json(['message' => 'Promo không tồn tại'], Response::HTTP_BAD_REQUEST);
        }
        if ($data['quantity'] <= 0 || !is_numeric($data['quantity'])) {
            return $this->json(['message' => 'Quantity phải là số dương'], Response::HTTP_BAD_REQUEST);
        }
        $totalPrice = 0;
        if ($combo) {
            $totalPrice = $combo->getPrice() * $data['quantity'];
        } else {
            if ($flight) {
                if (!$flight->getEmptySlot()) {
                    return $this->json(['message' => 'Flight không còn chỗ trống'], Response::HTTP_BAD_REQUEST);
                }
                $totalPrice = $flight->getPrice() * $data['quantity'];
                $this->flightService->decreaseEmptySlot($flight->getFlightId());
            }
            if ($activity){
                if (!$activity->getEmptySlot()) {
                    return $this->json(['message' => 'Activity không còn chỗ trống'], Response::HTTP_BAD_REQUEST);
                }
                $totalPrice = $totalPrice + $activity->getPrice() * $data['quantity'];
                $this->activityService->decreaseEmptySlot($activity->getActivityId());
            }
            if ($hotel){
                if (!$hotel->getEmptyRoom()) {
                    return $this->json(['message' => 'Hotel không còn phòng trống'], Response::HTTP_BAD_REQUEST);
                }
                if ($data['checkInDate'] == "NaN-NaN-NaN NaN:NaN:NaN" || $data['checkOutDate'] == "NaN-NaN-NaN NaN:NaN:NaN") {
                    return $this->json(['message' => 'Ngày nhận phòng và ngày trả phòng là bắt buộc'], Response::HTTP_BAD_REQUEST);
                }
                $checkInDate = new \DateTime($data['checkInDate']);
                $checkOutDate = new \DateTime($data['checkOutDate']);
                $now = new \DateTime(date('Y-m-d H:i:s'));
                if ($checkInDate < $now) {
                    return $this->json(['message' => 'Ngày nhận phòng phải sau ngày hiện tại'], Response::HTTP_BAD_REQUEST);
                }
                if ($checkInDate > $checkOutDate) {
                    return $this->json(['message' => 'Ngày nhận phòng phải trước ngày trả phòng'], Response::HTTP_BAD_REQUEST);
                }
                $day = $checkOutDate->diff($checkInDate)->days;
                $totalPrice = $totalPrice + $hotel->getPrice()* $day* $data['quantity'];
                $this->hotelService->decreaseEmptyRoom($hotel->getHotelId());
            }
        }
        if ($promo) {
            $totalPrice = $totalPrice - $totalPrice * $promo->getDiscount() / 100;
            $this->promoService->decreaseAmount($promo->getPromoId());
        }
        $dto = new CreateBookingDTO(
            userId: $userId,
            promoId: $promoId,
            totalPrice: $totalPrice,
            status: BookingStatus::PENDING->value,
        );
        $booking = $this->bookingService->createBooking($dto);
        $dto = new CreateBookingDetailDTO(
                    bookingId: $booking->getBookingId(),
                    flightId: $flightId,
                    hotelId: $hotelId,
                    activityId: $activityId,
                    comboId: $comboId,
                    quantity: $data['quantity'],
                    checkInDate: $checkInDate,
                    checkOutDate: $checkOutDate,
                );
        $bookingDetail = $this->bookingDetailService->createBookingDetail($dto);

        return $this->json([
            'booking' => (new BookingResponseDTO($booking))->toArray(),
            'bookingDetail' => (new BookingDetailResponseDTO($bookingDetail))->toArray()
        ],
            Response::HTTP_CREATED
        );
    }
    #[Route('/bookings/bulk', methods: ['GET'])]
    public function bulkRead(Request $request): JsonResponse
    {
        list($token,$check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $admin = $this->checkAdminRole($request);
        if (!$admin) {
            $bookings = $this->bookingService->getBookingsByUserId($this->jWTService->getIdFromToken($token));
            $response = [];
            foreach ($bookings as $booking) {
                $bookingDetail = $this->bookingDetailService->getBookingDetailByBookingId($booking->getBookingId());
                $response[] = [
                    'booking' => (new BookingResponseDTO($booking))->toArray(),
                    'bookingDetail' => $bookingDetail->getBookingDetailId()
                ];
            }
            return $this->json($response);
        }
        $bookings = $this->bookingService->getAllBookings();
        $response = [];

        foreach ($bookings as $booking) {
            $bookingDetail = $this->bookingDetailService->getBookingDetailByBookingId($booking->getBookingId());
            $response[] = [
                'booking' => (new BookingResponseDTO($booking))->toArray(),
                'bookingDetail' => $bookingDetail->getBookingDetailId()
            ];
        }

        return $this->json($response);
    }
    #[Route(self::BOOKING_ROUTE, methods: ['GET'])]
    public function read(int $id, Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $admin = $this->checkAdminRole($request);
        $booking = $this->bookingService->getBookingById($id);
        if (!$booking) {
            return $this->json(['message' => 'Không tìm thấy booking'], Response::HTTP_NOT_FOUND);
        }
        $bookingDetail = $this->bookingDetailService->getBookingDetailByBookingId($id);
        $result = (new BookingResponseDTO($booking))->toArray();
        if ($admin || $result['userId'] === $this->jWTService->getIdFromToken($token)) {
            return $this->json([
                'booking' => $result,
                'bookingDetail' => $bookingDetail->getBookingDetailId()
            ]);
        }
        return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
    }
    #[Route(self::BOOKING_ROUTE, methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $admin = $this->checkAdminRole($request);
        if (!$admin) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $booking = $this->bookingService->getBookingById($id);
        $totalPrice = (isset($data['totalPrice']) && $data['totalPrice'] !== '' ) ? $data['totalPrice'] : $booking->getTotalPrice();
        if ( $totalPrice < 0 ){
            return $this->json(['message' => 'Total price phải là số dương'], Response::HTTP_BAD_REQUEST);
        }
        $status = (isset($data['status']) && $data['status'] !== '' ) ? $data['status'] : $booking->getStatus()->value;
        if ($status !== 'Pending' && $status !== 'Confirmed' && $status !== 'Cancelled') {
            return $this->json(['message' => 'Status không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new UpdateBookingDTO(
            totalPrice: $totalPrice,
            status: $status,
        );

        $booking = $this->bookingService->updateBooking($id, $dto);

        return $this->json((new BookingResponseDTO($booking))->toArray());
    }
    #[Route(self::BOOKING_ROUTE, methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $admin = $this->checkAdminRole($request);
        if (!$admin) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $this->bookingService->deleteBooking($id);

        return $this->json(['message' => 'Xóa booking thành công'], Response::HTTP_OK);
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
