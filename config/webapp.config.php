<?php
return [
    'module_manager' => [
        //'version' => 1,
        'modules' => [
            Rindow\Container\Module::class     => true,
            Rindow\Aop\Module::class           => true,
            Rindow\Web\Mvc\Module::class       => true,
            Rindow\Web\Http\Module::class      => true,
            Rindow\Web\Router\Module::class    => true,
            Rindow\Web\View\Module::class      => false,  // PHP view
            Rindow\Module\Twig\Module::class   => true,   // Twig view
            Rindow\Module\Smarty\Module::class => false,  // Smarty view
            Rindow\Web\Form\Module::class      => true,
            Rindow\Web\Session\Module::class   => true,
            Rindow\Web\Security\Csrf\Module::class  => true,
            Rindow\Validation\Module::class    => true,
            Rindow\Stdlib\I18n\Module::class  => true,
            Rindow\Console\Module::class  => true,
            Rindow\Transaction\Local\Module::class =>  true,
            Rindow\Security\Core\Module::class  => true,
            Rindow\Database\Dao\Sql\Module::class => true,
            Rindow\Database\Pdo\LocalTxModule::class => true,
            Rindow\Module\Mongodb\LocalTxModule::class => false,
            Rindow\Module\Google\Cloud\LocalTxModule::class => false,
            Rindow\Module\Monolog\Module::class => true,
            Rindow\Web\Security\Authentication\FormAuthModule::class=>true,
            Rindow\Web\Security\Authentication\TokenBasedRememberMeModule::class=>true,
            // If you want to use "Whoops", Install ando Uncomment the Whoops module.
            //'Rindow\Module\Whoops\Module' => true,
            Acme\MyApp\Module::class          => true,
        ],
        'imports' => [
            __DIR__.'/local' => '@\.php$@',
        ],
        ## If you use Google Cloud, please uncomment and enable the following *manually*.
        'configCacheFactoryClass' => Rindow\Module\Google\Cloud\System\ServiceFactory::class,

        'annotation_manager' => true,
        'autorun' => Rindow\Web\Mvc\Module::class,
    ],
    'cache' => [
        'filePath'   => __DIR__.'/../cache',
    ],
    'web' => [
        'view' => [
            'view_managers' => [
                'default' => [
                    'template_paths' => __DIR__.'/../resources/views/global',
                    'layout' => 'layout/bootstrap4',
                    //'layout' => 'layout/foundation6',
                    //'layout' => 'layout/mdl13',
                ],
            ],
            'templates' => [
                'paginator' => 'partial/paginator_bootstrap',
                //'paginator' => 'partial/paginator_foundation',
                //'paginator' => 'partial/paginator_mdl',
            ]
        ],
        'error_page_handler' => [
            'error_policy' => [
                'display_detail' => true,
                // If you need to redirect:
                //'redirect_url' => '/',
            ],
        ],
        'form' => [
            'themes' => [
                'default'    => 'Rindow\Web\Form\View\Theme\Bootstrap4Horizontal',
                //'default'    => 'Rindow\Web\Form\View\Theme\Bootstrap3Horizontal',
                //'default'    => 'Rindow\Web\Form\View\Theme\Foundation6Horizontal',
                //'default'    => 'Rindow\Web\Form\View\Theme\Foundation5Horizontal',
                //'default'    => 'Rindow\Web\Form\View\Theme\Mdl13Horizontal',
            ],
        ],
    ],
    'security' => [
        'secret' => '---secret very long random string---',
        'authentication'=>[
            'default'=>[
                'repositoryName'=>'rindow_authusers',
                'authoritiesRepositoryName'=>'rindow_authorities',
                'securityContext' => [
                    'lifetime' =>  3600, //'Number of seconds of lifetime.',
                ],
            ],
        ],
    ],
    'aop' => [
        //'debug' => true,
    ],
    'container' => [
        //'debug' => true,

        'components' => [
            // If you want to enable debug logging, you can inject a logger to a transaction manager.
            //
            //'Rindow\\Database\\Pdo\\Transaction\\DefaultTransactionManager' => [
            //    'properties' => [
            //        'debug' => ['value'=>true],
            //        'logger' => ['ref'=>'Logger'],
            //    ],
            //],
        ],
    ],
    'database' => [
        'connections' => [
            'default' => [
                //'dsn' => 'sqlite:your_database_file',
                'dsn' => 'sqlite:'.__DIR__.'/../data/db.sqlite',
            ],
            'mysql' => [
                'dsn' => "mysql:host=127.0.0.1;dbname=databasename",
                'user'   => 'your_database_user',
                'password' => 'your_database_password',
                'options' => [
                    //PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ],
            ],
            'mongodb' => [
                'dbname' => 'test',
            ],
            'pgsql' => [
                'dsn' => "pgsql:host=localhost;dbname=databasename",
                'user'     => "dbuser",
                'password' => "dbpassword",
            ]
        ],
    ],
    'monolog' => [
        'handlers' => [
            'default' => [
                'path'  => __DIR__.'/../log/debug.log',
            ],
        ],
    ],
    'googleAppEngine' => [
        'scriptNames' => [
            '/public/index.php' => '/index.php',
        ],
    ],
    'acme' => [
        'myapp' => [
            'collections' => [
                'categories' => 'demo_categories',
                'products'   => 'demo_products',
                'colors'     => 'demo_colors',
                'rindow_authusers' => 'rindow_authusers',
                'rindow_authorities' => 'rindow_authorities',
            ],
        ],
    ],
];
