<?php
require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_before.php');

require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_admin_after.php');
$APPLICATION->SetTitle('Список исходящих email');
?>

<style>
    .messages-wrapper {
        display: flex;
        flex-direction: row;
        gap: 35px;
    }

    .message-list {
        width: 300px;
        display: flex;
        flex-direction: column;
        gap: 10px;
        max-height: 500px;
        overflow-y: auto;
    }

    .message-file {
        display: flex;
        flex-direction: column;
        cursor: pointer;
        background-color: lightblue;
        border-radius: 5px;
        padding: 10px;
    }

    .message-file--active,
    .message-file:hover {
        background-color: #4ba5bf;
    }


    iframe#message-preview-frame {
        width: 100%;
        height: 100%;
    }

    .message-preview {
        width: 1000px;
        min-height: 520px;
    }

    .panel-button {
        padding: 20px;
        background-color: lightgrey;
        margin-bottom: 20px;
        display: flex;
        gap: 10px;
    }
</style>

<?php
CModule::IncludeModule('aabushinov.maildumper');

$mail = new \AAbushinov\MailDumper\Mail();
if ($_GET['handle'] == 'Y' || $_GET['handle'] == 'N') {
    $mail->changeHandleParam($_GET['handle'] == 'Y');
}

if ($_GET['del'] == 'Y') {
    $mail->cleanMails();
}

if ($_GET['sync'] == 'Y') {
    $mail->runEvents();
}


$files = $mail->getList();

?>
    <div class="panel-button">
        <a href="?sync=Y" class="adm-btn">Запустить агент отправки</a>
        <a href="?del=Y" class="adm-btn">Очистить сообщения</a>
        <? if ($mail->getHandleParam()) :?>
            <a href="?handle=N" class="adm-btn">Отключить отслеживание сообщений</a>
        <? else: ?>
            <a href="?handle=Y" class="adm-btn">Включить отслеживание сообщений</a>
        <? endif; ?>

        <div>
            <input type="checkbox" onchange="changeWhiteSpace()" id="use-white-space">
            <label for="use-white-space">Использовать whitespace</label>
        </div>
    </div>
    <div class="messages-wrapper">
        <div class="message-list">
            <? foreach ($files as $file) :?>
                <div class="message-file" data-path="<?=$file['ABS_PATH']?>">
                    <span class="message-file__date"><b>Дата: </b><?=$file['DATE']?></span>
                    <span class="message-file__email"><b>Кому: </b><?=$file['PARSED_NAME']['EMAIL']?></span>
                    <span class="message-file__type"><b>Тип: </b><?=$file['PARSED_NAME']['TYPE']?></span>
                </div>
            <? endforeach; ?>
        </div>
        <div class="message-preview">
            <iframe id="message-preview-frame" onload="frameLoaded()" src="" frameborder="0"></iframe>
        </div>
    </div>


    <script>
        function frameLoaded() {
            changeWhiteSpace()
        }

        function changeWhiteSpace() {
            let frameWindow = document.getElementById('message-preview-frame').contentWindow

            if (document.getElementById('use-white-space').checked) {
                frameWindow.document.body.style = "white-space: pre;"
            } else {
                frameWindow.document.body.style = ""
            }
        }

        for (let message of document.getElementsByClassName('message-file')) {
            message.addEventListener('click', function (e) {
                for (let file of document.getElementsByClassName('message-file--active')) {
                    file.classList.toggle('message-file--active')
                }
                this.classList.toggle('message-file--active')
                document.getElementById('message-preview-frame').src = this.getAttribute('data-path');
            });
        }
    </script>
<?php
require($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/epilog_admin.php');
