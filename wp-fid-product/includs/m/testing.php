<?php
function testing_all(){
    global $wpdb;
    $table = $wpdb->prefix.'testing';
    $query = "SELECT * FROM $table ORDER  BY id DESC";
    return $wpdb->get_results($query, ARRAY_A);

}

function getXmlDb (){
    global $wpdb;

    $sql = " SELECT p.id AS id,p.post_title AS title, p.guid AS link, pr.price AS price, ss.stockstatus AS availability
FROM wp_posts      AS p
            LEFT JOIN (
       SELECT tr.object_id AS id,
              t.name       AS mozrank
       FROM wp_term_relationships AS tr
                   INNER JOIN wp_term_taxonomy AS x
                              ON (x.taxonomy='pa_mozrank'
                                     AND x.term_taxonomy_id=tr.term_taxonomy_id)
                   INNER JOIN wp_terms AS t
                              ON t.term_id=x.term_id
) AS mo ON p.id = mo.id
            LEFT JOIN (
       SELECT tr.object_id AS id,
              t.name       AS pa
       FROM wp_term_relationships AS tr
                   INNER JOIN wp_term_taxonomy AS x
                              ON (x.taxonomy='pa_pa'
                                     AND x.term_taxonomy_id=tr.term_taxonomy_id)
                   INNER JOIN wp_terms AS t
                              ON t.term_id=x.term_id
) AS pa ON p.id = pa.id
            LEFT JOIN (
       SELECT post_id AS id, meta_value AS price
       FROM wp_postmeta
       WHERE meta_key = '_regular_price'
) AS pr ON p.id = pr.id
            LEFT JOIN (
       SELECT post_id AS id, meta_value AS stockstatus
       FROM wp_postmeta
       WHERE meta_key = '_stock_status'
) AS ss ON p.id = ss.id
WHERE p.post_status = 'publish'
  AND p.post_type = 'product'
  AND pr.price <> ''
  AND ss.stockstatus = 'instock'";

    return $wpdb->get_results($sql, ARRAY_A);
}
 function paramsProduct($id){
    global $wpdb;
    $sql = "SELECT x.taxonomy as type, t.name as value
  FROM '$wpdb->prefix.'term_relationships AS tr
  INNER JOIN '$wpdb->prefix.'term_taxonomy AS x ON ( x.term_taxonomy_id=tr.term_taxonomy_id)
  INNER JOIN '$wpdb->prefix.'terms AS t ON t.term_id=x.term_id
  where tr.object_id = $id ";

     return $wpdb->get_results($sql, ARRAY_A);
 }

function testing_get($id){
    global $wpdb;
    $table = $wpdb->prefix.'testing';
    $t = "SELECT name, text  FROM $table WHERE id='%d'";
    $query = $wpdb->prepare($t, $id);
    return $wpdb->get_row($query, ARRAY_A);
}

function testing_add($title, $content){
    global $wpdb;
    $title = trim($title);
    $content = trim($content);

    if($title == '' || $content == '')
        return false;
    $table = $wpdb->prefix.'testing';

    $sql = "INSERT INTO $table (name, text) VALUES('%s', '%s') ";
    $query = $wpdb->prepare($sql, $title, $content);
    $result = $wpdb->query($query);

    if ($result === false)
        die('Ошибка БД');

    return true;


}

function testing_edit($id, $title, $content){
    global $wpdb;
    $title = trim($title);
    $content = trim($content);

    if($title == '' || $content == '')
        return false;
    $table = $wpdb->prefix.'testing';

    $sql = "UPDATE $table SET name='%s', text='%s' WHERE id= '%d'";
    $query = $wpdb->prepare($sql, $title, $content, $id);
    $result = $wpdb->query($query);

    if ($result === false)
        die('Ошибка БД');

    return true;

}
function rename1111DB()
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
      //   $wpdb->query("drop table wp_$table");
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
function get_product_id(){
    global $wpdb;
    $table = $wpdb->prefix.'posts';
    $t = "SELECT id ,guid as link FROM $table WHERE post_type='product'";

    return $wpdb->get_results( $t);


    }
function get_product_url(){
    global $wpdb;
    $table = $wpdb->prefix.'posts';
    $t = "SELECT guid as link FROM $table WHERE post_type='product'";

    return $wpdb->get_results( $t);


}
function testing_delete($id){
    global $wpdb;
    $table = $wpdb->prefix.'testing';
    $t = "DELETE   FROM $table WHERE id='%d'";
    $query = $wpdb->prepare($t, $id);
    return $wpdb->query($query);


}
