<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\KavefajtaForm;
use Application\Entity\Kavefajta;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class KavefajtaController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Kavefajta');
        $kavefajtak = $repo->findAll();
        
        return new ViewModel(array('kavefajtak' => $kavefajtak));
    }
	
    public function getformAction()
    {
        $form = new KavefajtaForm($this->getEntityManager());
        
        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Kavefajta');
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Kavefajta'));
            $kavefajta = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
			
            $form->bind($kavefajta);
        }
		
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }
	
	public function saveformAction()
	{
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new KavefajtaForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\KavefajtaFilter());
            $form->setData($request->getPost());
            
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Kavefajta');
                    $kavefajta = $repo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj aru
                    $kavefajta = new Kavefajta();
                }

                $kavefajta->setNev($request->getPost('nev'));
                $kavefajta->setMennyiseg($request->getPost('mennyiseg'));
				$kavefajta->setTejmennyiseg($request->getPost('tejmennyiseg'));
				$kavefajta->setAr($request->getPost('ar'));
				                
                $this->getEntityManager()->persist($kavefajta);
                $this->getEntityManager()->flush();
				
                return new JsonModel(array('id' => $kavefajta->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('kavefajta');
        }
	}
	
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Kavefajta');
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
