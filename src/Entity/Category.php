<?php
namespace Acme\MyApp\Entity;

use Interop\Lenient\Web\Form\Annotation as Form;
use Interop\Lenient\Validation\Annotation as Assert;
use Rindow\Stdlib\Entity\EntityTrait;

/**
 * @Form\Form(attributes={"method"="POST"})
 **/
class Category
{
    use EntityTrait;
    /**
     * @Form\Input(type="hidden")
     */
    public $id;

    /**
     * @Form\Input(type="text",label="Name")
     * @Assert\Size(min=1,max=8)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public $name;

    public function getIdString()
    {
        return strval($this->id);
    }
}
