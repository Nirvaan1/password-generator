<?php

namespace App\Service;

class PasswordGenerator
{
    public function generate(int $length, bool $digits = false, bool $uppercaseLetters = false, bool $specialCharacters = false): string
    {
        $lowercaseLettersAlphabet = range('a', 'z');
        $uppercaseLettersAlphabet = range('A', 'Z');
        $digitsAlphabet = range(0, 9);
        $specialCharactersAlphabet = range('/','!');

        $finalAlphabet = $lowercaseLettersAlphabet;

        $password = [];

        // add a lowercase letter
        $password[] =  $this->pickRandomElement($lowercaseLettersAlphabet);

        if ($uppercaseLetters){
            $finalAlphabet = array_merge($finalAlphabet ,$uppercaseLettersAlphabet);

            // add a capital letter
            $password[] =  $this->pickRandomElement($uppercaseLettersAlphabet);
        }
        if ($digits){
            $finalAlphabet = array_merge($finalAlphabet ,$digitsAlphabet);

            // add a number
            $password[] =  $this->pickRandomElement($digitsAlphabet);
        }
        if ($specialCharacters){
            $finalAlphabet = array_merge($finalAlphabet ,$specialCharactersAlphabet);

            // add a special character
            $password[] =  $this->pickRandomElement($specialCharactersAlphabet);
        }

        $lengthRemaining = $length - count($password);
        for ($i = 0; $i < $lengthRemaining; $i++){
            $password[] =  $this->pickRandomElement($finalAlphabet);
        }

        $password = $this->secureShuffle($password);

        $password = implode('',$password);

        return $password;
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
