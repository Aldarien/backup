<?php
namespace App\Service;

use Carbon\Carbon;

class Backup
{
  protected $configuration;
  protected $data;

  public function __construct(array $configuration)
  {
    $this->configuration = $configuration;
    $this->checkConfig();
  }
  protected function checkConfig()
  {
    if ($this->configuration['backup']['location'] == 'file' and !isset($this->configuration['backup']['file'])) {
      throw new \Exception('Missing filename for backup.location.');
    }
    if ($this->configuration['backup']['location'] == 'source') {
      switch ($this->configuration['source']['driver']) {
        case 'mysql':
          $extractor = new \Backup\Extractor\MySQLExtractor($this->configuration['source']['database']);
          break;
        default:
          throw new \Exception('No known driver for source defined in configuration.');
          return;
      }
      $extractor->createBackup();
    }
  }
  public function hasBackup()
  {
    switch ($this->configuration['backup']['location']) {
      case 'file':
        $last = $this->checkFile();
        break;
      case 'source':
        $last = $this->checkSource();
        break;
    }
    return $this->checkFrecuency($last);
  }
  protected function checkFile()
  {
    $data = Yaml::load($this->configuration['backup']['file']);
    return $data->date;
  }
  protected function checkSource()
  {
    switch ($this->configuration['source']['driver']) {
      case 'mysql':
        $extractor = new \Backup\Extractor\MySQLExtractor($this->configuration['source']['database']);
        break;
      default:
        throw new \Exception('No known driver for source defined in configuration.');
        return;
    }
    return $extractor->checkLast();
  }
  protected function checkFrecuency($last)
  {
    $last = Carbon::parse($last, config('app.timezone'));
    return false;
  }
  public function extract()
  {
    switch ($this->configuration['source']['driver']) {
      case 'mysql':
        $extractor = new \Backup\Extractor\MySQLExtractor($this->configuration['source']['database']);
        break;
      default:
        throw new \Exception('No known driver for source defined in configuration.');
        return;
    }
    $this->data = $extractor->extract()->getData();
    $this->saveLast();
  }
  protected function saveLast()
  {
    $date = Carbon::now(config('app.timezone'));
    switch ($this->configuration['backup']['location']) {
      case 'source':
        $extractor->saveLast($date);
        break;
      case 'file':
        file_put_contents($this->configuration['backup']['file'], Yaml::dump(['date' => $date->format('Y-m-d H:i:s')]));
        break;
    }
  }
  public function save()
  {
    $factory = new SaverFactory();
    foreach ($this->configuration['output'] as $save) {
      $output = $factory->create($save['type']);
      $output->load($this->data);
      $output->setFilename($save['name']);
      $output->build();
      $output->save();
    }
  }
}
