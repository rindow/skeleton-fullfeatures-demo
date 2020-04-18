<?php
namespace Acme\MyApp\Repository\Sql;

use Interop\Lenient\Container\Annotation\Inject;
use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\NamedConfig;
use Rindow\Stdlib\Entity\EntityTrait;
use Acme\MyApp\Repository\SchemaManager as SchemaManagerInterface;
use Acme\MyApp\Repository\Sql\SchemaBuilder;
use DomainException;

/**
* @Named
*/
class SchemaManager implements SchemaManagerInterface
{
    use EntityTrait;

    /**
    * @Inject({@Named("Acme\MyApp\DefaultDataSource")})
    */
    protected $dataSource;
    /**
    * @Inject({@NamedConfig("acme::myapp::sql::builderAliases")})
    */
    protected $builderAliases;
    /**
    * @Inject({@NamedConfig("acme::myapp::collections")})
    */
    protected $repositories;

    protected function getTableNames()
    {
        return $this->repositories;
    }

    public function createRepository($script=null)
    {
        $builder = $this->getSchemaBuilder();
        $sqls = $builder::createSchema($this->getTableNames());
        if($script)
            return $this->generateScript($sqls);
        $this->executeUpdate($sqls);
    }

    public function dropRepository($script=null)
    {
        $builder = $this->getSchemaBuilder();
        $sqls = $builder::dropSchema($this->getTableNames());
        if($script)
            return $this->generateScript($sqls);
        $this->executeUpdate($sqls);
    }

    protected function executeUpdate($sqls)
    {
        $connection = $this->dataSource->getConnection();
        foreach ($sqls as $sql) {
            $connection->executeUpdate($sql);
        }
    }

    protected function generateScript($sqls)
    {
        $script = '';
        foreach ($sqls as $sql) {
            $script .= $sql.";\n";
        }
        return $script;
    }

    protected function getSchemaBuilder()
    {
        $connection = $this->dataSource->getConnection();
        $slugName = $connection->getDriverName();
        if(!isset($this->builderAliases[$slugName]))
            throw new DomainException('sqlBuilder is not specified for driver "'.$slugName.'".');
        return $this->builderAliases[$slugName];
    }
}
