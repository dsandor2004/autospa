<?php
/** 
 * @author Sandor Denesi <dsandor2004@yahoo.com>
 *  
 */
namespace Application\Controller;

use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;
use Zend\View\Model\JsonModel;
use Application\Form\AktivitasForm;
use Application\Entity\Aktivitas;
use DoctrineORMModule\Stdlib\Hydrator\DoctrineEntity;

class AktivitasController extends AbstractActionController
{
    
    private function getEntityManager()
    {
        return $this->getServiceLocator()->get('Doctrine\ORM\EntityManager');
    }
    
    public function indexAction()
    {
        $now = new \DateTime();

        $plugin = $this->globalFunctions();
        $osszesKifizetetlen = $plugin->getKifizetetlenAktivitasok();
        
        $from = $this->params()->fromQuery('from', $now->format('Y-m-d'));
        $to = $this->params()->fromQuery('to', $now->format('Y-m-d'));
        
        $qb = $this->getEntityManager()->getRepository('\Application\Entity\Aktivitas')->createQueryBuilder('ak')
                ->select('ak')
                ->where("ak.idopont >= '" . $from . "' AND ak.idopont <= '" . $to . "' AND ak.ceg > 0 AND ak.status = " . \Application\Entity\Aktivitas::STATUS_OPEN);
        $aktivitasok = $qb->getQuery()->getResult();

        $kifizetetlenAktivitasok = array();


		$barosReszesedes = "";
		if ($from == $to) {
			$qb = $this->getEntityManager()->getRepository('\Application\Entity\Napimuszak')->createQueryBuilder('nm')
					->select('nm')
					->join('nm.munkas', 'm')
					->join('m.munkastipus', 'mt')
					->where("nm.datum = '" . $from . "' AND mt.id = " . \Application\Entity\Munkastipus::BAROS_TIPUS_ID);
			$napibaros = $qb->getQuery()->getResult();
			if ($napibaros) {
				$barosReszesedes = $napibaros[0]->getMunkas()->getReszesedes($this->getEntityManager(), $from, $from);				
			}
		}
				
        foreach ($aktivitasok as $akt) {
            $idopont = clone $akt->getIdopont();
            $utolsoNap = new \DateTime($idopont->format('Y-m-t'));
            if ($akt->getCeg()->getFizetesihatarido() == -1) {
                $hatarido = $utolsoNap->format('Y-m-d');
            } else {
                $utolsoNap->modify('+'.($akt->getCeg()->getFizetesihatarido() + 1).' day');
                $hatarido = $utolsoNap->format('Y-m-d');
            }

            if ($hatarido <= $now->format('Y-m-d'))
                $kifizetetlenAktivitasok[] = $akt;
        }
        
        
        $cash = $this->params()->fromQuery('cash', '');
        if ($cash == 2) {
            $aktivitasok = $kifizetetlenAktivitasok;
        } else {
            $cashFilter = '';
            if ($cash == '0') {
                $cashFilter = " AND (a.tipus = " . \Application\Entity\Aktivitas::TIPUS_CEG ." OR a.tipus = " . \Application\Entity\Aktivitas::TIPUS_FIKO . ")";
            } elseif ($cash == '1') {
                $cashFilter = " AND (a.tipus <> " . \Application\Entity\Aktivitas::TIPUS_CEG ." AND a.tipus <> " . \Application\Entity\Aktivitas::TIPUS_FIKO . ")";
            } elseif ($cash == '2') {
                $cashFilter = " AND (a.ceg > 0 AND a.status = " . \Application\Entity\Aktivitas::STATUS_OPEN . ")";
            }

            $qb = $this->getEntityManager()->getRepository('\Application\Entity\Aktivitas')->createQueryBuilder('a')
                    ->select('a')
                    ->where("a.idopont >= '" . $from . "' AND a.idopont <= '" . $to . "'" . $cashFilter);
            $aktivitasok = $qb->getQuery()->getResult();

            $qb = $this->getEntityManager()->getRepository('\Application\Entity\AktivitasKellek')->createQueryBuilder('ak')
                    ->select('ak')
                    ->join('ak.aktivitas', 'a')
                    ->where("a.idopont >= '" . $from . "' AND a.idopont <= '" . $to . "'" . $cashFilter);
            $aktivitasKellekek = $qb->getQuery()->getResult();

            $aktivitasKellekekArray = array();
            foreach ($aktivitasKellekek as $ak) {
                $aktivitasKellekekArray[$ak->getAktivitas()->getId()][] = $ak->getKellek()->getNev() . ($ak->getMunkas() ? '('.$ak->getMunkas()->getName().')' : '');
            }
        }
        return new ViewModel(array('aktivitasok' => $aktivitasok, 'aktivitasKellekekArray' => $aktivitasKellekekArray, 'kifizetetlenCount' => count($osszesKifizetetlen), 'from' => $from, 'to' => $to, 'login' => $this->zfcUserAuthentication()->getIdentity(), 'cash' => $cash, 'barosReszesedes' => $barosReszesedes));
        
    }
    
    public function getformAction()
    {
        $form = new AktivitasForm($this->getEntityManager());
        
		$login = $this->zfcUserAuthentication()->getIdentity();
        if ((int)$this->params()->fromQuery('id')) {
            $id = (int)$this->params()->fromQuery('id');
            
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aktivitas');

            $form->setHydrator(new DoctrineEntity($this->getEntityManager(),'\Application\Entity\Aktivitas'));
            $aktivitas = $repo->findOneBy(array('id' => $id));
            
            if (!$aktivitas)
                return $this->redirect()->toRoute('aktivitas');
            
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($aktivitas->isEditableAndDeletable() == false) {
                    $viewModel = new ViewModel(array('form' => $form, 'modosithato' => false));
                    $viewModel->setTerminal(true);
                    return $viewModel;    
                    
                }
            }
            
            $form->bind($aktivitas);
            
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Reszesedes');
            $reszesedes = $repo->findOneBy(array('aktivitas' => $id, 'munkas' => \Application\Entity\Reszesedes::VULK_RESZ_MUNKAS_ID));
            if ($reszesedes)
                $form->get('vulk_20')->setValue ('1');
            else
                $form->get('vulk_20')->setValue ('0');
            
            if (!$aktivitas->getSzolgaltatas())
                $form->get('szolgaltatas')->setValue ('0');
            
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\AktivitasKellek');
            $aktivitasKellekek = $repo->findBy(array('aktivitas' => $aktivitas->getId()));
            if (count($aktivitasKellekek)) {
                $form->get('kellek_megkezdve')->setValue ('1');
                        
                $kellekekCheckboxValues = array();
                foreach ($aktivitasKellekek as $ak) {
                    $kellekekCheckboxValues[] = $ak->getKellek()->getId();
                    
                    if ($ak->getMunkas())
                        $form->get('kellek_munkas_' . $ak->getKellek()->getId())->setValue($ak->getMunkas()->getId());
                }
                
                $form->get('kellekek')->setValue($kellekekCheckboxValues);
            }
        } else {
            $form->get('status')->setValue(1);
			if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS)) {
				$form->get('idopont')->setAttribute('readonly', true);
			}
		}
		
        $viewModel = new ViewModel(array('form' => $form, 'modosithato' => true));
        $viewModel->setTerminal(true);
        return $viewModel;    
    }
	
    public function saveformAction()
    {
        $request = $this->getRequest();
        if ($request->isPost()) {
            $form = new AktivitasForm($this->getEntityManager());
            $form->setInputFilter(new \Application\Form\AktivitasFilter());
            $form->setData($request->getPost());
            
            if ($request->getPost('tipus') == \Application\Entity\Aktivitas::TIPUS_CEG)
                $form->getInputFilter ()->get('ceg')->setRequired (true);

            if ($request->getPost('szolgaltatas') == 0)
                $form->getInputFilter ()->get('nev')->setRequired (true);
            
            if ($form->isValid()) {
                if ($request->getPost('id')) {
                    //modositas
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aktivitas');
                    $aktivitas = $repo->findOneBy(array('id' => $request->getPost('id')));
                } else {
                    //uj aktivitas
                    $aktivitas = new Aktivitas();
                }

                $aktivitas->setHely($request->getPost('hely'));
                
                $aktivitas->setTipus($request->getPost('tipus'));
                
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
                $ceg = $repo->findOneBy(array('id' => (int)$request->getPost('ceg')));
                $aktivitas->setCeg($ceg);

                if ($request->getPost('szolgaltatas') == 0) {
                    $aktivitas->setNev($request->getPost('nev'));
                } else {
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
                    $szolgaltatas = $repo->findOneBy(array('id' => (int)$request->getPost('szolgaltatas')));
                    $aktivitas->setSzolgaltatas($szolgaltatas);
                    $aktivitas->setNev($szolgaltatas->getNev());
                }
                
                $aktivitas->setAr($request->getPost('ar'));
                $aktivitas->setViasz($request->getPost('viasz'));
				$aktivitas->setSma($request->getPost('sma'));
				$aktivitas->setSmac($request->getPost('smac'));
				$aktivitas->setSs($request->getPost('ss'));
                $aktivitas->setStatus($request->getPost('status'));
                
                if ($request->getPost('rendszam'))
                    $aktivitas->setRendszam($request->getPost('rendszam'));
                
                $aktivitas->setIdopont(new \DateTime($request->getPost('idopont')));
                
                foreach ($aktivitas->getMunkasok() as $munkas) {
                    $aktivitas->getMunkasObjects()->removeElement($munkas);
                }
                
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Reszesedes');
                $reszesedesek = $repo->findBy(array('aktivitas' => (int)$request->getPost('id')));
                foreach ($reszesedesek as $reszesedes) {
                    $this->getEntityManager()->remove($reszesedes);
                    $this->getEntityManager()->flush();
                }
                
				if ($request->getPost('munkasok')) {
					$hanyfeleOszlik = 0;
					$barosResz = false;
					foreach ($request->getPost('munkasok') as $munkasId) {
						$munkas = $this->getEntityManager()->getRepository('\Application\Entity\Munkas')
									->findOneBy(array('id' => $munkasId));
						$aktivitas->getMunkasObjects()->add($munkas);

						if ($munkas->getMunkastipus()->getId() != \Application\Entity\Munkastipus::BAROS_TIPUS_ID)
							$hanyfeleOszlik++;
						else
							$barosMunkas = $munkas;
					}
				}
                                                
                $this->getEntityManager()->persist($aktivitas);
                $this->getEntityManager()->flush();
                
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\AktivitasKellek');
                $aktivitasKellekek = $repo->findBy(array('aktivitas' => (int)$request->getPost('id')));
                
                foreach ($aktivitasKellekek as $ak) {
                    $this->getEntityManager()->remove($ak);
                    $this->getEntityManager()->flush();
                }
                
                if($request->getPost('kellekek')) {
                    foreach ($request->getPost('kellekek') as $kellekId) {
                        $kellek = $this->getEntityManager()->getRepository('\Application\Entity\Kellek')
                                    ->findOneBy(array('id' => $kellekId));

                        $ak = new \Application\Entity\AktivitasKellek();
                        $ak->setKellek($kellek);
                        $ak->setAktivitas($aktivitas);
                        
                        if ((int)$request->getPost('kellek_munkas_' . $kellekId)) {
                            $kellekMunkas = $this->getEntityManager()->getRepository('\Application\Entity\Munkas')->findOneBy(array('id' => $request->getPost('kellek_munkas_' . $kellekId)));
                            $ak->setMunkas($kellekMunkas);
                        }
                        
                        $this->getEntityManager()->persist($ak);
                        $this->getEntityManager()->flush();
                    }
                }
                
				if($request->getPost('munkasok')) {
					//a Fiko autoira nincs reszesedes az aktivitasbol az alkalmazottaknak
					if ((float)$request->getPost('ar') && $request->getPost('tipus') != \Application\Entity\Aktivitas::TIPUS_FIKO && ($request->getPost('tipus') != \Application\Entity\Aktivitas::TIPUS_CEG || $ceg->getSzazalekmunkas() != 0)) {

						$teljesAr = $aktivitas->getAr();
						
						if (isset($barosMunkas) && $request->getPost('tipus') != \Application\Entity\Aktivitas::TIPUS_CEG && $request->getPost('rendszam')) {
							$juttatasok = $this->getEntityManager()->getRepository('\Application\Entity\Juttatasok')
										->findOneBy(array('id' => 1));
							if ($juttatasok->getBaroscsaj()) {
								$reszesedesOsszeg = $juttatasok->getBaroscsaj();
								$reszesedes = new \Application\Entity\Reszesedes();
								$reszesedes->setAktivitas($aktivitas);
								$reszesedes->setMunkas($barosMunkas);
								$reszesedes->setReszesedes($reszesedesOsszeg);
								$this->getEntityManager()->persist($reszesedes);
								$this->getEntityManager()->flush();

								$teljesAr -= $reszesedesOsszeg;
							}
						}
						
						foreach ($request->getPost('munkasok') as $munkasId) {
							$munkas = $this->getEntityManager()->getRepository('\Application\Entity\Munkas')
										->findOneBy(array('id' => $munkasId));

							//a barosoknak minden nem fisas autoval kapcsolatos aktivitasbol jar resz (jelenleg 20 bani)
							if ($munkas->getMunkastipus()->getId() != \Application\Entity\Munkastipus::BAROS_TIPUS_ID) {
								$now = new \DateTime();
								$fizeteskategoria = $munkas->getFizetesKategoria($this->getEntityManager(), $now->format('Y-m-d'), '');

								//az aktivitasbol az alkalmazottaknak kulonbozo szazaleku reszesedes jar,
								//ez oszlik annyi fele, ahanyan elvegeztek
								$reszesedesOsszeg = $teljesAr * $fizeteskategoria->getSzazalek() / 100 / $hanyfeleOszlik;
								if ($fizeteskategoria->getSzazalek()) {
									$reszesedes = new \Application\Entity\Reszesedes();
									$reszesedes->setAktivitas($aktivitas);
									$reszesedes->setMunkas($munkas);
									$reszesedes->setReszesedes($reszesedesOsszeg);
									$this->getEntityManager()->persist($reszesedes);
									$this->getEntityManager()->flush();                        
								}
							}
						}
					}
				}
                return new JsonModel(array('id' => $aktivitas->getId()));
            }

            $result = new JsonModel($form->getMessages());
            return $result;
        } else {
            return $this->redirect()->toRoute('aktivitas');
        }
    }
    
    public function deleteAction()
    {
        $request = $this->getRequest();
        $response = $this->getResponse();
        if ($request->isPost()) {
            $repo = $this->getEntityManager()->getRepository('\Application\Entity\Aktivitas');
            $object = $repo->findOneBy(array('id' => (int)$request->getPost('id')));
            
            $login = $this->zfcUserAuthentication()->getIdentity();
            if ($login->hasRole(\SamUser\Entity\Role::ROLE_NAME_BAROS) || $login->hasRole(\SamUser\Entity\Role::ROLE_NAME_UGYVEZETO)) {
                if ($object->isEditableAndDeletable() == false) {
                    $response->setContent("nodelete");
                    return $response;
                }
            }
            
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
    
    public function getarAction()
    {
        $response = $this->getResponse();
        $tipus = $this->params()->fromQuery('tipus');
        $cegId = $this->params()->fromQuery('ceg');
        $szolgaltatasId = $this->params()->fromQuery('szolgaltatas');
        $viaszChecked = (int)$this->params()->fromQuery('viasz');
		$smaChecked = (int)$this->params()->fromQuery('sma');
		$smacChecked = (int)$this->params()->fromQuery('smac');
		$ssChecked = (int)$this->params()->fromQuery('ss');
        
        if ($szolgaltatasId == '0') {
            $response->setContent('');
        } else {
            if ($viaszChecked) {
                $viasz = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas')->findOneBy(array('id' => \Application\Entity\Szolgaltatas::VIASZ_ID));
                $viaszAlapar = $viasz->getAlapar();
                $viaszCegesar = $viasz->getCegesar();
            } else {
                $viaszAlapar = 0;
                $viaszCegesar = 0;
            }

            if ($smaChecked) {
                $sma = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas')->findOneBy(array('id' => \Application\Entity\Szolgaltatas::SMA_ID));
                $smaAlapar = $sma->getAlapar();
                $smaCegesar = $sma->getCegesar();
            } else {
                $smaAlapar = 0;
                $smaCegesar = 0;
            }

            if ($smacChecked) {
                $smac = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas')->findOneBy(array('id' => \Application\Entity\Szolgaltatas::SMAC_ID));
                $smacAlapar = $smac->getAlapar();
                $smacCegesar = $smac->getCegesar();
            } else {
                $smacAlapar = 0;
                $smacCegesar = 0;
            }
			
            if ($ssChecked) {
                $ss = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas')->findOneBy(array('id' => \Application\Entity\Szolgaltatas::SS_ID));
                $ssAlapar = $ss->getAlapar();
                $ssCegesar = $ss->getCegesar();
            } else {
                $ssAlapar = 0;
                $ssCegesar = 0;
            }
			
			
            if ($tipus == \Application\Entity\Aktivitas::TIPUS_INGYEN) {
                $response->setContent('0');
            } else if ($tipus == \Application\Entity\Aktivitas::TIPUS_NORMAL) {
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
                $szolgaltatas = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('szolgaltatas')));
                $response->setContent($szolgaltatas->getAlapar() + $viaszAlapar + $smaAlapar + $smacAlapar + $ssAlapar);
            } else if ($tipus == \Application\Entity\Aktivitas::TIPUS_FIDEL || $tipus == \Application\Entity\Aktivitas::TIPUS_FIKO) {
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
                $szolgaltatas = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('szolgaltatas')));
                $response->setContent($szolgaltatas->getCegesar() + $viaszCegesar + $smaCegesar + $smacCegesar + $ssCegesar);
            } else if ($tipus == \Application\Entity\Aktivitas::TIPUS_CEG) {
                $repo = $this->getEntityManager()->getRepository('\Application\Entity\SpecialisAr');
                $specialisAr = $repo->findOneBy(array('szolgaltatas' => (int)$this->params()->fromQuery('szolgaltatas'), 'ceg' => (int)$this->params()->fromQuery('ceg')));
                if ($specialisAr)
                    $response->setContent($specialisAr->getAr() + $viaszCegesar + $smaCegesar + $smacCegesar + $ssCegesar);
                else {
                    $repo = $this->getEntityManager()->getRepository('\Application\Entity\Szolgaltatas');
                    $szolgaltatas = $repo->findOneBy(array('id' => (int)$this->params()->fromQuery('szolgaltatas')));
                    $response->setContent($szolgaltatas->getCegesar() + $viaszCegesar + $smaCegesar + $smacCegesar + $ssCegesar);
                }
            }
        }
            
        return $response;
    }

    public function getfizetesihataridoAction()
    {
        $response = $this->getResponse();
        $cegId = $this->params()->fromQuery('ceg');
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Ceg');
        $ceg = $repo->findOneBy(array('id' => (int)$cegId));
        
        $response->setContent($ceg->getFizetesihatarido());
            
        return $response;
    }
    
    public function getrendszamokAction()
    {
        $response = $this->getResponse();
        $cegId = (int)$this->params()->fromQuery('ceg');
        
        $repo = $this->getEntityManager()->getRepository('\Application\Entity\Rendszam');
        $rendszamok = $repo->findBy(array('ceg' => $cegId));
        
        if (count($rendszamok)) {
            $rendszamokArray = array();
            foreach ($rendszamok as $rendszam) {
                $rendszamokArray[] = $rendszam->getRendszam();
            }

            $response->setContent("(" . implode(', ', $rendszamokArray) . ")");
        } else
            $response->setContent("");
        
        return $response;
    }
    
    public function masskifizetesAction()
    {
        $response = $this->getResponse();
        $request = $this->getRequest();
        if ($request->isPost()) {
            $ids = $request->getPost('ids');
            
            $qb = $this->getEntityManager()->getRepository('\Application\Entity\Aktivitas')->createQueryBuilder('ak')
                    ->select('ak')
                    ->where("ak.id IN(".  implode(',', $ids).")");
            $aktivitasok = $qb->getQuery()->getResult();
            
            foreach ($aktivitasok as $aktivitas) {
                $aktivitas->setStatus(\Application\Entity\Aktivitas::STATUS_CLOSED);
                $this->getEntityManager()->persist($aktivitas);
                $this->getEntityManager()->flush();                
            }
        }
        
        return $response;
    }
}
