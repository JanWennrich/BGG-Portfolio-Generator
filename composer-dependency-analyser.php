<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;
use ShipMonk\ComposerDependencyAnalyser\Config\ErrorType;

$config = new Configuration();

return $config
    //// Adjusting scanned paths
    ->addPathToScan(__DIR__ . '/bin', isDev: false);
