<?php

class Page extends WpPost {
  public $data = [];
  function __construct($opts) {
    parent::__construct($opts);
    $this->id = intval($opts['id']) ?? null;
    if ($this->id) {
      $this->data = $this->getRow();
    }
    $this->form_fields = [
      new Field(['slug'=>'post_title', 'type'=>'string', 'value'=>$this->data->post_title]),
      new Field(['slug'=>'post_name', 'name'=>'Slug', 'type'=>'string']),
    ];
  }

  function getRow() {
    return $this->db->fetchObject(
      "SELECT * FROM wp_posts WHERE ID = ? ",
      [
        $this->id,
      ]
    );
  }
}