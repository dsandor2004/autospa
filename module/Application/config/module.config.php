<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

return array(
    'doctrine' => array(
        'driver' => array(
            'application_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/Application/Entity',
            ),

            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entity',
                ),
            ),
        ),
    ),
    
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route'    => '/',
                    'defaults' => array(
                        'controller' => 'Application\Controller\Index',
                        'action'     => 'index',
                    ),
                ),
            ),

            'teendo' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/teendo[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Teendo',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'aktivitas' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/aktivitas[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Aktivitas',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'munkas' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/munkas[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Munkas',
                        'action'     => 'index',
                    ),
                ),
            ),

            'ceg' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/ceg[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Ceg',
                        'action'     => 'index',
                    ),
                ),
            ),

            'autokoltseg' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/autokoltseg[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Autokoltseg',
                        'action'     => 'index',
                    ),
                ),
            ),

            'koltseg' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/koltseg[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Koltseg',
                        'action'     => 'index',
                    ),
                ),
            ),

            'tartozas' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/tartozas[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Tartozas',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'kocsi' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/kocsi[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Kocsi',
                        'action'     => 'index',
                    ),
                ),
            ),
            
            'szolgaltatas' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/szolgaltatas[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Szolgaltatas',
                        'action'     => 'index',
                    ),
                ),
            ),

            'kellek' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/kellek[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Kellek',
                        'action'     => 'index',
                    ),
                ),
            ),

            'juttatasok' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/juttatasok[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Juttatasok',
                        'action'     => 'index',
                    ),
                ),
            ),

            'fizeteskategoria' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/fizeteskategoria[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Fizeteskategoria',
                        'action'     => 'index',
                    ),
                ),
            ),

            'ugyintezofizetes' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/ugyintezofizetes[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Ugyintezofizetes',
                        'action'     => 'index',
                    ),
                ),
            ),

            'leltar' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/leltar[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Leltar',
                        'action'     => 'index',
                    ),
                ),
            ),

            'bar' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/bar[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'datum'     => '-[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Bar',
                        'action'     => 'index',
                    ),
                ),
            ),
			
            'aru' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/aru[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Aru',
                        'action'     => 'index',
                    ),
                ),
            ),

            'kavefajta' => array(
                'type'    => 'Segment', // <- I added S
                'options' => array(
                    'route'       => '/kavefajta[/][:action][/:id]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                        'id'     => '[0-9]+',
                    ),
                    'defaults'    => array(
                        'controller' => 'Application\Controller\Kavefajta',
                        'action'     => 'index',
                    ),
                ),
            ),
			
            'zfcuser' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route' => '/user',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'index',
                    ),
                ),
                'may_terminate' => true,
            ),

            'zfcuser-getform' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/users/getform',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'getform',
                    ),
                ),
            ),

            'zfcuser-saveform' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/users/saveform',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'saveform',
                    ),
                ),
            ),

            'zfcuser-delete' => array(
                'type' => 'Literal',
                'options' => array(
                    'route' => '/users/delete',
                    'defaults' => array(
                        'controller' => 'Application\Controller\User',
                        'action'     => 'delete',
                    ),
                ),
            ),
            
            // The following is a route to simplify getting started creating
            // new controllers and actions without needing to create a new
            // module. Simply drop new controllers in, and you can access them
            // using the path /application/:controller/:action
            'application' => array(
                'type'    => 'Literal',
                'options' => array(
                    'route'    => '/application',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller'    => 'Index',
                        'action'        => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type'    => 'Segment',
                        'options' => array(
                            'route'    => '/[:controller[/:action]]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action'     => '[a-zA-Z][a-zA-Z0-9_-]*',
                            ),
                            'defaults' => array(
                            ),
                        ),
                    ),
                ),
            ),
        ),
    ),
    
    'service_manager' => array(
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
            'Zend\Log\LoggerAbstractServiceFactory',
        ),
        'aliases' => array(
            'translator' => 'MvcTranslator',
        ),
        
        'factories' => array(
            'navigation' => 'Zend\Navigation\Service\DefaultNavigationFactory',
        ),
        
    ),
    'translator' => array(
        'locale' => 'en_US',
        'translation_file_patterns' => array(
            array(
                'type'     => 'gettext',
                'base_dir' => __DIR__ . '/../language',
                'pattern'  => '%s.mo',
            ),
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Index'              => 'Application\Controller\IndexController',
            'Application\Controller\Teendo'             => 'Application\Controller\TeendoController',
            'Application\Controller\Aktivitas'          => 'Application\Controller\AktivitasController',
            'Application\Controller\Munkas'             => 'Application\Controller\MunkasController',
            'Application\Controller\User'               => 'Application\Controller\UserController',
            'Application\Controller\Ceg'                => 'Application\Controller\CegController',
            'Application\Controller\Autokoltseg'        => 'Application\Controller\AutokoltsegController',
            'Application\Controller\Koltseg'            => 'Application\Controller\KoltsegController',
            'Application\Controller\Tartozas'           => 'Application\Controller\TartozasController',
            'Application\Controller\Kocsi'              => 'Application\Controller\KocsiController',
            'Application\Controller\Szolgaltatas'       => 'Application\Controller\SzolgaltatasController',
            'Application\Controller\Kellek'             => 'Application\Controller\KellekController',
            'Application\Controller\Juttatasok'         => 'Application\Controller\JuttatasokController',
            'Application\Controller\Fizeteskategoria'   => 'Application\Controller\FizeteskategoriaController',
            'Application\Controller\Ugyintezofizetes'   => 'Application\Controller\UgyintezofizetesController',
			'Application\Controller\Leltar'				=> 'Application\Controller\LeltarController',
			'Application\Controller\Aru'				=> 'Application\Controller\AruController',
			'Application\Controller\Bar'				=> 'Application\Controller\BarController',
			'Application\Controller\Kavefajta'			=> 'Application\Controller\KavefajtaController',
        ),
    ),
    'controller_plugins' => array(
        'invokables' => array(
            'globalFunctions' => 'Application\Controller\Plugin\GlobalFunctions',
        )
    ),    
    'view_helpers' => array(
        'invokables'=> array(
            'accent_trimmer' => 'Application\Helper\AccentTrimmer',
            'fizeteskategoria' => 'Application\Helper\FizetesKategoria',
        )
    ),
    'view_manager' => array(
        'display_not_found_reason' => true,
        'display_exceptions'       => true,
        'doctype'                  => 'HTML5',
        'not_found_template'       => 'error/404',
        'exception_template'       => 'error/index',
        'template_map' => array(
            'layout/layout'           => __DIR__ . '/../view/layout/layout.phtml',
            'application/index/index' => __DIR__ . '/../view/application/index/index.phtml',
            'error/403'               => __DIR__ . '/../view/error/403.phtml',
            'error/404'               => __DIR__ . '/../view/error/404.phtml',
            'error/index'             => __DIR__ . '/../view/error/index.phtml',
        ),
        'template_path_stack' => array(
            __DIR__ . '/../view',
            'zfcuser' => __DIR__ . '/../view',
        ),
        'strategies' => array(
            'ViewJsonStrategy',
        ),        
    ),
    // Placeholder for console routes
    'console' => array(
        'router' => array(
            'routes' => array(
            ),
        ),
    ),
    
    'navigation' => array(
        'default' => array(
            array(
                'label' => 'Bár',
                'route' => 'bar',
                'resource' => 'bar',
                'privilege' => 'list',
            ),
            array(
                'label' => 'Aktivitások',
                'route' => 'aktivitas',
                'resource' => 'aktivitas',
                'privilege' => 'list',
            ),
            array(
                'label' => 'Alkalmazottak',
                'route' => 'munkas',
                'resource' => 'munkas',
                'privilege' => 'list',
            ),
            array(
                'label' => 'Felhasználók',
                'route' => 'zfcuser',
                'resource' => 'user',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Szerződések',
                'route' => 'ceg',
                'resource' => 'ceg',
                'privilege' => 'list',
            ),
            array(
                'label' => 'Szolgáltatások',
                'route' => 'szolgaltatas',
                'resource' => 'szolgaltatas',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Kellékek',
                'route' => 'kellek',
                'resource' => 'kellek',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Juttatások',
                'route' => 'juttatasok',
                'resource' => 'juttatasok',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Autóköltségek',
                'route' => 'autokoltseg',
                'resource' => 'autokoltseg',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Autók',
                'route' => 'kocsi',
                'resource' => 'auto',
                'privilege' => 'list',                       
            ),
            array(
                'label' => 'Egyéb költségek',
                'route' => 'koltseg',
                'resource' => 'mindenkoltseg',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Fizetéskategóriák',
                'route' => 'fizeteskategoria',
                'resource' => 'fizeteskategoria',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Ügyintéző Fizetés',
                'route' => 'ugyintezofizetes',
                'resource' => 'ugyintezofizetes',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Leltár',
                'route' => 'leltar',
                'resource' => 'leltar',
                'privilege' => 'list',
            ),            
            array(
                'label' => 'Kávéfajták',
                'route' => 'kavefajta',
                'resource' => 'kavefajta',
                'privilege' => 'list',
            ),            
        ),
    ),
);
