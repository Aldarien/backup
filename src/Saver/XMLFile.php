<?php
namespace Backup\Saver;

class XMLFile extends Saver
{
  public function __construct()
  {
    parent::__construct();
    $this->extension = 'xml';
  }
  public function build()
  {
    $xml = new \SimpleXMLElement('<?xml version="1.0" encoding="UTF-8"?><sql></sql>');
    foreach ($this->data as $data) {
      $table = $xml->addChild('table');
      $table->addChild('name', $data['name']);
      $fields = $table->addChild('fields');
      foreach ($data['fields'] as $field_data) {
        $field = $fields->addChild('field');
        foreach ($field_data as $key => $value) {
          $field->addAttribute($key, $value);
        }
      }
      $values = $table->addChild('values');
      foreach ($data['values'] as $row) {
        $xRow = $values->addChild('value');
        foreach ($row as $column => $value) {
          if ($value != null) {
            $xRow->addChild($column, \htmlspecialchars($value));
          }
        }
      }
    }
    $dom = dom_import_simplexml($xml)->ownerDocument;
    $dom->formatOutput = true;
    $this->file_data = $dom->saveXML();
  }
}
