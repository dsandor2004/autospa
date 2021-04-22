<?php
namespace Application\Controller\Plugin;

use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class GlobalFunctions extends AbstractPlugin
{
    private function getEntityManager()
    {
        return $this->getController()->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function getHabfelelos($hely_id)
    {
        $monthStart = new \DateTime('first day of this month');
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\HaviHabfelelos')->createQueryBuilder('h')
                ->select('h')
                ->join('h.munkas', 'm')
                ->where("m.hely = $hely_id AND h.datum = '" . $monthStart->format('Y-m-d') . "'");
        
        $habfelelos = $qb->getQuery()->getResult();
        if ($habfelelos)
            $habfelelos = $habfelelos[0];
        
        return $habfelelos;
    }
    
    public function getReklamfelelos()
    {
        $monthStart = new \DateTime('first day of this month');
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\HaviReklamfelelos');
        $reklamfelelos = $repo->findOneBy(array('datum' => $monthStart));
        
        return $reklamfelelos;
    }
    
    public function getKifizetetlenAktivitasok()
    {
        $now = new \DateTime();
        
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Aktivitas')->createQueryBuilder('ak')
                ->select('ak')
                ->where("ak.ceg > 0 AND ak.status = " . \Application\Entity\Aktivitas::STATUS_OPEN);
        $aktivitasok = $qb->getQuery()->getResult();

        $kifizetetlenArray = array();
        
        foreach ($aktivitasok as $akt) {
            $idopont = clone $akt->getIdopont();
            $utolsoNap = new \DateTime($idopont->format('Y-m-t'));
            if ($akt->getCeg()->getFizetesihatarido() == -1) {
                $hatarido = $utolsoNap->format('Y-m-d');
            } else {
                $utolsoNap->modify('+'.($akt->getCeg()->getFizetesihatarido() + 1).' day');
                $hatarido = $utolsoNap->format('Y-m-d');
            }

            if ($hatarido <= $now->format('Y-m-d'))
                $kifizetetlenArray[] = $akt;
        }
        
        return $kifizetetlenArray;
    }
	
	public function getAktualisAruDb($aru_id)
	{
		$qb = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas')->createQueryBuilder('af')
                ->select('SUM(af.darab)')
                ->join('af.aru', 'a')
                ->where("a.id = " . $aru_id);
        $darab = $qb->getQuery()->getSingleScalarResult();
		return $darab;
	}
}