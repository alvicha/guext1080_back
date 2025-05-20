<?php

namespace App\Controller;

use App\Entity\Imagenes;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ImagenesController extends AbstractController
{
    #[Route('/imagenes', name: 'app_imagenes')]
    public function index(): Response
    {
        return $this->render('imagenes/index.html.twig', [
            'controller_name' => 'ImagenesController',
        ]);
    }

    #[Route(path: '/api/uploadImage', name: 'upload_images', methods: ['POST'])]
    public function uploadImagesTemplates(Request $request, EntityManagerInterface $entity): JsonResponse
    {
        try {
            $image = $request->files->get('file');

            if (!$image || !$image->isValid()) {
                return new JsonResponse(['error' => 'Archivo inválido o no enviado'], 400);
            }

            $uploadDir = $this->getParameter('kernel.project_dir') . '/public/uploads/img';

            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }

            $filename = uniqid() . '.' . $image->guessExtension();
            $image->move($uploadDir, $filename);
            $relativePath = '/uploads/img/' . $filename;

            $imagen = new Imagenes();
            $imagen->setPath($relativePath);

            $entity->persist($imagen);
            $entity->flush();

            return new JsonResponse([
                'mensaje' => 'Imagen subida y guardada exitosamente',
                'urlImagen' => $relativePath,
                'id' => $imagen->getId()
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'mensaje' => 'Error al insertar la imagen',
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

}
