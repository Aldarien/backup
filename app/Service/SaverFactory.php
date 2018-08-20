<?php
namespace App\Service;

use Stringy\Stringy;

class SaverFactory
{
  protected $configuration;
  public function __construct($configuration = [])
  {
    $this->configuration = $configuration;
    $this->loadSavers();
  }
  protected function loadSavers()
  {
    $dir = root() . '/src/Saver';
    $files = glob($dir . '/*.php');
    foreach ($files as $file) {
      $info = pathinfo($file);
      $name = '' . Stringy::create($info['filename'])->trimRight('File')->toLowerCase();
      if ($name == 'saver') {
        continue;
      }
      $class = '' . Stringy::create('/Backup/Saver/' . $info['filename'])->replace('/', "\\");
      $this->addSaver($name, $class);
    }
  }
  public function addSaver($name, $class_name)
  {
    $name = strtolower($name);
    if ($name == 'yaml') {
      $name = 'yml';
    }
    $this->configuration['savers'][$name] = $class_name;
  }
  public function removeSaver($name)
  {
    $name = strtolower($name);
    if ($name == 'yaml') {
      $name = 'yml';
    }
    unset($this->configuration['savers'][$name]);
  }
  public function getSaver($name)
  {
    $name = strtolower($name);
    if ($name == 'yaml') {
      $name = 'yml';
    }
    if (isset($this->configuration['savers'][$name])) {
      return $this->configuration['savers'][$name];
    }
    return null;
  }
  public function create($name)
  {
    $name = strtolower($name);
    if ($name == 'yaml') {
      $name = 'yml';
    }
    $class = $this->getSaver($name);
    if ($class == null) {
      throw new \Exception('There is no saver ' . $class . '.');
    }
    return new $class();
  }
}
