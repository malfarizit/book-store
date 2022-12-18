<?php

function get_db_conn() {
	$conf = $GLOBALS['config'];
	$conn = mysql_connect($conf['db_host'], $conf['db_user'], $conf['db_pass']);
	if (!$conn) {
		echo "We are sorry, but we could not connect to the SQL database.<BR>\n".mysql_error();
		return;
	}
	mysql_select_db($conf['db_name'], $conn);
	return $conn;
}

function create_header(){
	
	$lg = '';
	if(isset($_COOKIE['uid'])){	
		$lg = 'yes';
		$lvl = $_COOKIE['level'];
	}else{
		header('location:login.php');	
	}
?>

<link href="css/1.css" rel="stylesheet" type="text/css">
  <link href="css/style.css" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="css/font-face.css">
  <link rel="stylesheet" href="css/animate.css">

   

    <div class="menu tidakprint">   
    <div class="logo animated bounce">
    <img src="img/logo.png">
    </div>
    <ul>
      <li class="animated fadeInDown"><a href="index.php">      <img src="img/Home.png">Home</a></li>
      <?php if($lvl == 'admin'){ ?>
      <li class="animated fadeInDown"><a href="buku.php">       <img src="img/buku.png">BUKU</a></li>
      <li class="animated fadeInDown"><a href="distributor.php"><img src="img/dist.png">DISTRIBUTOR</a></li>
      <li class="animated fadeInDown"><a href="pasok.php">      <img src="img/pasok.png">PASOK</a></td></li>
      <li class="animated fadeInDown"><a href="kasir.php">      <img src="img/kasir.png">KASIR</a></li>
      <li class="animated fadeInDown"><a href="pembeli.php">    <img src="img/pembeli.png">PEMBELI</a></li>
      <li class="animated fadeInDown"><a href="pembelian.php" > <img src="img/pembelian.png">PEMBELIAN</a></li>
      <?php } ?>
      <?php if($lvl == 'kasir'){ ?>
      <li class="animated fadeInDown"><a href="kasirlogin.php"> <img src="img/kasir.png">PEMBELI</a></li>
      <li class="animated fadeInDown"><a href="pembeli.php">    <img src="img/pembeli.png">PEMBELI</a></li></a></li>
      <?php } ?>
      <li class="animated fadeInDown"><a href="login.php?action=logout" ><img src="img/logout.png">Log - Out</a></li>
    </ul>
		  
      </div>
          
        
        </td>
      </tr>
    </table>
    </td>
  </tr>
</table>
  	
                
                
                
                 
                
                
                
                
                
                
                
                
                
                
                
                
                
            </div>
        </div>
    
    
    </div>
		
<?php	
}

?>
