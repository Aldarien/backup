<?php
use PHPUnit\Framework\TestCase;

use App\Service\Backup;

class BackupTest extends TestCase
{
  protected $backup;
  public function setUp()
  {
    $this->configuration = [
      'source' => [
        'driver' => 'mysql',
        'database' => [
          'host' => [
            'name' => 'localhost'
          ],
          'name' => 'dev_incoviba',
          'user' => [
            'name' => 'incoviba_test',
            'password' => 'test'
          ]
        ]
      ],
      'output' => [
        [
          'type' => 'yaml',
          'name' => 'incoviba'
        ]
      ]
    ];
    $this->backup = new Backup($this->configuration);
  }
  protected function backupSave()
  {
    $this->backup->extract();
    $response = $this->backup->save();
    $this->assertNull($response);
  }
  public function testExtract()
  {
    $response = $this->backup->extract();
    $this->assertNull($response);
  }
  public function testYAMLRun()
  {
    $this->backupSave();
  }
  /*public function testJSONRun()
  {
    $this->backup->changeConfig('output.type', 'json');
    $this->backupSave();
  }
  public function testXMLRun()
  {
  $this->backup->changeConfig('output.type', 'xml');
  $this->backupSave();
  }
  public function testSQLRun()
  {
  $this->backup->changeConfig('output.type', 'sql');
  $this->backupSave();
  }*/
}
