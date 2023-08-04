<?php

class Field {
  public
    $slug,
    $name,
    $type,
    $value,
    $validation,
    $error = false;

  function __construct($opts) {
    if (!isset($opts['slug'])) {
      throw new Exception("A 'slug' value is necessary.");
    }
    $this->slug = $opts['slug'];
    $this->name = $opts['name'] ?? $this->slug;
    $this->type = $opts['type'] ?? 'string'; // string or text, int, float, date, datetime, bool
    $this->value = $opts['value'] ?? '';
    $this->validation = $opts['validation'] ?? [];
  }
  function __toString() {
    $value = $this->value;
    $html = "<label for='field_".h($this->slug)."'>".h($this->name)."</label>";
    $classes = '';
    if ($this->error) {
      $classes .= ' has-error';
    }
    switch ($this->type) {
    case 'string':
      $html .= "<input type='text' name='".h($this->slug)."'";
      $html .= " value='".h($value)."' id='field_".h($this->slug)."' classes='$classes'>";
      break;
    case 'text':
      $html .= "<textarea name='".h($this->slug)."' id='field_".h($this->slug)."'";
      $html .= " classes='$classes'>".h($value)."</textarea>";
      break;
    case 'bool':
      $is_checked = $value ? 'checked' : '';
      $html .= "<input type='checkbox' name='".h($this->slug)."' id='field_".h($this->slug)."'";
      $html .= " $is_checked classes='$classes'>";
      break;
    case 'int':
      $html .= "<input type='number' name='".h($this->slug)."' id='field_".h($this->slug)."'";
      $html .= " value='".h($value)."' classes='$classes'>";
      break;
    case 'float':
      $html .= "<input type='number' step='any' name='".h($this->slug)."'";
      $html .= " value='".h($value)."' classes='$classes'>";
      break;
    }
    return $html;
  }

}
