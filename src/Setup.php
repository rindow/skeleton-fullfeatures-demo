<?php
namespace Acme\MyApp;

use Composer\Script\Event;

class Setup
{
    public static function createSecret(Event $event)
    {
        $filename = __DIR__.'/../config/local/security.secret.local.php';
        if(file_exists($filename)) {
            return;
        }
        $config = [];
        $random = '';
        for($i=0;$i<8;$i++) {
            $n = dechex(mt_rand());
            $n = substr('00000000'.$n,-8);
            $random .= hex2bin($n);
        }

        $config['security']['secret'] = base64_encode($random);
        $php = "<?php\nreturn ".var_export($config,true).";\n";
        file_put_contents($filename,$php);
    }
}
