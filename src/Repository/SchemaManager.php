<?php
namespace Acme\MyApp\Repository;

interface SchemaManager
{
    public function createRepository($script=null);
    public function dropRepository($script=null);
}
