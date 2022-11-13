<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

class AppExtension extends AbstractExtension
{
    public function getFilters(): array
    {
        return [
            // If your filter generates SAFE HTML, you should add a third
            // parameter: ['is_safe' => ['html']]
            // Reference: https://twig.symfony.com/doc/3.x/advanced.html#automatic-escaping
            new TwigFilter('filter_name', [$this, 'doSomething']),
        ];
    }

    public function getFunctions(): array
    {
        return [
             new TwigFunction('ShowAllGamesByLetter', [$this, 'ShowAllGamesByLetter']),
             new TwigFunction('isNotEmptyArray', [$this, 'isNotEmptyArray']),
             new TwigFunction('getClass', [$this, 'getClass']),
             new TwigFunction('dateToString', [$this, 'dateToString']),
             new TwigFunction('haveOneGameOnPlatform' , [$this, 'haveOneGameOnPlatform'])
        ];
    }

     public function ShowAllGamesByLetter($letter, $games): array
     {
        $result = array();
        foreach ($games as $key) {
            $substring = substr($key->getName(), 0, 1);
            if($substring == $letter){
                $result[] = $key;
            }
        }
        return $result;
    }

    public function isNotEmptyArray($array): bool
    {
        if(!$array){
            return false;
        }
         else {
             return true;
         }
    }

    public function getClass($platform): string{
        if($platform == "DS"){
            return "img_DS_game";
        } else {
            return "img_game";
        }
    }

    public function dateToString($date): string
    {
        return $date->format('d/m/Y');
    }

    public function haveOneGameOnPlatform($p, $g): bool
    {
        foreach($g as $game){
            if($game->getPlatform() == $p['platform']){
                return true;
            }
        }
        return false;
    }


}
