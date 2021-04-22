<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\AutoForm;
use Application\Entity\Auto;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class KocsiController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Auto');
        $autok = $repo->findBy(array('status' => \Application\Entity\Auto::STATUS_ACTIVE));        
        
        return new ViewModel(array('autok' => $autok));
    }
    
    public function getformAction()
    {
        $form = new AutoForm();

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Auto');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Auto'));
            $auto = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            $form->bind($auto);
        }
        
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new AutoForm();
            $form->setInputFilter(new \Application\Form\AutoFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $autokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Auto');
                    $auto = $autokRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj auto
                    $auto = new Auto();
                }

                $auto->setRendszam($request->getPost('rendszam'));
                $this->getEntityManager()->persist($auto);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $auto->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('auto');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Auto');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            if ($object) {
                $object->setStatus(\Application\Entity\Auto::STATUS_DELETED);
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
