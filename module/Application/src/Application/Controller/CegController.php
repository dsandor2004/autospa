<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\CegForm;
use Application\Entity\Ceg;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class CegController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
        $cegek = $repo->findBy(array('status' => \Application\Entity\Ceg::STATUS_ACTIVE));        
        
        return new ViewModel(array('cegek' => $cegek));
    }
    
    public function getformAction()
    {
        $form = new CegForm();

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Ceg'));
            $ceg = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            $form->bind($ceg);
        }
        
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new CegForm();
            $form->setInputFilter(new \Application\Form\CegFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $cegekRepo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
                    $ceg = $cegekRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj ceg
                    $ceg = new Ceg();
                }

                $ceg->setCegname($request->getPost('cegname'));
                $ceg->setSzerzodesdatum(new \DateTime($request->getPost('szerzodesdatum')));
                $ceg->setIdotartam($request->getPost('idotartam'));
                $ceg->setAutomatikushosszabbitas($request->getPost('automatikushosszabbitas'));
                $ceg->setFizetesihatarido($request->getPost('fizetesihatarido'));
                $ceg->setSzazalekmunkas($request->getPost('szazalekmunkas'));
                
                $this->getEntityManager()->persist($ceg);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $ceg->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('ceg');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            if ($object) {
                $object->setStatus(\Application\Entity\Ceg::STATUS_DELETED);
                $this->getEntityManager()->persist($object);
                $this->getEntityManager()->flush();
                
                $response->setContent("success");    
            } else {
                $response->setContent("error");
            }
        }
        return $response;
    }
    
    public function viewAction()
    {
        $id = (int)$this->params()->fromRoute('id');
        if (!$id)
            return $this->redirect()->toRoute('ceg');
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
        $ceg = $repo->findOneBy(array('id' => $id));
        
        if (!$ceg || $ceg->getStatus() == Ceg::STATUS_DELETED)
            return $this->redirect()->toRoute('ceg');

        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Rendszam');
        $rendszamok = $repo->findBy(array('ceg' => $id));

        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
        $szolgaltatasok = $repo->findAll();

        $spec_arak = array();
        foreach ($szolgaltatasok as $szolgaltatas) {
            $spec_arak[$szolgaltatas->getId()] = 0;
        }
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\SpecialisAr');
        $specialis_arak = $repo->findBy(array('ceg' => $id));
        
        foreach ($specialis_arak as $spec) {
            $spec_arak[$spec->getSzolgaltatas()->getId()] = $spec->getAr();
        }
        
        return new ViewModel(array('ceg' => $ceg, 'spec_arak' => $spec_arak, 'szolgaltatasok' => $szolgaltatasok, 'rendszamok' => $rendszamok));
    }
    
    public function saverendszamAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        if ($request->isPost()) {
            if ($request->getPost('rendszam') && $request->getPost('ceg_id')) {
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
                $ceg = $repo->findOneBy(array('id' => (int)$request->getPost('ceg_id')));
                
                if (!$ceg)
                    return $this->redirect()->toRoute('ceg');
                
                $rendszam = new \Application\Entity\Rendszam();
                $rendszam->setRendszam($request->getPost('rendszam'));
                $rendszam->setCeg($ceg);
                $this->getEntityManager()->persist($rendszam);
                $this->getEntityManager()->flush();

                $response->setContent($rendszam->getId());
            } else {
                return $this->redirect()->toRoute('ceg');
            }
        } else {
            return $this->redirect()->toRoute('ceg');
        }
        
        return $response;
    }
    
    public function deleterendszamAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Rendszam');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            if ($object) {
                $this->getEntityManager()->remove($object);
                $this->getEntityManager()->flush();
                
                $response->setContent("success");    
            } else {
                $response->setContent("error");
            }
        }
        return $response;
    }
    
    public function savespecialAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        
        if ($request->isPost()) {
            $cegId = $request->getPost('ceg_id');
                
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
            $ceg = $repo->findOneBy(array('id' => (int)$request->getPost('ceg_id')));
            
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\SpecialisAr');
            $specialis_arak = $repo->findBy(array('ceg' => $cegId));
            
            foreach ($specialis_arak as $spec) {
                $this->getEntityManager()->remove($spec);
                $this->getEntityManager()->flush();
            }
            
            foreach ($request->getPost('special_chk') as $szolgaltatasId) {
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
                $szolgaltatas = $repo->findOneBy(array('id' => $szolgaltatasId));
                
                $ar = $request->getPost('specialis_ar_'.$szolgaltatasId);
                
                $specialisAr = new \Application\Entity\SpecialisAr();
                $specialisAr->setAr($ar);
                $specialisAr->setCeg($ceg);
                $specialisAr->setSzolgaltatas($szolgaltatas);
                $this->getEntityManager()->persist($specialisAr);
                $this->getEntityManager()->flush();
            }                
        } else {
            return $this->redirect()->toRoute('ceg');
        }
        
        return $response;
    }
}
