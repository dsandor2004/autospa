<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\JuttatasokForm;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class JuttatasokController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $form = new JuttatasokForm();
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Juttatasok');
        $juttatasok = $repo->findOneBy(array('id' => 1));        
        
        $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Juttatasok'));
        $form->bind($juttatasok);
        
        return new ViewModel(array('form' => $form , 'kellekek' => $juttatasok));
    }
    
    public function saveformAction()
    {
        $form = new JuttatasokForm();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form->setInputFilter(new \Application\Form\JuttatasokFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Juttatasok');
                $juttatasok = $repo->findOneBy(array('id' => 1));
                
                $juttatasok->setEjjelipenz($request->getPost('ejjelipenz'));
                $juttatasok->setReklampenz($request->getPost('reklampenz'));
                $juttatasok->setHabpenz($request->getPost('habpenz'));
                $juttatasok->setBaroscsaj($request->getPost('baroscsaj'));
                $this->getEntityManager()->persist($juttatasok);
                $this->getEntityManager()->flush();
            }
            
            $result = new JsonModel($form->getMessages());
            return $result;            
        }
        
    }
}
