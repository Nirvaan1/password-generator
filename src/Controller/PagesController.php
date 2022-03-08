<?php

namespace App\Controller;

use DateTimeImmutable;
use App\Service\PasswordGenerator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PagesController extends AbstractController
{
    #[Route('/', name: 'app_pages')]
    public function index(ParameterBagInterface $parameterBag, Request $request): Response
    {
        return $this->render('pages/home.html.twig', [
            'password_default_length' => $request->cookies->getInt('app_lenght') ?: $parameterBag->get('app.password_default_length'),
            'password_min_length' => $parameterBag->get('app.password_min_length') ,
            'password_max_length' => $parameterBag->get('app.password_max_length'),
            'digits' => $request->cookies->getBoolean('app_digits'),
            'uppercaseLetters' => $request->cookies->getBoolean('app_uppercase_letters'),
            'specialCharacters' => $request->cookies->getBoolean('app_special_characters'),
        ]);
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request, PasswordGenerator $passwordGenerator,
                                     ParameterBagInterface $parameterBag): Response
    {
        $lenght = max(
            min($request->query->getInt('length'), $this->getParameter('app.password_max_length')),
            $this->getParameter('app.password_min_length')
        );

        $digits = $request->query->getBoolean('digits');
        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $specialCharacters = $request->query->getBoolean('special_characters');

        $session = $request->getSession();

        $session->set('app.length',$lenght );
        $session->set('app.digits',$digits );
        $session->set('app.uppercase_letters',$uppercaseLetters );
        $session->set('app.special_characters',$specialCharacters );

        $password = $passwordGenerator->generate(
            length : $lenght,
            digits : $digits,
            uppercaseLetters : $uppercaseLetters,
            specialCharacters : $specialCharacters
        );


        $response = $this->render('pages/password.html.twig', [
            'password' => $password,
        ]);

        $response->headers->setCookie(new Cookie('app_lenght', $lenght, new \DateTimeImmutable('+5 years')));
        $response->headers->setCookie(new Cookie('app_digits', $digits ? '1' : '0', new \DateTimeImmutable('+5 years')));
        $response->headers->setCookie(new Cookie('app_uppercase_letters', $uppercaseLetters ? '1' : '0', new \DateTimeImmutable('+5 years')));
        $response->headers->setCookie(new Cookie('app_special_characters', $specialCharacters? '1' : '0', new \DateTimeImmutable('+5 years')));

        return $response;
    }

}
