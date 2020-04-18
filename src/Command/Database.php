<?php
namespace Acme\MyApp\Command;

use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\Inject;
use Rindow\Stdlib\Entity\EntityTrait;
use Rindow\Console\Command\Arguments;
use Rindow\Console\Exception\InvalidCommaindLineOptionException;

/**
 * @Named
 */
class Database
{
    use EntityTrait;
    /**
    * @Inject({@Named("Rindow\Console\Display\DefaultOutput")})
    */
    protected $output;

    /**
    * @Inject({@Named("Acme\MyApp\Repository\SchemaManager")})
    */
    protected $schemaManager;

    public function create($argv)
    {
        $args = new Arguments($argv,'s',array('script'));
        $options = $args->getOptions();
        if(isset($options['s']) || isset($options['script'])) {
            $script = true;
        } else {
            $script = false;
        }
        if(!$script)
            $this->output->printfln('Creating database schema...');
        $text = $this->schemaManager->createRepository($script);
        if(!$script)
            $this->output->printfln('Database schema created successfully.');
        if($script)
            $this->output->write($text);
    }

    public function drop($argv)
    {
        $args = new Arguments($argv,'s',array('force','script'));
        $options = $args->getOptions();
        if(isset($options['s']) || isset($options['script'])) {
            $script = true;
        } else {
            $script = false;
        }
        if(!$script && !isset($options['force'])) {
            throw new InvalidCommaindLineOptionException('Please run the drop command with --force option to execute.');
        }
        if(!$script)
            $this->output->printfln('Dropping database schema...');
        $text = $this->schemaManager->dropRepository($script);
        if(!$script)
            $this->output->printfln('Database schema dropped successfully.');
        if($script)
            $this->output->write($text);
    }
}
