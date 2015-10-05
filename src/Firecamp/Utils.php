<?php

namespace Firecamp;

use Silex\Application;
use SplFileInfo;
use Symfony\Component\Finder\Finder;

/**
 * Class Utils
 *
 * @package Firecamp
 */
class Utils
{
    /**
     * Small utility function to register conf in silex app
     *
     * @param string      $configFile     Path to the config file to load
     * @param Application $app            Silex application
     * @param array       $existingConfig Array of existing config if needed
     *
     * @return array|mixed
     */
    public static function loadConf($configFile, $app = null, $existingConfig = null)
    {
        if (file_exists($configFile)) {
            $conf = require($configFile);
            if ($app && $conf) {
                foreach ($conf as $key => $value) {
                    $app[$key] = $value;
                }
            }

            if (is_array($existingConfig) && $conf) {
                return array_merge($existingConfig, $conf);
            }

            return $conf;
        }

        return false;
    }

    /**
     * Quick helper function for automatic class share in Silex
     *
     * @param string $searchDirectory Directory to scan for php classes
     * @param string $nameEndWith     End string to find in the name to consider the class as valid
     *                                Example : for classes of type 'UserController', set 'Controller' for this parameter
     * @param string $pathFilter      Path filter (optional)
     *                                Usage example : we want to add the condition that the path contains a folder 'Controllers'
     * @param bool   $useCache        Use cache feature or not
     * @param string $cacheDir        Cache directory to use ('app/cache' by default)
     *
     * @return array
     */
    public static function appShareClassHelper(
        $searchDirectory,
        $nameEndWith,
        $pathFilter,
        $useCache = true,
        $cacheDir = 'app/cache'
    ) {
        if ($useCache) {
            if (file_exists($cacheDir.'/shares/'.strtolower($nameEndWith).'.log')) {
                return unserialize(file_get_contents($cacheDir.'/shares/'.strtolower($nameEndWith).'.log'));
            }
        }

        if (!file_exists($searchDirectory)) {
            return false;
        }

        $shares      = array();
        $classFinder = new Finder();

        $searchDirectory = realpath($searchDirectory);
        $cacheDir        = realpath($cacheDir);

        $fileNameFilter = $nameEndWith;
        if (false === strripos($fileNameFilter, '.php')) {
            $fileNameFilter .= '.php';
        }

        $classFinder->files()->name('*'.$fileNameFilter);

        if (!empty($pathFilter)) {
            $classFinder->path('/'.$pathFilter.'/');
        }

        $classFinder->in($searchDirectory);

        /**
         * @var SplFileInfo $classFile
         */
        foreach ($classFinder as $classFile) {
            $key = strtolower(str_replace($fileNameFilter, '', $classFile->getFilename()));
            if (!empty($key)) {
                $className                                 = str_replace('.php', '', $classFile->getFilename());
                $classFQCN                                 = str_replace(
                    realpath($searchDirectory).DIRECTORY_SEPARATOR,
                    '',
                    realpath($classFile->getPath())
                );
                $classFQCN                                 = str_replace('/', '\\', $classFQCN).'\\'.$className;
                $shares[$key.'.'.strtolower($nameEndWith)] = $classFQCN;
            }
        }

        if ($useCache) {
            if (!file_exists($cacheDir.'/shares')) {
                mkdir($cacheDir.'/shares', 0755, true);
            }
            if (!file_exists($cacheDir.'/shares/'.strtolower($nameEndWith).'')) {
                file_put_contents($cacheDir.'/shares/'.strtolower($nameEndWith).'', serialize($shares));
            }
        }

        return $shares;
    }
}
