<?php
// src/Twig/AppExtension.php
namespace App\twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class AppExtension extends AbstractExtension
{
    public function getFilters()
    {
        return [
            new TwigFilter('cast_to_array', [$this, 'castToArray']),
        ];
    }

    public function castToArray($stdClassObject)
    {   
        $response = array();
        foreach ($stdClassObject as $key => $value) {
            $response[] += array($key, $value);
        }
        return $response;
    }
}