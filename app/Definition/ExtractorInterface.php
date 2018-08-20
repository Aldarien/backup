<?php
namespace App\Definition;

interface ExtractorInterface
{
  public function extract(): ExtractorInterface;
  public function getData(): array;
}
