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

        $mappingConstraints = [
            [$uppercaseLetters, $uppercaseLettersAlphabet],
            [$digits,  $digitsAlphabet],
            [$specialCharacters, $specialCharactersAlphabet],
        ];

        foreach ($mappingConstraints as [$constraintEnabled, $constraintAlphabet]){
            if ($constraintEnabled){
                $finalAlphabet = array_merge($finalAlphabet ,$constraintAlphabet);

                $password[] =  $this->pickRandomElement($constraintAlphabet);
            }
        }

        $lengthRemaining = $length - count($password);
        for ($i = 0; $i < $lengthRemaining; $i++){
            $password[] =  $this->pickRandomElement($finalAlphabet);
        }

        $password = $this->secureShuffle($password);

        return  implode('',$password);

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
