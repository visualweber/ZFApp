<?php

/**
 * Resource for loading module configs
 *
 * @category   App
 * @package    App.Platform
 * @subpackage Resource
 * @see http://blog.vandenbos.org/2009/07/07/zend-framework-module-config/
 */
class App_Application_Resource_Modulesetup extends Zend_Application_Resource_ResourceAbstract {

    /**
     * Initialize resource
     *
     * @return mixed
     */
    public function init() {
        $this->_getModuleSetup();
    }

    /**
     * Load the module's ini files
     *
     * @return void
     */
    protected function _getModuleSetup() {
        $bootstrap = $this->getBootstrap();

        if (!($bootstrap instanceof Zend_Application_Bootstrap_Bootstrap)):
            throw new Zend_Application_Exception('Invalid bootstrap class');
        endif;

        $bootstrap->bootstrap('frontcontroller');
        $front = $bootstrap->getResource('frontcontroller');
        $modules = $front->getControllerDirectory();

        foreach (array_keys($modules) as $module):
            $configPath = $front->getModuleDirectory($module) . DIRECTORY_SEPARATOR . 'configs';

            if (file_exists($configPath)):
                $cfgdir = new DirectoryIterator($configPath);
                $appOptions = $this->getBootstrap()->getOptions();
                foreach ($cfgdir as $file):
                    if ($file->isFile()):
                        $filename = $file->getFilename();
                        $options = $this->_loadOptions($configPath . DIRECTORY_SEPARATOR . $filename);

                        if (($len = strpos($filename, '.')) !== false):
                            $cfgtype = substr($filename, 0, $len);
                        else:
                            $cfgtype = $filename;
                        endif;

                        if (strtolower($cfgtype) == 'module') :
                            if (array_key_exists($module, $appOptions)):
                                if (is_array($appOptions[$module])):
                                    $appOptions[$module] = array_merge($appOptions[$module], $options);
                                else:
                                    $appOptions[$module] = $options;
                                endif;
                            else:
                                $appOptions[$module] = $options;
                            endif;
                        else:
                            $appOptions[$module]['resources'][$cfgtype] = $options;
                        endif;
                    endif;
                endforeach;
                $bootstrap->setOptions($appOptions);
            else:
                continue;
            endif;
        endforeach;
    }

    /**
     * Load the config file
     *
     * @param string $fullpath
     * @return array
     */
    protected function _loadOptions($fullpath) {
        if (file_exists($fullpath)):

            switch (substr(trim(strtolower($fullpath)), -3)):
                case 'ini':
                    $cfg = new Zend_Config_Ini($fullpath, $this->getBootstrap()->getEnvironment());
                    break;
                case 'xml':
                    $cfg = new Zend_Config_Xml($fullpath, $this->getBootstrap()->getEnvironment());
                    break;
                default:
                    return array();
                    // throw new Zend_Config_Exception('Invalid format for config file ' . $fullpath);
                    break;
            endswitch;
        else:
            // throw new Zend_Application_Resource_Exception('File does not exist');
            return array();
        endif;
        return $cfg->toArray();
    }

}
