<?php
namespace AAbushinov\MailDumper;

class Mail
{
    private string $path = '/local/modules/aabushinov.maildumper/emails/';

    public function __construct(string $path = null)
    {
        if ($path != null) {
            $this->path = $path;
        }
    }

    public function getList()
    {
        $arDirs = [];
        $arFiles = [];
        \CFileMan::GetDirList(['s1', $this->path], $arDirs, $arFiles, [], ['timestamp' => 'desc']);

        return array_map(function ($file) {
            $file['PARSED_NAME'] = $this->parseMessageName($file['NAME']);
            return $file;
        }, $arFiles);
    }

    public function addMessage($to, $subject, $message, $type)
    {
        if (!is_dir($_SERVER['DOCUMENT_ROOT'] . $this->path)) {
            mkdir($_SERVER['DOCUMENT_ROOT'] . $this->path);
        }

        if ($subject != '') {
            $message = "<h2>Тема: ". base64_decode($subject) ."</h2><br>" . $message;
        }

        $fileName = $to . '--' . $type . '--' . time() . '.html';
        return file_put_contents($_SERVER['DOCUMENT_ROOT'] . $this->path . $fileName, $message);
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function cleanMails()
    {
        $files = $this->getList();
        foreach($files as $file) {
            unlink($file['PATH']);
        }
    }

    public function changeHandleParam(bool $active)
    {
        \COption::SetOptionInt('aabushinov.maildumper', 'handleMessages', (int)$active);
    }

    public function getHandleParam(): bool
    {
        return \COption::GetOptionInt('aabushinov.maildumper', 'handleMessages', false) > 0;
    }

    public function runEvents()
    {
        \Bitrix\Main\Mail\EventManager::executeEvents();
    }

    private function parseMessageName(string $messageName): array
    {
        if ($messageName == '') {
            return [];
        }

        $arMessageName = explode('--', str_replace('.html', '', $messageName));

        return [
            'EMAIL' => $arMessageName[0],
            'TYPE' => $arMessageName[1]
        ];
    }
}
