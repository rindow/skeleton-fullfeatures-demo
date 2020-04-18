<?php
namespace Acme\MyApp\Command;

use Interop\Lenient\Container\Annotation\Named;
use Interop\Lenient\Container\Annotation\Inject;
use Rindow\Stdlib\Entity\EntityTrait;
use Rindow\Console\Command\Arguments;
use Rindow\Console\Exception\InvalidCommaindLineOptionException;
use Rindow\Security\Core\Authentication\UserDetails\User as UserInfo;
use Rindow\Security\Core\Authentication\Exception\DuplicateUsernameException;

/**
 * @Named
 */
class User
{
    use EntityTrait;
    /**
    * @Inject({@Named("Rindow\Console\Display\DefaultOutput")})
    */
    protected $output;

    /**
    * @Inject({@Named("Rindow\Security\Core\Authentication\DefaultUserDetailsService")})
    */
    protected $userManager;

    /**
    * @Inject({@Named("Rindow\Security\Core\Authentication\DefaultDaoAuthenticationProvider")})
    */
    protected $authenticationProvider;

    protected $usages = [
        'add' => [
            'user-add [options] username',
            'options:',
            '  -p|--password password',
            '  -G|--groups group,group,group....',
        ],
        'delete' => [
            'user-delete username',
        ],
        'show' => [
            'user-show username',
        ],
        'modify' => [
            'user-modify [options] username',
            'options:',
            '  -p|--password password',
            '  -G|--groups group,group,group....',
            '  -a|--append',
        ],
    ];

    public function add($argv)
    {
        $args = new Arguments($argv,'p:G:',array('password','groups'));
        $arguments = $args->getArguments();
        $this->showUsage('add',$arguments);
        $username = $this->parseUsername($arguments);
        $options = $args->getOptions();
        $password = $this->parsePassword($options);
        if($password)
            $password = $this->encodePassword($password);
        $authorities = $this->parseAuthorities($options);
        if(empty($authorities))
            $authorities = ['ROLE_USER'];
        try {
            $user = new UserInfo($username,$password,$authorities);
            $this->userManager->createUser($user);
        } catch(DuplicateUsernameException $e) {
            throw new InvalidCommaindLineOptionException('Duplicate user name.');
        }
    }

    public function delete($argv)
    {
        $args = new Arguments($argv);
        $arguments = $args->getArguments();
        $this->showUsage('delete',$arguments);
        $username = $this->parseUsername($arguments);
        $this->userManager->deleteUser($username);
    }

    public function show($argv)
    {
        $args = new Arguments($argv);
        $arguments = $args->getArguments();
        $this->showUsage('show',$arguments);
        $username = $this->parseUsername($arguments);
        $user = $this->userManager->loadUserByUsername($username);
        if(!$user)
            throw new InvalidCommaindLineOptionException('User not found.');
        $this->output->writeln('user: '.$user->getUsername());
        $this->output->writeln('password: '.$user->getPassword());
        $this->output->writeln('id: '.$user->getId());
        $this->output->writeln('roles: '.implode(',',$user->getAuthorities()));
    }

    public function modify($argv)
    {
        $args = new Arguments($argv,'p:G:a',array('password','groups','append'));
        $arguments = $args->getArguments();
        $this->showUsage('modify',$arguments);
        $username = $this->parseUsername($arguments);
        $options = $args->getOptions();
        $password = $this->parsePassword($options);
        if($password)
            $password = $this->encodePassword($password);
        $authorities = $this->parseAuthorities($options);
        if(isset($options['a']) || isset($options['append'])) {
            if(!isset($options['G']) && !isset($options['groups']))
                throw new InvalidCommaindLineOptionException('The "append" option requires the "groups" option.');
            $append = true;
        } else {
            $append = null;
        }
        $user = $this->userManager->loadUserByUsername($username);
        $user = $this->userManager->demapUser($user);
        if(!empty($password)) {
            $user['password'] = $password;
        }
        if(!empty($authorities)) {
            if($append) {
                $oldAuthorities = $user['authorities'];
                if(empty($oldAuthorities))
                    $oldAuthorities = [];
                $authorities = array_merge($authorities,$oldAuthorities);
                $user['authorities'] = array_unique($authorities);
            } else {
                $user['authorities'] = $authorities;
            }
        }
        $user = $this->userManager->mapUser($user);
        $this->userManager->updateUser($user);
    }

    protected function showUsage($command,$arguments)
    {
        if(count($arguments)==0) {
            $this->usage($command);
            throw new InvalidCommaindLineOptionException('');
        }
    }

    protected function parseUsername($arguments)
    {
        if(count($arguments)!=1) {
            throw new InvalidCommaindLineOptionException('Please specify only one user name.');
        }
        $arguments = array_values($arguments);
        return $arguments[0];
    }

    protected function parsePassword($options)
    {
        $password = null;
        if(isset($options['p']) && isset($options['password']))
            throw new InvalidCommaindLineOptionException('Please specify only one password.');
        foreach(array('p','password') as $name) {
            if(isset($options[$name])) {
                if(is_array($options[$name])) {
                    throw new InvalidCommaindLineOptionException('Please specify only one password.');
                }
                if($options[$name]===false) {
                    throw new InvalidCommaindLineOptionException('Please specify password.');
                }
                $password = $options[$name];
            }
        }
        if($password=='-') {
            $this->output->printf('password: ');
            $password = fgets(STDIN);
            $password = trim($password);
        }
        return $password;
    }

    protected function parseAuthorities($options)
    {
        $totalAuthorities = null;
        foreach(array('G','groups') as $name) {
            $authorities = null;
            if(isset($options[$name])) {
                $values = $options[$name];
                if($values===false) {
                    throw new InvalidCommaindLineOptionException('Please specify authorities.');
                }
                if(!is_array($values)) {
                    $values = array($values);
                }
                $authorities = array();
                foreach ($values as $value) {
                    $authorities = array_merge($authorities,explode(',', $value));
                }
            }
            if($authorities!==null) {
                if($totalAuthorities===null)
                    $totalAuthorities = array();
                $totalAuthorities = array_merge($totalAuthorities,$authorities);
            }
        }
        if($totalAuthorities==null)
            return null;
        return $this->checkAuthorities($totalAuthorities);
    }

    protected function checkAuthorities($authorities)
    {
        foreach ($authorities as $value) {
            if(!preg_match('/^[A-Z_]+$/', $value)) {
                throw new InvalidCommaindLineOptionException('authority must be made Alphabet characters');
            }
        }
        return $authorities;
    }

    protected function encodePassword($password)
    {
        return $this->authenticationProvider->getPasswordEncoder()->encode($password);
    }


    protected function usage($command)
    {
        $this->output->writeln('Usage:');
        foreach ($this->usages[$command] as $message) {
            $this->output->writeln('  '.$message);
        }
    }
}
