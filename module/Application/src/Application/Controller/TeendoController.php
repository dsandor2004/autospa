<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Application\Form\TeendoForm;
use Application\Entity\Teendo;
use Zend\View\Model\JsonModel;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class TeendoController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function getformAction()
    {
//        die('Kovetkezik..');
        $form = new TeendoForm($this->getEntityManager());

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Teendo');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Teendo'));
            $teendo = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            $form->bind($teendo);
        }
        
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }
    
    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new TeendoForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\TeendoFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Teendo');
                    $teendo = $repo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj munkas
                    $teendo = new Teendo();
                }

                $repo = $this->getEntityManager()->getRepository('\SamUser\Entity\User');
                $user = $repo->findOneBy(array('id' => $request->getPost('user')));
                
                $teendo->setUser($user);
                $teendo->setDescription($request->getPost('description'));
                
                $this->getEntityManager()->persist($teendo);
                $this->getEntityManager()->flush();

                $now = new \DateTime();
                return new JsonModel(array('id' => $teendo->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('home');
        }
    }
    
    public function lezarteendoAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Teendo');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            if ($object) {
                $object->setStatus(\Application\Entity\Teendo::STATUS_DONE);
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