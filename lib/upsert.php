<?php

class Upsert {
  public
    $model,
    $id,
    $errors;

  function __construct($id) {
    $this->id = $id;
  }

  function isValid(): bool {
    return $this->model->isValid();
  }
}
