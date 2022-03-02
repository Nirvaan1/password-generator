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
    public function generatePassword(Request $request)
    {
        $length = $request->query->getInt('length');
        $digits = $request->query->getBoolean('digits');
        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $specialCharacters = $request->query->getBoolean('special_characters');

        $lowercaseLettersSet= range('a', 'z');
        $uppercaseLettersSet = range('A', 'Z');
        $digitsSet = range(0, 9);
        $specialCharactersSet = range('/','!');

        $characters = $lowercaseLettersSet;

        $password = '';

        // on rajoute une lettre en miniscule
        $password .=  $lowercaseLettersSet[random_int(0, count( $lowercaseLettersSet) -1) ];

        if ($uppercaseLetters){
            $characters = array_merge($characters ,$uppercaseLettersSet);

            // on rajoute une majuscule
            $password .=  $uppercaseLettersSet[random_int(0, count( $uppercaseLettersSet) -1) ];
        }
        if ($digits){
            $characters = array_merge($characters ,$digitsSet);

            // on rajoute un chiffre
            $password .=  $digitsSet[random_int(0, count( $digitsSet) -1) ];
        }
        if ($specialCharacters){
            $characters = array_merge($characters ,$specialCharactersSet);

           // on rajoute un caractere special
            $password .=  $specialCharactersSet[random_int(0, count( $specialCharactersSet) -1) ];
        }

        $lengthRemaining = $length - mb_strlen($password);
        for ($i = 0; $i < $lengthRemaining; $i++){
            $password .=  $characters[random_int(0, count( $characters) -1) ];
        }

        $password = str_split($password);

        $this->secureShuffle($password);

        $password = implode('',$password);

        return $this->render('pages/password.html.twig', [
            'password' => $password,
        ]);
    }


    private function secureShuffle(array &$arr) :void
    {
        // Source : https://github.com/lamansky/secure-shuffle/blob/master/src/functions.php
        $arr = array_values($arr);
        $length = count($arr);
        for ($i = $length - 1; $i > 0; $i--) {
            $j = random_int(0, $i);
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
        }

    }
}
