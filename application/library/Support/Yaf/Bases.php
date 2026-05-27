<?php

namespace Support\Yaf;

if (class_exists('Yaf\\Application', false)) {
    abstract class BootstrapBase extends \Yaf\Bootstrap_Abstract
    {
    }

    abstract class ControllerBase extends \Yaf\Controller_Abstract
    {
    }

    abstract class PluginBase extends \Yaf\Plugin_Abstract
    {
    }
} else {
    abstract class BootstrapBase extends \Yaf_Bootstrap_Abstract
    {
    }

    abstract class ControllerBase extends \Yaf_Controller_Abstract
    {
    }

    abstract class PluginBase extends \Yaf_Plugin_Abstract
    {
    }
}
