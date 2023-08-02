<?php

class Model {
  public static $table_name = null;
  public $form_fields = [ ];
  protected $db;
  function __construct($opts) {
    global $db;
    $this->db = $db;
  }
}
