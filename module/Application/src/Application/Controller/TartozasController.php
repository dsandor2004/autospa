<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\TartozasForm;
use Application\Entity\Tartozas;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class TartozasController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
        
    public function getformAction()
    {
        $form = new TartozasForm($this->getEntityManager());

        $munkas_id = (int)$this->params()->fromQuery('munkas_id');
        $form->get('munkas_id')->setValue($munkas_id);
        
        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Tartozas');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Tartozas'));
            $tartozas = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            
            $login = $this->zfcUserAuthentication()->getIdentity();
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($tartozas->isEditableAndDeletable() == false) {
                    $viewModel = new ViewModel(array('form' => $form, 'modosithato' => false));
                    $viewModel->setTerminal(true);
                    return $viewModel;
                }
            }
            
            $form->bind($tartozas);
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
            $form = new TartozasForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\TartozasFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $tartozasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Tartozas');
                    $tartozas = $tartozasokRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj tartozas
                    $tartozas = new Tartozas();
                }

                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
                $munkas = $repo->findOneBy(array('id' => $request->getPost('munkas_id')));                
                $tartozas->setMunkas($munkas);
                
                $tartozas->setTartozasnev($request->getPost('tartozasnev'));
                $tartozas->setOsszeg($request->getPost('osszeg'));
                $tartozas->setDatum(new \DateTime($request->getPost('datum')));
                $tartozas->setStatus(\Application\Entity\Tartozas::STATUS_OPEN);
                
                $this->getEntityManager()->persist($tartozas);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $tartozas->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('tartozas');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Tartozas');
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
    
    public function closeAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Tartozas');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));

            if ($object) {
                $object->setStatus(\Application\Entity\Tartozas::STATUS_CLOSED);
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
