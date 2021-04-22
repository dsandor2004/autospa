<?php
    error_reporting(E_ALL ^ E_NOTICE);
    ini_set('display_errors', 1);

if ($_POST) {
    chdir('../../vendor/bin');

    $cmd_string = "doctrine-module orm:schema-tool:update --force";
    $result = array();
    exec($cmd_string, $result);

    foreach ($result as $res) {
        echo $res;
    }
} else {
    ?>
    <form method="post" id="formx">
        <input type="hidden" value="1" name="x" />
    </form>

    <a href="javascript:void(0)" onclick="document.getElementById('formx').submit();">doctrine-module orm:schema-tool:update --force</a>
    <?php
}