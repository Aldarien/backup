<?php
namespace Backup\Saver;

class SQLFile extends Saver
{
  public function __construct()
  {
    parent::__construct();
    $this->extension = 'sql';
  }
  public function build()
  {
    $sql = [];
    foreach ($this->data as $data) {
       $sql []= $this->createTable($data);
       $sql []= $this->inserts($data);
    }
    $this->file_data = implode(PHP_EOL, $sql);
  }
  protected function createTable($data)
  {
    $sql = ["CREATE TABLE IF NOT EXISTS `" . $data['name'] . "` ("];
    foreach ($data['fields'] as $field) {
      $str = "`" . $field['name'] . "` " . $field['type'];
      if ($field['length'] != '') {
        $str .= "(" . $field['length'] . ")";
      }
      if ($field['unsigned'] == true) {
        $str .= " UNSIGNED";
      }
      if ($field['null'] == true) {
        $str .= " NULL";
      } else {
        $str .= " NOT NULL";
      }
      if ($field['default'] != '') {
        $str .= " DEFAULT ";
        if ($field['type'] != 'int') {
          $str .= "'";
        }
        $str .= $field['default'];
        if ($field['type'] != 'int') {
          $str .= "'";
        }
      }
      if ($field['primary']) {
        if ($field['type'] == 'int') {
          $str .= " AUTO_INCREMENT";
        }
        $str .= " PRIMARY KEY";
      }
      $sql []= $str;
    }
    $sql []= ");";
    return implode(PHP_EOL, $sql);
  }
  protected function inserts($data)
  {
    $sql = "INSERT INTO `" . $data['name'] . "` (";
    $fields = [];
    foreach ($data['fields'] as $field) {
      $fields []= "`" . $field['name'] . "`";
    }
    $sql .= implode(', ', $fields) . ") VALUES ";
    $values = [];
    foreach ($data['values'] as $value) {
      $vals = [];
      foreach ($value as $v) {
        $str = '';
        if (!is_numeric($v)) {
          $str .= '"';
        }
        $str .= $v;
        if (!is_numeric($v)) {
          $str .= '"';
        }
        $vals []= $str;
      }
      $values []= "(" . implode(', ', $vals) . ")";
    }
    $sql .= implode(', ', $values) . ';';
    return $sql;
  }
}
