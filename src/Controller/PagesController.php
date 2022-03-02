<?php

namespace App\Controller;

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

        $lowercaseLettersAlphabet = range('a', 'z');
        $uppercaseLettersAlphabet = range('A', 'Z');
        $digitsAlphabet = range(0, 9);
        $specialCharactersAlphabet = range('/','!');

        $finalAlphabet = $lowercaseLettersAlphabet;

        $password = '';

        // add a lowercase letter
        $password .=  $this->pickRandomElement($lowercaseLettersAlphabet);

        if ($uppercaseLetters){
            $finalAlphabet = array_merge($finalAlphabet ,$uppercaseLettersAlphabet);

            // add a capital letter
            $password .=  $this->pickRandomElement($uppercaseLettersAlphabet);
        }
        if ($digits){
            $finalAlphabet = array_merge($finalAlphabet ,$digitsAlphabet);

            // add a number
            $password .=  $this->pickRandomElement($digitsAlphabet);
        }
        if ($specialCharacters){
            $finalAlphabet = array_merge($finalAlphabet ,$specialCharactersAlphabet);

           // add a special character
            $password .=  $this->pickRandomElement($specialCharactersAlphabet);
        }

        $lengthRemaining = $length - mb_strlen($password);
        for ($i = 0; $i < $lengthRemaining; $i++){
            $password .=  $this->pickRandomElement($finalAlphabet);
        }

        $password = str_split($password);

        $password = $this->secureShuffle($password);

        $password = implode('',$password);

        return $this->render('pages/password.html.twig', [
            'password' => $password,
        ]);
    }

    private function secureShuffle(array $arr) :array
    {
        // Source : https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
        $length = count($arr);
        for ($i = $length - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }

        return $arr;
    }

    private function pickRandomElement(array $alphabet): string
    {
        return $alphabet[random_int(0, count( $alphabet) -1) ];
    }
}
