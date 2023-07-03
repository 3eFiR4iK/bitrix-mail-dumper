<?php

namespace AAbushinov\MailDumper;

class MainEvent
{
    public static function onProlog()
    {
        CModule::IncludeModule('aabushinov.maildumper');
    }
}
