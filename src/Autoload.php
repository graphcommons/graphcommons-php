<?php
declare (strict_types=1);
namespace GraphCommons;

final /* static */ class Autoload
{
    public static function register(): bool
    {
        return spl_autoload_register(function ($objectName) {
            if ($objectName[0] != '\\') {
                $objectName = '\\'. $objectName;
            }

            // only self files
            if (1 !== strpos($objectName, __namespace__)) {
                return;
            }

            // prepare file name & path
            $objectName = substr($objectName, 1 + strlen(__namespace__));
            $objectFile = sprintf('%s/%s.php', __dir__, str_replace('\\', '/', $objectName));

            // check file is exists
            if (!is_file($objectFile)) {
                throw new \RuntimeException(sprintf(
                    'Object file not found! object name(%s) file name(%s)',
                    $objectName, $objectFile
                ));
            }

            require $objectFile;
        });
    }
}
