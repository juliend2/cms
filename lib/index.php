<?php

class Index {
  public $model, $slug;
  function __construct($opts) {
    $this->model = $opts['model'] ?? null;
    $this->slug = $opts['slug'] ?? null;
  }

  function __toString() {
    $field_slugs = [];
    $html = '<table class="index-table">';
    $html .= '<tr>';
    foreach ($this->model->form_fields as $field) {
      $html .= "<th>$field->name</th>";
      $field_slugs[] = $field->slug;
    }
    $html .= '<th>Actions</th>';
    $html .= '</tr>';
    foreach ($this->model->getRows() as $row) {
      $html .= '<tr>';
      foreach ($field_slugs as $field_slug) {
        $html .= '<td>'.$row->$field_slug.'</td>';
      }
      if ($this->can_write) {
        $html .= '<td class="index-table__actions">';
        $html .= '<a href="?uri=/'.$this->slug.'/'.$row->ID.'/edit">Edit</a>';
        #$html .= '<a href="?uri=/'.$this->slug.'/'.$row->ID.'/edit">Delete</a>';
        $html .= '<form action="?uri=/'.$this->slug.'/'.$row->ID.'/delete" ';
        $html .=    'method="post" onsubmit="return confirm(\'Are you sure?\')">';
        $html .= '<input type="submit" value="Delete">';
        $html .= '</form>';
        $html .= '<td>';
      }
      $html .= '</tr>';
    }
    $html .= '</table>';
    return $html;
  }
}
