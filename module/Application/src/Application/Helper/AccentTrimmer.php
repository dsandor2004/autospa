<?php

namespace Application\Helper;
use Zend\View\Helper\AbstractHelper;

class AccentTrimmer extends AbstractHelper
{
    public function __invoke($str)
    {
        $accented_characters = array('á', 'Á', 'é', 'É', 'í', 'Í', 'ö', 'Ö', 'Ü', 'ü', 'ó', 'Ó', 'Ő', 'ő', 'Ú', 'ú', 'Ű', 'ű', 'ă', 'Ă', 'î', 'Î', 'ș', 'Ș', 'ț', 'Ț', 'â', 'Â');
        $nonaccented_characters = array('a', 'A', 'e', 'E', 'i', 'I', 'o', 'O', 'U', 'u', 'o', 'O', 'O', 'o', 'U', 'u', 'U', 'u', 'a', 'A', 'i', 'I', 's', 'S', 't', 'T', 'a', 'A');
        return str_replace($accented_characters, $nonaccented_characters, $str);
    }
}
