<?php

namespace App\Twig\Extension;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;
use Symfony\Component\HttpFoundation\Request;

class AppExtension extends AbstractExtension
{
    private $kernelProjectDir;

    // le constructeur indique que nous avons ici besoin d'un paramètre, le kernelProjectDir.
    // Nous allons passer ce paramètre en le déclarant dans notre fichier config/services.yaml. 
    // Il s'agit d'un paramètre par défaut introduit dans Symfony 4.
    public function __construct(string $kernelProjectDir)
    {
        $this->kernelProjectDir = $kernelProjectDir;
    }

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
             new TwigFunction('ShowAllGamesByLetter', [$this, 'show_game'])
        ];
    }

     public function ShowAllGamesByLetter($letter, $games)
    {

        return $games[1];
    }
}
