<?php

declare(strict_types=1);

namespace App\Controller\Web;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class AdminController extends AbstractController
{
    #[Route('/')]
    public function main(): RedirectResponse
    {
        return $this->redirect('/admin');
    }

    #[Route('/admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }
}
