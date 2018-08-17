<?php
namespace Backup\Extractor;

use App\Definition\ExtractorInterface;

class MySQLExtractor implements ExtractorInterface
{
  protected $connection;
  protected $data;

  public function __construct($configuration)
  {
    $dsn = 'mysql:host=' . $configuration['host']['name'];
    if (isset($configuration['host']['port'])) {
      $dsn .= ';port=' . $configuration['host']['port'];
    }
    $dsn .= ';dbname=' . $configuration['name'];
    $this->connection = new \PDO(
      $dsn,
      $configuration['user']['name'],
      $configuration['user']['password']
    );
    $this->data = [];
  }
  public function extract(): ExtractorInterface
  {
    $query = "SELECT DATABASE() AS db";
    $st = $this->connection->query($query, \PDO::FETCH_OBJ);
    $db = $st->fetch()->db;
    $query = "SHOW TABLES";
    $tables = $this->connection->query($query, \PDO::FETCH_OBJ);
    foreach ($tables as $row) {
      $table = $row->{"Tables_in_{$db}"};
      $this->data[$table] = ['name' => $table, 'fields' => [], 'values' => []];
      $query = "DESCRIBE {$table}";
      $columns = $this->connection->query($query, \PDO::FETCH_OBJ);
      foreach ($columns as $column) {
        $type = $column->Type;
        $length = null;
        $unsigned = false;
        if (strpos($column->Type, ' ') !== false) {
          list($type, $unsigned) = explode(' ', $column->Type);
        }
        if (strpos($type, '(') !== false) {
          list($type, $length) = explode('(', $type);
          $length = rtrim($length, ')');
        }
        $this->data[$table]['fields'] []= [
          'name' => $column->Field,
          'type' => $type,
          'length' => $length,
          'unsigned' => ($unsigned == 'unsigned'),
          'null' => ($column->{"Null"} == 'YES'),
          'primary' => ($column->Key == 'PRI'),
          'default' => $column->Default
        ];
      }

      $query = "SELECT * FROM {$table}";
      $st = $this->connection->query($query, \PDO::FETCH_ASSOC);
      $this->data[$table]['values'] = (array) $st->fetchAll();
    }
    return $this;
  }
  public function getData(): array
  {
    return $this->data;
  }
  public function createBackup()
  {
    $query = "CREATE TABLE IF NOT EXISTS `backup` (`id` INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY, `date` DATETIME)";
    $this->connection->query($query);
  }
  public function saveLast(\DateTime $date)
  {
    $query = "INSERT INTO backup (`date`) VALUES (?)";
    $st = $this->connection->prepare($query);
    $st->execute([$date->format('Y-m-d H:i:s')]);
  }
  public function checkLast()
  {
    $query = "SELECT `date` FROM backup ORDER BY DESC id LIMIT 1";
    $st = $this->connection->query($query, \PDO::FETCH_OBJ);
    return $st->fetch()->date;
  }
}
