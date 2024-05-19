<?php
include_once("koneksi.php");
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, 
        initial-scale=1.0">

        <!-- Bootstrap offline -->

        <link rel="stylesheet" href="assets/css/bootstrap.css"> 

        <!-- Bootstrap Online -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" 
        rel="stylesheet" 
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC"
        crossorigin="anonymous">   
    </head>
    <body>
        <!--Form Input Data-->
        <form class="mb-4" method="POST" action="index.php?page=periksa" name="periksaform" onsubmit="return(validate());">
            <!-- Kode php untuk menghubungkan form dengan database -->
            <!-- Kode berikut ditambahkan setelah tag form dan sebelum input nama -->
            <?php
            $id_pasien = '';
            $id_dokter = '';
            $tgl_periksa = '';
            $obat = '';
            $catatan = '';
            if (isset($_GET['id'])) {
                $periksa = mysqli_query($mysqli, "SELECT pr.*,d.nama as 'nama_dokter', p.nama as 'nama_pasien' 
                                                    FROM periksa pr 
                                                    LEFT JOIN dokter d ON (pr.id_dokter=d.id)
                                                    LEFT JOIN pasien p ON (pr.id_pasien=p.id)
                                                    WHERE pr.id='" . $_GET['id'] . "'
                                                    ORDER BY pr.tgl_periksa DESC");
                while ($row = mysqli_fetch_array($periksa)) {
                    $id_pasien = $row['id_pasien'];
                    $id_dokter = $row['id_dokter'];
                    $tgl_periksa = $row['tgl_periksa'];
                    $obat = $row['obat'];
                    $catatan = $row['catatan'];
                }
            ?>
                <input type="hidden" name="id" value="<?php echo $_GET['id'] ?>">
            <?php
            }
            ?>
            <!--input nama-->
            <div class="form-group mx-sm-3 mb-2">
                <label for="namaPasien" class="form-label fw-bold">Nama Pasien</label>
                <select name="id_pasien" class="form-control">
                        <option value=""></option>
                        <?php
                        $selected = '';
                        $pasien = mysqli_query($mysqli, "SELECT * FROM pasien");
                        while ($data = mysqli_fetch_array($pasien)) {
                            if ($data['id'] == $id_pasien) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                        ?>
                            <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                        <?php
                        }
                        ?>
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="namaDokter" class="form-label fw-bold">Nama Dokter</label>
                <select name="id_dokter" class="form-control">
                        <option value=""></option>
                        <?php
                        $selected = '';
                        $dokter = mysqli_query($mysqli, "SELECT * FROM dokter");
                        while ($data = mysqli_fetch_array($dokter)) {
                            if ($data['id'] == $id_dokter) {
                                $selected = 'selected="selected"';
                            } else {
                                $selected = '';
                            }
                        ?>
                            <option value="<?php echo $data['id'] ?>" <?php echo $selected ?>><?php echo $data['nama'] ?></option>
                        <?php
                        }
                        ?>
                </select>
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="tglPeriksa" class="form-label fw-bold">Tanggal Periksa</label>
                <input type="datetime-local" class="form-control" id="tglPeriksa" name="tgl_periksa" value="<?php echo $tgl_periksa ?>">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="obat" class="form-label fw-bold">Obat</label>
                <input type="text" class="form-control" id="obat" name="obat" value="<?php echo $obat ?>">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <label for="catatan" class="form-label fw-bold">Catatan</label>
                <input type="text" class="form-control" id="catatan" name="catatan" value="<?php echo $catatan ?>">
            </div>
            <div class="form-group mx-sm-3 mb-2">
                <button type="submit" class="btn btn-primary rounded-pill px-3" name="simpan">Simpan</button>
            </div>
        </form>        
        <!-- Table hasil isinya -->
        <table class="table table-hover">
            <!--thead atau baris judul-->
            <thead>
                <tr>
                    <th scope="col">No</th>
                    <th scope="col">Nama Pasien</th>
                    <th scope="col">Nama Dokter</th>
                    <th scope="col">Tanggal Periksa</th>
                    <th scope="col">Obat</th>
                    <th scope="col">Catatan</th>
                    <th scope="col">Aksi</th>
                </tr>
            </thead>
            <!--tbody berisi isi tabel sesuai dengan judul atau head-->
            <tbody>
                <!-- Kode PHP untuk menampilkan semua isi dari tabel -->
                <?php
                    $result = mysqli_query($mysqli, "SELECT pr.*,d.nama as 'id_dokter', p.nama as 'id_pasien' FROM periksa pr LEFT JOIN dokter d ON (pr.id_dokter=d.id) LEFT JOIN pasien p ON (pr.id_pasien=p.id) ORDER BY pr.tgl_periksa DESC");
                    $no = 1;
                    while ($data = mysqli_fetch_array($result)) {
                    ?>
                        <tr>
                            <td><?php echo $no++ ?></td>
                            <td><?php echo $data['id_pasien'] ?></td>
                            <td><?php echo $data['id_dokter'] ?></td>
                            <td><?php echo $data['tgl_periksa'] ?></td>
                            <td><?php echo $data['obat'] ?></td>
                            <td><?php echo $data['catatan'] ?></td>
                            <td>
                                <a class="btn btn-success rounded-pill px-3" 
                                href="index.php?page=periksa&id=<?php echo $data['id'] ?>">
                                Ubah</a>
                                <a class="btn btn-danger rounded-pill px-3" 
                                href="index.php?page=periksa&id=<?php echo $data['id'] ?>&aksi=hapus">Hapus</a>
                            </td>
                        </tr>
                    <?php
                    }
                ?>
            </tbody>
        </table>
        <!-- php simpan ubah hapus -->
        <?php
            if (isset($_POST['simpan'])) {
                if (isset($_POST['id'])) {
                    $ubah = mysqli_query($mysqli, "UPDATE periksa SET 
                                                id_pasien = '" . $_POST['id_pasien'] . "',
                                                id_dokter = '" . $_POST['id_dokter'] . "',
                                                tgl_periksa = '" . $_POST['tgl_periksa'] . "',
                                                obat = '" . $_POST['obat'] . "',
                                                catatan = '" . $_POST['catatan'] . "'
                                                WHERE
                                                id = '" . $_POST['id'] . "'");
                } else {
                    $tambah = mysqli_query($mysqli, "INSERT INTO periksa (id_pasien,id_dokter,tgl_periksa,obat,catatan) 
                                                VALUES ( 
                                                    '" . $_POST['id_pasien'] . "',
                                                    '" . $_POST['id_dokter'] . "',
                                                    '" . $_POST['tgl_periksa'] . "',
                                                    '" . $_POST['obat'] . "',
                                                    '" . $_POST['catatan'] . "'
                                                    )");
                }            
                echo "<script> 
                        document.location='index.php?page=periksa';
                        </script>";
            }
            if (isset($_GET['aksi'])) {
                if ($_GET['aksi'] == 'hapus') {
                    $hapus = mysqli_query($mysqli, "DELETE FROM periksa WHERE id = '" . $_GET['id'] . "'");
                }
                echo "<script> 
                        document.location='index.php?page=periksa';
                        </script>";
            }
        ?>        
    </body>
</html>