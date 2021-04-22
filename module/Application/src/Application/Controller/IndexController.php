<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */

namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        if (!$this->zfcUserAuthentication()->hasIdentity())
            return $this->redirect()->toRoute('zfcuser/login');
     
        $login = $this->zfcUserAuthentication()->getIdentity();
        if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_ADMIN) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
            
            $qb = $this->getEntityManager()->getRepository('\Application\Entity\Teendo')->createQueryBuilder('t')
                    ->select('t')
                    ->where("t.status = " . \Application\Entity\Teendo::STATUS_ACTIVE);
            $teendok = $qb->getQuery()->getResult();
        }
        
        $munkasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
        $munkasokFiko = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE, 'munkastipus' => \Application\Entity\Munkastipus::MOSODAS_TIPUS_ID, 'hely' => \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID));
        $munkasokKoronka = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE, 'munkastipus' => \Application\Entity\Munkastipus::MOSODAS_TIPUS_ID, 'hely' => \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID));
        
        $now = new \DateTime();
        $next7daysArray = array();
        for ($i = 0; $i < 7; $i++) {
            $now->modify('+1 day');
            $next7daysArray[] = $now->format('m-d');
        }
        
        $yesterday = new \DateTime();
        $yesterday->sub(new \DateInterval('P1D'));
        
        $munkasok = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE));
        $szulinaposok = array();
        $fizetesValtozasok = array();
        foreach ($munkasok as $munkas) {
            $aktualis_fizetesPerHo = $munkas->getFixFizetes($this->getEntityManager(), $now->format('Y-m-d'), '');
            
            $tegnapi_fizetesPerHo = $munkas->getFixFizetes($this->getEntityManager(), $yesterday->format('Y-m-d'), '');
            if ($aktualis_fizetesPerHo != $tegnapi_fizetesPerHo)
                $fizetesValtozasok[] = $munkas->getName();
            
            $birthdate = $munkas->getBirthdate();
            if (in_array($birthdate->format('m-d'), $next7daysArray))
                $szulinaposok[] = $munkas;
        }

        $plugin = $this->globalFunctions();
        
        $habfelelos_fiko = $plugin->getHabfelelos(\Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID);
                
        $habfelelos_koronka = $plugin->getHabfelelos(\Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID);
        
        $reklamfelelos = $plugin->getReklamfelelos();
        
        return new ViewModel(array('teendok' => $teendok, 'munkasokFiko' => $munkasokFiko, 'munkasokKoronka' => $munkasokKoronka, 'szulinaposok' => $szulinaposok, 'fizetesValtozasok' => $fizetesValtozasok, 'habfelelos_fiko' => $habfelelos_fiko, 'habfelelos_koronka' => $habfelelos_koronka, 'reklamfelelos' => $reklamfelelos, 'login' => $login));
    }    
}
