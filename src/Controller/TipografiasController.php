<?php

namespace App\Controller;

use App\Repository\TipografiasRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TipografiasController extends AbstractController
{
    #[Route('/tipografias', name: 'app_tipografias')]
    public function index(): Response
    {
        return $this->render('tipografias/index.html.twig', [
            'controller_name' => 'TipografiasController',
        ]);
    }

    #[Route('/api/fonts', name: 'api_fonts', methods: ['GET'])]
    public function getAllFonts(TipografiasRepository $fontRepository): JsonResponse
    {
        try {
            $fonts = $fontRepository->findAll();
            $data = [];

            foreach ($fonts as $font) {
                $data[] = [
                    'id' => $font->getId(),
                    'name' => $font->getName(),
                    'url' => $font->getUrl()
                ];
            }
            return new JsonResponse($data, Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse([
                'mensaje' => 'Error al obtener las tipografias',
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
