<?php
namespace Backup\Saver;

use Symfony\Component\Yaml\Yaml;

class YAMLFile extends Saver
{
  public function __construct()
  {
    parent::__construct();
    $this->extension = 'yml';
  }
  public function build()
  {
    $this->file_data = Yaml::dump(array_values($this->data), 10, 2, 0);
  }
}
