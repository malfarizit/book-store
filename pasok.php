
<?php
include  ('config/koneksi.php');

$db = get_db_conn();
if(isset($_POST['action'])){
	$kode_pasok	= $_POST['kode_pasok'];
	$kode_distributor	= $_POST['kode_distributor'];
	$kode_buku 	= $_POST['kode_buku'];
	$jumlah 	= $_POST['jumlah'];
	$tanggal	= $_POST['tanggal'];
	
	if($_POST['action'] == 'save'){
		$qr = mysql_query("INSERT INTO pasok(kode_pasok,kode_distributor,kode_buku,jumlah,tanggal) 
		values('".$kode_pasok."','".$kode_distributor."','".$kode_buku."' , '".$jumlah."', '".$tanggal."') ");
		echo mysql_error();
	}
	if($_POST['action'] == 'update'){
		$upd = "kode_pasok = '".$kode_pasok."',kode_distributor = '".$kode_distributor."',kode_buku = '".$kode_buku."', jumlah = '".$jumlah."', tanggal = '".$tanggal."'";
		$qr = mysql_query("UPDATE pasok SET ".$upd." WHERE kode_pasok ='".$_POST['old_kode_pasok']."' ");
		echo mysql_error();
	}
}
$act = '';
if(isset($_GET['action'])){
	$act = $_GET['action'];
	$kd = $_GET['kd'];
	if($act =='hapus'){
		$qr = mysql_query("DELETE FROM pasok WHERE kode_pasok='".$kd."' ");
		header("location:pasok.php");
	}
	if($act=='edit'){
		$qr = ''; $r ='';
		// 							0	  1		2			3	4
		$qr = mysql_query("SELECT kode_pasok,kode_distributor,kode_buku,jumlah,tanggal FROM pasok WHERE kode_pasok='".$kd."' ");	
		$r = mysql_fetch_array($qr);
		$ED['kode_pasok'] 			= $r[0];
		$ED['kode_distributor'] 	= $r[1];
		$ED['kode_buku'] 			= $r[2];
		$ED['jumlah']				= $r[3];
		$ED['tanggal']	 			= $r[4];
		}
}


$kd_nw = '';

$qr = ''; $sh= ''; $r ='';
$qr = mysql_query("SELECT kode_distributor, nama_distributor FROM distributor order by nama_distributor asc"); 
while($r = mysql_fetch_array($qr)){
	$bs[$r[0]]['kode_distributor'] = $r[0];
	$bs[$r[0]]['nama_distributor'] = $r[1];
}
$qr1 = mysql_query("SELECT kode_buku,penerbit FROM buku order by penerbit asc"); 
while($r1 = mysql_fetch_array($qr1)){
	$bs1[$r1[0]]['kode_buku'] = $r1[0];
	$bs1[$r1[0]]['penerbit'] = $r1[1];
}

$qr2 = mysql_query("SELECT tanggal FROM pasok "); 
while($r2 = mysql_fetch_array($qr2)){
		$r2['tanggal']=date("Y-m-d");	
}
// 							0	  1		2			3	4
$qr = mysql_query("SELECT kode_pasok,kode_distributor,kode_buku,jumlah,tanggal FROM pasok order by kode_pasok ASC"); 
if($qr){
	while($r = mysql_fetch_array($qr)){
		$sh[$r[0]]['kode_pasok'] 		    = $r[0];
		$sh[$r[0]]['kode_distributor'] 	= $r[1];
		$sh[$r[0]]['kode_buku'] 	     	= $r[2];
		$sh[$r[0]]['jumlah']		 	      = $r[3];
		$sh[$r[0]]['tanggal'] 		    	= date('d-M-Y');
		
	}
	$qr = '';
	$qr = mysql_query("SELECT max(right(kode_pasok,4)) FROM pasok");
	$q = mysql_fetch_array($qr);
	$kd_new = 'PSK-'.str_pad(($q[0] + 1),4,'0',STR_PAD_LEFT);
}else{
	$kd_new = 'PSK-0001';
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>PASOK</title>


<?php include'cssin.html' ?>

</head>

<body>


<!-- menu -->
<?php
	create_header();
?>
<!-- menu -->


<div class="content">
  <p>          <form action="pasok.php" enctype="multipart/form-data"method="post">
             
    <?php
   		if($act=='edit'){
			
			
			//ACTION UNTUK UBAH
	?>
	 		 <table border="0"cellspacing="0"cellpadding="10" align="center" width="100%">
       <tr>
          <td colspan="3" bgcolor="#ccc"><div align="center"><B>UBAH</B></div></td>
               	<input type="hidden" name="action" id="action" value="update" />
        <input type="hidden" id="old_kode_pasok" name="old_kode_pasok" value="<?php echo $ED['kode_pasok'] ; ?>" />
      
          </tr>
            <tr> 	
 					<tr valign="baseline">
                      <td nowrap="nowrap" align="right">Kode Pasok</td>
                      <td>:</td>
                      <td> <input type="text" id="kode_pasok" name="kode_pasok" value="<?php echo $ED['kode_pasok'] ; ?>" readonly="readonly"size="32"/></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Distributor</td>
                      <td>:</td>
                      <td><select name="kode_distributor" id="kode_distributor">
							<?php
                            if($bs){
                            foreach($bs as $n => $v){
                                $sel = '';
                                if($v['kode_distributor']==$ED['kode_distributor']){$sel ='selected';}
                                echo '<option value="'.$v['nama_distributor'].'" '.$sel.'>'.$v['kode_distributor'].' : '.$v['nama_distributor'].'</option>';	
                                }
                            }
                            ?> 
        					</select> </td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Buku</td>
                      <td>:</td>
                      <td><select name="kode_buku" id="kode_buku"onchange="hargabuku()">
							<?php
                            if($bs1){
                            foreach($bs1 as $n => $v){
                                $sel = '';
                               if($v['kode_buku']==$ED['kode_buku']){$sel ='selected';}
                               echo '<option value="'.$v['penerbit'].'" '.$sel.'>'.$v['kode_buku'].' : '.$v['penerbit'].'</option>';	
                                }
                            }
                            ?> 
        					</select></td>
                    </tr>
					<tr valign="baseline">
                      <td nowrap="nowrap" align="right">Jumlah</td>
                      <td>:</td>
                      <td><input type="text" name="jumlah" id="jumlah"value="<?php echo $ED['jumlah'] ; ?>" size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Tanggal</td>
                      <td>:</td>
                      <td><input type="text" name="tanggal" id="tanggal"value="<?php echo $ED['tanggal']=date("d-m-Y") ; ?>" size="32" /></td>
                    </tr>
                    
                   <tr valign="baseline">
                     
                      <td colspan="3"><div align="center"><input type="submit"a;l value="Ubah Data" /></div></td>
                    </tr>
                </form>
                 
       </table>
    <?php	
		}else{
			
			
			
			
			//ACTION UNTUK TAMBAH


	?>
  <div class="tampil" >
    <img src="img/newdata.png" />TAMBAH DATA</div>
    <div class="sembunyi">
 	<table border="0"cellspacing="0"cellpadding="10" align="center" width="100%">
                 <tr>
          <td colspan="3" bgcolor="#ccc"><div align="center"><B>TAMBAH DATA</B></div></td>
          <input type="hidden" name="action" id="action" value="save" />
          </tr>
            <tr> 	
 					<tr valign="baseline">
                      <td nowrap="nowrap" align="right">Kode Pasok</td>
                      <td>:</td>
                      <td><input type="text" id="kode_pasok" name="kode_pasok"  value="<?php echo $kd_new; ?>" readonly="readonly"size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Distributor</td>
                      <td>:</td>
                      <td><select name="kode_distributor" id="kode_distributor">
           				 <?php
         				   if($bs){
						   foreach($bs as $n => $v){
						   echo '<option value="'.$v['nama_distributor'].'">'.$v['kode_distributor'].' : '.$v['nama_distributor'].'</option>';	
								}
						   }
						   ?> </select></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Buku</td>
                      <td>:</td>
                      <td><select name="kode_buku" id="kode_buku">
           				 <?php
         				   if($bs1){
						   foreach($bs1 as $n => $v){
						   echo '<option value="'.$v['penerbit'].'">'.$v['kode_buku'].' : '.$v['penerbit'].'</option>';	
								}
						   }
						   ?> </select></td>
                    </tr>
					<tr valign="baseline">
                      <td nowrap="nowrap" align="right">Jumlah</td>
                      <td>:</td>
                      <td><input type="text" name="jumlah" id="jumlah"value=""  "size="32" /></td>
                    </tr>
                    <tr valign="baseline">
                      <td nowrap="nowrap" align="right">Tanggal</td>
                      <td>:</td>
                      <td><input name="tanggal" type="text" id="tanggal" value="<?php echo $r2['tanggal']=date("Y-m-d"); ?>"readonly="readonly">
            </td>

                    
                    <tr valign="baseline">
                      <td colspan="3"><div align="center"><input type="submit" value="Tambah" /></div></td>
                    </tr>
                  </table>
                  <?php $kd_new++; }?>
                </form>
                <p>&nbsp;</p>
                </tr>
                   </div>
       <table width="100%"border="1" cellpadding="10" cellspacing="0">
        <tr>
        <td  align="center" ><b>Kode Pasok</b></td>
        <td  align="center" ><b>Distributor</b></td>
        <td align="center" ><b>Buku</b></td>
        <td  align="center" ><b>Jumlah</b></td>
        <td  align="center" ><b>Tanggal</b></td>
        <td colspan="3" align="center" ><b>Modifikasi</b></td>
        </tr>
                  <?php
      	if($sh){
			foreach($sh as $n => $v){
				?>
			<tr>
			<td align="center"><?=$v['kode_pasok']; ?></td>
            <td align="center"><?=$v['kode_distributor'];?></td>
			<td align="center"><?=$v['kode_buku'];?></td>
			<td align="center"><?=$v['jumlah'];?></td>
            <td align="center"><?=$v['tanggal'];?></td>
            <td width="15" align="center" bgcolor="#CCCCCC"><a href="pasok.php?action=edit&kd=<?php echo $v['kode_pasok']; ?>">Edit</a></td>
            <td width="53" align="center" bgcolor="#CCCCCC"><a href="javascript:if (confirm('Anda Yakin Untuk Menghapus Data?'))
             { 	window.location.href='pasok.php?action=hapus&kd=<?php echo $v['kode_pasok']; ?>' }
              else { volid('') };">Delete</a></td></tr>
            <?php 
			}
		}else{
			echo '<td colspan="8"><div align="center"><b>Maaf Tidak ada data untuk ditampilkan</b><td> ';	
		}

	  ?>
      </tr>
       </table>
       </td>
            </tr>
            <tr>
                          <td>  <form method="get" action="config/laporan_excel.php">
              <input name="tipeLaporan" type="hidden" id="tipeLaporan" value="belicash" />
         
          <input name="sql" type="hidden" id="sql" value="<?php echo $qr; ?>" />
        
        </form></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td height="51" bgcolor="#CCCCCC">&nbsp;</td>
      </tr>
    </table></td>
  </tr>
 </div>
<div class="footer">
  <img src="img/facebook.png">
  <img src="img/instagram.png">
  <img src="img/twitter.png">
  <img src="img/google-plus.png">

  <p>ini footer nya guys, isi sendiri ya , he..he..he..</p>

  <div class="ft">Copyright 2016</div>

</div>
</body>
</html>