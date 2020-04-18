<?php
use Rindow\Web\Security\Authentication\FormAuthModule;
return [
    'web' => [
        'router' => [
            'builders' => [
                'annotation' => [
                    'controller_paths' => [
                        __DIR__.'/../../Controller' => true,
                    ],
                ],
            ],
            //'pathPrefixes' => [
            //    'Acme\\MyApp' => '/myapp',
            //],
            'routes' => [
                FormAuthModule::ROUTE_LOGIN => array(
                    'path' => '/login',
                ),
                FormAuthModule::ROUTE_LOGOUT => array(
                    'path' => '/logout',
                ),
            ]
        ],
        'view' => [
            'view_managers' => [
                $namespace => [
                    'template_paths' => __DIR__.'/../views/local',
                ],
            ],
            'templates' => [
                'paginator' => 'partial/paginator_bootstrap',
            ]
        ],
    ],
    'console' => [
        'commands' => [
            $namespace => [
                'create-schema' => [
                    'component' => 'Acme\\MyApp\\Command\\Database',
                    'method' => 'create',
                ],
                'drop-schema' => [
                    'component' => 'Acme\\MyApp\\Command\\Database',
                    'method' => 'drop',
                ],
                'user-add' => [
                    'component' => 'Acme\\MyApp\\Command\\User',
                    'method' => 'add',
                ],
                'user-delete' => [
                    'component' => 'Acme\\MyApp\\Command\\User',
                    'method' => 'delete',
                ],
                'user-show' => [
                    'component' => 'Acme\\MyApp\\Command\\User',
                    'method' => 'show',
                ],
                'user-modify' => [
                    'component' => 'Acme\\MyApp\\Command\\User',
                    'method' => 'modify',
                ],
            ],
        ],
    ],
    'container' => [
        'aliases' => [
            // Default Resources

            // Pdo
            $namespace.'\\DefaultDataSource' => 'Rindow\\Database\\Pdo\\Transaction\\DefaultDataSource',

            // MongoDB
            //'Database'      => 'Rindow\Module\Mongodb\DefaultConnection',

            // Repositories
            $namespace.'\\Repository\\ProductRepository'  => $namespace.'\\Repository\\Sql\\ProductSqlRepository',
            $namespace.'\\Repository\\CategoryRepository' => $namespace.'\\Repository\\Sql\\CategorySqlRepository',
            Acme\MyApp\Repository\SchemaManager::class => Acme\MyApp\Repository\Sql\SchemaManager::class,
            $namespace.'\\Hydrator' => $namespace.'\\Hydrator\\PropertyHydrator',

            // UserManager
            'Rindow\\Security\\Core\\Authentication\\DefaultUserDetailsService' => 'Rindow\\Security\\Core\\Authentication\\DefaultCrudRepositoryUserDetailsManager',
            'Rindow\\Security\\Core\\Authentication\\DefaultUserDetailsRepository' => 'Rindow\\Security\\Core\\Authentication\\DefaultUserDetailsSqlRepository',


        ],
        'components' => [
            $namespace.'\\Hydrator\\PropertyHydrator' => [
                'class' => Rindow\Stdlib\Entity\PropertyHydrator::class
            ],
            //
            // Repositories for SQL
            //
            $namespace.'\\Repository\\Sql\\ProductSqlRepository' => [
                'parent'=>'Rindow\\Database\\Dao\\Repository\\AbstractSqlRepository',
                'class' => Acme\MyApp\Repository\Sql\ProductSqlRepository::class,
                'properties' => [
                    'tableName' => ['config'=>'acme::myapp::collections::products'],
                    'colorsTableName' => ['config'=>'acme::myapp::collections::colors'],
                    'dataMapper' => ['ref'=>$namespace.'\\Repository\\ProductMapper'],
                ],
            ],
            $namespace.'\\Repository\\Sql\\CategorySqlRepository' => [
                'parent'=>'Rindow\\Database\\Dao\\Repository\\AbstractSqlRepository',
                'properties' => [
                    'tableName' => ['config'=>'acme::myapp::collections::categories'],
                    'dataMapper' => ['ref'=>$namespace.'\\Repository\\CategoryMapper'],
                ],
            ],
            //
            // Repositories for Mongodb
            //
            $namespace.'\\Repository\\Mongodb\\ProductRepository' => [
                'parent'=>'Rindow\\Module\\Mongodb\\Repository\\AbstractRepository',
                'properties' => [
                    'collection' => ['config'=>'acme::myapp::collections::products'],
                    'dataMapper' => ['ref'=>$namespace.'\\Repository\\ProductMapper'],
                ],
            ],
            $namespace.'\\Repository\\Mongodb\\CategoryRepository' => [
                'parent'=>'Rindow\\Module\\Mongodb\\Repository\\AbstractRepository',
                'properties' => [
                    'collection' => ['config'=>'acme::myapp::collections::categories'],
                    'dataMapper' => ['ref'=>$namespace.'\\Repository\\CategoryMapper'],
                ],
            ],

            $namespace.'\\Repository\\Mongodb\\DefaultUserDetailsMongodbRepository'=>[
                'parent'=>'Rindow\\Module\\Mongodb\\Repository\\AbstractRepository',
                'properties' => [
                    'collection' => ['config'=>'security::authentication::default::repositoryName'],
                ],
            ],
            //
            // Repositories for Google Cloud Datastore
            //
            $namespace.'\\Repository\\Google\\Cloud\\ProductRepository' => [
                'parent'=>'Rindow\\Module\\Google\\Cloud\\Repository\\AbstractGoogleCloudRepository',
                'properties' => [
                    'kindName' => ['config'=>'acme::myapp::collections::products'],
                    'dataMapper' => ['ref'=>$namespace.'\\Repository\\ProductMapper'],
                    'unique'=>['value'=>[
                        'name'=>true,
                    ]],
                    // 'unindexed' => ['value' => [
                    //     'field name' => true,
                    // ]],
                ],
            ],
            $namespace.'\\Repository\\Google\\Cloud\\CategoryRepository' => [
                'parent'=>'Rindow\\Module\\Google\\Cloud\\Repository\\AbstractGoogleCloudRepository',
                'properties' => [
                    'kindName' => ['config'=>'acme::myapp::collections::categories'],
                    'dataMapper' => ['ref'=>$namespace.'\\Repository\\CategoryMapper'],
                    'unique'=>['value'=>[
                        'name'=>true,
                    ]],
                    // 'unindexed' => ['value' => [
                    //     'field name' => true,
                    // ]],
                ],
            ],
            $namespace.'\\Repository\\Google\\Cloud\\DefaultUserDetailsGoogleCloudRepository'=>[
                'parent'=>'Rindow\\Module\\Google\\Cloud\\Repository\\AbstractGoogleCloudRepository',
                'properties' => [
                    'kindName' => ['config'=>'security::authentication::default::repositoryName'],
                ],
            ],
        ],
        'component_paths' => [
            // Register named components to use the AOP on the "Container::PROXY_MODE_COMPONENT" mode.
            __DIR__.'/../../Model' => true,
            __DIR__.'/../../Controller' => true,
            __DIR__.'/../../Repository' => true,
            __DIR__.'/../../Command' => true,
        ],
    ],
    'security' => [
        'web' => [
            'forceXsrfTokenValidation' => [
                'excludingPaths' => [
                    '/api/category' => true,
                ],
            ],
        ],
    ],
    'database' => [
        'repository' => [
            'GenericSqlRepository' => [
                'extends' => [
                    Acme\MyApp\Repository\Sql\ProductSqlRepository::class => true,
                ]
            ],
        ],
    ],
    'acme' => [
        'myapp' => [
            'sql' => [
                'builderAliases' => [
                    'sqlite' => Acme\MyApp\Repository\Sql\SchemaBuilder\Sqlite::class,
                    'mysql'  => Acme\MyApp\Repository\Sql\SchemaBuilder\Mysql::class,
                    'pgsql'  => Acme\MyApp\Repository\Sql\SchemaBuilder\Pgsql::class,
                ],
            ],
        ],
    ],
];
