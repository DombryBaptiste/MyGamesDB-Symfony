<?php

namespace App\Twig\Extension;

use App\Entity\Games;
use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Request;

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
             new TwigFunction('ShowAllGamesByLetter', [$this, 'ShowAllGamesByLetter'])
        ];
    }

     public function ShowAllGamesByLetter($letter, $games)
    {
        $result = array();
        foreach ($games as $key) {
            $substring = substr($key->getName(), 0, 1);
            if($substring == $letter){
                array_push($result, $key);
            }
        }
        return $result;
    }
}
