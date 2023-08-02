<?php

class Page extends WpPost {
  public $data = [];
  function __construct($opts) {
    parent::__construct($opts);
    $this->id = $opts['id'] ?? null;
    if ($this->id) {
      $this->data = $this->getRow();
    }
    $this->form_fields = [
      new Field(['slug'=>'post_title', 'name'=>'Title', 'type'=>'string']),
      new Field(['slug'=>'post_name', 'name'=>'Slug', 'type'=>'string']),
    ];
  }

  function getRows() {
    return $this->db->fetchObjects(
      "SELECT ID, post_title, post_name FROM wp_posts WHERE post_status = ? AND post_type = ?",
      [
        'publish',
        'page',
      ]
    );
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
