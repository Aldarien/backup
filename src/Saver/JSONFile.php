<?php
namespace Backup\Saver;

class JSONFile extends Saver
{
  public function __construct()
  {
    parent::__construct();
    $this->extension = 'json';
  }
  public function build()
  {
    $this->file_data = json_encode(array_values($this->data), \JSON_PRETTY_PRINT);
  }
}
