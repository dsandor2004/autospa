<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\KoltsegForm;
use Application\Entity\Koltseg;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class KoltsegController extends AbstractActionController
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

        
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Koltseg')->createQueryBuilder('k')
                ->select('k')
                ->where("k.datum >= '" . $from . "' AND k.datum <= '" . $to . "'");
        $koltsegek = $qb->getQuery()->getResult();
        
        return new ViewModel(array('koltsegek' => $koltsegek, 'from' => $from, 'to' => $to, 'login' => $this->zfcUserAuthentication()->getIdentity()));
    }
    
    public function getformAction()
    {
        $form = new KoltsegForm($this->getEntityManager());

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Koltseg');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Koltseg'));
            $koltseg = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            
            $login = $this->zfcUserAuthentication()->getIdentity();
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($koltseg->isEditableAndDeletable() == false) {
                    $viewModel = new ViewModel(array('form' => $form, 'modosithato' => false));
                    $viewModel->setTerminal(true);
                    return $viewModel;
                }
            }
            
            $form->bind($koltseg);
        } else {
            $today =  new \DateTime();
            $form->get('datum')->setValue($today);
        }
        
        $viewModel = new ViewModel(array('form' => $form, 'modosithato' => true));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new KoltsegForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\KoltsegFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $koltsegekRepo = $this->getEntityManager()->getRepository('\Application\Entity\Koltseg');
                    $koltseg = $koltsegekRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj koltseg
                    $koltseg = new Koltseg();
                }

                $koltseg->setTipus($request->getPost('tipus'));
                $koltseg->setKoltsegnev($request->getPost('koltsegnev'));
                $koltseg->setOsszeg($request->getPost('osszeg'));
                $koltseg->setDatum(new \DateTime($request->getPost('datum')));
                
                $this->getEntityManager()->persist($koltseg);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $koltseg->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('koltseg');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Koltseg');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            $login = $this->zfcUserAuthentication()->getIdentity();
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
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
