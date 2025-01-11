<?php

namespace App\Controller;

use App\DTO\Request\Feedback\CreateFeedbackDTO;
use App\DTO\Request\Feedback\UpdateFeedbackDTO;
use App\DTO\Response\Feedback\FeedbackResponseDTO;
use App\Service\FeedbackService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;

class FeedbackController extends AbstractController
{
    private const FEEDBACK_ROUTE = '/feedbacks/{id}';
    public function __construct(private FeedbackService $feedbackService, private JWTService $jWTService) {}

    #[Route('/feedbacks', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $userId = $this->jWTService->getIdFromToken($token);
        $data = json_decode($request->getContent(), true);
        if ($data['rating'] < 0 || $data['rating'] > 5) {
            return $this->json(['message' => 'Đánh giá phải từ 0 đến 5'], Response::HTTP_BAD_REQUEST);
        }
        if ($data['ratedType'] !== 'Hotel' && $data['ratedType'] !== 'Flight' && $data['ratedType'] !== 'Activity' && $data['ratedType'] !== 'Combo') {
            return $this->json(['message' => 'Loại đánh giá không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new CreateFeedbackDTO(
            userId: $userId,
            ratedType: $data['ratedType'],
            relatedId: $data['relatedId'],
            rating: $data['rating'],
            comment: $data['comment']
        );
        $feedback = $this->feedbackService->createFeedback($dto);

        return $this->json((new FeedbackResponseDTO($feedback))->toArray(), Response::HTTP_CREATED);
    }
    #[Route('/feedbacks/bulk', methods: ['GET'])]
    public function bulkRead(): JsonResponse
    {
        $feedbacks = $this->feedbackService->getAllFeedbacks();
        $response = [];

        foreach ($feedbacks as $feedback) {
            $response[] = (new FeedbackResponseDTO($feedback))->toArray();
        }

        return $this->json($response);
    }
    #[Route('/feedbacks/hotel/{id}', methods: ['GET'])]
    public function getFeedbacksByHotel(int $id): JsonResponse
    {
        $hotelId = $id;
        $feedbacks = $this->feedbackService->getFeedbacksByHotel($hotelId);
        $response = [];

        foreach ($feedbacks as $feedback) {
            $response[] = (new FeedbackResponseDTO($feedback))->toArray();
        }

        return $this->json($response);
    }
    #[Route('/feedbacks/flight/{id}', methods: ['GET'])]
    public function getFeedbacksByFlight(int $id): JsonResponse
    {
        $flightId = $id;
        $feedbacks = $this->feedbackService->getFeedbacksByFlight($flightId);
        $response = [];

        foreach ($feedbacks as $feedback) {
            $response[] = (new FeedbackResponseDTO($feedback))->toArray();
        }

        return $this->json($response);
    }
    #[Route('/feedbacks/activity/{id}', methods: ['GET'])]
    public function getFeedbacksByActivity(int $id): JsonResponse
    {
        $activityId = $id;
        $feedbacks = $this->feedbackService->getFeedbacksByActivity($activityId);
        $response = [];

        foreach ($feedbacks as $feedback) {
            $response[] = (new FeedbackResponseDTO($feedback))->toArray();
        }

        return $this->json($response);
    }
    #[Route('/feedbacks/combo/{id}', methods: ['GET'])]
    public function getFeedbacksByCombo(int $id): JsonResponse
    {
        $comboId = $id;
        $feedbacks = $this->feedbackService->getFeedbacksByCombo($comboId);
        $response = [];

        foreach ($feedbacks as $feedback) {
            $response[] = (new FeedbackResponseDTO($feedback))->toArray();
        }

        return $this->json($response);
    }
    #[Route('/feedbacks/user/{id}', methods: ['GET'])]
    public function getFeedbacksByUser(int $id): JsonResponse
    {
        $userId = $id;
        $feedbacks = $this->feedbackService->getFeedbacksByUser($userId);
        $response = [];

        foreach ($feedbacks as $feedback) {
            $response[] = (new FeedbackResponseDTO($feedback))->toArray();
        }

        return $this->json($response);
    }
    #[Route(self::FEEDBACK_ROUTE, methods: ['GET'])]
    public function read(int $id): JsonResponse
    {
        $feedback = $this->feedbackService->getFeedbackById($id);

        if (!$feedback) {
            return $this->json(['message' => 'Feedback not found'], Response::HTTP_NOT_FOUND);
        }

        return $this->json(new FeedbackResponseDTO($feedback));
    }
    #[Route(self::FEEDBACK_ROUTE, methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $feedback = $this->feedbackService->getFeedbackById($id);
        if (!$feedback) {
            return $this->json(['message' => 'Không tìm thấy feedback'], Response::HTTP_NOT_FOUND);
        }
        if ($feedback->getUser()->getId() !== $this->jWTService->getIdFromToken($token)) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $feedback = $this->feedbackService->getFeedbackById($id);
        $ratedType = (isset($data['ratedType']) && $data['ratedType'] !== '' ) ? $data['ratedType'] : $feedback->getRatedType()->value;
        $relatedId = (isset($data['relatedId']) && $data['relatedId'] !== '' ) ? $data['relatedId'] : $feedback->getRelatedId();
        $rating = (isset($data['rating']) && $data['rating'] !== '' ) ? $data['rating'] : $feedback->getRating();
        $comment = (isset($data['comment']) && $data['comment'] !== '' ) ? $data['comment'] : $feedback->getComment();
        if ($rating < 0 || $rating > 5) {
            return $this->json(['message' => 'Đánh giá phải từ 0 đến 5'], Response::HTTP_BAD_REQUEST);
        }
        if ($ratedType !== 'Hotel' && $ratedType !== 'Flight' && $ratedType !== 'Activity' && $ratedType !== 'Combo') {
            return $this->json(['message' => 'Loại đánh giá không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new UpdateFeedbackDTO(
            ratedType: $ratedType,
            relatedId: $relatedId,
            rating: $rating,
            comment: $comment,
        );

        $feedback = $this->feedbackService->updateFeedback($id, $dto);

        return $this->json((new FeedbackResponseDTO($feedback))->toArray());
    }
    #[Route(self::FEEDBACK_ROUTE, methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            return $this->json(['message' => 'Bạn cần đăng nhập trước'], Response::HTTP_UNAUTHORIZED);
        }
        $admin = $this->checkAdminRole($request);
        if (!$admin || $this->jWTService->getIdFromToken($token) !== $this->feedbackService->getFeedbackById($id)->getUser()->getId()) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $this->feedbackService->deleteFeedback($id);

        return $this->json(['message' => 'Xóa feedback thành công'], Response::HTTP_OK);
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
