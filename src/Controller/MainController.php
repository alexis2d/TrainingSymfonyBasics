<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Attribute\Route;

final class MainController extends AbstractController
{
    #[Route('/', name: 'app_main_index', methods: [Request::METHOD_GET])]
    public function index(Request $request): Response
    {
        $name = $request->query->getString('name', 'World');
        $html = sprintf('
        <html>
            <body>
                <div>
                    <p>Hello <strong>%s</strong></p>
                </div>
            </body>
        </html>
        ',$name);
        return new Response($html);
    }

    #[Route('/contact', name: 'app_main_contact', methods: [Request::METHOD_GET])]
    public function contact(): Response
    {
        return new Response('Contact : contact@contact.com');
    }
}
