<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Entity\Fizeteskategoria;

class FizeteskategoriaController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkastipus');
        $munkastipus = $repo->findAll();
                
        return new ViewModel(array('munkastipus' => $munkastipus));
    }
    
    public function saveAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Fizeteskategoria');
            $fizeteskategoriak = $repo->findBy(array('munkastipus' => $request->getPost('munkastipus'), 'hely' => $request->getPost('hely')));
            foreach ($fizeteskategoriak as $kateg) {
                $this->getEntityManager()->remove($kateg);
                $this->getEntityManager()->flush();
            }
            
            $munkastipus = $this->getEntityManager()->getRepository('\Application\Entity\Munkastipus')->findOneBy(array('id' => $request->getPost('munkastipus')));
            
            $i = $request->getPost('rowcount');
            for ($j = 1; $j <= $i; $j++) {
                $kategoria = new Fizeteskategoria();
                $kategoria->setMunkastipus($munkastipus);
                $kategoria->setHely($request->getPost('hely'));
                $kategoria->setMinregisegHo($request->getPost('minregiseg_' . $j));
                $kategoria->setMaxregisegHo($request->getPost('maxregiseg_' . $j));
                $kategoria->setFizetes($request->getPost('fizetes_' . $j));
                $kategoria->setSzazalek($request->getPost('szazalek_' . $j));
                $this->getEntityManager()->persist($kategoria);
                $this->getEntityManager()->flush();
            }
        }
        
        return $response;
    }
    
    public function getfizeteskategoriaAction()
    {   
        $munkastipus = $this->params()->fromQuery('munkastipus');
        $hely = $this->params()->fromQuery('hely');

            
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Fizeteskategoria');
        $fizeteskategoriak = $repo->findBy(array('munkastipus' => $munkastipus, 'hely' => $hely), array('minregisegHo' => 'ASC'));
        
        $viewModel = new ViewModel(array('fizeteskategoriak' => $fizeteskategoriak));
        
        $viewModel->setTerminal(true);
        return $viewModel;        
        
    }
}
