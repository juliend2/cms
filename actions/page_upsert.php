<?php

class PageUpsert extends Upsert {
  function __construct($id) {
    parent::__construct($id);
  }
  function __toString() {
    return 'blue';
  }
}
