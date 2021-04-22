<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\KellekForm;
use Application\Entity\Kellek;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class KellekController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Kellek');
        $kellekek = $repo->findAll();
        
        return new ViewModel(array('kellekek' => $kellekek));
    }
    
    public function getformAction()
    {
        $form = new KellekForm();

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Kellek');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Kellek'));
            $kellek = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            $form->bind($kellek);
        }
        
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new KellekForm();
            $form->setInputFilter(new \Application\Form\KellekFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $kellekekRepo = $this->getEntityManager()->getRepository('\Application\Entity\Kellek');
                    $kellek = $kellekekRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj kellek
                    $kellek = new Kellek();
                }

                $kellek->setNev($request->getPost('nev'));                
                $this->getEntityManager()->persist($kellek);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $kellek->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('kellek');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Kellek');
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
    
    public function schemaupdateAction()
    {
        $request = $this->getRequest();
        
        if ($request->isPost()) {
            if ($request->getPost('password') == 'm1gr4t3') {
                $em = $this->getEntityManager();
                $tool = new \Doctrine\ORM\Tools\SchemaTool($em);

                $meta = $em->getMetadataFactory()->getAllMetadata();        
                $tool->updateSchema($meta);
                $success = 1;
            } else
                $success = -1;
        }
        
        $viewModel = new ViewModel(array('success' => $success));
        $viewModel->setTerminal(true);
        return $viewModel;
        
    }
    
}
