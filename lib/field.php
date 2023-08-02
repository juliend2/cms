<?php

class Field {
  public $slug, $name, $type, $value;
  function __construct($opts) {
    if (!isset($opts['slug'])) {
      throw new Exception("A 'slug' value is necessary.");
    }
    $this->slug = $opts['slug'];
    $this->name = $opts['name'] ?? $this->slug;
    $this->type = $opts['type'] ?? 'string'; // string or text, int, float, date, datetime, bool
    $this->value = $opts['value'] ?? '';
  }
  function __toString() {
    $value = $this->value;
    $html = "<label for='field_$this->slug'>$this->name</label>";
    switch ($this->type) {
    case 'string':
      $html .= "<input type='text' name='$this->slug' value='$value' id='field_$this->slug'>";
      break;
    case 'text':
      $html .= "<textarea name='$this->slug'>$value</textarea>";
      break;
    case 'bool':
      $is_checked = $value ? 'checked' : '';
      $html .= "<input type='checkbox' name='$this->slug' $is_checked>";
      break;
    case 'int':
      $html .= "<input type='number' name='$this->slug' value='$value'>";
      break;
    case 'float':
      $html .= "<input type='number' step='any' name='$this->slug' value='$value'>";
      break;
    }
    return $html;
  }

}
