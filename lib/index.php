<?php

class Index {
  public $model;
  function __construct($opts) {
    $this->model = $opts['model'] ?? null;
  }
  function __toString() {
    $field_slugs = [];
    $html = '<table>';
    $html .= '<tr>';
    foreach ($this->model->form_fields as $field) {
      $html .= "<th>$field->name</th>";
      $field_slugs[] = $field->slug;
    }
    $html .= '</tr>';
    foreach ($this->model->getRows() as $row) {
      $html .= '<tr>';
      foreach ($field_slugs as $field_slug) {
        $html .= '<td>'.$row->$field_slug.'</td>';
      }
      if ($this->can_write) {
        $html .= '<td>';
        $html .= '<a href="?uri=/pages/'.$row->ID.'/edit">Edit</a>';
        $html .= '<td>';
      }
      $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
  }
}
