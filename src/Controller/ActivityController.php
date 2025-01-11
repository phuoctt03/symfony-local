<?php

namespace App\Controller;

use App\Service\FileUploader;
use App\Service\JWTService;
use App\DTO\Request\Activity\CreateActivityDTO;
use App\DTO\Request\Activity\UpdateActivityDTO;
use App\DTO\Response\Activity\ActivityResponseDTO;
use App\Service\ActivityService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ActivityController extends AbstractController
{
    public function __construct(private ActivityService $activityService, private JWTService $jWTService, private FileUploader $fileUploader) {}

    #[Route('/activities', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = $request->request;
        $file = $request->files->get('file');

        if (!$file){
            return $this->json(['error' => 'Không tìm thấy file'], Response::HTTP_BAD_REQUEST);
        }
        $fileName = $this->fileUploader->upload($file);
        if ($data->get('price') < 0) {
            return $this->json(['message' => 'Giá không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        if ($data->get('emptySlot') < 0) {
            return $this->json(['message' => 'Số chỗ trống không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        if ($data->get('name') === '' || $data->get('location') === '' || $data->get('description') === '') {
            return $this->json(['message' => 'Dữ liệu không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new CreateActivityDTO(
            imgUrl: $fileName,
            name: $data->get('name'),
            emptySlot: $data->get('emptySlot'),
            location: $data->get('location'),
            description: $data->get('description'),
            price: $data->get('price'),
        );
        
        $activity = $this->activityService->createActivity($dto);

        return $this->json((new ActivityResponseDTO($activity))->toArray(), Response::HTTP_CREATED);
    }

    #[Route('/activities/bulk', methods: ['GET'])]
    public function bulkRead(): JsonResponse
    {
        $activities = $this->activityService->getAllActivities();
        $response = [];

        foreach ($activities as $activity) {
            $response[] = (new ActivityResponseDTO($activity))->toArray();
        }

        return $this->json($response);
    }

    #[Route('/activities/{id}', methods: ['GET'])]
    public function read(int $id): JsonResponse
    {
        $activity = $this->activityService->getActivityById($id);
    
        if (!$activity) {
            return $this->json(['message' => 'Không tìm thấy hoạt động'], Response::HTTP_NOT_FOUND);
        }
    
        return $this->json((new ActivityResponseDTO($activity))->toArray());
    }

    #[Route('/activities/{id}', methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $activity = $this->activityService->getActivityById($id);
        $name = (isset($data['name']) && $data['name'] !== '' ) ? $data['name'] : $activity->getName();
        $emptySlot = (isset($data['emptySlot']) && $data['emptySlot'] !== '' ) ? $data['emptySlot'] : $activity->getEmptySlot();
        $location = (isset($data['location']) && $data['location'] !== '' ) ? $data['location'] : $activity->getLocation();
        $description = (isset($data['description']) && $data['description'] !== '' ) ? $data['description'] : $activity->getDescription();
        $price = (isset($data['price']) && $data['price'] !== '' ) ? $data['price'] : $activity->getPrice();
        if ($price < 0) {
            return $this->json(['message' => 'Giá không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        if ($emptySlot < 0) {
            return $this->json(['message' => 'Số chỗ trống không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new UpdateActivityDTO(
            name: $name,
            emptySlot: $emptySlot,
            location: $location,
            description: $description,
            price: $price,
        );

        $activity = $this->activityService->updateActivity($id, $dto);

        return $this->json((new ActivityResponseDTO($activity))->toArray());
    }

    #[Route('/activities/{id}', methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $this->activityService->deleteActivity($id);

        return $this->json(['message' => 'Xóa hoạt động thành công']);
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
