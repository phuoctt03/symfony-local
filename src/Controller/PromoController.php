<?php

namespace App\Controller;

use App\DTO\Request\Promo\CreatePromoDTO;
use App\DTO\Request\Promo\UpdatePromoDTO;
use App\DTO\Response\Promo\PromoResponseDTO;
use App\Service\FileUploader;
use App\Service\PromoService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\JWTService;
use DateTime;
class PromoController extends AbstractController
{
    public function __construct(private PromoService $promoService, private JWTService $jWTService, private FileUploader $fileUploader) {}

    #[Route('/promos', methods: ['POST'])]
    public function create(Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = $request->request;
        $expiredDate = DateTime::createFromFormat('Y-m-d H:i:s', $data->get('expiredDate'), new \DateTimeZone('UTC'));
        if (!$expiredDate){
            return $this->json(['message' => 'Thời gian không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $file = $request->files->get('file');
        if (!$file) {
            return $this->json(['error' => 'Không tìm thấy file'], Response::HTTP_BAD_REQUEST);
        }
        $fileName = $this->fileUploader->upload($file);
        if ($data->get('discount') > 100 || $data->get('discount') < 0) {
            return $this->json(['message' => 'Discount không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        if ($data->get('amount') < 0) {
            return $this->json(['message' => 'Amount không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $conditions = $data->get('conditions');
        if ($conditions !== 'Public' && $conditions !== 'Silver' && $conditions !== 'Gold') {
            return $this->json(['message' => 'Conditions không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new CreatePromoDTO(
            name: $data->get('name'),
            imgUrl: $fileName,
            description: $data->get('description'),
            discount: $data->get('discount'),
            expiredDate: $expiredDate,
            amount: $data->get('amount'),
            conditions: $conditions
        );
        $promo = $this->promoService->createPromo($dto);

        return $this->json((new PromoResponseDTO($promo))->toArray(), Response::HTTP_CREATED);
    }

    #[Route('/promos/bulk', methods: ['GET'])]
    public function bulkRead(Request $request): JsonResponse
    {
        $this->checkAdminRole($request);
        list($token, $check) = $this->checkAuthor($request);
        if (!$check) {
            $promos = $this->promoService->getAllPromosForUser();
            $response = [];
            foreach ($promos as $promo) {
                $response[] = (new PromoResponseDTO($promo))->toArray();
            }
            return $this->json($response);
        }
        $role = $this->jWTService->getRoleFromToken($token);
        if ($role == 'user') {
            $promos = $this->promoService->getAllPromosForUser();
            $response = [];
            foreach ($promos as $promo) {
                $response[] = (new PromoResponseDTO($promo))->toArray();
            }
            return $this->json($response);
        }
        if ($role == 'silver') {
            $promos = $this->promoService->getAllPromosForSilver();
            $response = [];
            foreach ($promos as $promo) {
                $response[] = (new PromoResponseDTO($promo))->toArray();
            }
            return $this->json($response);
        }
        $promos = $this->promoService->getAllPromos();
        $response = [];

        foreach ($promos as $promo) {
            $response[] = (new PromoResponseDTO($promo))->toArray();
        }

        return $this->json($response);
    }

    private const PROMO_ROUTE = '/promos/{id}';

    #[Route(self::PROMO_ROUTE, methods: ['GET'])]
    public function read(int $id): JsonResponse
    {
        $promo = $this->promoService->getPromoById($id);

        if (!$promo) {
            return $this->json(['message' => 'Không tìm thấy khuyến mãi'], Response::HTTP_NOT_FOUND);
        }

        return $this->json((new PromoResponseDTO($promo))->toArray());
    }

    #[Route(self::PROMO_ROUTE, methods: ['PATCH'])]
    public function update(int $id, Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $data = json_decode($request->getContent(), true);
        $promo = $this->promoService->getPromoById($id);
        $expiredDate = $promo->getExpiredDate();
        if (isset($data['expiredDate']) && $data['expiredDate'] !== 'NaN-NaN-NaN NaN:NaN:NaN') {
            $expiredDate = DateTime::createFromFormat('Y-m-d H:i:s', $data['expiredDate'], new \DateTimeZone('UTC'));
            $now = DateTime::createFromFormat('Y-m-d H:i:s', date('Y-m-d H:i:s'), new \DateTimeZone('UTC'));
            if ($expiredDate < $now || !$expiredDate) {
                return $this->json(['message' => 'Thời gian hết hạn không hợp lệ'], Response::HTTP_BAD_REQUEST);
            }
        }
        $name = (isset($data['name']) && $data['name'] !== '' ) ? $data['name'] : $promo->getName();
        $description = (isset($data['description']) && $data['description'] !== '' ) ? $data['description'] : $promo->getDescription();
        $discount = (isset($data['discount']) && $data['discount'] !== '' ) ? $data['discount'] : $promo->getDiscount();
        $amount = (isset($data['amount']) && $data['amount'] !== '' ) ? $data['amount'] : $promo->getAmount();
        $conditions = (isset($data['conditions']) && $data['conditions'] !== '' ) ? $data['conditions'] : $promo->getConditions()->value;
        if ($discount > 100 || $discount < 0) {
            return $this->json(['message' => 'Discount không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        if ($amount < 0) {
            return $this->json(['message' => 'Amount không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        if ($conditions !== 'Public' && $conditions !== 'Silver' && $conditions !== 'Gold') {
            return $this->json(['message' => 'Conditions không hợp lệ'], Response::HTTP_BAD_REQUEST);
        }
        $dto = new UpdatePromoDTO(
            name: $name,
            description: $description,
            discount: $discount,
            expiredDate: $expiredDate,
            amount: $amount,
            conditions: $conditions
        );

        $promo = $this->promoService->updatePromo($id, $dto);

        return $this->json((new PromoResponseDTO($promo))->toArray());
    }

    #[Route(self::PROMO_ROUTE, methods: ['DELETE'])]
    public function delete(int $id, Request $request): JsonResponse
    {
        $check = $this->checkAdminRole($request);
        if (!$check) {
            return $this->json(['message' => 'Không đủ quyền'], Response::HTTP_UNAUTHORIZED);
        }
        $this->promoService->deletePromo($id);

        return $this->json(['message' => 'Xóa khuyến mãi thành công'], Response::HTTP_OK);
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
