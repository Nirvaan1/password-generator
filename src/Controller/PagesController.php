<?php

namespace App\Controller;

use App\Service\PasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_pages')]
    public function index(): Response
    {
        return $this->render('pages/home.html.twig');
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request): Response
    {
        $length = $request->query->getInt('length');
        $digits = $request->query->getBoolean('digits');
        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $specialCharacters = $request->query->getBoolean('special_characters');

        $passwordGenerator = new PasswordGenerator;
        $password = $passwordGenerator->generate($length, $digits, $uppercaseLetters, $specialCharacters);

        return $this->render('pages/password.html.twig', [
            'password' => $password,
        ]);
    }

}
