<?php

namespace Application\Helper;
use Zend\View\Helper\AbstractHelper;
 
class FizetesKategoria extends AbstractHelper
{
    public static function __invoke($fizeteskategoriak, $regisegHo, $tipusId, $honapString)
    {
        $string = '';
        
        foreach ($fizeteskategoriak as $kat) {
            if ($kat->getMunkasTipus()->getId() == $tipusId && $regisegHo >= $kat->getMinregisegHo() && ($regisegHo < $kat->getMaxregisegHo() || !$kat->getMaxregisegHo())) {
                if (!$kat->getMinregisegHo() && !$kat->getMaxregisegHo()) {
                    $string .= '> ' . $kat->getMinregisegHo();
                } elseif ($kat->getMinregisegHo() && $kat->getMaxregisegHo()) {
                    $string .= $kat->getMinregisegHo() . '-' . $kat->getMaxregisegHo();
                } elseif (!$kat->getMinregisegHo()) {
                    $string .= '< ' . $kat->getMaxregisegHo();
                } elseif (!$kat->getMaxregisegHo()) {
                    $string .= '> ' . $kat->getMinregisegHo();
                }

                $string .=  ' ' . $honapString . ': ';

                $string .= $kat->getFizetes() . ' RON';

                if ($kat->getSzazalek())
                    $string .= ' + ' . $kat->getSzazalek() . '%';

                break;
            }
        }
        
        return $string;
    }
}
