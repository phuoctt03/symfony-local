<?php

namespace App\Controller;

use App\DTO\Request\ComboDetail\CreateComboDetailDTO;
use App\DTO\Request\ComboDetail\UpdateComboDetailDTO;
use App\DTO\Response\Activity\ActivityResponseDTO;
use App\DTO\Response\Combo\ComboResponseDTO;
use App\DTO\Response\ComboDetail\ComboDetailResponseDTO;
use App\DTO\Response\Flight\FlightResponseDTO;
use App\DTO\Response\Hotel\HotelResponseDTO;
use App\Service\ComboDetailService;
use App\Service\FlightService;
use App\Service\HotelService;
use App\Service\ActivityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;

class ComboDetailController extends AbstractController
{
    private const COMBO_DETAIL_ROUTE = '/combo-details/{id}';
    public function __construct(
        private ComboDetailService $comboDetailService, 
        private JWTService $jWTService,
        private FlightService $flightService,
        private HotelService $hotelService,
        private ActivityService $activityService,
    ) {}

    #[Route('/combo-details/bulk', methods: ['GET'])]
    public function bulkRead(): JsonResponse
    {
        $comboDetails = $this->comboDetailService->getAllComboDetails();
        $response = [];

        foreach ($comboDetails as $comboDetail) {
            $response[] = [
                'comboDetailId' => $comboDetail->getComboDetailId(),
                'combo' => $comboDetail->getCombo() ? (new ComboResponseDTO($comboDetail->getCombo()))->toArray() : null,
                'flight' => $comboDetail ? (new FlightResponseDTO($comboDetail->getFlight()))->toArray() : null,
                'hotel' => $comboDetail ? (new HotelResponseDTO($comboDetail->getHotel()))->toArray() : null,
                'activity' => $comboDetail ? (new ActivityResponseDTO($comboDetail->getActivity()))->toArray() : null,
                'checkInDate' => $comboDetail->getCheckInDate(),
                'checkOutDate' => $comboDetail->getCheckOutDate(),
            ];
        }

        return $this->json($response);
    }
    #[Route(self::COMBO_DETAIL_ROUTE, methods: ['GET'])]
    public function read(int $id): JsonResponse
    {
        $comboDetail = $this->comboDetailService->getComboDetailById($id);

        if (!$comboDetail) {
            return $this->json(['message' => 'Combo Detail not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json([
                'comboDetailId' => $comboDetail->getComboDetailId(),
                'combo' => $comboDetail->getCombo() ? (new ComboResponseDTO($comboDetail->getCombo()))->toArray() : null,
                'flight' => $comboDetail ? (new FlightResponseDTO($comboDetail->getFlight()))->toArray() : null,
                'hotel' => $comboDetail ? (new HotelResponseDTO($comboDetail->getHotel()))->toArray() : null,
                'activity' => $comboDetail ? (new ActivityResponseDTO($comboDetail->getActivity()))->toArray() : null,
                'checkInDate' => $comboDetail->getCheckInDate(),
                'checkOutDate' => $comboDetail->getCheckOutDate(),
            ]);
    }
    #[Route(self::COMBO_DETAIL_ROUTE, methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $comboDetail = $this->comboDetailService->getComboDetailById($id);
        $checkInDate = $comboDetail->getCheckInDate();
        $checkOutDate = $comboDetail->getCheckOutDate();
        $checkTime = (isset($data['checkInDate']) && $data['checkInDate'] !== 'NaN-NaN-NaN NaN:NaN:NaN' && isset($data['checkOutDate']) && $data['checkOutDate'] !== 'NaN-NaN-NaN NaN:NaN:NaN') ? true : false;
        if ($checkTime) {
            $checkInDate = new \DateTime($data['checkInDate']);
            $checkOutDate = new \DateTime($data['checkOutDate']);
            $now = new \DateTime(date('Y-m-d H:i:s'));
            if ( $checkInDate < $now || $checkOutDate < $now || $checkInDate > $checkOutDate || !$checkInDate || !$checkOutDate) {
                return $this->json(['message' => 'Invalid date'], Response::HTTP_BAD_REQUEST);
            }
        }
        $flightId = (isset($data['flightId']) && $data['flightId'] !== '' ) ? $data['flightId'] : $comboDetail->getFlight()->getFlightId();
        $hotelId = (isset($data['hotelId']) && $data['hotelId'] !== '' ) ? $data['hotelId'] : $comboDetail->getHotel()->getHotelId();
        $activityId = (isset($data['activityId']) && $data['activityId'] !== '' ) ? $data['activityId'] : $comboDetail->getActivity()->getActivityId();
        $flight = $this->flightService->getFlightById($flightId);
        if (!$flight) {
            return $this->json(['message' => 'Không tìm thấy flight'], Response::HTTP_NOT_FOUND);
        }
        $hotel = $this->hotelService->getHotelById($hotelId);
        if (!$hotel) {
            return $this->json(['message' => 'Không tìm thấy hotel'], Response::HTTP_NOT_FOUND);
        }
        $activity = $this->activityService->getActivityById($activityId);
        if (!$activity) {
            return $this->json(['message' => 'Không tìm thấy activity'], Response::HTTP_NOT_FOUND);
        }
        $dto = new UpdateComboDetailDTO(
            flightId: $flightId,
            hotelId: $hotelId,
            activityId: $activityId,
            checkInDate: $checkInDate,
            checkOutDate: $checkOutDate,
        );

        $comboDetail = $this->comboDetailService->updateComboDetail($id, $dto);

        return $this->json([
                'comboDetailId' => $comboDetail->getComboDetailId(),
                'combo' => $comboDetail->getCombo() ? (new ComboResponseDTO($comboDetail->getCombo()))->toArray() : null,
                'flight' => $comboDetail ? (new FlightResponseDTO($comboDetail->getFlight()))->toArray() : null,
                'hotel' => $comboDetail ? (new HotelResponseDTO($comboDetail->getHotel()))->toArray() : null,
                'activity' => $comboDetail ? (new ActivityResponseDTO($comboDetail->getActivity()))->toArray() : null,
                'checkInDate' => $comboDetail->getCheckInDate(),
                'checkOutDate' => $comboDetail->getCheckOutDate(),
            ]);
    }

    private function checkAdminRole(Request $request)
    {
       // Lấy header Authorization
       $authorizationHeader = $request->headers->get('Authorization');
       $check = true;
       // Kiểm tra nếu không có header hoặc header không đúng định dạng
       if (!$authorizationHeader || !preg_match('/Bearer\s(\S+)/', $authorizationHeader, $matches)) {
           $check = false;
       }
       // Tách token từ header
       $token = $matches[1];

       // Kiểm tra role
       if (!$this->jWTService->isAdmin($token)) {
           $check = false;
       }
       return $check;
    }
}
