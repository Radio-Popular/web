<?php

require_once 'excel_reader/reader.php';



function grilla_mostrar($filename) {
  $excel = new Spreadsheet_Excel_Reader();
  $excel->setOutputEncoding('CP1251');
  $excel->setRowColOffset(0);
  $excel->read($filename);
  $sheet = $excel->sheets[0];
  $cells = $sheet['cells'];
  $info = array_key_exists('cellsInfo', $sheet) ? $sheet['cellsInfo'] : array();
  $num_rows = $sheet['numRows'];
  $num_cols = $sheet['numCols'];
  $tabla = array();
  for ($y = 0; $y < $num_rows; $y++)
    for ($x = 0; $x < $num_cols; $x++)
      if (array_key_exists($y, $cells) && array_key_exists($x, $cells[$y])) {
        if (array_key_exists($y, $info) && array_key_exists($x, $info[$y]) && array_key_exists('type', $info[$y][$x]) && $info[$y][$x]['type'] == 'date') {
          $hora_dec = '' . (24 * $info[$y][$x]['raw']);
          $tabla[$y][$x] = sprintf('%d:%02d', $hora_dec, 60 * ($hora_dec - intval($hora_dec)));
        } else
          $tabla[$y][$x] = $cells[$y][$x];
      } else
        $tabla[$y][$x] = '';


  $colspans = array();
  for ($y = 0; $y < $num_rows; $y++) {
    $colspan = 0;
    for ($x = 1; $x < $num_cols; $x++) {
      $colspan++;
      $ant = $tabla[$y][$x - 1];
      $act = $tabla[$y][$x];
      if ($ant != $act) {
        $colspans[$y][$x - $colspan] = $colspan;
        $colspan = 0;
      } else
        $colspans[$y][$x] = 0;
    }
    $colspans[$y][$x - $colspan - 1] = $colspan + 1;
  }

  $rowspans = array();
  for ($x = 0; $x < $num_cols; $x++) {
    $rowspan = 0;
    for ($y = 1; $y < $num_rows; $y++) {
      $rowspan++;
      $ant = $tabla[$y - 1][$x];
      $act = $tabla[$y][$x];
      if ($ant != $act || $colspans[$y - 1][$x] != $colspans[$y][$x]) {
        $rowspans[$y - $rowspan][$x] = $rowspan;
        $rowspan = 0;
      } else
        $rowspans[$y][$x] = 0;
    }
    $rowspans[$y - $rowspan - 1][$x] = $rowspan + 1;
  }

  echo "<table class=\"grilla_programacion\">\n";
  echo '<thead><tr>';
  for ($x = 0; $x < $num_cols; $x++)
    echo '<th>', $tabla[0][$x], '</th>';
  echo "</tr></thead>\n";
  echo '<tbody>';
  for ($y = 1; $y < $num_rows; $y++) {
    echo '<tr>';
    for ($x = 0; $x < $num_cols; $x++) {
      $colspan = $colspans[$y][$x];
      $rowspan = $rowspans[$y][$x];
      if ($colspan > 0 && $rowspan > 0) {
        $tag = $x == 0 ? 'th' : 'td';
        echo '<', $tag;
        if ($colspan > 1)
          echo ' colspan="', $colspan, '"';
        if ($rowspan > 1)
          echo ' rowspan="', $rowspan, '"';

        if ($x > 0 && !empty($tabla[$y][$x])) {
          echo ' title="';
          echo $tabla[0][$x];
          if ($colspan > 1)
            echo ' a ', $tabla[0][$x + $colspan - 1];
          echo ' de ', $tabla[$y][0];
          if ($y + $rowspan < $num_rows)
            echo ' a ', $tabla[$y + $rowspan][0];
          else
            echo ' en adelante';
            
          echo '"';
        }

        if (empty($tabla[$y][$x]))
          echo ' class="vacio">&nbsp;';
        else
          echo '>', $tabla[$y][$x];
        echo '</', $tag, '>';
      }
    }
    echo "</tr>\n";
  }
  echo "</tbody></table>\n";

}

?>
