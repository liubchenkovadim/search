<?php
include_once('m/testing.php');

$testing = testing_all();

?>
<h2>Отзывы</h2>
<ul>
    <li><a href="<?= $_SERVER['PHP_SELF'] ?>?page=testing&c=add">Добавить новый отзыв</a></li>
    <p> Список всех отзывов:</p>
    <?php
    foreach ($testing as $op) { ?>
        <li><a href="<?= $_SERVER['PHP_SELF'] ?>?page=testing&c=edit&id=<?= $op['id'] ?>"> <?= $op['name'] ?> </a></li>
    <?php } ?>
</ul>

<?php

if($_POST['go']){

$name = $_POST['name'];
$dom = new domDocument("1.0", "utf-8");

$feed = $dom->createElement("feed"); // Создаём корневой элемент
$feed->setAttribute("xmlns", "http://www.w3.org/2005/Atom");
$feed->setAttribute("xmlns:g", "http://base.google.com/ns/1.0");

$dom->appendChild($feed);


$title = $dom->createElement('title', 'fidproduct');
$link = $dom->createElement('link');
$link->setAttribute('rel', 'self');
$link->setAttribute('href', 'https://tesoro-jewelry.com.ua');
$update = $dom->createElement('update', date("m-d-y H:m:s"));
$feed->appendChild($title);
$feed->appendChild($link);
$feed->appendChild($update);
$feed->appendChild($dom->createElement('id', '00000001'));


$product_ids = get_product_id();

foreach ($product_ids as $product_id) {
    $product = wc_get_product($product_id->id);
    if (strpos($product->get_title(), 'Roberto Bravo') == false)
         {


        $imgUrls = wp_get_attachment_url($product->get_image_id());


        $entry = $dom->createElement('entry');
        $entry->appendChild($dom->createElement('g:id', $product_id->id));

        $entry->appendChild($dom->createElement('g:title', preg_replace('%&%', 'AND ', $product->get_title())));

        $entry->appendChild($dom->createElement('g:description', '&lt;em&gt;' . preg_replace('%&%', 'AND ', $product->get_title()) . '&lt;/em&gt;'));
        $entry->appendChild($dom->createElement('g:link', $product_id->link));
        $entry->appendChild($dom->createElement('g:image_link', $imgUrls));
        $entry->appendChild($dom->createElement('g:price', $product->get_price() . ' UAH'));

        $paramses = paramsProduct($product_id->id);


        $params = $dom->createElement('g:params');
        foreach ($paramses as $p) {
            $params->appendChild($dom->createElement('g:' . $p['type'], $p['value']));
        }
        $entry->appendChild($params);
        if (strpos($product->get_availability()['class'], 'in') !== false) // именно через жесткое сравнение
        {
            $entry->appendChild($dom->createElement('g:availability', 'in stock'));
        } elseif (strpos($product->get_availability()['class'], 'out') !== false) {
            $entry->appendChild($dom->createElement('g:availability', 'out of stock'));
        } elseif (strpos($product->get_availability()['class'], 'preorder ') !== false) {
            $entry->appendChild($dom->createElement('g:availability', 'preorder '));
        }


        $feed->appendChild($entry);
    }
    }


$dom->formatOutput = true;
$dom->save($name . ".xml"); // Сохраняем полученный XML-документ в файл
if (file_exists($name . '.xml')) {
   echo 'файл сохранен ';
   exit();
} else {
    echo 'Ошибка: файл не сохранен';
     }


}
?>
<form action=" " method="post">
    <input type="text" name="name" placeholder="Введите название файла">.xml<br>
    <input type="submit" name="go" value="Сохранить">
</form>
