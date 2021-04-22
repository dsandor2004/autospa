<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Entity\Aruforgas;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class LeltarController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
		$qb = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas')->createQueryBuilder('af')
                ->select('SUM(af.darab) as darab, a.id, a.nev, a.vetelar, a.eladasiar')
				->join('af.aru', 'a')
				->groupBy('a.id')
				->where('a.status = ' . \Application\Entity\Aru::STATUS_ACTIVE);
		$aruk = $qb->getQuery()->getResult();
		
		$now = new \DateTime();
        $from = $now->format('Y-m-d');
        $to = $now->format('Y-m-d');
		
		return new ViewModel(array('aruk' => $aruk, 'from' => $from, 'to' => $to));
    }
	
	public function saveleltarAction()
	{
        $request = $this->getRequest();
		$response = $this->getResponse();
        if ($request->isPost()) {
	        $plugin = $this->globalFunctions();
			
			foreach ($request->getPost() as $aruId => $ujDarab) {
				$repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
				$aru = $repo->findOneBy(array('id' => $aruId));
				$eredetiDarab = $plugin->getAktualisAruDb($aru->getId());
				
				$aruforgas = new Aruforgas();
				$aruforgas->setAru($aru);
				$aruforgas->setDarab($ujDarab - $eredetiDarab);
				$aruforgas->setTipus(\Application\Entity\Aruforgas::TIPUS_LELTAR);
				$aruforgas->setMegjegyzes('Manuális darab módosítás');
                $this->getEntityManager()->persist($aruforgas);
                $this->getEntityManager()->flush();
			}
        } else {
            return $this->redirect()->toRoute('leltar');
        }
		
		return $response;
	}
	
	public function getaruforgasAction()
	{
		$now = new \DateTime();
        $from = $this->params()->fromQuery('from', $now->format('Y-m-d'));		
        $to = $this->params()->fromQuery('to', $now->format('Y-m-d'));
		$selectedAru = $this->params()->fromQuery('selectedAru', '');
		
		$viewModel = new ViewModel(
			array(
				'aruforgasok' => $this->getAruForgas($from, $to, $selectedAru),
				'from' => $from,
				'to' => $to,
				'selectedAru' => $selectedAru,
				'aktualisDarabok'	=> $this->getAktualisDarabok($from, $selectedAru)
			)
		);
		
		$viewModel->setTerminal(true);
		return $viewModel;
		
	}

	private function getAktualisDarabok($from, $selectedAru = "")
	{
        $aruFilter = '';
        if ($selectedAru)
			$aruFilter = " AND a.id =  $selectedAru ";
		
		$qb = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas')->createQueryBuilder('af')
                ->select('a.id, SUM(af.darab) as darab')
				->join('af.aru', 'a')
				->groupBy('a.id')
				->where("af.created_at < '$from 00:00:00' AND a.status = " . \Application\Entity\Aru::STATUS_ACTIVE . $aruFilter);
		$aktualisDarabok = $qb->getQuery()->getResult();

		$returnArray = array();
		foreach ($aktualisDarabok as $db)
			$returnArray[$db['id']] = $db['darab'];
		
		return $returnArray;
	}
	
	private function getAruForgas($from, $to, $selectedAru = "")
	{
        $aruFilter = '';
        if ($selectedAru)
			$aruFilter = " AND a.id =  $selectedAru ";
		
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas')->createQueryBuilder('af')
                ->select('af')
				->join('af.aru', 'a')
                ->where("af.created_at >= '$from' AND af.created_at <= '$to 23:59:59'" . $aruFilter)
				->orderBy('af.created_at, a.nev');
        $aruforgas = $qb->getQuery()->getResult();

		return $aruforgas;
	}
	
}
