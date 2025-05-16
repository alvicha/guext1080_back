<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Attribute\Route;

final class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact')]
    public function index(): Response
    {
        return $this->render('contact/index.html.twig', [
            'controller_name' => 'ContactController',
        ]);
    }

    #[Route('/api/sendTemplateEmail', name: 'send_template_email', methods: ['POST'])]
    public function sendTemplateEmail(Request $request, MailerInterface $mailer): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
        if (!isset($data['to']) || !isset($data['subject']) || !isset($data['html'])) {
            return new JsonResponse(['error' => 'Faltan datos'], 400);
        }

        try {
            $email = (new Email())
                ->from('avchaparro04@gmail.com')
                ->to($data['to'])
                ->subject($data['subject'])
                ->html($data['html']);

            $mailer->send($email);
            return new JsonResponse(['status' => 'Correo enviado correctamente']);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => 'Error al enviar el correo: ' . $e->getMessage()], 500);
        }
    }
}
