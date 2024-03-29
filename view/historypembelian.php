<?php
 session_start();
 include '../connection/koneksi.php';
 $id = $_SESSION['id'];
 ?>
<html>

  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <script type="text/javascript" src="http://cdnjs.cloudflare.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
    <script type="text/javascript" src="http://netdna.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js"></script>
    <link href="http://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.3.0/css/font-awesome.min.css"
    rel="stylesheet" type="text/css">
    <link href="http://pingendo.github.io/pingendo-bootstrap/themes/default/bootstrap.css"
    rel="stylesheet" type="text/css">
  </head>

  <body>
    <?php if (!empty($_SESSION['id'])): ?>

    <div class="navbar navbar-default navbar-static-top">
      <div class="container">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-ex-collapse">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#"><span>Brand</span></a>
        </div>
        <div class="collapse navbar-collapse" id="navbar-ex-collapse">
          <ul class="nav navbar-nav navbar-right">
            <li class="active">
              <a href="home.php">Beranda</a>
            </li>
            <li>
              <a href="#">About Me </a>
            </li>
            <li>
              <a href="pembelian.php">Cart <span class="fa fa-cart">

                <?php
                $cek = mysqli_query($mysqli,"SELECT *from cart where status = 'aktif' AND pengguna_id = '$_SESSION[id]' ");
                $row = mysqli_num_rows($cek);
                if ($row > 0) {
                  # code...
                  echo '('.$row.')';
                }
                 ?>
              </span></a>
            </li>
            <li>
              <a href="historypembelian.php">Pembelian <span class="fa fa-cart">

              </span></a>
            </li>
            <li>
              <a href="#">Cara Pembelian Tiket</a>
            </li>
            <li>
              <a href="../logout.php">Logout</a>
            </li>
          </ul>
        </div>
      </div>
    </div>
    <div class="section">
      <div class="container">
        <div class="row">
          <div class="col-md-12">
            <div class="panel panel-primary">
              <div class="panel-heading">
                <h3 class="panel-title text-center">List Pembelian</h3>
              </div>
              <div class="panel-body">
                <table class="table">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Nama Pesawat</th>
                      <th>Tujuan Pesawat</th>
                      <th>Tanggal Berangkat</th>
                      <th>Jam Berangkat</th>
                      <th>Harga Tiket</th>
                      <th>Kode Booking</th>
                      <th>Status</th>
                    </tr>
                  </thead>
                  <tbody>
                    <?php
                      $no=1;
                      $tampil = $mysqli->query("SELECT pesawat.pesawat,
                        pesawat.harga,
                        pesawat.tujuan,
                        pesawat.jam,
                        pesawat.tanggal,
                        pembayaran.formid,
                        pembayaran.tandabukti,
                        pembayaran.total,
                        pembayaran.created_at,
                        pembelian.* FROM pembelian
                        JOIN pesawat
                        ON pembelian.pesawat_id = pesawat.id
                        JOIN pembayaran
                        ON pembelian.pembayaran_id = pembayaran.id
                      where
                      pembayaran.pengguna_id = '$_SESSION[id]'
                      AND
                      pembelian.formid = pembayaran.formid
                      AND
                      pembelian.pesawat_id = pesawat.id
                      ") or die($mysqli->error);


                    ?>
                    <?php while ($data=$tampil->fetch_array()) {?>

                        <tr class="text-center">
                          <td><?php echo $no++; ?></td>
                          <td><?php echo $data['pesawat'] ?></td>
                          <td><?php echo $data['tujuan'] ?></td>
                          <td><?php echo $data['tanggal'] ?></td>
                          <td>
                            <?php echo $data['jam'] ?>
                          </td>
                          <td>
                            <?php echo $data['harga'] ?>
                          </td>
                          <td>
                            <?php if ($data['kode'] == NULL || $data['kode'] == ""): ?>
                              <?php echo $data['status'] ?>
                            <?php endif; ?>
                            <?php if ($data['status'] == 'ditolak'): ?>
                              <?php echo $data['status'] ?>
                            <?php endif; ?>
                            <?php if ($data['status'] == 'diterima'): ?>
                              <?php echo $data['kode'] ?>
                              <form method="get" action="cetakticket.php">
                                <input type="hidden" name="pembelian_id" value="<?php echo $data['id']; ?>">
                                <button type="submit" class="btn btn-primary">Cetak Kartu</button>
                              </form>
                            <?php endif; ?>
                          </td>
                          <td>
                            <?php echo $data['status'] ?>
                          </td>
                        </tr>

                  <?php
                  } ?>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  <?php else: ?>
    <?php include 'view/404.php'; ?>
  <?php endif; ?>
  </body>

</html>
