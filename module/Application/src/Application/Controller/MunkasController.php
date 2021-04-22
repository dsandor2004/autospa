<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\MunkasForm;
use Application\Entity\Munkas;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class MunkasController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $munkasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
        $munkasok = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE));
        
        $fixFizetesekArray = array();
        
        $now = new \DateTime();
        
        foreach ($munkasok as $munkas) {
            $fixFizetesekArray[$munkas->getId()] = $munkas->getFixFizetes($this->getEntityManager(), $now->format('Y-m-d'), '');
        }
        
        $fizetesKategoriak = $this->getEntityManager()->getRepository('\Application\Entity\Fizeteskategoria')->findAll();
        
        return new ViewModel(array('munkasok' => $munkasok, 'fixFizetesekArray' => $fixFizetesekArray));
    }
    
    public function getformAction()
    {
        $form = new MunkasForm($this->getEntityManager());

        if ((int)$this->params()->fromQuery('id')) {
            $munkasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
            
            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Munkas'));
            $munkas = $munkasokRepo->findOneBy(array('id' => (int)$this->params()->fromQuery('id')));
            $form->bind($munkas);
            
            if ($munkas->getFizetes()) {
                $form->get('add_fizetes')->setValue(1);
            }
        }
        $viewModel = new ViewModel(array('form' => $form));
        $viewModel->setTerminal(true);
        return $viewModel;
    }

    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new MunkasForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\MunkasFilter());
            $form->setData($request->getPost());
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $munkasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
                    $munkas = $munkasokRepo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj munkas
                    $munkas = new Munkas();
                }

                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkastipus');
                $munkastipus = $repo->findOneBy(array('id' => $request->getPost('munkastipus')));
                
                $munkas->setHely($request->getPost('hely'));
                $munkas->setMunkastipus($munkastipus);
                $munkas->setName($request->getPost('name'));
                $munkas->setHiredate(new \DateTime($request->getPost('hiredate')));
                $munkas->setBirthdate(new \DateTime($request->getPost('birthdate')));
                
                if ($request->getPost('add_fizetes') && $request->getPost('fizetes'))
                    $munkas->setFizetes($request->getPost('fizetes'));
                else
                    $munkas->setFizetes(NULL);
                
                $this->getEntityManager()->persist($munkas);
                $this->getEntityManager()->flush();

                $now = new \DateTime();
                return new JsonModel(array('id' => $munkas->getId(), 'fizetes' => number_format($munkas->getFixFizetes($this->getEntityManager(), $now->format('Y-m-d'), ''), 2)));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('munkas');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            if ($object) {
                $object->setStatus(\Application\Entity\Munkas::STATUS_DELETED);
                $this->getEntityManager()->persist($object);
                $this->getEntityManager()->flush();
                
                $response->setContent("success");    
            } else {
                $response->setContent("error");
            }
        }
        return $response;
    }
    
    public function savemuszakAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            
            if ($request->getPost('muszakmunkas')) {
                $datum = \DateTime::createFromFormat('Y-m-d', $request->getPost('datum'));
                
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ejjelimuszak');
                $maiMuszak = $repo->findBy(array('datum' => $datum));
                foreach ($maiMuszak as $muszak) {
                    $this->getEntityManager()->remove($muszak);
                    $this->getEntityManager()->flush();
                }
                
                foreach ($request->getPost('muszakmunkas') as $munkas_id) {
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
                    $munkas = $repo->findOneBy(array('id' => $munkas_id));
                    
                    $muszak = new \Application\Entity\EjjeliMuszak();
                    $muszak->setDatum($datum);
                    $muszak->setMunkas($munkas);
                    $this->getEntityManager()->persist($muszak);
                    $this->getEntityManager()->flush();
                    
                }   
            }
            return $response;
        }
    }

    public function savenapimuszakAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            
            if ($request->getPost('napi_muszakmunkas') && $request->getPost('napi_baros')) {
                $datum = \DateTime::createFromFormat('Y-m-d', $request->getPost('datum'));

                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Napimuszak');
                $maiMuszak = $repo->findBy(array('datum' => $datum));
                foreach ($maiMuszak as $muszak) {
                    $this->getEntityManager()->remove($muszak);
                    $this->getEntityManager()->flush();
                }
                
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
                $baros = $repo->findOneBy(array('id' => $request->getPost('napi_baros')));
                $muszak = new \Application\Entity\NapiMuszak();
                $muszak->setDatum($datum);
                $muszak->setMunkas($baros);
                $this->getEntityManager()->persist($muszak);
                $this->getEntityManager()->flush();
                
                foreach ($request->getPost('napi_muszakmunkas') as $munkas_id) {
                    $munkas = $repo->findOneBy(array('id' => $munkas_id));
                    
                    $muszak = new \Application\Entity\NapiMuszak();
                    $muszak->setDatum($datum);
                    $muszak->setMunkas($munkas);
                    $muszak->setHely($request->getPost('muszakmunkas_hely_' . $munkas_id));
                    $this->getEntityManager()->persist($muszak);
                    $this->getEntityManager()->flush();
                    
                }   
            }
            return $response;
        }
    }
    
    public function egyenlegAction()
    {        
        $id = (int)$this->params()->fromRoute('id');
        if (!$id)
            return $this->redirect()->toRoute('munkas');
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
        $munkas = $repo->findOneBy(array('id' => $id));
        
        if (!$munkas || $munkas->getStatus() == Munkas::STATUS_DELETED)
            return $this->redirect()->toRoute('munkas');

        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Tartozas');
        $tartozasok = $repo->findBy(array('munkas' => $id, 'status' => \Application\Entity\Tartozas::STATUS_OPEN));

        $now = new \DateTime();
        if ($now->format('d') <= 15) {
            $aktualisFrom = $now->format('Y-m-01');
            $aktualisTo = $now->format('Y-m-16');
            
            $from = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-15'));
            $from->sub(new \DateInterval("P1M"));
            
            $to = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-t'));
            $to->sub(new \DateInterval("P1M"));
            
            $from4 = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-01'));
            $from4->sub(new \DateInterval("P1M"));

            $to4 = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-15'));
            $to4->sub(new \DateInterval("P1M"));
            
        } else {
            $aktualisFrom = $now->format('Y-m-16');
            $aktualisTo = $now->format('Y-m-t');
            
            $from = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-01'));
            $to = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-15'));
            
            $from4 = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-16'));
            $from4->sub(new \DateInterval("P1M"));

            $to4 = \DateTime::createFromFormat('Y-m-d', $now->format('Y-m-t'));
            $to4->sub(new \DateInterval("P1M"));            
        }
        
        $reszesedes_aktualis = $munkas->getReszesedes($this->getEntityManager(), $aktualisFrom, $aktualisTo);
        $reszesedes = $munkas->getReszesedes($this->getEntityManager(), $from->format('Y-m-d'), $to->format('Y-m-d'));
        $reszesedes4 = $munkas->getReszesedes($this->getEntityManager(), $from4->format('Y-m-d'), $to4->format('Y-m-d'));
        
        
        $ejjelimuszakok_aktualis = $munkas->getEjjeliMuszakok($this->getEntityManager(), $aktualisFrom, $aktualisTo);
        $ejjelimuszakok = $munkas->getEjjeliMuszakok($this->getEntityManager(), $from->format('Y-m-d'), $to->format('Y-m-d'));
        $ejjelimuszakok4 = $munkas->getEjjeliMuszakok($this->getEntityManager(), $from4->format('Y-m-d'), $to4->format('Y-m-d'));
        
        $juttatasok = $this->getEntityManager()->getRepository('\Application\Entity\Juttatasok')
                    ->findOneBy(array('id' => 1));
        
        $ejjelipenz_aktualis  = number_format($ejjelimuszakok_aktualis * $juttatasok->getEjjelipenz(), 2);
        $ejjelipenz = number_format($ejjelimuszakok * $juttatasok->getEjjelipenz(), 2);
        $ejjelipenz4 = number_format($ejjelimuszakok4 * $juttatasok->getEjjelipenz(), 2);

        $monthStart = new \DateTime('first day of this month');
        
        $reklamfelelos = $this->getEntityManager()->getRepository('\Application\Entity\HaviReklamfelelos')
                    ->findOneBy(array('munkas' => $munkas->getId(), 'datum' => $monthStart));
        
        if ($reklamfelelos)
            $reklampenz = $juttatasok->getReklampenz();

        $habfelelos = $this->getEntityManager()->getRepository('\Application\Entity\HaviHabfelelos')
                    ->findOneBy(array('munkas' => $munkas->getId(), 'datum' => $monthStart));
        
        if ($habfelelos)
            $habpenz = $juttatasok->getHabpenz();

        $monthStart4 = new \DateTime('first day of last month');
        
        $reklamfelelos4 = $this->getEntityManager()->getRepository('\Application\Entity\HaviReklamfelelos')
                    ->findOneBy(array('munkas' => $munkas->getId(), 'datum' => $monthStart4));
        
        if ($reklamfelelos4)
            $reklampenz4 = $juttatasok->getReklampenz();

        $habfelelos4 = $this->getEntityManager()->getRepository('\Application\Entity\HaviHabfelelos')
                    ->findOneBy(array('munkas' => $munkas->getId(), 'datum' => $monthStart4));
        
        if ($habfelelos4)
            $habpenz4 = $juttatasok->getHabpenz();
        
        if ($munkas->getMunkastipus()->getId() == \Application\Entity\Munkastipus::BAROS_TIPUS_ID) {
            $fizetesPerHo_aktualis = $munkas->getFixFizetes($this->getEntityManager(), $aktualisFrom, '');
            $napimuszakok_aktualis = $munkas->getNapiMuszakCountByHely($this->getEntityManager(), $aktualisFrom, $aktualisTo);
			

            $fizetesPerHo = $munkas->getFixFizetes($this->getEntityManager(), $from->format('Y-m-d'), '');
            $napimuszakok = $munkas->getNapiMuszakCountByHely($this->getEntityManager(), $from->format('Y-m-d'), $to->format('Y-m-d'));
            
            $fizetesPerHo4 = $munkas->getFixFizetes($this->getEntityManager(), $from4->format('Y-m-d'), '');
            $napimuszakok4 = $munkas->getNapiMuszakCountByHely($this->getEntityManager(), $from4->format('Y-m-d'), $to4->format('Y-m-d'));

			$fixFizetes_aktualis = $fizetesPerHo_aktualis / 15 * $napimuszakok_aktualis / 2;
			$fixFizetes = $fizetesPerHo / 15 * $napimuszakok / 2;
            $fixFizetes4 = $fizetesPerHo4 / 15 * $napimuszakok4 / 2;
            
        } elseif ($munkas->getMunkastipus()->getId() == \Application\Entity\Munkastipus::MOSODAS_TIPUS_ID) {

            $fizetesFiko_aktualis = $this->getFizetes($munkas, $aktualisFrom, $aktualisTo, \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID);
            $fizetesKoronka_aktualis = $this->getFizetes($munkas, $aktualisFrom, $aktualisTo, \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID);

            $fizetesFiko = $this->getFizetes($munkas, $from->format('Y-m-d'), $to->format('Y-m-d'), \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID);
            $fizetesKoronka = $this->getFizetes($munkas, $from->format('Y-m-d'), $to->format('Y-m-d'), \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID);
                        
            $fizetesFiko4 = $this->getFizetes($munkas, $from4->format('Y-m-d'), $to4->format('Y-m-d'), \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID);
            $fizetesKoronka4 = $this->getFizetes($munkas, $from4->format('Y-m-d'), $to4->format('Y-m-d'), \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID);
                        
            $fixFizetes_aktualis = $fizetesFiko_aktualis['fizetes'] + $fizetesKoronka_aktualis['fizetes'];
            $fixFizetes = $fizetesFiko['fizetes'] + $fizetesKoronka['fizetes'];
            $fixFizetes4 = $fizetesFiko4['fizetes'] + $fizetesKoronka4['fizetes'];

            $dayCounter = array(
                'fiko_aktualis' => $fizetesFiko_aktualis['nap'],
                'fiko' => $fizetesFiko['nap'],
                'fiko4' => $fizetesFiko4['nap'],
                
                'koronka_aktualis' => $fizetesKoronka_aktualis['nap'],
                'koronka' => $fizetesKoronka['nap'],
                'koronka4' => $fizetesKoronka4['nap'],
            );
            
        } else {
            //jelenleg a Vulkanizalosok tartoznak ebbe az agba
            $fixFizetes_aktualis = $fixFizetes = $fixFizetes4 = $fizetesPerHo;
        }
        
        return new ViewModel(array('munkas' => $munkas, 'fixFizetes_aktualis'  => $fixFizetes_aktualis, 'fixFizetes'  => $fixFizetes, 'fixFizetes4'  => $fixFizetes4, 'tartozasok' => $tartozasok, 'reszesedes' => $reszesedes, 'reszesedes_aktualis' => $reszesedes_aktualis, 'reszesedes4' => $reszesedes4, 'ejjelipenz' => $ejjelipenz, 'ejjelipenz_aktualis' => $ejjelipenz_aktualis, 'ejjelipenz4' => $ejjelipenz4, 'reklampenz' => $reklampenz, 'reklampenz4' => $reklampenz4, 'habpenz' => $habpenz, 'habpenz4' => $habpenz4, 'login' => $this->zfcUserAuthentication()->getIdentity(), 'from' => $from->format('Y.m.d'), 'to' => $to->format('Y.m.d'), 'aktualisFrom' => $aktualisFrom, 'aktualisTo' => $aktualisTo, 'from4' => $from4->format('Y.m.d'), 'to4' => $to4->format('Y.m.d'), 'dayCounter' => $dayCounter, 'napimuszakok' => $napimuszakok, 'napimuszakok4' => $napimuszakok4, 'napimuszakok_aktualis' => $napimuszakok_aktualis));
    }
    
    public function teljesitmenyAction()
    {
        $viewModel = new ViewModel();
        $viewModel->setTerminal(true);
        
        return $viewModel;
    }
    
    public function getteljesitmenyAction()
    {
        $now = new \DateTime();
        
        $response = $this->getResponse();
        
        $from = $this->params()->fromQuery('from', $now->format('Y-m-01'));
        $to = $this->params()->fromQuery('to', $now->format('Y-m-d'));
        $munkas_id = $this->params()->fromQuery('munkas_id');
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
        $munkas = $repo->findOneBy(array('id' => $munkas_id));
        
        $reszesedes = $munkas->getReszesedes($this->getEntityManager(), $from, $to);
        
        $response->setContent(number_format($reszesedes, 2));
        
        return $response;
    }
    
    public function getejjelimuszakAction()
    {
        $now = new \DateTime();
        
        $datum = $this->params()->fromQuery('datum', $now->format('Y-m-d'));

        $datum = new \DateTime($datum);
        
        $munkasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
        $munkasokFiko = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE, 'munkastipus' => \Application\Entity\Munkastipus::MOSODAS_TIPUS_ID, 'hely' => \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID ));
        $munkasokKoronka = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE, 'munkastipus' => \Application\Entity\Munkastipus::MOSODAS_TIPUS_ID, 'hely' => \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID ));
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ejjelimuszak');
        
        $ejjelimuszak = $repo->findBy(array('datum' => $datum));        
        
        $viewModel = new ViewModel(array('munkasokFiko' => $munkasokFiko, 'munkasokKoronka' => $munkasokKoronka, 'ejjelimuszak' => $ejjelimuszak, 'login' => $this->zfcUserAuthentication()->getIdentity()));
        
        $viewModel->setTerminal(true);
        return $viewModel;        
    }

    public function getnapimuszakAction()
    {
        $now = new \DateTime();
        
        $datum = $this->params()->fromQuery('datum', $now->format('Y-m-d'));
        
        $datum = new \DateTime($datum);
        
        $munkasokRepo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
        $munkasokFiko = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE, 'munkastipus' => \Application\Entity\Munkastipus::MOSODAS_TIPUS_ID, 'hely' => \Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID ));
        $munkasokKoronka = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE, 'munkastipus' => \Application\Entity\Munkastipus::MOSODAS_TIPUS_ID, 'hely' => \Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID ));
        $barosokFiko = $munkasokRepo->findBy(array('status' => \Application\Entity\Munkas::STATUS_ACTIVE, 'munkastipus' => \Application\Entity\Munkastipus::BAROS_TIPUS_ID ));
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Napimuszak');
        
        $napimuszak = $repo->findBy(array('datum' => $datum));        
        
        $viewModel = new ViewModel(array('munkasokFiko' => $munkasokFiko, 'munkasokKoronka' => $munkasokKoronka, 'barosokFiko' => $barosokFiko, 'napimuszak' => $napimuszak, 'login' => $this->zfcUserAuthentication()->getIdentity()));
        
        $viewModel->setTerminal(true);
        return $viewModel;        
    }
    
    public function savehabfelelosAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            
            if ($request->getPost('habfelelos')) {

                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
                $munkas = $repo->findOneBy(array('id' => $request->getPost('habfelelos')));
                
                $plugin = $this->globalFunctions();
                $aktualis_habfelelos = $plugin->getHabfelelos($munkas->getHely());
                                
                if (!count($aktualis_habfelelos)) {
                    $habfelelos = new \Application\Entity\HaviHabfelelos();
                    
                    $monthStart = new \DateTime('first day of this month');
                    $habfelelos->setDatum($monthStart);
                }
                else{
                    $habfelelos = $aktualis_habfelelos;
                }
          
                $habfelelos->setMunkas($munkas);
                $this->getEntityManager()->persist($habfelelos);
                $this->getEntityManager()->flush();                
            }
            return $response;
        }
    }
    
    public function savereklamfelelosAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            
            if ($request->getPost('reklamfelelos')) {

                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
                $munkas = $repo->findOneBy(array('id' => $request->getPost('reklamfelelos')));
                
                $monthStart = new \DateTime('first day of this month');
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\HaviReklamfelelos');
                $reklamfelelos = $repo->findOneBy(array('datum' => $monthStart));
                
                if (!$reklamfelelos) {
                    $reklamfelelos = new \Application\Entity\HaviReklamfelelos();
                    $reklamfelelos->setDatum($monthStart);
                }
                
                $reklamfelelos->setMunkas($munkas);
                $this->getEntityManager()->persist($reklamfelelos);
                $this->getEntityManager()->flush();
                
            }
            return $response;
        }
    }
    
    public function gethomewarningcountAction()
    {
        $now = new \DateTime();

        $warningCounter = 0;
        
        $ejjelimuszak = $this->getEntityManager()->getRepository('\Application\Entity\Ejjelimuszak')->findBy(array('datum' => $now));
        if (!$ejjelimuszak)
            $warningCounter++;

        $napimuszak = $this->getEntityManager()->getRepository('\Application\Entity\Napimuszak')->findBy(array('datum' => $now));
        if (!$napimuszak)
            $warningCounter++;
        
        $plugin = $this->globalFunctions();
        
        $habfelelos_fiko = $plugin->getHabfelelos(\Application\Entity\Aktivitas::AKTIVITAS_HELY_FIKO_ID);
        if (!$habfelelos_fiko)
            $warningCounter++;
        
        $habfelelos_koronka = $plugin->getHabfelelos(\Application\Entity\Aktivitas::AKTIVITAS_HELY_KORONKA_ID);
        if (!$habfelelos_koronka)
            $warningCounter++;
        
        $reklamfelelos = $plugin->getReklamfelelos();
        if (!$reklamfelelos)
            $warningCounter++;
        
        $viewModel = new ViewModel(array('warningCounter' => $warningCounter));
        $viewModel->setTerminal(true);
        return $viewModel;        
    }
    
    public function getlezarttartozasAction()
    {
        $now = new \DateTime();
                
        $from = $this->params()->fromQuery('from', $now->format('Y-m-01'));
        $to = $this->params()->fromQuery('to', $now->format('Y-m-d'));

        $munkas_id = $this->params()->fromQuery('munkas_id');
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Munkas');
        $munkas = $repo->findOneBy(array('id' => $munkas_id));
        
        $lezart_tartozasok = $munkas->getLezartTartozasok($this->getEntityManager(), $from, $to);
        
        $viewModel = new ViewModel(array('lezart_tartozasok' => $lezart_tartozasok));
        $viewModel->setTerminal(true);
        return $viewModel;                
    }
    
    private function getFizetes($munkas, $from, $to, $hely_id)
    {
        /**
         * A standard program a mosodasoknak 3 munkanap utan 1 szabad, 
         * tehat 30 napbol a 3/4-eden dolgoznak atlagban, azaz 22.5 napot egy honapban
         * A teljes fix fizetes 30 napra adott.
         * Ahhoz, hogy megkapjuk, mennyi a fix fizetes 1 napra, ezt el kell osztani 22.5-el
         */
        $napimuszakCount = $munkas->getNapiMuszakCountByHely($this->getEntityManager(), $from, $to, $hely_id);
        
        if ($napimuszakCount) {
            //teljes Fix fizetes 1 honapra;
            $fizetesPerHo = $munkas->getFixFizetes($this->getEntityManager(), $from, $hely_id);
            
            //napi fizetes
            $fizetesPerNap = $fizetesPerHo / 22.5;

            //a munkas fizetese erre a periodusra
            $fizetes = $fizetesPerNap * $napimuszakCount;

            //nem lehet tobb, mint a meghatarozott fix
            if ($fizetes > $fizetesPerHo / 2)
                $fizetes = $fizetesPerHo / 2;

        } else {
            $fizetes = 0;
        }
        
        return array( 'fizetes' => $fizetes, 'nap' => $napimuszakCount);
    }
}
