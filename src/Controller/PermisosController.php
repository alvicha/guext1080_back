<?php

namespace App\Controller;

use App\Entity\Usuarios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class PermisosController extends AbstractController
{
    #[Route('/permisos', name: 'app_permisos')]
    public function index(): Response
    {
        return $this->render('permisos/index.html.twig', [
            'controller_name' => 'PermisosController',
        ]);
    }

    #[Route('/api/userPermissions/{id}', name: 'api_user_permissions')]
    public function getUsersPermissions(Usuarios $user): JsonResponse
    {
        try {
            $listPermissions = [];
            if (!$user) {
                return new JsonResponse(['error' => "Usuario con id introducido no encontrado"], 400);
            }

            foreach ($user->getRoles() as $role) {
                foreach ($role->getPermissions() as $permission) {
                    $listPermissions[] = $permission->getName();
                }
            }

            return new JsonResponse([
                'permissions' => $listPermissions,
            ]);
        } catch (\Exception $e) {
            return new JsonResponse([
                'mensaje' => 'Error al obtener permisos de usuario',
                'error' => $e->getMessage()
            ], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
