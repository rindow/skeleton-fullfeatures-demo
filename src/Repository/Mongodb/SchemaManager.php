<?php
namespace Acme\MyApp\Repository\Mongodb;

use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\NamedConfig;
use Rindow\Stdlib\Entity\EntityTrait;
use Acme\MyApp\Repository\SchemaManager as SchemaManagerInterface;
use DomainException;

/**
* @Named
*/
class SchemaManager implements SchemaManagerInterface
{
    use EntityTrait;
    protected $schema = [
        'products' => [
            ['name'=>'productName_idx','key'=>['name'=>1],'unique'=>true],
        ],
        'categories' => [
            ['name'=>'categoryName_idx','key'=>['name'=>1],'unique'=>true],
        ],
        'rindow_authusers' => [
            ['name'=>'rindow_authusersName_idx','key'=>['name'=>1],'unique'=>true],
        ],
    ];
    /**
    * @Inject({@Named("Acme\MyApp\DefaultDataSource")})
    */
    protected $dataSource;
    /**
    * @Inject({@NamedConfig("acme::myapp::collections")})
    */
    protected $repositories;
    protected $collection;

    protected function getTableNames()
    {
        return $this->repositories;
    }

    public function getConnection()
    {
        return $this->dataSource->getConnection();
    }

    public function createRepository($script=null)
    {
        //$this->assertCollection();
        $tables = $this->getTableNames();
        if(!$script)
            $connection = $this->getConnection();
        $scriptText = '';
        foreach ($tables as $name => $collection) {
            if(!$collection) {
                continue;
            }
            if(!array_key_exists($name,$this->schema)) {
                throw new DomainException('unknown tablename.: '.$name);
            }
            if(empty($this->schema)) {
                continue;
            }
            if(!$script) {
                $cmd = array(
                    'createIndexes'=> $collection,
                    'indexes' => $this->schema[$name],
                );
                $connection->executeCommand($cmd);
            } else {
                $scriptText .= "db.{$collection}.createIndex(".
                    json_encode($this->schema[$name],JSON_UNESCAPED_SLASHES).")\n";
            }
        }
        if($script)
            return $scriptText;
    }


    public function dropRepository($script=null)
    {
        if(!$script)
            $connection = $this->getConnection();
        $tables = $this->getTableNames();
        $scriptText = '';
        foreach ($tables as $name => $collection) {
            if(!$collection) {
                continue;
            }
            if(!array_key_exists($name,$this->schema)) {
                throw new DomainException('unknown tablename.: '.$name);
            }
            if(!$script) {
                $cmd = array('drop'=>$collection);
                $connection->executeCommand($cmd);
            } else {
                $scriptText .= "db.{$collection}.drop()\n";
            }
        }
        if($script)
            return $scriptText;
    }
}
