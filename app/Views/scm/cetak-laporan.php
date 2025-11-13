<!doctype html>
<html lang="id">
<head>
  <meta charset="utf-8">
  <title>Laporan Data</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 20px;
      color: #222;
    }
    h1 {
      text-align: center;
      margin-bottom: 20px;
    }
    table.table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 20px;
    }
    table.table th, table.table td {
      border: 1px solid #ccc;
      padding: 8px 10px;
      font-size: 14px;
    }
    table.table th {
      background: #f4f4f4;
      text-align: left;
    }
    table.table tfoot td {
      font-weight: bold;
      background: #fafafa;
    }
    .text-right { text-align: right; }
    .controls {
      margin-bottom: 15px;
    }
    .btn {
      padding: 6px 12px;
      background: #1976d2;
      color: white;
      border: none;
      border-radius: 4px;
      cursor: pointer;
    }
    @media print {
      .controls { display: none; }
    }
  </style>
</head>
<body>
  <h1>Laporan <?=$title?></h1>
  <h4>Periode : <?=date('d/m/Y', strtotime($filter['start_date']))?> s/d <?=date('d/m/Y', strtotime($filter['end_date']))?></h4>

  <table class="table">
    <thead>
        <tr>
            <th>#</th>
            <?php foreach($columns as $key => $column): ?>
            <th><?=$column['label']?></th>
            <?php endforeach ?>
        </tr>
    </thead>
    <tbody>
        <?php 
        $total = 0; 
        $totalHarga = 0;
        foreach($data as $index => $list): 
        $total += $list['jumlah']; 
        $totalHarga += $list['sub_total_harga'];
        ?>
        <tr>
            <td><?=$index+1?></td>
            <?php foreach($columns as $key => $column): ?>
            <td><?=$list[$key]?></td>
            <?php endforeach ?>
        </tr>
        <?php endforeach ?>

        <?php if(empty($data)): ?>
        <tr>
            <td colspan="<?=count($columns)+2?>"><i>Tidak ada data</i></td>
        </tr>
        <?php else: ?>
        <tr>
            <td colspan="<?= session()->get('level') == 'Supplier' ? 3 : 4?>">Total</td>
            <td><?=number_format($total)?></td>
            <td></td>
            <td><?=number_format($totalHarga)?></td>
        </tr>
        <?php endif ?>
    </tbody>
  </table>
  <center>
    <p>
      <b>Diketahui Oleh,</b><br>
      Air Joman, <?= date('d F Y') ?>
    </p>
  </center>
  <br><br><br><br><br><br>
  <table width="100%" cellspacing="0" cellpadding="0">
    <tr>
      <td width="50%">
        <center>
          <b><u>Pak Usuf</u></b><br>
          <b>OWNER GROSIR USUF</b>
        </center>
      </td>
      <td width="50%">
        <center>
          <b><u>Pak Anang</u></b><br>
          <b>OWNER CV. KUE GELANG PUJA</b>
        </center>
      </td>
    </tr>
  </table>
  <script>window.print()</script>
</body>
</html>
