<?php

class PageIndex extends Index {
  protected $can_write = true;
  function __construct() {
    parent::__construct([
      'model'=>new Page([]),
    ]);
  }
}
