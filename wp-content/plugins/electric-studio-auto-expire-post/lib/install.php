<?php



function ES_auto_expire_post_install() {
  /* Creates new database field */
  add_option("esape_posttypes", array('post','page'), '', 'yes');
}

function ES_auto_expire_post_remove() {
  /* Deletes the database field */
  delete_option('esape_posttypes');
}

?>
