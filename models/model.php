<?php

class Model {
  public static $table_name = null;
  public $form_fields = [ ];
  protected $db;
  function __construct($opts) {
    global $db;
    $this->db = $db;
  }

  function getValidationErrors($post) {
    $errors = [];
    foreach ($this->form_fields as $field) {
      if (!isset($field->validation)) {
        continue;
      }
      foreach ($field->validation as $validation_rule) {
        switch ($validation_rule)
        {
        case 'notempty':
          $errors[$field->slug] = $this->validateNotEmpty($field, $post[$field->slug]);
          break;
        case 'onlyslugcharacters':
          $errors[$field->slug] = $this->validateOnlySlugCharacters($field, $post[$field->slug]);
          break;
        }
        if ($errors[$field->slug] !== true) {
          break; // break the loop, to stop validating the other validation types on this field
        }
      }
    }
    return $errors;
  }

  function isValid() {
    foreach ($this->getValidationErrors($_POST) as $field_name => $validation) {
      if ($validation !== true) {
        return false;
      }
    }
    return true;
  }

  function validateNotEmpty($field, $posted_value) {
    if ($posted_value === '') {
      return "Must not be empty.";
    }
    return true;
  }

  function validateOnlySlugCharacters($field, $posted_value) {
    if (preg_match('/^[a-z0-9-]+$/i', $posted_value) !== 1) {
      return "Only lowercase letters, digits and dashes are permitted.";
    }
    return true;
  }
}
