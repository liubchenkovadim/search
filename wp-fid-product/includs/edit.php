<?php
include_once ('m/testing.php');


?>


<h3><a href="<?=$_SERVER['PHP_SELF']?>?page=testing">Отзывы</a></h3>
<?php

    $id = (int)$_GET['id'];
    if ($id == 0)
        die('Не передан  id отзыва');

if(!empty($_POST)){
    if(isset($_POST['save'])){
        if( testing_edit($id,$_POST['title'], $_POST['content'])){
            die('Отзыв успешно сохранен');}

    } elseif(isset($_POST['delete'])){
        if( testing_delete($id)){
            die('Отзыв успешно удален');}

    }



    $title = $_POST['title'];
    $content = $_POST['content'];
    $error = true;

}
else{
    $testing = testing_get($id);
    $title = $testing['name'];
    $content = $testing['text'];;
    $error = false;
}
?>

<h2>Редактирование отзыва </h2>
<?php

if($error) { ?>
    <p  > Заполните все поля!</p >
<?php }
?>
<form method="post">

    <p>Имя Фамилия:</p>
    <br>
    <input type="text" name="title" value="<?=$title?>">
    <br>
    <br>
    <p>Содержание:</p>
    <br>
    <textarea  name="content"><?=$content?></textarea>
    <br>
    <input type="submit" name="save" value="Сохранить">
    <input type="submit" name="delete" value="Удалить">

</form>