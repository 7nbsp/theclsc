<?php

function get_expiring_posts(){
    global $wpdb;

    $results = array();

    $query = $wpdb->prepare("SELECT posts.ID as ID FROM $wpdb->posts as posts LEFT JOIN $wpdb->postmeta as meta ON posts.ID = meta.post_id
	            WHERE posts.post_status = 'publish'
	            AND meta.meta_key = '_es_expirydate'
	            AND meta.meta_value < CURDATE()
                    AND meta.meta_value IS NOT null
	            AND meta.meta_value != \"\"");

    $sqlresults = $wpdb->get_results($query);

    foreach($sqlresults as $row){
        array_push($results, $row->ID);
    }

    return $results;
}

function expire_post($ID){
    global $wpdb;
    $query = $wpdb->prepare("UPDATE $wpdb->posts SET post_status = 'draft' WHERE ID='$ID'");
    $wpdb->query($query);
}

?>
