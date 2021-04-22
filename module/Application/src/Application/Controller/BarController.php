<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\ArulasForm;
//use Application\Entity\Aru;
use Application\Entity\Aruforgas;
use Application\Entity\Tartozas;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class BarController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
	private function getAruForgas($from, $to, $cashOnly = "")
	{   
        $cashFilter = '';
        if ($cashOnly == '1')
			$cashFilter = " AND (af.tipus = " . \Application\Entity\Aruforgas::TIPUS_NORMAL . ")";
		
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas')->createQueryBuilder('af')
                ->select('af')
                ->where("af.parent IS NULL AND af.tipus <> ". \Application\Entity\Aruforgas::TIPUS_LELTAR." AND af.created_at >= '$from' AND af.created_at <= '$to 23:59:59'" . $cashFilter);
        $aruforgas = $qb->getQuery()->getResult();

		return $aruforgas;
	}

	private function getChildAruForgas($from, $to, $cashOnly=null)
	{
        $cashFilter = '';
        if ($cashOnly == '1')
			$cashFilter = " AND (af.tipus = " . \Application\Entity\Aruforgas::TIPUS_NORMAL . ")";
		
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas')->createQueryBuilder('af')
                ->select('af')
                ->where("af.parent > 0 AND af.tipus <> ". \Application\Entity\Aruforgas::TIPUS_LELTAR." AND af.created_at >= '$from' AND af.created_at <= '$to 23:59:59'" . $cashFilter);
        $childAruforgasok = $qb->getQuery()->getResult();
		
		$cukor = array();
		$pohar = array();
		$poharfedo = array();
		if ($childAruforgasok) {
			foreach ($childAruforgasok as $af) {
				if ($af->getAru()->getId() == \Application\Entity\Aru::CUKOR_ID)
					$cukor[$af->getParent()->getId()] = $af->getDarab() / ($af->getParent()->getDarab() / $af->getParent()->getKavefajta()->getMennyiseg());
				if ($af->getAru()->getId() == \Application\Entity\Aru::POHAR_ID)
					$pohar[$af->getParent()->getId()] = $af->getDarab() / ($af->getParent()->getDarab() / $af->getParent()->getKavefajta()->getMennyiseg());
				if ($af->getAru()->getId() == \Application\Entity\Aru::POHARFEDO_ID)
					$poharfedo[$af->getParent()->getId()] = $af->getDarab() / ($af->getParent()->getDarab() / $af->getParent()->getKavefajta()->getMennyiseg());
			}
		}
		
		return array(
			'cukor'		=> $cukor,
			'pohar'		=> $pohar,
			'poharfedo'	=> $poharfedo,
		);
	}
	
    public function indexAction()
    {		
		$now = new \DateTime();
        $from = $this->params()->fromQuery('from', $now->format('Y-m-d'));		
        $to = $this->params()->fromQuery('to', $now->format('Y-m-d'));
		$cashOnly = $this->params()->fromQuery('cash', '');
		$login = $this->zfcUserAuthentication()->getIdentity();
		
		$ret = $this->getChildAruForgas($from, $to, $cashOnly);
		return new ViewModel(
			array(
				'arulasok' => $this->getAruForgas($from, $to, $cashOnly),
				'from' => $from,
				'to' => $to,
				'cash' => $cashOnly,
				'login' => $login,
				'cukor' => $ret['cukor'],
				'pohar' => $ret['pohar'],
				'poharfedo' => $ret['poharfedo'],
			)
		);
    }
	
	public function osszesitoAction()
	{
		$now = new \DateTime();
        $from = $this->params()->fromQuery('from', $now->format('Y-m-d'));		
        $to = $this->params()->fromQuery('to', $now->format('Y-m-d'));
		
		$ret = $this->getChildAruForgas($from, $to);
		$aruforgas = $this->getAruForgas($from, $to);
		$aruforgasOsszesito = array();
		$arak = array();
		foreach ($aruforgas as $row) {
			$aruforgasOsszesito[$row->getAruNev()][$row->getTipusString()] += $row->getAruDarab() * -1;
			$arak[$row->getAruNev()] = $row->getAruEladasiAr();
		}
		
		$viewModel = new ViewModel(
			array(
				'aruforgasOsszesito' => $aruforgasOsszesito,
				'arak' => $arak,
				'cukor' => $ret['cukor'],
				'pohar' => $ret['pohar'],
				'poharfedo' => $ret['poharfedo'],
			)
		);
		
		$viewModel->setTerminal(true);
		return $viewModel;
	}
	
    public function getformAction()
    {
        $form = new ArulasForm($this->getEntityManager());
        $login = $this->zfcUserAuthentication()->getIdentity();
		
        if ((int)$this->params()->fromQuery('id')) {
            $id = (int)$this->params()->fromQuery('id');
            
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas');
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Aru'));
            $aruforgas = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
			
            if (!$aruforgas)
                return $this->redirect()->toRoute('bar');
			
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($aruforgas->isEditableAndDeletable() == false) {
                    $viewModel = new ViewModel(array('form' => $form, 'modosithato' => false));
                    $viewModel->setTerminal(true);
                    return $viewModel;
                }
            }

            $form->bind($aruforgas);
			if ($aruforgas->getKavefajta())
				$darab = $form->get('darab')->getValue() / $aruforgas->getKavefajta()->getMennyiseg() * -1;
			else
				$darab = $form->get('darab')->getValue() * -1;
			
			$form->get('darab')->setValue($darab);
			
			if ($aruforgas->getAru()->getId() == \Application\Entity\Aru::KAVE_ID) {
				$childAruforgas = $repo->findBy(array('parent' => (int)$this->params()->fromQuery('id')));
				foreach ($childAruforgas as $af) {
					switch ($af->getAru()->getId()) {
						case \Application\Entity\Aru::CUKOR_ID :
							$form->get('cukor')->setValue($af->getDarab() / $af->getParent()->getAruDarab());
							break;
						case \Application\Entity\Aru::POHAR_ID :
							$form->get('pohar')->setValue(1);
							break;
						case \Application\Entity\Aru::POHARFEDO_ID :
							$form->get('poharfedo')->setValue(1);
							break;
					}
				}
			}
			
			if ($aruforgas->getTartozas())
				$form->get('munkas')->setValue($aruforgas->getTartozas()->getMunkas()->getId());
				
        } else {
			$form->get('darab')->setValue(1);
		}
		
        $viewModel = new ViewModel(array('form' => $form, 'modosithato' => true));
        $viewModel->setTerminal(true);
        return $viewModel;
    }
	
	public function saveformAction()
	{
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new ArulasForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\ArulasFilter());
            $form->setData($request->getPost());
            
			if ($request->getPost('aru') == \Application\Entity\Aru::KAVE_ID) {
				$form->getInputFilter()->get('kavefajta')->setRequired(true);
			}
			
            if ($request->getPost('tipus') == \Application\Entity\Aruforgas::TIPUS_INGYEN) {
                $form->getInputFilter()->get('megjegyzes')->setRequired(true);
			} elseif ($request->getPost('tipus') == \Application\Entity\Aruforgas::TIPUS_MUNKASNAK) {
                $form->getInputFilter()->get('munkas')->setRequired(true);
			}
			
			if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas');
                    $aruforgas = $repo->findOneBy(array('id' => $request->getPost('id')));
					
					$repo = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas');
					$kavehozzavalok = $repo->findBy(array('parent' => $request->getPost('id')));
					
					if ($kavehozzavalok) {
						foreach($kavehozzavalok as $item) {
							$this->getEntityManager()->remove($item);
							$this->getEntityManager()->flush();
						}
					}
					
					if ($aruforgas->getTartozas()) {
						$tartozasId = $aruforgas->getTartozas()->getId();
						$aruforgas->setTartozas(NULL);
						$this->getEntityManager()->persist($aruforgas);
						$this->getEntityManager()->flush();
						
						$repo = $this->getEntityManager()->getRepository('\Application\Entity\Tartozas');
						$tartozas = $repo->findOneBy(array('id' => $tartozasId));
						$this->getEntityManager()->remove($tartozas);
						$this->getEntityManager()->flush();
					}
                } else {
                    //uj aruforgas
                    $aruforgas = new Aruforgas();
                }
				
				$repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
				$aru = $repo->findOneBy(array('id' => $request->getPost('aru')));
				$aruforgas->setAru($aru);
				
				$aruforgas->setTipus($request->getPost('tipus'));
				$aruforgas->setMegjegyzes($request->getPost('megjegyzes'));

				if ($request->getPost('aru') == \Application\Entity\Aru::KAVE_ID) {
					$repo = $this->getEntityManager()->getRepository('\Application\Entity\Kavefajta');
					$kavefajta = $repo->findOneBy(array('id' => $request->getPost('kavefajta')));
					$aruforgas->setKavefajta($kavefajta);
					
					$aruforgas->setDarab($request->getPost('darab') * -1 * $kavefajta->getMennyiseg());
					
					$this->getEntityManager()->persist($aruforgas);
					$this->getEntityManager()->flush();
				} else {
					$aruforgas->setDarab($request->getPost('darab') * -1);
				}
				
				if ($request->getPost('munkas')) {
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
                    $munkas = $repo->findOneBy(array('id' => $request->getPost('munkas')));
					
					$tartozas = new Tartozas();
					$tartozas->setMunkas($munkas);
					
					$arunev =   $aruforgas->getAruNev();					
					if ($request->getPost('megjegyzes'))
						$arunev .= ' - ' . $request->getPost('megjegyzes');
					
					if ($request->getPost('darab') > 1)
						$arunev = $request->getPost('darab') . 'x ' . $arunev;
					
					$tartozas->setTartozasnev($arunev);
					
					$tartozas->setOsszeg($aruforgas->getAruEladasiAr() * $request->getPost('darab'));
					
					$tartozas->setDatum(new \DateTime());
					$tartozas->setStatus(\Application\Entity\Tartozas::STATUS_OPEN);
					$this->getEntityManager()->persist($tartozas);
					$this->getEntityManager()->flush();
					$aruforgas->setTartozas($tartozas);
				}
				
				if ($request->getPost('aru') == \Application\Entity\Aru::KAVE_ID) {					
					if ($request->getPost('cukor')) {
						$cukorAruforgas = new Aruforgas();
						$repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
						$aru = $repo->findOneBy(array('id' => \Application\Entity\Aru::CUKOR_ID));
						$cukorAruforgas->setAru($aru);
						$cukorAruforgas->setDarab($request->getPost('cukor') * $request->getPost('darab') * -1);
						$cukorAruforgas->setTipus($request->getPost('tipus'));
						$cukorAruforgas->setparent($aruforgas);
						$this->getEntityManager()->persist($cukorAruforgas);
						$this->getEntityManager()->flush();
					}
					
					if ($request->getPost('pohar')) {
						$poharAruforgas = new Aruforgas();
						$repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
						$aru = $repo->findOneBy(array('id' => \Application\Entity\Aru::POHAR_ID));
						$poharAruforgas->setAru($aru);
						$poharAruforgas->setDarab($request->getPost('darab') * -1);
						$poharAruforgas->setTipus($request->getPost('tipus'));
						$poharAruforgas->setparent($aruforgas);
						$this->getEntityManager()->persist($poharAruforgas);
						$this->getEntityManager()->flush();
					}
					
					if ($request->getPost('poharfedo')) {
						$poharfedoAruforgas = new Aruforgas();
						$repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
						$aru = $repo->findOneBy(array('id' => \Application\Entity\Aru::POHARFEDO_ID));
						$poharfedoAruforgas->setAru($aru);
						$poharfedoAruforgas->setDarab($request->getPost('darab') * -1);
						$poharfedoAruforgas->setTipus($request->getPost('tipus'));
						$poharfedoAruforgas->setparent($aruforgas);
						$this->getEntityManager()->persist($poharfedoAruforgas);
						$this->getEntityManager()->flush();
					}
					
					if ($kavefajta->getTejmennyiseg()) {
						$tejAruforgas = new Aruforgas();
						$repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
						$aru = $repo->findOneBy(array('id' => \Application\Entity\Aru::TEJ_ID));
						$tejAruforgas->setAru($aru);
						$tejAruforgas->setDarab($kavefajta->getTejmennyiseg() * $request->getPost('darab') * -1);
						$tejAruforgas->setTipus($request->getPost('tipus'));
						$tejAruforgas->setparent($aruforgas);
						$this->getEntityManager()->persist($tejAruforgas);
						$this->getEntityManager()->flush();						
					}
				} else {				
					$this->getEntityManager()->persist($aruforgas);
					$this->getEntityManager()->flush();
				}
								
				return new JsonModel(array('id' => $aruforgas->getId(), 'created_at' => $aruforgas->getCreatedAt()->format('Y-m-d H:i:s')));
			}
			
			$result = new JsonModel($form->getMessages());
            return $result;			
		} else {
			return $this->redirect()->toRoute('bar');
		}			
	}
	
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aruforgas');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            $login = $this->zfcUserAuthentication()->getIdentity();
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($object->isEditableAndDeletable() == false) {
                    $response->setContent("nodelete");
                    return $response;
                }
            }
            
           if ($object) {
				if ($object->getTartozas()) {
					$tartozasId = $object->getTartozas()->getId();
					$object->setTartozas(NULL);
					$this->getEntityManager()->persist($object);
					$this->getEntityManager()->flush();

					$repo = $this->getEntityManager()->getRepository('\Application\Entity\Tartozas');
					$tartozas = $repo->findOneBy(array('id' => $tartozasId));
					$this->getEntityManager()->remove($tartozas);
					$this->getEntityManager()->flush();
				}
							   
                $this->getEntityManager()->remove($object);
                $this->getEntityManager()->flush();
                 
                $response->setContent("success");
            } else {
                $response->setContent("error");
            }
        }
        return $response;
    }
	
}
