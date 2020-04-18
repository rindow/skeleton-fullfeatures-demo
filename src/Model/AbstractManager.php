<?php
namespace Acme\MyApp\Model;

use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\Inject;
use Rindow\Database\Dao\Repository\GenericPagingRepository;

abstract class AbstractManager
{
    protected function findAllEntities($repository,$pagination=null)
    {
        if($pagination) {
            $repository = new GenericPagingRepository($repository);
        }
        return $repository->findAll(null,['name'=>1]);
    }
}
