<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\UgyintezofizetesForm;
use Application\Entity\Ugyintezofizetes;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class UgyintezofizetesController extends AbstractActionController
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

        
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Ugyintezofizetes')->createQueryBuilder('k')
                ->select('k')
                ->where("k.datum >= '" . $from . "' AND k.datum <= '" . $to . "'");
        $fizetesek = $qb->getQuery()->getResult();
        
        return new ViewModel(array('fizetesek' => $fizetesek, 'from' => $from, 'to' => $to));
    }
    
    public function getformAction()
    {
        $form = new UgyintezofizetesForm($this->getEntityManager());

        if ((int)$this->params()->fromQuery('id')) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ugyintezofizetes');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Ugyintezofizetes'));
            $fizetes = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
                        
            $form->bind($fizetes);
        } else {
            $today =  new \DateTime();
            $form->get('datum')->setValue($today);
        }
        
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new UgyintezofizetesForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\UgyintezofizetesFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $fizetesekRepo = $this->getEntityManager()->getRepository('\Application\Entity\Ugyintezofizetes');
                    $fizetes = $fizetesekRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj fizetes
                    $fizetes = new Ugyintezofizetes();
                }

                $fizetes->setOsszeg($request->getPost('osszeg'));
                $fizetes->setDatum(new \DateTime($request->getPost('datum')));
                
                $this->getEntityManager()->persist($fizetes);
                $this->getEntityManager()->flush();
                                
                return new JsonModel(array('id' => $fizetes->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('ugyintezofizetes');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ugyintezofizetes');
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
