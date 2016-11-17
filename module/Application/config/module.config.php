<?php

namespace Application;

return array(
    'router' => array(
        'routes' => array(
            'home' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Album',
                        'action' => 'index',
                    ),
                ),
            ),
            'album' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/album',
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller',
                        'controller' => 'Application\Controller\Album',
                        'action' => 'index',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '[:controller][/:action][/:id][/:id2][/]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                                'id2' => '[0-9]+',
                            ),
                            'defaults' => array(
                                'action' => 'index'
                            ),
                        ),
                    ),
                ),
            ),
            'api' => array(
                'type' => 'Zend\Mvc\Router\Http\Literal',
                'options' => array(
                    'route' => '/api/v1',
                    'constraints' => array(),
                    'defaults' => array(
                        '__NAMESPACE__' => 'Application\Controller\Api',
                    ),
                ),
                'may_terminate' => true,
                'child_routes' => array(
                    'default' => array(
                        'type' => 'Zend\Mvc\Router\Http\Segment',
                        'options' => array(
                            'route' => '/[:controller][/:action][/:id][/]',
                            'constraints' => array(
                                'controller' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                                'id' => '[0-9]+',
                            ),
                            'defaults' => array(),
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
        'factories' => array(
            'translator' => 'Zend\Mvc\Service\TranslatorServiceFactory',
            'logger' => 'Application\Factory\Service\LoggerServiceFactory',
            'pagination' => 'Application\Factory\Service\PaginationServiceFactory',
            'album' => 'Application\Factory\Service\AlbumServiceFactory',
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Application\Controller\Error' => Controller\ErrorController::class,
        ),
        'factories' => array(
            'Application\Controller\Album' => Factory\Controller\AlbumFactory::class,
            'Application\Controller\Api\Albums' => Factory\Controller\Api\AlbumsFactory::class,
        )
    ),
    'view_helpers' => array(
        'invokables' => array(),
        'factories' => array(
            'utils' => '\Application\View\Helper\UtilsHelperFactory',
        )
    ),
    'doctrine' => array(
        'driver' => array(
            'application_entities' => array(
                'class' => 'Doctrine\ORM\Mapping\Driver\AnnotationDriver',
                'cache' => 'array',
                'paths' => array(__DIR__ . '/../src/Application/Entity')
            ),
            'orm_default' => array(
                'drivers' => array(
                    'Application\Entity' => 'application_entities',
                ),
            )
        ),
        'fixture' => array(
            'Application_fixture' => __DIR__ . '/../test/Fixture',
        )
    ),
    'view_manager' => [
        'display_not_found_reason' => true,
        'display_exceptions' => true,
        'doctype' => 'HTML5',
        'not_found_template' => 'error/404',
        'exception_template' => 'error/index',
        'template_map' => [
            'layout/layout' => __DIR__ . '/../view/layout/layout.phtml',
            'error/404' => __DIR__ . '/../view/error/404.phtml',
            'layout/404' => __DIR__ . '/../view/error/404.phtml',
            'layout/error' => __DIR__ . '/../view/error/error.phtml',
        ],
        'template_path_stack' => [
            __DIR__ . '/../view',
        ],
        'strategies' => array(
            'ViewJsonStrategy',
        ),
    ],
);
