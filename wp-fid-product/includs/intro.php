<?php
include_once ('m/testing.php');
$testing = testing_all();
?>

<div id="testing">
    <!-- start short version--!>
    <?php foreach ($testing as $item){?>
    <div style="word-wrap: break-word; width: 100%; border-top: 1px dotted #555;">
        <div style="font: bold 14px Arial; padding: 10px 0px; text-align: center; overflow: hidden;">
            <?=$item['name']?>
        </div>
        <div>
            <?=$item['text']?>
        </div>
        <div >
            Читать отзыв полностью
        </div>
    </div>
        <!-- end short version--!>

    <?php }?>
</div>
