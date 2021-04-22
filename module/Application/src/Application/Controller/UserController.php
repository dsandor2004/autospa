<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Application\Form\UserForm;
use Zend\View\Model\ViewModel;
use SamUser\Entity\User;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;
use Zend\View\Model\JsonModel;
use Zend\Crypt\Password\Bcrypt;

class UserController extends \ZfcUser\Controller\UserController
{
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    /**
     * Index Action
     * @return \Zend\View\Model\ViewModel
     */
    public function indexAction()
    {
        $repo = $this->getEntityManager()->getRepository('\SamUser\Entity\User');
        $users = $repo->findAll();
        
        $loggedinUser = $this->zfcUserAuthentication()->getIdentity();
        
        return new ViewModel(array('users' => $users, 'loginId' => $loggedinUser->getId()));
    }
    
    public function getformAction()
    {
        $form = new UserForm($this->getEntityManager());
        
        if ((int)$this->params()->fromQuery('id')) {
            $usersRepo = $this->getEntityManager()->getRepository('\SamUser\Entity\User');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\SamUser\Entity\User'));
            $user = $usersRepo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            $form->bind($user);
        }
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new UserForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\UserFilter());
            $form->setData($request->getPost());
            
            if ($request->getPost('id')) {
                $form->getInputFilter()->get('password')->setRequired(false);
            }

            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $usersRepo = $this->getEntityManager()->getRepository('\SamUser\Entity\User');
                    $user = $usersRepo->findOneBy(array('id' => (int)$request->getPost('id')));
                } else {
                    //uj munkas
                    $user = new User();
                }

                $user->setUsername($request->getPost('username'));
                                                
                if ($request->getPost('password')) {
                    $options = $this->getServiceLocator()->get('zfcuser_module_options');
                    $bcrypt = new Bcrypt;
                    $bcrypt->setCost($options->getPasswordCost());
                    $user->setPassword($bcrypt->create($request->getPost('password')));
                }
                
                $user->setEmail($request->getPost('username'));
                                
                foreach ($user->getRoles() as $role) {
                    $user->getRoleObjects()->removeElement($role);
                }
                                
                foreach ($request->getPost('roles') as $roleId) {
                    $role = $this->getEntityManager()->getRepository('\SamUser\Entity\Role')
                                ->findOneBy(array('id' => $roleId));
                    $user->getRoleObjects()->add($role);
                    
                }

                $this->getEntityManager()->persist($user);
                $this->getEntityManager()->flush();
                
                return new JsonModel(array('id' => $user->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('user');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\SamUser\Entity\User');
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
