<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\SzolgaltatasForm;
use Application\Entity\Szolgaltatas;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class SzolgaltatasController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
        $szolgaltatasok = $repo->findAll();
        
        return new ViewModel(array('szolgaltatasok' => $szolgaltatasok));
    }
    
    public function getformAction()
    {
        $form = new SzolgaltatasForm();

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Szolgaltatas'));
            $szolgaltatas = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            $form->bind($szolgaltatas);
        }
        
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new SzolgaltatasForm();
            $form->setInputFilter(new \Application\Form\SzolgaltatasFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $szolgaltatasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
                    $szolgaltatas = $szolgaltatasokRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj szolgaltatas
                    $szolgaltatas = new Szolgaltatas();
                }

                $szolgaltatas->setNev($request->getPost('nev'));
                $szolgaltatas->setAlapar($request->getPost('alapar'));
                if ($request->getPost('cegesar'))
                    $szolgaltatas->setCegesar($request->getPost('cegesar'));
                else
                    $szolgaltatas->setCegesar(NULL);
                
                $this->getEntityManager()->persist($szolgaltatas);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $szolgaltatas->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('szolgaltatas');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
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
}
