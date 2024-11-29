<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class SimpleSheetExport implements FromArray, WithTitle, WithHeadings
{
  private $data;
  private $title;
  private $headers;

  public function __construct(array $data, string $title, array $headers)
  {
    $this->data = $data;
    $this->title = $title;
    $this->headers = $headers;
  }

  public function array(): array
  {
    return $this->data;
  }

  public function title(): string
  {
    return $this->title;
  }

  public function headings(): array
  {
    return $this->headers;
  }
}
