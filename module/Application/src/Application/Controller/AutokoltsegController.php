<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\AutokoltsegForm;
use Application\Entity\Autokoltseg;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class AutokoltsegController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $now = new \DateTime();
        
        $from = $this->params()->fromQuery('from', $now->format('Y-m-01'));
        $to = $this->params()->fromQuery('to', $now->format('Y-m-d'));

        
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Autokoltseg')->createQueryBuilder('ak')
                ->select('ak')
                ->where("ak.datum >= '" . $from . "' AND ak.datum <= '" . $to . "'");
        $autokoltsegek = $qb->getQuery()->getResult();
        
        return new ViewModel(array('autokoltsegek' => $autokoltsegek, 'from' => $from, 'to' => $to, 'login' => $this->zfcUserAuthentication()->getIdentity()));
    }
    
    public function getformAction()
    {
        $form = new AutokoltsegForm($this->getEntityManager());

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Autokoltseg');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Autokoltseg'));
            $autokoltseg = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            
            $login = $this->zfcUserAuthentication()->getIdentity();
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($autokoltseg->isEditableAndDeletable() == false) {
                    $viewModel = new ViewModel(array('form' => $form, 'modosithato' => false));
                    $viewModel->setTerminal(true);
                    return $viewModel;
                }
            }
            
            $form->bind($autokoltseg);
            $form->get('tipus')->setValue($autokoltseg->getTipus());
        } else {
            $today =  new \DateTime();
            $form->get('datum')->setValue($today);
            $form->get('koltsegnev')->setValue(\Application\Entity\Autokoltseg::UZEMANYAG_STRING);
        }
        
        $viewModel = new ViewModel(array('form' => $form, 'modosithato' => true));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new AutokoltsegForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\AutokoltsegFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $autokoltsegekRepo = $this->getEntityManager()->getRepository('\Application\Entity\Autokoltseg');
                    $autokoltseg = $autokoltsegekRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj koltseg
                    $autokoltseg = new Autokoltseg();
                }

                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Auto');
                $auto = $repo->findOneBy(array('id' => $request->getPost('auto')));

                $autokoltseg->setAuto($auto);
                $autokoltseg->setKoltsegnev($request->getPost('koltsegnev'));
                $autokoltseg->setOsszeg($request->getPost('osszeg'));
                $autokoltseg->setDatum(new \DateTime($request->getPost('datum')));
                
                $this->getEntityManager()->persist($autokoltseg);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $autokoltseg->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('autokoltseg');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Autokoltseg');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            $login = $this->zfcUserAuthentication()->getIdentity();
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($object->isEditableAndDeletable() == false) {
                    $response->setContent("nodelete");
                    return $response;
                }
            }
            
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
}
