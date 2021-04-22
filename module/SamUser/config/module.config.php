<?php
return array(
    'doctrine' => array(
        'driver' => array(
            // overriding zfc-user-doctrine-orm's config
            'zfcuser_entity' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'paths' => __DIR__ . '/../src/SamUser/Entity',
            ),

            'orm_default' => array(
                'drivers' => array(
                    'SamUser\Entity' => 'zfcuser_entity',
                ),
            ),
        ),
    ),

    'zfcuser' => array(
        // telling ZfcUser to use our own class
        'user_entity_class'       => 'SamUser\Entity\User',
        // telling ZfcUserDoctrineORM to skip the entities it defines
        'enable_default_entities' => false,
    ),

    'bjyauthorize' => array(
        'default_role' => 'guest',
        
        // Using the authentication identity provider, which basically reads the roles from the auth service's identity
        'identity_provider' => 'BjyAuthorize\Provider\Identity\AuthenticationIdentityProvider',

        'role_providers'        => array(
            // using an object repository (entity repository) to load all roles into our ACL
            'BjyAuthorize\Provider\Role\ObjectRepositoryProvider' => array(
                'object_manager'    => 'doctrine.entitymanager.orm_default',
                'role_entity_class' => 'SamUser\Entity\Role',
            ),
        ),
        
        'resource_providers' => array(
            'BjyAuthorize\Provider\Resource\Config' => array(
				'bar',
                'aktivitas',
                'munkas',
                'tartozas',
                'user',
                'ceg',
                'autokoltseg',
                'mindenkoltseg',
                'auto',
                'szolgaltatas',
                'kellek',
                'juttatasok',
                'fizeteskategoria',
                'ugyintezofizetes',
				'leltar',
				'aru',
				'kavefajta',
            )
        ),
        
        'rule_providers' => array(
            'BjyAuthorize\Provider\Rule\Config' => array(
                'allow' => array(
                    array(array('baros'), 'bar', 'list'),
					array(array('baros'), 'bar', 'letrehoz'),
					array(array('baros'), 'bar', 'modosit'),
					array(array('baros'), 'bar', 'torol'),
					
                    array(array('baros'), 'aktivitas', 'list'),
                    array(array('baros'), 'aktivitas', 'letrehoz'),
                    array(array('baros'), 'aktivitas', 'modosit'),
                    array(array('baros'), 'aktivitas', 'torol'),
                    array(array('admin'), 'aktivitas', 'fizet'),
                    
                    array(array('baros'), 'munkas', 'list'),
                    array(array('ugyvezeto'), 'munkas', 'letrehoz'),
                    array(array('ugyvezeto'), 'munkas', 'modosit'),
                    array(array('ugyvezeto'), 'munkas', 'torol'),

                    array(array('baros'), 'tartozas', 'list'),
                    array(array('baros'), 'tartozas', 'letrehoz'),
                    array(array('baros'), 'tartozas', 'modosit'),
                    array(array('baros'), 'tartozas', 'torol'),
                    array(array('admin'), 'tartozas', 'lezar'),
                    
                    array(array('baros'), 'ceg', 'list'),
                    array(array('ugyvezeto'), 'ceg', 'letrehoz'),
                    array(array('ugyvezeto'), 'ceg', 'modosit'),
                    array(array('ugyvezeto'), 'ceg', 'torol'),

                    array(array('baros'), 'szolgaltatas', 'list'),
                    array(array('ugyvezeto'), 'szolgaltatas', 'letrehoz'),
                    array(array('ugyvezeto'), 'szolgaltatas', 'modosit'),
                    array(array('ugyvezeto'), 'szolgaltatas', 'torol'),

                    array(array('ugyvezeto'), 'kellek', 'list'),
                    array(array('ugyvezeto'), 'kellek', 'letrehoz'),
                    array(array('ugyvezeto'), 'kellek', 'modosit'),
                    array(array('ugyvezeto'), 'kellek', 'torol'),

                    array(array('baros'), 'juttatasok', 'list'),
                    array(array('ugyvezeto'), 'juttatasok', 'modosit'),
                    
                    array(array('ugyvezeto'), 'autokoltseg', 'list'),
                    array(array('ugyvezeto'), 'autokoltseg', 'letrehoz'),
                    array(array('ugyvezeto'), 'autokoltseg', 'modosit'),
                    array(array('ugyvezeto'), 'autokoltseg', 'torol'),

                    array(array('baros'), 'mindenkoltseg', 'list'),
                    array(array('baros'), 'mindenkoltseg', 'letrehoz'),
                    array(array('baros'), 'mindenkoltseg', 'modosit'),
                    array(array('baros'), 'mindenkoltseg', 'torol'),
                    
                    array(array('admin'), 'auto', 'list'),
                    array(array('admin'), 'auto', 'letrehoz'),
                    array(array('admin'), 'auto', 'modosit'),
                    array(array('admin'), 'auto', 'torol'),

                    array(array('admin'), 'fizeteskategoria', 'list'),
                    
                    array(array('ugyvezeto'), 'ugyintezofizetes', 'list'),
                    array(array('admin'), 'ugyintezofizetes', 'letrehoz'),
                    array(array('admin'), 'ugyintezofizetes', 'modosit'),
                    array(array('admin'), 'ugyintezofizetes', 'torol'),

                    array(array('admin'), 'leltar', 'list'),
					
					array(array('admin'), 'aru', 'list'),
					array(array('admin'), 'aru', 'letrehoz'),
					array(array('admin'), 'aru', 'modosit'),
                    array(array('admin'), 'aru', 'torol'),
					
                    array(array('admin'), 'user', 'list'),
                    array(array('admin'), 'user', 'letrehoz'),
                    array(array('admin'), 'user', 'modosit'),
                    array(array('admin'), 'user', 'torol'),
					
                    array(array('admin'), 'kavefajta', 'list'),
                    array(array('admin'), 'kavefajta', 'letrehoz'),
                    array(array('admin'), 'kavefajta', 'modosit'),
                    array(array('admin'), 'kavefajta', 'torol'),
                ),
                'deny'  => array(

                ), 
            )
        ),
        
        'guards' => array(
            'BjyAuthorize\Guard\Controller' => array(
                array('controller' => 'zfcuser', 'roles' => array('guest', 'baros', 'ugyvezeto', 'admin')),
				array('controller' => 'Application\Controller\Bar', 'roles' => array('admin', 'baros', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Index', 'roles' => array('guest', 'baros', 'ugyvezeto', 'admin')),
                array('controller' => 'Application\Controller\User', 'roles' => array('admin')),
                array('controller' => 'Application\Controller\Teendo', 'roles' => array('admin', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Aktivitas', 'roles' => array('admin', 'baros', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Munkas', 'roles' => array('admin', 'baros', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Ceg', 'roles' => array('baros', 'admin', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Autokoltseg', 'roles' => array('baros', 'admin', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Koltseg', 'roles' => array('admin', 'baros', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Tartozas', 'roles' => array('baros', 'ugyvezeto', 'admin')),
                array('controller' => 'Application\Controller\Kocsi', 'roles' => array('admin')),
                array('controller' => 'Application\Controller\Szolgaltatas', 'roles' => array('admin', 'baros', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Kellek', 'roles' => array('baros', 'admin', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Juttatasok', 'roles' => array('baros', 'admin', 'ugyvezeto')),
                array('controller' => 'Application\Controller\Fizeteskategoria', 'roles' => array('admin')),
                array('controller' => 'Application\Controller\Ugyintezofizetes', 'roles' => array('admin', 'ugyvezeto')),
				array('controller' => 'Application\Controller\Leltar', 'roles' => array('admin')),
				array('controller' => 'Application\Controller\Aru', 'roles' => array('admin')),
				array('controller' => 'Application\Controller\Kavefajta', 'roles' => array('admin')),
            ),
        ),
    ),
);
