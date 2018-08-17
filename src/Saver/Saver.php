<?php
namespace backup\Saver;

use App\Definition\SaverInterface;

class Saver implements SaverInterface
{
  protected $filename;
  protected $extension;
  protected $data;
  protected $file_data;

  public function __construct()
  {
    $this->data = [];
  }
  public function load(array $data)
  {
    $this->data = $data;
  }
  public function setFilename(string $filename)
  {
    $this->filename = $filename;
  }
  public function addTable(string $table)
  {
    $this->data[$table] = ['table' => $table];
  }
  public function addField(string $table, array $field_data)
  {
    $this->data[$table]['fields'] []= $field_data;
  }
  public function add(string $table, array $row)
  {
    $this->data[$table]['values'] []= $row;
  }
  public function save()
  {
    file_put_contents($this->filename . '.' . $this->extension, $this->file_data);
  }
  public function build() {}
}
