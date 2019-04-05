<?php
    include_once ('m/testing.php');


?>


<h3><a href="<?=$_SERVER['PHP_SELF']?>?page=testing">Отзывы</a></h3>
<?php

    if(!empty($_POST)){
       if( testing_add($_POST['title'], $_POST['content'])){
        die('Отзыв успешно добавлен');}

       $title = $_POST['title'];
       $content = $_POST['content'];
           $error = true;

    }
    else{
        $title = '';
        $content = '';
        $error = false;
    }
?>

<h2>Новый отзыв</h2>
<?php

if($error) { ?>
    <p  > Пожалуста, заполните все поля!</p >
<?php }
 ?>
<form method="post">

    <p>Имя Фамилия:</p>
    <br>
    <input type="text" name="title" value="<?=$article_title?>">
    <br>
    <br>
    <p>Содержание:</p>
    <br>
    <textarea name="content"><?=$article_content?></textarea>
    <br>
    <input type="submit" value="Добавить">
</form>