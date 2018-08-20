<?php
namespace App\Definition;

interface SaverInterface
{
  public function load(array $data);
  public function setFilename(string $filename);
  public function addTable(string $table);
  public function addField(string $table, array $field_data);
  public function add(string $table, array $row);
  public function build();
  public function save();
}
