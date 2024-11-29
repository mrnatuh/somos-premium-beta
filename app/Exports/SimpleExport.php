<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class SimpleExport implements WithMultipleSheets
{
  private $data;

  public function __construct(array $data)
  {
    $this->data = $data;
  }

  public function sheets(): array
  {
    $sheets = [];

    foreach ($this->data as $title => $sheetData) {
      $sheets[] = new SimpleSheetExport(
        $sheetData['data'],
        $title,
        $sheetData['headers'] ?? [],
      );
    }

    return $sheets;
  }
}
