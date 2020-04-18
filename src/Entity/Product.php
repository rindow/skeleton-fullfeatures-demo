<?php
namespace Acme\MyApp\Entity;

use Interop\Lenient\Web\Form\Annotation as Form;
use Interop\Lenient\Validation\Annotation as Assert;
use Rindow\Stdlib\Entity\EntityTrait;

/**
 * @Form\Form(attributes={"method"="POST"})
 **/
class Product
{
    use EntityTrait;

    static public $colorNames = [1=>"Red",2=>"Green",3=>"Blue"];

    public function getColorNames()
    {
        return self::$colorNames;
    }

    /**
     * Id Field
     *
     * @Form\Input(type="hidden")
     */
    public $id;

    /**
     * Category Field
     */
    public $category;

    /**
     * @Form\Select(
     *      type="select",label="Category:",
     *      bindTo="category",
     *      mappedValue="idString",mappedLabel="name",
     *      mappedOptions="categoryOptions")
     * @Assert\NotBlank(path="category")
     * @Assert\NotNull(path="category")
     */
    public $categoryForm;
    public $categoryOptions;

    /**
     * Name Field
     *
     * @Form\Input(type="text",label="Name:")
     * @Assert\Size(min=1,max=8)
     * @Assert\NotBlank()
     * @Assert\NotNull()
     */
    public $name;

    /**
     * Color Field
     * @Form\Select(type="checkbox",label="Color:",options={1="Red",2="Green",3="Blue"},
     *      mappedValue="color")
     */
    public $colors;

    /**
     * @Assert\AssertTrue(path="colors",message = "must be Red or Green or Blue.")
     */
    public function isValidColor()
    {
        if($this->colors==null)
            return true;
        foreach ($this->colors as $color) {
            if(!is_numeric($color))
                return false;
            if($color<1 || $color>3)
                return false;
        }
        return true;
    }
}
