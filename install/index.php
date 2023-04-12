<?php

use Bitrix\Main\Application;
use Bitrix\Main\Config\Configuration;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Korus\Kpi\Data\Mapper\CardStatusDataMapper;
use Korus\Kpi\Data\Mapper\CardTransitionDataMapper;
use Korus\Kpi\Repository\CardStatusRepository;
use Korus\Kpi\Repository\CardTransitionRepository;
use Korus\Kpi\Repository\TaskRepository;
use Korus\Kpi\Tools\WorkflowDataLoader;

Loc::loadMessages(__FILE__);

class AAbushinov_maildumper extends CModule
{
    public $MODULE_ID = 'aabushinov.maildumper';
    public $MODULE_VERSION;
    public $MODULE_VERSION_DATE;
    public $MODULE_NAME;
    public $MODULE_DESCRIPTION;
    public $MODULE_CSS;
    public $PARTNER_NAME;
    public $PARTNER_URI;

    function __construct()
    {
        $this->MODULE_NAME = 'Просмоторщик исходящих сообщений с портала';
        $this->MODULE_DESCRIPTION = '';
        $this->PARTNER_NAME = '';
        $this->PARTNER_URI = 'https://bitrix24.korusconsulting.ru';

        $arModuleVersion = array();

        $path = str_replace('\\', '/', __FILE__);
        $path = substr($path, 0, strlen($path) - strlen('/index.php'));
        include($path . '/version.php');

        if (is_array($arModuleVersion) && array_key_exists('VERSION', $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion['VERSION'];
            $this->MODULE_VERSION_DATE = $arModuleVersion['VERSION_DATE'];
        } else {
            $this->MODULE_VERSION = '1.0.0';
            $this->MODULE_VERSION_DATE = '2022-06-03 10:00:00';
        }
    }

    public function DoInstall()
    {
        ModuleManager::registerModule($this->MODULE_ID);
        $root = Application::getDocumentRoot();
        CopyDirFiles(__DIR__ . '/bitrix', $root.'/bitrix', true, true);
    }

    public function DoUninstall()
    {
        ModuleManager::unRegisterModule($this->MODULE_ID);
    }
}
