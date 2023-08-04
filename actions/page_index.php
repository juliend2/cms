<?php

class PageIndex extends Index {
  public $can_write = true;

  function __construct() {
    parent::__construct([
      'model'=>new Page([]),
      'slug'=>'pages',
    ]);
  }
}
