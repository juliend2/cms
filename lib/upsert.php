<?php

class Upsert {
  public $model, $id;
  function __construct($id) {
    $this->id = $id;
  }
}
