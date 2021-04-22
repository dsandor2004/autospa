<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\AruForm;
use Application\Entity\Aru;
use Application\Entity\Aruforgas;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class AruController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
		
    }
	
    public function getformAction()
    {
        $form = new AruForm($this->getEntityManager());
        
        if ((int)$this->params()->fromQuery('id')) {
            $id = (int)$this->params()->fromQuery('id');
            
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Aru'));
            $aru = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
			
            $form->bind($aru);
			
			$plugin = $this->globalFunctions();
			$darab = $plugin->getAktualisAruDb($aru->getId());
			$form->get('darab')->setValue($darab);
			
			$kavekellekek = array(
				\Application\Entity\Aru::KAVE_ID,
				\Application\Entity\Aru::CUKOR_ID,
				\Application\Entity\Aru::POHAR_ID,
				\Application\Entity\Aru::POHARFEDO_ID,
				\Application\Entity\Aru::TEJ_ID,
			);

			if (in_array($id, $kavekellekek)) {
				$form->get('nev')->setAttribute('readonly', true);
			}
        }
		
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }
	
	public function saveformAction()
	{
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new AruForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\AruFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
                    $aru = $repo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj aru
                    $aru = new Aru();
                }

                $aru->setNev($request->getPost('nev'));
                
                $aru->setVetelAr($request->getPost('vetelar'));
				
				$aru->setEladasiAr($request->getPost('eladasiar'));
                
                
                $this->getEntityManager()->persist($aru);
                $this->getEntityManager()->flush();
                
				$plugin = $this->globalFunctions();
				$eredetiDarab = $plugin->getAktualisAruDb($aru->getId());
				$aruforgas = new Aruforgas();
				$aruforgas->setAru($aru);
				$aruforgas->setDarab($request->getPost('darab') - $eredetiDarab);
				$aruforgas->setTipus(\Application\Entity\Aruforgas::TIPUS_LELTAR);
				if ($request->getPost('id'))
					$aruforgas->setMegjegyzes('Manuális darab módosítás');
				else
					$aruforgas->setMegjegyzes('Termék létrehozása');
				
                $this->getEntityManager()->persist($aruforgas);
                $this->getEntityManager()->flush();
				
                return new JsonModel(array('id' => $aru->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('leltar');
        }
	}
	
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aru');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            if ($object) {
                $object->setStatus(\Application\Entity\Aru::STATUS_DELETED);
                $this->getEntityManager()->persist($object);
                $this->getEntityManager()->flush();
                
                $response->setContent("success");    
            } else {
                $response->setContent("error");
            }
        }
        return $response;
    }
}
