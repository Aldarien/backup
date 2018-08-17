<?php
namespace Backup\Saver;

class SQLFile extends Saver
{
  public function __construct()
  {
    parent::__construct();
    $this->extension = 'sql';
  }
  public function build()
  {

  }
}
