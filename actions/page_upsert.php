<?php

class PageUpsert extends Upsert {

  function __construct($id = null) {
    parent::__construct($id);
    $this->id = $id;
    $this->model = new Page(['id'=>$this->id]);
  }
  function __toString() {
    $html = '<form method="post" action="'.$_SERVER['REQUEST_URI'].'">';
    $data = $this->model->data;
    foreach ($this->model->form_fields as $field) {
      $field->value = $data->{$field->slug} ?? null;
      $html .= '<p>';
      $html .= $field;
      $html .= '</p>';
    }
    $html .= '<input type="submit">';
    $html .= 'or <a href="?uri=/pages/">Cancel</a>';
		$html .= '</form>';
    return $html;
  }
}

