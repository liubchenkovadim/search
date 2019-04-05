<?php
function renameDB()
{
    global $wpdb;
    $tables = array(
        'posts',
        'postmeta',
        'terms',
        'termmeta',
        'term_taxonomy',
        'term_relationships');
  //  foreach ($tables as $table){
  //      $wpdb->query("drop table wp_$table");
  //  }

    foreach ($tables as $table){
        $query = " RENAME TABLE dbpref_$table TO wp_$table ";
        $wpdb->query($query);
    }

    /*
    foreach ($tables as $table){
        $wpdb->query("drop table dbpref_$table");
    } */
}
<?php
include_once ('m/testing.php');

$testing = testing_all();

?>
<h2>Отзывы</h2>
<ul>
    <li><a href="<?=$_SERVER['PHP_SELF']?>?page=testing&c=add">Добавить новый отзыв</a> </li>
    <p> Список всех отзывов:</p>
    <?php
    foreach($testing as $op){ ?>
        <li><a href="<?=$_SERVER['PHP_SELF']?>?page=testing&c=edit&id=<?=$op['id']?>"> <?=$op['name']?> </a> </li>
    <?php } ?>
</ul>

<?php



try {
    $rows = getXmlDb();
} catch (PDOException $e){
    echo '<h1> ПРОБЛЕМЫ:'. $e->getMessage();
    die();
}

if(!empty($_POST['go'])){
    $name = $_POST['name'] ;
$dom = new domDocument("1.0", "utf-8");

$feed = $dom->createElement("feed"); // Создаём корневой элемент
$feed->setAttribute("xmlns","http://www.w3.org/2005/Atom" );
$feed->setAttribute("xmlns:g","http://base.google.com/ns/1.0" );

$dom->appendChild($feed);


$title = $dom->createElement('title','fidproduct');
         $link = $dom->createElement('link');
              $link->setAttribute('rel', 'self');
              $link->setAttribute('href', 'https://tesoro-jewelry.com.ua');
         $update = $dom->createElement('update',date("m-d-y H:m:s"));
    $feed->appendChild($title);
    $feed->appendChild($link);
    $feed->appendChild($update);
    $feed->appendChild($dom->createElement('id','00000001'));
$id = 'id';
$price = 'price';
$i = 0;
    foreach ($rows as $row) {
if($i < 10){
    $i++;
        $entry = $dom->createElement('entry');
        $entry->appendChild($dom->createElement('g:description' ,'&lt;em&gt;'. $row['title'].'&lt;/em&gt;'));
        $entry->appendChild($dom->createElement('g:image_link' , $row['link']));
        foreach ($row as $key=>$val) {


                if($key ==$id) {

                    $paramses = paramsProduct($val);

                }

                if($key == $price) {
                    $entry->appendChild($dom->createElement('g:' . $key, $val.' UAH'));
                    $params = $dom->createElement('g:params');
                        foreach ($paramses as $p) {
                                $params->appendChild($dom->createElement('g:' . $p['type'], $p['value']));
                        }
                    $entry->appendChild($params);

                } else {
                    if ($key == 'availability') {
                        $entry->appendChild($dom->createElement('g:' . $key, ' in stock'));

                    } else {

                        $entry->appendChild($dom->createElement('g:' . $key, $val));
                    }
                }


        }
        $feed->appendChild($entry);

    }
    }
$dom->formatOutput = true;
$dom->save($name.".xml"); // Сохраняем полученный XML-документ в файл

exit();

}
?>
<form action=" " method="post">
    <input type="text" name="name" placeholder="Введите название файла">.xml<br>
    <input type="submit" name="go" value="Сохранить">
</form>
