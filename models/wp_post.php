<?php

class WpPost extends Model {
  public static $table_name = 'wp_posts';
  function __construct($opts) {
    parent::__construct($opts);
  }
}

