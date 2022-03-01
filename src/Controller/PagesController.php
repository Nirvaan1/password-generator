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
        $characters = range('a', 'z');
        $characters = array_merge($characters, range('A', 'Z'));
        $characters = array_merge($characters, range(0, 9));
        $characters = array_merge($characters, array('/','\\',':',';','!','@','#','$','%','^','*','(',')','_','+','=','|','{','}','[',']','"',"'",'<','>',',','?','~','`','&',' ','.'));

        return $this->render('pages/home.html.twig');
    }

    #[Route('/generate-password', name: 'app_generate_password')]
    public function generatePassword(Request $request)
    {
        $length = $request->query->getInt('length');
        $digits = $request->query->getBoolean('digits');
        $uppercaseLetters = $request->query->getBoolean('uppercase_letters');
        $specialCharacters = $request->query->getBoolean('special_characters');

        $characters = range('a', 'z');
        if ($uppercaseLetters){
            $characters = array_merge($characters, range('A', 'Z'));
        }
        if ($digits){
            $characters = array_merge($characters, range(0, 9));
        }
        if ($specialCharacters){
            $characters = array_merge($characters, array('/','!','@','#','$','%','^','*','(',')','_','+','=','|','{','}','[',']','<','>',',','?','~','`','&',' ','.'));
        }

        $password = '';
        $tab = count($characters) ;

        for ($i = 0; $i < $length; $i++){
            $password .=  $characters[mt_rand($i, $tab -1 ) ];
        }


        dump($password);


        return $this->render('pages/password.html.twig', [
            'password' => $password,
        ]);

    }
}
