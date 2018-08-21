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
            'name' => 'localhost',
            "port" => 3307
          ],
          'name' => 'test',
          'user' => [
            'name' => 'test',
            'password' => 'test'
          ]
        ]
      ],
      'output' => [
        "files" => [
          "path" => "test_files",
          "name" => "test",
          "types" => [
            "yaml",
            "json",
            "xml",
            "sql"
          ]
        ]
      ],
      "backup" => [
        "location" => "source",
        "timezone" => "America/Santiago"
      ]
    ];
    $this->addTestData();
    $this->backup = new Backup($this->configuration);
  }
  protected function addTestData()
  {
    $dsn = 'mysql:host=localhost;port=' . $this->configuration['source']['database']['host']['port'] . ';dbname=test';
    $pdo = new PDO($dsn, $this->configuration['source']['database']['user']['name'], $this->configuration['source']['database']['user']['password']);
    $query = "CREATE TABLE IF NOT EXISTS test (id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY, value VARCHAR(50) NULL DEFAULT 'test')";
    $pdo->query($query);
    $query = "INSERT INTO test (value) VALUES ('test'), ('test2')";
    $pdo->query($query);
  }
  public function tearDown()
  {
    $dsn = 'mysql:host=localhost;port=' . $this->configuration['source']['database']['host']['port'] . ';dbname=test';
    $pdo = new PDO($dsn, $this->configuration['source']['database']['user']['name'], $this->configuration['source']['database']['user']['password']);
    $query = "DROP TABLE test";
    $pdo->query($query);
  }

  public function testExtract()
  {
    $response = $this->backup->extract();
    $this->assertNull($response);
  }
  public function testSave()
  {
    $this->backupSave();
  }
  protected function backupSave()
  {
    $this->backup->extract();
    $response = $this->backup->save();
    $this->assertNull($response);
  }
}
