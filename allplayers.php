<?php

$IDU=$_COOKIE['IDU'];
$kolorred='#FF8F00';

$TABELA_KLUCZY_BAZA[1000];  //WYKRYWA DUBLE
$iTB=0;

/********************************************************************************************************************************************/
function CreateIniFile($data_array, $output_file, $keys)
{
	// Create ini content:
    $content = "";
	foreach ($data_array as $section_name => $section_data)
	{
		$content .= "[" . $section_name . "]\r\n";
		foreach ($section_data as $key => $value)
		{
			$content .= $key . "=".$value."\r\n";
		}
	}

	if (is_array($keys)) // crypt or not
	{
		// Crypt by xor keys:
		$keys_count = count($keys);
		$content_len = strlen($content);
		for ($i = 0; $i < $content_len; $i++) $content[$i] = chr(ord(substr($content, $i, 1)) ^ $keys[$i % $keys_count]);
	}

	// Save file:
    if (!$handle = fopen($output_file, 'w'))
	{
        return false;
    }
    if (!fwrite($handle, $content))
	{
        return false;
    }
    fclose($handle);
    return true;
}
/********************************************************************************************************************************************/
function getLineWithString($fileName, $str)
{
    $lines = file($fileName);
    foreach ($lines as $lineNumber => $line) {
        if (strpos($line, $str) !== false) {
            return $line;
        }
    }
    return '';
}
/********************************************************************************************************************************************/
 // definiujemy dane do połączenia z bazą danych
define('DBHOST_bqs', 'sql.prnpolska.nazwa.pl:3307');
define('DBUSER_bqs', 'prnpolska_2');
define('DBPASS_bqs', 'root4PLK');
define('DBNAME_bqs', 'prnpolska_2');

function db_connect_bqs() 
{
    // połączenie z mysql
    mysql_connect(DBHOST_bqs, DBUSER_bqs, DBPASS_bqs) or die('<h2>ERROR</h2> MySQL Server is not responding');

    // wybór bazy danych
    mysql_select_db(DBNAME_bqs) or die('<h2>ERROR</h2> Cannot connect to specified database');
}

function db_close_bqs() 
{
    mysql_close();
}
/********************************************************************************************************************************************/
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-2" />
<title>PRN Polska</title>
<link rel="stylesheet" type="text/css" href="basicwhite.css"/>
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon" />
<meta http-equiv="Content-Script-Type" content="text/javascript">

<?php
if((isset($_GET['scrollx']))||(isset($_GET['scrolly'])))
{
$scrx=$_GET['scrollx'];
$scry=$_GET['scrolly'];
}
else
{
$scrx=0;
$scry=0;
}

?>

<script language="javascript">

function saveScrollCoordinates(element)
{
element.scrollx.value = (window.document.all)?window.document.body.scrollLeft:window.pageXOffset;
element.scrolly.value = (window.document.all)?window.document.body.scrollTop:window.pageYOffset;
}

function scrollToCoordinates()
{
      //window.scrollTo(<?php echo $_GET['scrollx'];?>, <?php echo $_GET['scrolly'];?>);
      window.scrollTo(<?php echo $scrx;?>, <?php echo $scry;?>);
}
</script>

</head>

<?php
/************************************************************************************************************************************************************/
include('config.php');
$akcja=$_GET['akcja'];

if($akcja=='showplayers')
{
 $zapytanie = "UPDATE `user` SET `player`='$playlist' WHERE `idu`='$IDU' LIMIT 1";
 $update = @mysql_query($zapytanie);
 if($update){}
 else echo "<BR><h2>Error update playlist show</h2>";
 echo "<script>setTimeout('document.location = \"https://prnpolska.nazwa.pl/polsat/allplayers.php\"', 1);</script> ";
}
/************************************************************************************************************************************************************/
$akcja2=$_GET['akcja2'];

if($akcja2=='wyszukiwarka')
{
  $name_szukaj=$_GET['name_szukaj'];

  $wyszukiwarka="AND (`NAZWA` LIKE '%".$name_szukaj."%' OR `KEY` LIKE '%".$name_szukaj."%' OR `GROUP` LIKE '%".$name_szukaj."%')";
}
else
{
  $wyszukiwarka="";
}
/************************************************************************************************************************************************************/
?>
<body id="tsmenu10" onload="scrollToCoordinates();">
<?php include('menu.php'); ?>



<BR><BR><div class="right">
<div class="content">

<?php

include('skan_akcje.php');

$idp=$_GET['idp'];
$playlist=$_GET['playlist'];

$wynik = mysql_query("SELECT * FROM user WHERE idu='$IDU' LIMIT 1")or die('Błąd zapytania');
if(mysql_num_rows($wynik) > 0)
{while($r = mysql_fetch_assoc($wynik)){$kategoria=$r['typ'];$playershow=$r['player'];}}

echo "Kategoria: ".$kategoria."<br>";
echo "Players: ".$playershow."<br>";

if($kategoria=='administrator')
{
echo "<BR><h1>Serwery</h1><br>";

/***************************************************************************************************/
$wynik = mysql_query("SELECT * FROM tvwall_server WHERE '1'ORDER BY IP")or die('Błąd zapytania');
if(mysql_num_rows($wynik) > 0)
{
    $kolormenu='#eeeeee';
    $kolorurlop='#f3f3f3';
    echo "<table cellpadding=\"0\" border=0 width='100%'>";
    echo "<tr>";
    echo "<td bgcolor='$kolormenu'  align='center'>IDS</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>NAME</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>TYPE</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>IP</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>PORT</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>INTERVAL</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>MIN. AGE OF FILE</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>LOGIN</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>PASSWORD</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>FOLDER</td>";
    echo "</tr>";




    while($r = mysql_fetch_assoc($wynik)) {

        echo "<tr>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>".$r['IDS']."</td>";
        $ids=$r['IDS'];
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r['NAME']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>".$r['TYPE']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='$kolorred'>".$r['IP']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>".$r['PORT']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>".$r['INTERVAL']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>".$r['MAOF']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>".$r['LOGIN']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>******</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r['FOLDER']."</td>";
        echo "</tr>";
    }
    echo "</table>";
}


echo "<br><br><h1>Komputery</h1><br><BR>";

/************************************************************************************************************************************************************/
if($akcja=='add' or $akcja=='adddone')
{
$styl="STYLE=\"color: grey; font-family: Verdana; background-color: $kolorurlop; border: thin solid grey;\"";

echo "<table bordercolor='$kolorurlop' cellpadding=\"0\" border=0 width='100%'>";
echo "<tr><td colspan='13' bgcolor='$kolormenu'  align='center'><font color='white'>PLAYER</td></tr>";
echo"<tr><td>-                                       </td></tr>";
echo"<tr><td>Nazwa</td>";
echo"<td><input type=\"text\" $styl name=\"nazwa\" value=\"\" size=\"80\"/></td></tr>";
echo"<tr><td>Miasto</td>";
echo"<td><input type=\"text\" $styl name=\"miasto\" value=\"\" size=\"40\"/></td></tr>";
echo"<tr><td>Adres</td>";
echo"<td><TEXTAREA name=\"adres\" $styl COLS=\"39\" ROWS=\"1\" maxlength=\"20\"></TEXTAREA></td></tr>";

echo"<tr><td>IP/domena</td>";
echo"<td><input type=\"text\" $styl name=\"ip\" value=\"\" size=\"20\"/></td></tr>";
echo"<tr><td>-                                       </td></tr>";
echo"<tr><td>Serwery</td></tr>";
echo"<tr><td><font color=$kolorred>Playlist i konfiguracji</font></td>";

echo"<td >
    <SELECT NAME=\"playlist\" TYPE=\"text\" $styl>";

    	$wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE TYPE='PLAYLIST'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0){while($r1 = mysql_fetch_assoc($wynik1)) {
        echo "<OPTION>".$r1['IDS'].".".$r1['NAME'];}}

echo"</SELECT></td></tr>";

echo"<tr><td><font color=$kolorred>Media</font></td>";

echo"<td >
    <SELECT NAME=\"media\" TYPE=\"text\" $styl>";

    	$wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE TYPE='MEDIA'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0){while($r1 = mysql_fetch_assoc($wynik1)) {
        echo "<OPTION>".$r1['IDS'].".".$r1['NAME'];}}

echo"</SELECT></td></tr>";

echo"<tr><td><font color=$kolorred>Billing</font></td>";

echo"<td >
    <SELECT NAME=\"playlist\" TYPE=\"text\" $styl>";

    	$wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE TYPE='BILLING'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0){while($r1 = mysql_fetch_assoc($wynik1)) {
        echo "<OPTION>".$r1['IDS'].".".$r1['NAME'];}}

echo"</SELECT></td></tr>";

echo"<tr><td>MediaPath</td>";
echo"<td><input type=\"text\" $styl name=\"mediapath\" value=\"C:\HD\"/></td></tr>";
echo"<tr><td>Grupa</td>";
echo"<td >
    <SELECT NAME=\"grupa\" TYPE=\"text\" $styl >
        <OPTION>DISPLAY
        <OPTION>POSTV
	<OPTION>TVWALL
	<OPTION>TVWALLHD
	<OPTION>POK
    </SELECT></td></tr>";
echo"<tr><td>Shutdown</td>";
echo"<td><input type=\"text\" $styl name=\"shutdown\" value=\"22:00\" size=\"5\"/></td></tr>";
echo"<tr><td>Preloading</td>";
echo"<td >
    <SELECT NAME=\"preloading\" TYPE=\"text\" $styl >
        <OPTION>1
        <OPTION>0
    </SELECT></td></tr>";
echo"<tr><td>-                                       </td></tr>";
echo"<tr><td><input type=\"submit\" value=\"ADD\" onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class='button'></td></tr></table></form>";echo "<BR>";

}
/************************************************************************************************************************************************************/
if($akcja=='add_idl_baza_serwisowa')
{
  $location=$_GET['location'];
  $location_array=explode(":", $location);
  echo $IDL=$location_array[1];
  


  $update = @mysql_query("UPDATE `tvwall_player` SET `IDL_BS` = '$IDL' WHERE `ID` = '$idp' LIMIT 1");

  if($update){echo "<BR><h2>OK add IDL</h2>";}else{echo "<BR><h2>Error add IDL</h2>";}
  echo "<script>setTimeout('document.location = \"https://prnpolska.nazwa.pl/polsat/allplayers.php?akcja=serialnumber&scrollx=".$scrx."&scrolly=".$scry."\"', 1);</script> ";
}

/************************************************************************************************************************************************************/
echo "<table cellpadding=\"0\" border=0 >";
echo "<tr>";
echo "<td bgcolor='$kolorurlop' align='left' colspan='9'><font color='white'>
                             <form name=\"szukaj\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"text\" name=\"name_szukaj\" value=\"$name_szukaj\">
                             <input type=\"hidden\" name=\"akcja\" value=\"$akcja\">
                             <input type=\"hidden\" name=\"akcja2\" value=\"wyszukiwarka\">
                             <input type=\"submit\" value=\"Szukaj\" class=\"button\"> </form></td>";
                             
echo "</tr>";
echo "<tr>";
echo "<td bgcolor='$kolorurlop' align='center'><font color='white'>
                             <form name=\"all\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"playlist\" value=\"All\">
                             <input type=\"hidden\" name=\"akcja\" value=\"showplayers\">
                             <input type=\"submit\" value=\"All\" class=\"button\"> </form></td>";
                             
echo "<td bgcolor='$kolorurlop' align='center'><font color='white'>
                             <form name=\"all\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"playlist\" value=\"disable\">
                             <input type=\"hidden\" name=\"akcja\" value=\"showplayers\">
                             <input type=\"submit\" value=\"Dis\" class=\"button\"> </form></td>";

$wynikplaylist = mysql_query("SELECT * FROM `allplaylist` WHERE `TYPE`='MAIN' ORDER BY 'Playlist'")or die('Bd zapytania');
if(mysql_num_rows($wynikplaylist) > 0){while($rpls = mysql_fetch_assoc($wynikplaylist))
{echo "<td bgcolor='$kolorurlop' align='center'>
                             <form name=\"channel\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"playlist\" value=\"".$rpls['PLAYLIST']."\">
                             <input type=\"hidden\" name=\"akcja\" value=\"showplayers\">
                             <input type=\"submit\" value=\"".$rpls['PLAYLIST']."\" class=\"button\"> </form></td>";}}

echo "
<td bgcolor='white' align='center'><font color='$kolorred'>&nbsp;&nbsp;&nbsp;Akcje&nbsp;&nbsp;&nbsp;</font></td>
<td bgcolor='$kolorurlop' align='center'>
                             <form name=\"add\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"akcja\" value=\"add\">
                             <input type=\"submit\" value=\"Add player\" class=\"button\"> </form></td>";
echo "<td bgcolor='$kolorurlop' align='center'>
                             <form name=\"info\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"akcja\" value=\"info\">
                             <input type=\"submit\" value=\"Information\" class=\"button\"> </form></td>";
echo "<td bgcolor='$kolorurlop' align='center'>
                             <form name=\"info\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"akcja\" value=\"serialnumber\">
                             <input type=\"submit\" value=\"SerialNumber\" class=\"button\"> </form></td>";

$numer_starts=$_GET['numer_starts'];
$file_mp4_error=$_GET['file_mp4_error'];
$offline=$_GET['offline'];
$hang=$_GET['hang'];
$version=$_GET['version'];

if($numer_starts==1){$cheked1='checked';}else{$cheked1='';}
if($file_mp4_error==1){$cheked2='checked';}else{$cheked2='';}
if($offline==1){$cheked3='checked';}else{$cheked3='';}
if($hang==1){$cheked4='checked';}else{$cheked4='';}
if($version==1){$cheked5='checked';}else{$cheked5='';}

echo "<td bgcolor='white' align='center'>
                             <form name=\"info\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"checkbox\" name=\"numer_starts\" value=\"1\" ".$cheked1."/> liczba startów
                             <input type=\"checkbox\" name=\"file_mp4_error\" value=\"1\" ".$cheked2."/> bł&#261;d pliku
                             <input type=\"checkbox\" name=\"offline\" value=\"1\" ".$cheked3."/> brak ł&#261;cznosci
                             <input type=\"checkbox\" name=\"hang\" value=\"1\" ".$cheked4."/> zawieszenia
                             <input type=\"checkbox\" name=\"version\" value=\"1\" ".$cheked5."/> wersja
                             <input type=\"hidden\" name=\"akcja\" value=\"errors\">
                             <input type=\"submit\" value=\"Errors\" class=\"button\"> </form></td></tr>";
echo"</table><BR>";

echo "<font color='orange'><B>".$playershow."</B></font><BR><BR>";

echo "<table border=0 width='100%' style=\"padding: 5px;\">";
/***************************************************************************************************/
if($playershow=='All'){$wynik = mysql_query("SELECT * FROM tvwall_player WHERE `STATUS`='ENABLE' $wyszukiwarka ORDER BY `NAZWA` ASC, `GROUP` ASC")or die('Błąd zapytania');}
elseif($playershow=='disable'){$wynik = mysql_query("SELECT * FROM tvwall_player WHERE 1 $wyszukiwarka ORDER BY `NAZWA` ASC, `GROUP` ASC")or die('Błąd zapytania');}
else
{
  if($playershow=='Aleja')$key='ALEJA';
  elseif($playershow=='Monopol')$key='MONOPOL';
  elseif($playershow=='TvWall')$key='TVWALL';
  elseif($playershow=='TvWallHD')$key='TVWALLHD';
  elseif($playershow=='POK')$key='POK';
  elseif($playershow=='Display')$key='DISPLAY';
  elseif($playershow=='Wedzarnia')$key='WEDZARNIA';
  $wynik = mysql_query("SELECT * FROM tvwall_player WHERE `GROUP`='$key' AND `STATUS`='ENABLE' $wyszukiwarka ORDER BY `NAZWA` ASC, `GROUP` ASC")or die('Błąd zapytania');
}


/************************************ CSV *******************************************************/
$fd=@fopen("csv/komputery.csv","w");        //export csv

$content_csv="LP;ID;NAZWA;IP;WIN;SN;STATUS;PROCESOR;PLYTA GLOWNA;WOLNA PRZESTRZEN DYSKOWA\r\n";  //export csv
/************************************ CSV *******************************************************/

if(mysql_num_rows($wynik) > 0)
{    $i=0;


    echo "<tr>";
    echo "<td bgcolor='$kolormenu' align='center'>Lp</td>";
    echo "<td bgcolor='$kolormenu' align='center'>ID</td>";
    echo "<td bgcolor='$kolormenu' align='center'>Nazwa</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>Miasto</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>Adres</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>IP</td>";
    if($akcja=='serialnumber'){}
    else
    {
    echo "<td bgcolor='$kolormenu'  align='center'>Serwery<br>medialny/playlisty/billing</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>Media path</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>Grupa</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>Shutdown</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>Preloading</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>RSS</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>VPN</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>BILL</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>ERR</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>SKAN</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>CONFIG</td>";
    echo "<td bgcolor='$kolormenu'  align='center'>EDIT</td>";
    }
    echo "<td bgcolor='$kolormenu'  align='center'>LOGI</td>";
    echo "<td bgcolor='$kolormenu'  align='center'></td>";
    echo "<td bgcolor='$kolormenu'  align='center'></td>";
    echo "</tr>";




    while($r = mysql_fetch_assoc($wynik)) {



    /************************************************ Analiza billing **********************************************************/
    if($akcja=='info' or $akcja=='errors')
    {
       $errors=false;
       $komunikat="";

       $wczoraj  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
       $dataww = date('Y-m-d', $wczoraj);

       $wczorajb  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
       $datawwb = date('Y-m-d', $wczorajb);

       //$nazwaplikubill[$b] = "StatsBillings_".$datawwb.".csv";
       //$filenamebill = "./PublishFeedback/".$r['NAZWA']."/Billings/".$nazwaplikubill[$b];
       //if (file_exists($filenamebill)){}else{$errors=true;}

       $file5="./PublishFeedback/".$r['NAZWA']."/Messages/Messages_".$datawwb.".txt";
       $file6="./PublishFeedback/".$r['NAZWA']."/Messages/MessagesPlayer_".$datawwb.".txt";

      if (file_exists($file5))
      {
        $filecontent5= file_get_contents($file5);
        $convert = explode("\n", $filecontent5);
        $godz_start=$convert[0][0].$convert[0][1].":".$convert[0][2].$convert[0][3];
        $data = file($file5);
        $line = $data[count($data)-1];
        $godz_stop=$line[0].$line[1].":".$line[2].$line[3];

        //ERROR 6 wersja

        if($version==1){if($convert[0][27]!=='6' or $convert[0][28]!=='0'){$errors=true;}}
      }
      else
      {
         //ERROR 5 brak pliku

         if($offline==1){$errors=true; $komunikat=$komunikat."Brak pliku Messages.txt<BR>";}
      }


      if (file_exists($file6))
      {
        $filecontent6= file_get_contents($file6);
        $convert6 = explode("\n", $filecontent6);
        $godz_start_player=$convert6[0][1].$convert6[0][2].$convert6[0][3].$convert6[0][4].$convert6[0][5];
        $data = file($file6);
        $line = $data[count($data)-1];
        $godz_stop_player=$line[1].$line[2].$line[3].$line[4].$line[5];

        //ERROR 1  liczba startow

        $start = substr_count($filecontent6,'App started');
        $stop = substr_count($filecontent6,'App shutdown');
        if($start==$stop or $start==($stop+1)){}else {if($numer_starts==1){$errors=true; $komunikat=$komunikat."Liczba startów nieprawidłowa<BR>";}}

         //ERROR 2  błąd pliku

        $error_file_number=substr_count($filecontent6,"OnLog: [ERROR] (failed to render file) 'FAILED TO PLAY");
        if($error_file_number>0){if($file_mp4_error==1){$errors=true; $komunikat=$komunikat."Bł&#261;d pliku mp4<BR>";}}

         //ERROR 3 zawieszenia

        $zawieszenia_number=substr_count($filecontent6,'PlayingWatcher: playing state OK, position changed FAIL');
        if($zawieszenia_number>0){if($hang==1){$errors=true;}}

        //ERROR 7 wersja

        if($version==1){if($convert6[0][23]!=='6' or $convert6[0][24]!=='0'){$errors=true;}}
        


      }
      else
      {
         //ERROR 4  brak pliku

         if($offline==1){$errors=true;$komunikat=$komunikat."Brak pliku MessagesPlayer.txt<BR>";}
      }
   }
  /*********************************************** Analiza **********************************************************/

  if((($akcja!=='errors' and $akcja!=='serialnumber')  or ($akcja=='errors' and $errors and ($r['ERR']=='1'))) or ($akcja=='serialnumber' and ($r['SKAN']=='1')))
  {
        echo "<tr>";

        if($r['STATUS']=='ENABLE'){$kolorurlop='#f3f3f3';}elseif($r['STATUS']=='OFF'){$kolorurlop='#d3d3d3';}else{$kolorurlop='#FFDD99';}
        $i++;
        if($errors or $r['TVWallPlayer']=='0'){echo "<td bgcolor='$kolorred'  align='center'><font color='white'>".$i."</td>";}
        else{echo "<td bgcolor='$kolorurlop'  align='center'>".$i."</td>";}
        echo "<td bgcolor='$kolorurlop'  align='left'>".$r['ID']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='left'>".$r['NAZWA'];
        if($r['HD']=='1'){echo "<font color='orange'><B> HD</B></font>";}

        echo"</td>";
        echo "<td bgcolor='$kolorurlop'  align='left'><font color='grey'>".$r['MIASTO']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='left'><font color='grey'>".$r['ADRES']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'><font color='orange'>".$r['IP']."</td>";

        $NAZWAPLAYER=$r['NAZWA'];
        $MIASTOPLAYER=$r['MIASTO'];
        $ADRESPLAYER=$r['ADRES'];
        $IPPLAYER=$r['IP'];
        $SNPLAYER=$r['SN'];
        $WINPLAYER=$r['KEY'];
        $idpb=$r['ID'];
        $media=$r['MEDIA'];
        $playlist=$r['PLAYLIST'];
        $billing=$r['BILLING'];
        $preloading=$r['PRELOADING'];
        $notatka=$r['NOTATKA'];
        $IDL_BAZA_SERWISOWA=$r['IDL_BS'];
        $TVWallPlayer=$r['TVWallPlayer'];

        $volume=$r['VOLUME'];
        $volume_array=explode(':',$volume);
        
        $volume_1=$volume_array[0];
        $volume_2=$volume_array[1];
        $volume_3=$volume_array[2];
        $volume_4=$volume_array[3];
        
        
if($akcja=='serialnumber'){$GROUP=$r['GROUP'];}
else
{

        $wynik2 = mysql_query("SELECT * FROM tvwall_server WHERE IDS='$playlist'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik2) > 0)
        {
         while($r2 = mysql_fetch_assoc($wynik2)) {
         echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'>".$r2['IP']."<br>";

        $IDS = $r2['IDS'];
        $NameServer = $r2['NAME'];
        $URI = $r2['IP'];
        $Port = $r2['PORT'];
        $User = $r2['LOGIN'];
        $Pass = $r2['PASSWORD'];
        $Interval= $r2['INTERVAL'];
        $MAOF = $r2['MAOF'];

        }}

        $wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE IDS='$media'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0)
        {
         while($r1 = mysql_fetch_assoc($wynik1)) {
         echo $r1['IP']."<br>";

        $MediaIDS = $r1['IDS'];
        $MediaNameServer = $r1['NAME'];
        $MediaURI = $r1['IP'];
        $MediaPort = $r1['PORT'];
        $MediaUser = $r1['LOGIN'];
        $MediaPass = $r1['PASSWORD'];
        $MediaInterval= $r1['INTERVAL'];
        $MediaMAOF = $r1['MAOF'];

        }}

        $wynik3 = mysql_query("SELECT * FROM tvwall_server WHERE IDS='$billing'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik3) > 0)
        {
         while($r3 = mysql_fetch_assoc($wynik3)) {
         echo $r3['IP']."</td>";

        $BillingIDS = $r3['IDS'];
        $BillingNameServer = $r3['NAME'];
        $BillingURI = $r3['IP'];
        $BillingPort = $r3['PORT'];
        $BillingUser = $r3['LOGIN'];
        $BillingPass = $r3['PASSWORD'];
        $BillingInterval= $r3['INTERVAL'];

        }}

        echo "<td bgcolor='$kolorurlop' align='center'>".$r['MEDIAPATH']."</td>";
        $LocalMediaPath=$r['MEDIAPATH'];
        echo "<td bgcolor='$kolorurlop' align='center'>".$r['GROUP']."</td>";
        $Group=$r['GROUP'];
	echo "<td bgcolor='$kolorurlop' align='center'>".$r['CLOSETIME']."</td>";
        $CloseTime=$r['CLOSETIME'];

        if($r['PRELOADING']=='1')
        {echo "<td bgcolor='$kolorurlop' align='center'>ON</td>";}
        else
        {echo "<td bgcolor='$kolorurlop' align='center'>OFF</td>";}

         echo "<td bgcolor='$kolorurlop' align='center'>".$r['RSS']."</td>";
         
          if($r['TEMP']=='VPN'){echo "<td bgcolor='$kolorurlop' align='center'>".$r['TEMP']."</td>";}
          else {echo "<td bgcolor='darkgrey' align='center'><font color='white'>".$r['TEMP']."ERNET</font></td>";}
          
          
         echo "<td bgcolor='$kolorurlop'  align='center'><B>".$r['BILL']."</td>";
         echo "<td bgcolor='$kolorurlop'  align='center'><B>".$r['ERR']."</td>";
         echo "<td bgcolor='$kolorurlop'  align='center'><B>".$r['SKAN']."</td>";

        $PRELOADING=$r['PRELOADING'];
        $HDon=$r['HD'];
        $PASSWORD=$r['PASSWORD'];
        $GROUP=$r['GROUP'];
        $STATUS=$r['STATUS'];

        
        $file3="Publish/".$r['NAZWA']."/config.inx";
        $file4="PublishFeedback/".$r['NAZWA']."/config.inx";

        $crc3 = strtoupper(dechex(crc32(file_get_contents($file3))));
        $crc4 = strtoupper(dechex(crc32(file_get_contents($file4))));
        
        $filename = "/folder/" . $dirname . "/";

if (file_exists("Publish/".$r['NAZWA'].""))
{

	if ($crc3==$crc4)
        {
                       echo "<td bgcolor='$kolorurlop' align='center'>
                             <form name=\"config\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"scrollx\" value=\"0\">
                             <input type=\"hidden\" name=\"scrolly\" value=\"0\">
                             <input type=\"hidden\" name=\"idp\" value=\"".$r['ID']."\">
                             <input type=\"hidden\" name=\"akcja\" value=\"config\">
                             <input type=\"submit\" value=\"Config\"
                             onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class=\"button\"> </form></td>";
        }
	else
        { 
                       echo "<td bgcolor='orange' align='center'>
                             <form name=\"config\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"scrollx\" value=\"0\">
                             <input type=\"hidden\" name=\"scrolly\" value=\"0\">
                             <input type=\"hidden\" name=\"idp\" value=\"".$r['ID']."\">
                             <input type=\"hidden\" name=\"akcja\" value=\"config\">
                             <input type=\"submit\" value=\"Config\"
                             onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class=\"button\"> </form></td>";
        }

        rmdir("Publish/".$r['NAZWA']."/Billings");
        rmdir("Publish/".$r['NAZWA']."/Messages");

}
else
{
    echo "<td bgcolor='$kolorred' align='center'>";

    if (!file_exists("Publish/".$r['NAZWA']."")){mkdir("Publish/".$r['NAZWA']."", 0777); echo "<font color='white'>The directory <B>Publish/".$r['NAZWA']."</B> was successfully created.</font>";}
    //if (!file_exists("Publish/".$r['NAZWA']."/Billings")){mkdir("Publish/".$r['NAZWA']."/Billings", 0777); echo "<font color='white'>The directory <B>Publish/".$r['NAZWA']."/Billings</B> was successfully created.</font>";}
    //if (!file_exists("Publish/".$r['NAZWA']."/Messages")){mkdir("Publish/".$r['NAZWA']."/Messages", 0777); echo "<font color='white'>The directory <B>Publish/".$r['NAZWA']."/Messages</B> was successfully created.</font>";}

    echo"</td>";
}

        echo "<td bgcolor='$kolorurlop' align='center'>
                             <form name=\"edit\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
                             <input type=\"hidden\" name=\"scrollx\" value=\"0\">
                             <input type=\"hidden\" name=\"scrolly\" value=\"0\">
                             <input type=\"hidden\" name=\"idp\" value=\"".$r['ID']."\">
                             <input type=\"hidden\" name=\"akcja\" value=\"edit\">
                             <input type=\"submit\" value=\"Edit\"
                             onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class=\"button\"> </form></td>";
}//od warunku if akcja==serialnumber
        echo "<td bgcolor='$kolorurlop' align='center'>
                             <form name=\"edit\" action=\"logi.php\" enctype=\"multipart/form-data\" method=\"get\" target=\"_blank\">
                             <input type=\"hidden\" name=\"NAZWA\" value=\"".$r['NAZWA']."\">
                             <input type=\"hidden\" name=\"data\" value=\"";
                             echo date('Y-m-d'); echo "\">
                             <input type=\"submit\" value=\"Logi\" class=\"button\"> </form></td>";


        $file1dateofmodification='';
        $file2dateofmodificationplayer='';


        $file1="Publish/".$r['NAZWA']."/playlist.ini";
        $file2="PublishFeedback/".$r['NAZWA']."/playlist.ini";

        $crc1 = strtoupper(dechex(crc32(file_get_contents($file1))));
        $crc2 = strtoupper(dechex(crc32(file_get_contents($file2))));

        if (file_exists($file1))
        {$file1dateofmodification=date("d/m/Y H:i:s", filemtime($file1));}
        if (file_exists($file2))
        {$file2dateofmodificationplayer=date("d/m/Y H:i:s", filemtime($file2));}

          if ($crc1==$crc2) {
           echo "<td bgcolor='$kolorurlop' align='center'><B>OK</B></td>";
          } else {
          echo "<td bgcolor='orange' align='center'>out of date<BR><small>S:<font color=white>$file1dateofmodification</font><BR>P:<font color=white>$file2dateofmodificationplayer</font></small></td>";
          }

 /************************************************ Analiza billing **********************************************************/
if($akcja=='info' or $akcja=='errors')
{

 echo "<td bgcolor='$kolorurlop' align='left'>";


 echo"<table width='100%'><tr>";

 $wczoraj  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
 $dataww = date('Y-m-d', $wczoraj);

 $wczorajb  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
 $datawwb = date('Y-m-d', $wczorajb);

/*********************************************************************/

echo"<td width='25%'>";

$nazwaplikubill[$b] = "StatsBillings_".$datawwb.".csv";
$filenamebill = "./PublishFeedback/".$r['NAZWA']."/Billings/".$nazwaplikubill[$b];
if (file_exists($filenamebill))
{
          $file_handle = fopen("".$filenamebill."", "r");

          while (!feof($file_handle))
          {
          $line_of_text = fgets($file_handle);
          $string = $line_of_text;
          $needle  = ';';

          $pos = strlen($string) - stripos($string, $needle)-1;
          $stringtemp=substr($string,-$pos);

          $pos2= stripos($stringtemp, $needle);
          $pos3 = strlen($stringtemp) - stripos($stringtemp, $needle)-1;
          $tag=substr($stringtemp,0,$pos2);
          $number=substr($stringtemp,-$pos3);
          $number=(int)$number;
          if($tag==''){}
          else{echo "<small>".$tag." = <font color=$kolorred>".$number."</font></small><BR><BR>";}
          }
          fclose($file_handle);

}

$file5="./PublishFeedback/".$r['NAZWA']."/Messages/Messages_".$datawwb.".txt";
$file6="./PublishFeedback/".$r['NAZWA']."/Messages/MessagesPlayer_".$datawwb.".txt";

echo"</td>";

/*********************************************************************/

echo"<td width='25%'>";

echo "<small><font color='orange'> ";

$hardware = getLineWithString($file5,'* Procesor');
$hardware2 = getLineWithString($file5,'* Płyta główna');

if($hardware=='')
{
  echo $hardware = getLineWithString($file5,'Intel');
  echo"<BR>";
  echo $hardware2;
}
else
{
  echo $hardware;
  echo"<BR>";
  echo $hardware2;
}

echo "</font></small><BR>";

echo"</td>";

echo"<td width='25%'>";

if (file_exists($file5))
{
  echo "<br><a href='./PublishFeedback/".$r['NAZWA']."/Messages/Messages_".$datawwb.".txt'>Messages_".$datawwb.".txt<br></a>";
  echo"Start: ".$convert[0][0].$convert[0][1].":".$convert[0][2].$convert[0][3]." - ";
  $data = file($file5);
  $line = $data[count($data)-1];
  echo $line[0].$line[1].":".$line[2].$line[3];
}
echo"<br>";


if (file_exists($file6))
{
  echo "<br><a href='./PublishFeedback/".$r['NAZWA']."/Messages/MessagesPlayer_".$datawwb.".txt'>MessagesPlayer_".$datawwb.".txt<br></a>";
  echo "Start: ".$convert6[0][0].$convert6[0][1].":".$convert6[0][2].$convert6[0][3]." - ";
  $data = file($file6);
  $line = $data[count($data)-1];
  echo $line[0].$line[1].":".$line[2].$line[3];
  echo "<br><br>";

if (file_exists($file5)){
$filecontent5= file_get_contents($file5);
$convert = explode("\n", $filecontent5);
if($convert[0][27]=='6' and $convert[0][28]=='0'){echo"<font color='grey'>TvWallClient:".$convert[0][25].$convert[0][26].$convert[0][27].$convert[0][28]."</font>";}
else{ echo"<font color='red'>TvWallClient:".$convert[0][25].$convert[0][26].$convert[0][27].$convert[0][28]."</font>";}

echo "<small><font color='orange'> ";
echo $VERSIONfound = substr(getLineWithString($file5,'App started (2.60)'), -7);
echo "</font></small><BR>";
}

if (file_exists($file6)){
$filecontent6= file_get_contents($file6);
$convert6 = explode("\n", $filecontent6);
if($convert6[0][23]=='6' and $convert6[0][24]=='0'){echo"<font color='grey'>TvWallPlayer:".$convert6[0][21].$convert6[0][22].$convert6[0][23].$convert6[0][24]."</font>";}
else {echo"<font color='red'>TvWallPlayer:".$convert6[0][21].$convert6[0][22].$convert6[0][23].$convert6[0][24]."</font>";}

echo "<small><font color='orange'> ";
echo $VERSIONfound = substr(getLineWithString($file6,'App started (2.60)'), -7);
echo "</font></small><BR>";
}

echo "<small><font color='grey'>";
if (file_exists($file5)){

echo "<BR><BR><font color='grey'>";
$IPfound = getLineWithString($file5,'Local IP Addresses:');
echo substr($IPfound, 32);

if (strpos($IPfound,$r['IP'])== false)
{
    echo "<font size =5>IP ERROR</font>";
}
echo "</font><BR>";

$DNSfound = getLineWithString($file5,'DNS IP Addresses:');
if($DNSfound !=='')
{
  echo "<BR>DNS: ";
  echo substr($DNSfound,29);
}

$FREESPACEfound = getLineWithString($file5,'Free space of local media drive:');
if($FREESPACEfound !=='')
{
  echo"<BR>";
  echo substr($FREESPACEfound,45);
  $dane=explode("/",substr($FREESPACEfound,49));

  $dane0=$dane[0];
  $dane0=str_replace("MB", "", $dane0);
  $dane0=str_replace(" ", "", $dane0);

  $dane1=$dane[1];
  $dane1=str_replace("MB", "", $dane1);
  $dane1=str_replace(" ", "", $dane1);
  
  $freespace=$dane0/$dane1*100;
  
  echo "<BR>Free space: ";
  echo $freespace_round=round($freespace);
  echo "%";
  
  if($freespace<20){echo "<font color='orange'> ALERT FREE SPACE</font>";}

}

echo"<BR>";
echo substr(getLineWithString($file5,'TVWallPlayer:'),12);

echo "</font></small>";
}
  echo"</td>";

  $start = substr_count($filecontent6,'App started');
  $stop = substr_count($filecontent6,'App shutdown');
}

  if(file_exists($file5)==false and file_exists($file6)==false)
  {
      echo"<td width='25%' bgcolor='$kolorred'>";
      echo"<BR><center><font color='white'>NO CONNECTION</font></center><BR>";
      echo"</td>";
  }
  else
  {
      if($start==$stop or $start==($stop+1) or $start==($stop+2))
      {echo"<td width='25%'>";}else {echo"<td width='25%' bgcolor='$kolorred'><font color='white'>";}
      echo"Liczba startów: ";echo $start;echo "<br>";
      echo"Liczba zamknięć: ";echo $stop;echo "<br>";
      $error_file_number=substr_count($filecontent6,"OnLog: [ERROR] (failed to render file) 'FAILED TO PLAY");if($error_file_number>0){echo"<font color='$kolorred'><B>Error file: "; echo $error_file_number; echo "</B></font><br>";}
      //echo"Info codec: ";echo substr_count($filecontent6,"OnLog: [INFO] (failed to bind codec)");echo "<br>";
      echo"</td>";
  }

$zawieszenia_number=substr_count($filecontent6,'PlayingWatcher: playing state OK, position changed FAIL');
if($zawieszenia_number>0){$komunikat=$komunikat."Zawieszenia = ".$zawieszenia_number."<BR>";}

/*********************************************************************/
echo"</tr>
<tr><td colspan='2'></td><td><br><font color='$kolorred'>".$komunikat."</font></td></tr>

</table>";


echo "</td>";
}
/*********************************************** Analiza koniec**********************************************************/
$hardware=trim($hardware);
$hardware2=trim($hardware2);
$dane0=trim($dane0);
$dane1=trim($dane1);

/*********************************************************************/
$content_csv=$content_csv."".$i.";".$r['ID'].";".$r['NAZWA'].";".$r['IP'].";".$r['KEY'].";".$r['SN'].";".$r['STATUS'].";".$hardware.";".$hardware2.";".$freespace_round."%(".$dane0."/".$dane1.")\r\n";  //export csv
/*********************************************************************/



 /************************************************ Analiza SN **********************************************************/
if($akcja=='serialnumber')
{
 echo "<td bgcolor='$kolorurlop' align='left' width='40%'>";


 echo"<table width='100%'><tr>";

 $wczoraj  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
 $dataww = date('Y-m-d', $wczoraj);

 $wczorajb  = mktime(0, 0, 0, date("m")  , date("d"), date("Y"));
 $datawwb = date('Y-m-d', $wczorajb);

/*********************************************************************/

$file5="./PublishFeedback/".$r['NAZWA']."/Messages/Messages_".$datawwb.".txt";
$file6="./PublishFeedback/".$r['NAZWA']."/Messages/MessagesPlayer_".$datawwb.".txt";

/*********************************************************************/
$SN_temp='';

echo"<td width='50%'>";

// z bazy MYSQL
if($r['KEY']==''){$KEY='none_key';}else{$KEY=$r['KEY'];}
echo "Windows Product Key:<BR><B>".$KEY."</B><BR>";
// z logów playera
$WindowsProductKeyfound = getLineWithString($file5,'Windows Product Key found');
if($WindowsProductKeyfound==''){echo "<B><font color='orange'>not found</font></B>";}
else
{
   $WindowsProductKeyfound = substr($WindowsProductKeyfound, -30,29);

    //TEMP

   if($KEY=='none_key')
   {
      echo "UPDATE `tvwall_player` SET `KEY` = '".$WindowsProductKeyfound."' WHERE `ID` = '".$r['ID']."' LIMIT 1";
      $update = @mysql_query("UPDATE `tvwall_player` SET `KEY` = '".$WindowsProductKeyfound."' WHERE `ID` = '".$r['ID']."' LIMIT 1");
      if($update){}
      else echo "Error";
   }

   //TEMP
   echo "<B><font color='orange'>".$WindowsProductKeyfound."</font></B>";

   if($r['KEY']!==$WindowsProductKeyfound)
   {
     echo "<B><font color='orange'> - ERROR KEY</font></B>";
   }
}

//SPRAWDZANIE DUBLI


if($KEY!=='none_key')
{
  if($iTB==0){$TABELA_KLUCZY_BAZA[$iTB]=$KEY;$iTB++;}
  else
  {
      $dubel=false;

      for($i_test=0;$i_test<sizeof($TABELA_KLUCZY_BAZA);$i_test++)
      {
         if($KEY==$TABELA_KLUCZY_BAZA[$i_test])
         {
            echo "<BR><BR>";
            echo "<B>Dubel: pozycja playera: ";
            echo $i_test+1;
            echo "</B><BR><BR>";

            $dubel=true; break 1;}
      }

      $TABELA_KLUCZY_BAZA[$iTB]=$KEY;$iTB++;
   }
}
/*
if($WindowsProductKeyfound!=='')
{
  for($i=0;$i<sizeof($TABELA_KLUCZY_BAZA);$i++)
  {
    if($WindowsProductKeyfound==$TABELA_KLUCZY_BAZA[$i]){echo "dubel_2";}
    else{$TABELA_KLUCZY_BAZA[$iTB]=$WindowsProductKeyfound;$iTB++;}

     echo $TABELA_KLUCZY_BAZA[$i];
  }
}
  */
//SPRAWDZANIE DUBLI



$WindowsProductKeyfound_end = substr($KEY, -5);
//echo "<BR> end -->".$WindowsProductKeyfound_end."<--";

$wynik_SN_find = mysql_query("SELECT * FROM `komputer` WHERE `win` LIKE '%".$KEY."%'")or die('Error - komputer_off - found old SN');
if(mysql_num_rows($wynik_SN_find) > 0){while($rsn_find = mysql_fetch_assoc($wynik_SN_find)){

$SN_temp=$rsn_find['sn'];
echo "<BR> bqs --><B>".$SN_temp."</B><--";

}}


//TEMP
 /*
   //$update2 = @mysql_query("UPDATE `tvwall_player` SET `SN` = '".$SN_temp."' WHERE `ID` = '".$r['ID']."' LIMIT 1");
   $update2 = @mysql_query("UPDATE `tvwall_player` SET `SN` = '' WHERE `ID` = '".$r['ID']."' LIMIT 1");
   if($update2){}
   else echo "Error";
  */
//TEMP


echo"</td>";

echo"<td width='40%'>";

// z bazy MYSQL
if($r['SN']==''){$SN='none_sn';}else{$SN=$r['SN'];}
echo "Serial number: <B>".$SN."</B><BR>";
// z logów playera
$WindowsSerialNumberfound = getLineWithString($file5,'Machine Serial Number found');
if($WindowsSerialNumberfound==''){echo "<B><font color='orange'>no files</font></B> : machine_serial_number.txt";}
else
{
   $WindowsSerialNumberfound = substr($WindowsSerialNumberfound, -30,29);
   //TEMP
   /*
   $update2 = @mysql_query("UPDATE `tvwall_player` SET `SN` = '".$WindowsSerialNumberfound."' WHERE `ID` = '".$r['ID']."' LIMIT 1");
   if($update2){}
   else echo "Error";
   */
   //TEMP
   echo "<B><font color='orange'>".$WindowsSerialNumberfound."</font></B>";
   if($r['SN']!==$WindowsSerialNumberfound)
   {echo "<B><font color='orange'> - ERROR SN</font></B>";}

}

echo"</td>";

if(file_exists($file5)==false and file_exists($file6)==false)
{
      echo"<td width='5%' bgcolor='darkgrey'>";
      echo"<BR><center><font color='white'>>N<</font></center><BR>";
      echo"</td>";
}
else
{
      echo"<td width='5%' bgcolor='$kolorurlop'>";
      echo"</td>";
}


/*********************************************************************/
echo"</tr></table>";


echo "</td>";
}
/*********************************************** Analiza SN koniec **********************************************************/



/********************************************************************************************************** KOMPUTERY 2 SZT *************************************************************************************/
if($akcja=='serialnumber')
{

db_connect_bqs();
/*************************************************/
/*************************************************/

echo "<td bgcolor='$kolorurlop' align='center'>";


$idk_current='';
$sn_current='';
$win_current='';
$lokalizacja_current='';

$wynik_bqs = mysql_query("SELECT * FROM komputer WHERE `win`='$KEY' LIMIT 1")or die('Błąd zapytania');


if(mysql_num_rows($wynik_bqs) > 0)
{

    echo "<table cellpadding=\"2\" border=0 width='100%'>";

    $stan=0;

    while($r_bqs = mysql_fetch_assoc($wynik_bqs))
    {

        if($r_bqs['status']=='zainstalowany')$bgcolor_status='orange';
        elseif($r_bqs['status']=='sprawny')$bgcolor_status='black';
        elseif($r_bqs['status']=='uszkodzony')$bgcolor_status='red';
        else$bgcolor_status=$kolormenu;

        if($stan==0)
        {
            echo "<tr><td width='60%' bgcolor='$kolorurlop'  colspan='5'></td><td width='40%' bgcolor='$bgcolor_status'  colspan='2' align='center'><font color='white'>".$r_bqs['status']."</font></td></tr>";
            echo "<tr>";
            echo "<td bgcolor='$kolormenu'  align='center'>IDK</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Nazwa</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Typ</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>S/N</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Klucz WIN</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Uwagi</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Data dodania</td>";
            echo "</tr>";

            $stan=1;
        }

        echo "<tr>";
        echo "<td bgcolor='$kolorurlop' align='center'>".$r_bqs['idk']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['nazwa']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['typ']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['sn']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['win']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['uwagi']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['datadod']."</td>";
        echo "</tr>";
        
        $idk_current=$r_bqs['idk'];
        $sn_current=$r_bqs['sn'];
        $win_current=$r_bqs['win'];
        $lokalizacja_current=$r_bqs['lokalizacja'];


        $wynikinf = mysql_query("SELECT * FROM komputerinf WHERE idk='".$r_bqs['idk']."' ORDER BY datadod DESC LIMIT 3")or die('Błąd zapytania');
        if(mysql_num_rows($wynikinf) > 0)
        {
          echo "<tr><td bgcolor='$kolorurlop' colspan='8' align='left'><b>HISTORIA</b><BR><BR></td></tr>";

          $lpinf=1;
          while($rinf = mysql_fetch_assoc($wynikinf))
          {
        	 echo "<tr>
                 <td bgcolor='$kolorurlop' align='center'>".$lpinf."</td>
                 <td bgcolor='$kolorurlop' align='center'>".$rinf['typ']."</td>
                 <td bgcolor='$kolorurlop' colspan='3' align='center'>";

            $wynik_idl_bs = mysql_query("SELECT * FROM `LOKALIZACJE` WHERE `IDL` = '".$rinf['lokalizacja']."' LIMIT 1")or die('Błąd zapytania');
            if(mysql_num_rows($wynik_idl_bs) > 0){while($r_idl_bs = mysql_fetch_assoc($wynik_idl_bs)){echo $r_idl_bs['Miasto']." ".$r_idl_bs['Adres']."";}}

           echo"</td>
                 <td bgcolor='$kolorurlop' align='center'>".$rinf['uwaga']."</td>
                 <td bgcolor='$kolorurlop' align='center'>".$rinf['datadod']."</td>
                 </tr>";
		$lpinf=$lpinf+1;
          }
        }

    }

    echo "</table>";
}



/*************************************************/
/*************************************************/
if($KEY=='none_key'){}
else
{
    $wynik_bqs_check = mysql_query("SELECT * FROM komputer WHERE `win`='$KEY' LIMIT 1")or die('Błąd zapytania');

    if(mysql_num_rows($wynik_bqs_check) == 0)
    {

         $zapytanie_dod_skaner="INSERT INTO `komputer` (`idk`, `nazwa`, `sn`, `win`, `lokalizacja`, `status`, `uwagi`, `typ`, `kategoria`, `datadod`, `kto`)
          VALUES (NULL, 'PLAYER', '$SN', '$KEY', '$IDL_BAZA_SERWISOWA', 'zainstalowany', 'Komputer dodany automatycznie ze skanera.', '$GROUP', 'komputer', CURRENT_TIMESTAMP, '0')";

         $Wstawienie_skaner = @mysql_query($zapytanie_dod_skaner);
    }
}
/*************************************************/
/*************************************************/

// DRUGI KOMPUTER

$idk_new='';
$sn_new='';
$win_new='';
$lokalizacja_new='';


$WindowsProductKeyfound = substr($WindowsProductKeyfound, -30,29);

if($WindowsProductKeyfound=='' or $WindowsProductKeyfound==$KEY){}
else
{

$wynik_bqs = mysql_query("SELECT * FROM komputer WHERE `win`='$WindowsProductKeyfound' LIMIT 1")or die('Błąd zapytania');

if(mysql_num_rows($wynik_bqs) > 0)
{

    echo "<table cellpadding=\"2\" border=0 width='100%'>";

    $stan=0;

    while($r_bqs = mysql_fetch_assoc($wynik_bqs))
    {

        if($r_bqs['status']=='zainstalowany')$bgcolor_status='orange';
        elseif($r_bqs['status']=='sprawny')$bgcolor_status='black';
        elseif($r_bqs['status']=='uszkodzony')$bgcolor_status='red';
        else$bgcolor_status=$kolormenu;

        if($stan==0)
        {
            echo "<tr><td width='60%' bgcolor='$kolorurlop'  colspan='5'></td><td width='40%' bgcolor='$bgcolor_status'  colspan='2' align='center'><font color='white'>".$r_bqs['status']."</font></td></tr>";
            echo "<tr>";
            echo "<td bgcolor='$kolormenu'  align='center'>IDK</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Nazwa</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Typ</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>S/N</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Klucz WIN</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Uwagi</td>";
            echo "<td bgcolor='$kolormenu'  align='center'>Data dodania</td>";
            echo "</tr>";

            $stan=1;
        }

        echo "<tr>";
        echo "<td bgcolor='$kolorurlop' align='center'>".$r_bqs['idk']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['nazwa']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['typ']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['sn']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['win']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['uwagi']."</td>";
        echo "<td bgcolor='$kolorurlop'  align='center'>".$r_bqs['datadod']."</td>";
        echo "</tr>";


        $idk_new=$r_bqs['idk'];
        $sn_new=$r_bqs['sn'];
        $win_new=$r_bqs['win'];
        $lokalizacja_new=$r_bqs['lokalizacja'];


        $wynikinf = mysql_query("SELECT * FROM komputerinf WHERE idk='".$r_bqs['idk']."' ORDER BY datadod DESC LIMIT 3")or die('Błąd zapytania');
        if(mysql_num_rows($wynikinf) > 0)
        {
          echo "<tr><td bgcolor='$kolorurlop' colspan='8' align='left'><b>HISTORIA</b><BR><BR></td></tr>";

          $lpinf=1;
          while($rinf = mysql_fetch_assoc($wynikinf))
          {
        	 echo "<tr>
                 <td bgcolor='$kolorurlop' align='center'>".$lpinf."</td>
                 <td bgcolor='$kolorurlop' align='center'>".$rinf['typ']."</td>
                 <td bgcolor='$kolorurlop' colspan='3' align='center'>";

            $wynik_idl_bs = mysql_query("SELECT * FROM `LOKALIZACJE` WHERE `IDL` = '".$rinf['lokalizacja']."' LIMIT 1")or die('Błąd zapytania');
            if(mysql_num_rows($wynik_idl_bs) > 0){while($r_idl_bs = mysql_fetch_assoc($wynik_idl_bs)){echo $r_idl_bs['Miasto']." ".$r_idl_bs['Adres']."";}}

           echo"</td>
                 <td bgcolor='$kolorurlop' align='center'>".$rinf['uwaga']."</td>
                 <td bgcolor='$kolorurlop' align='center'>".$rinf['datadod']."</td>
                 </tr>";
		$lpinf=$lpinf+1;
          }
        }

    }

    echo "</table>";
}

/*************************************************/
/*************************************************/

if($KEY!==$WindowsProductKeyfound and $WindowsProductKeyfound!=='')
{

echo "<table cellpadding=\"2\" border=0 width='100%'>
            <tr align='right'>
            <td width='100%' bgcolor='$kolorurlop' align='right'>
            <form name=\"edit\" action=\"allplayers.php\" enctype=\"multipart/form-data\" method=\"get\">
            <input type=\"hidden\" name=\"akcja\" value=\"serialnumber_update\">
            <input type=\"hidden\" name=\"ID_PLAYERA\" value=\"".$r['ID']."\">
            <input type=\"hidden\" name=\"PLAYER_MIASTO\" value=\"".$r['MIASTO']."\">
            <input type=\"hidden\" name=\"GROUP\" value=\"".$r['GROUP']."\">

            <input type=\"hidden\" name=\"idk_current\" value=\"".$idk_current."\">
            <input type=\"hidden\" name=\"sn_current\" value=\"".$sn_current."\">
            <input type=\"hidden\" name=\"win_current\" value=\"".$win_current."\">
            <input type=\"hidden\" name=\"lokalizacja_current\" value=\"".$lokalizacja_current."\">

            <input type=\"hidden\" name=\"idk_new\" value=\"".$idk_new."\">
            <input type=\"hidden\" name=\"sn_new\" value=\"".$sn_new."\">
            <input type=\"hidden\" name=\"win_new\" value=\"".$win_new."\">
            <input type=\"hidden\" name=\"lokalizacja_new\" value=\"".$lokalizacja_new."\">

            <input type=\"hidden\" name=\"scrollx\" value=\"0\">
            <input type=\"hidden\" name=\"scrolly\" value=\"0\">
            <input type=\"submit\" value=\"update ID : ".$r['ID']."\" onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class=\"button\">
            </form>
            </td>
            </tr>
            </table>";
}


echo"</td>";

/*************************************************/
/*************************************************/

    $wynik_bqs_check = mysql_query("SELECT * FROM komputer WHERE `win`='$WindowsProductKeyfound' LIMIT 1")or die('Błąd zapytania');

    if(mysql_num_rows($wynik_bqs_check) == 0)
    {

         $zapytanie_dod_skaner="INSERT INTO `komputer` (`idk`, `nazwa`, `sn`, `win`, `lokalizacja`, `status`, `uwagi`, `typ`, `kategoria`, `datadod`, `kto`)
          VALUES (NULL, 'PLAYER', '$WindowsSerialNumberfound', '$WindowsProductKeyfound', '0', 'sprawny', 'Komputer dodany automatycznie ze skanera. Nowy zainstalowany.', '$GROUP', 'komputer', CURRENT_TIMESTAMP, '0')";

         $Wstawienie_skaner = @mysql_query($zapytanie_dod_skaner);
    }
/*************************************************/
/*************************************************/
}
}
/********************************************************************************************************** KOMPUTERY 2 SZT *************************************************************************************/


if($r['NOTATKA']=='' or $akcja=='serialnumber'){}else {echo "<td bgcolor='$kolorurlop' align='center'>".$r['NOTATKA']."</td>";}
echo "</tr>";


/*****************************************************************************************************************************/
if(($akcja=='serialnumber') and ($IDL_BAZA_SERWISOWA==''))
{

   echo "<tr><td bgcolor='$kolorurlop' colspan='9' align='left'></td><td bgcolor='orange' align='left'>";

   echo"<form enctype=\"multipart/form-data\" action=\"allplayers.php\" method=\"GET\">
      <input type=\"hidden\" name=\"akcja\" value=\"add_idl_baza_serwisowa\">
      <input type=\"hidden\" name=\"idp\" value=\"$idpb\">";

      echo "->".$IDL_BAZA_SERWISOWA;

      echo"<SELECT NAME=\"location\" TYPE=\"text\" $styl>
    	   <OPTION></OPTION>";

        $wynik_idl_bs = mysql_query("SELECT * FROM `LOKALIZACJE` ORDER BY `Miasto` ASC, `Adres` ASC")or die('Błąd zapytania');
        if(mysql_num_rows($wynik_idl_bs) > 0)
        {  while($r_idl_bs = mysql_fetch_assoc($wynik_idl_bs))
           {echo "<OPTION>".$r_idl_bs['Miasto']." ".$r_idl_bs['Adres'].":".$r_idl_bs['IDL']."</OPTION>";}
        }

        //UPDATE `prnpolska`.`tvwall_player` SET `IDL_BAZA_SERWISOWA` = '90' WHERE `tvwall_player`.`ID` = 140;

     echo"</SELECT>";

      echo"<input type=\"hidden\" name=\"scrollx\" value=\"0\">
      <input type=\"hidden\" name=\"scrolly\" value=\"0\">
      <input type=\"submit\" value=\"Add\" onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class='button'>
      </form>";

   echo"</td></tr>";
}
elseif(($akcja=='serialnumber') and ($IDL_BAZA_SERWISOWA!==''))
{
   echo "<tr><td bgcolor='$kolorurlop' colspan='9' align='left'></td><td bgcolor='$kolorurlop' align='left'>";

   $wynik_idl_bs = mysql_query("SELECT * FROM `LOKALIZACJE` WHERE `IDL` = '$IDL_BAZA_SERWISOWA' LIMIT 1")or die('Błąd zapytania');
   if(mysql_num_rows($wynik_idl_bs) > 0){while($r_idl_bs = mysql_fetch_assoc($wynik_idl_bs)){echo $r_idl_bs['Miasto']." ".$r_idl_bs['Adres'].":".$r_idl_bs['IDL']."/".$lokalizacja_current."/".$lokalizacja_new; $r_idl_bs_IDL=$r_idl_bs['IDL'];}}
   
   if($r_idl_bs_IDL!==$lokalizacja_current){echo "<BR>error_pozycja 1: ".$lokalizacja_current;}

   echo"</td></tr>";

}

/*****************************************************************************************************************************/
}

/************************************************************************************************************************************************************************/
        if(($akcja=='config' or $akcja=='configcreate') and $idp==$idpb)
        {
        	echo "<tr>";
                echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'></td>";
                echo "<td colspan='11' bgcolor='$kolorurlop'  align='left'>";

                echo"
                [Config]<BR>
                URI=".$URI.":".$Port."<BR>
                User=".$User."<BR>
                Pass=".$Pass."<BR>
                Path=".$NAZWAPLAYER."<BR>
                Interval=".$Interval."<BR>
                MinimumAgeOfFile=".$MAOF."<BR>
                <BR>
                MediaURI=".$MediaURI.":".$MediaPort."<BR>
                MediaUser=".$MediaUser."<BR>
                MediaPass=".$MediaPass."<BR>
                MediaPath=/<BR>
                MediaInterval=".$MediaInterval."<BR>
                MediaMinimumAgeOfFile=".$MediaMAOF."<BR>
                <BR>
                BillingURI=".$BillingURI.":".$BillingPort."<BR>
                BillingUser=".$BillingUser."<BR>
                BillingPass=".$BillingPass."<BR>
                BillingInterval=".$BillingInterval."<BR>
                <BR>
                TVWallPlayer=".$TVWallPlayer."<BR>
                LocalMediaPath=".$LocalMediaPath."<BR>
                MediaPreloading=".$PRELOADING."<BR>
                CloseTime=".$CloseTime.":00<BR>
                AdminPassword=".$PASSWORD."<BR>
                Hostname=".$GROUP."<BR>
                <BR>
                ExcludedMediaFileMask1=thumb.db<BR>
                ExcludedMediaFileMask2=*.log<BR>
                ExcludedMediaFileMask3=*.tmp<BR>
                ExcludedMediaFileMask3=*.php<BR>
                ExcludedMediaFileMask4=*.txt<BR>
                <BR>
                [SerialPort1]<BR>
                Baudrate=9600<BR>
                ByteSize=8<BR>
                Parity=None<BR>
                StopBits=One<BR>
                FlowControl=None<BR>
                <BR>
                [VolumeSchedule]<BR>
                Level00:00=".$volume_1."<BR>
                Level08:00=".$volume_2."<BR>
                Level11:00=".$volume_3."<BR>
                Level20:30=".$volume_4."<BR>
                <BR>
                [HardwareInfo]<BR>
                Info1Class=Processor;Procesor<BR>
                Info1Param1=Name;Model<BR>
                Info1Param2=DataWidth;Szyna<BR>
                Info2Class=BaseBoard;Płyta główna<BR>
                Info2Param1=Manufacturer;Producent<BR>
                Info3Class=PhysicalMemory;Pamięć<BR>
                Info3Param1=PartNumber;Numer seryjny<BR>
                <BR>
                [Footer]<BR>
                Check=Ok<BR><BR>";

if($akcja=='config')
{
echo "<form enctype=\"multipart/form-data\" action=\"allplayers.php\" method=\"GET\">
      <input type=\"hidden\" name=\"akcja\" value=\"configcreate\">
      <input type=\"hidden\" name=\"idp\" value=\"$idp\">
      <input type=\"hidden\" name=\"scrollx\" value=\"0\">
      <input type=\"hidden\" name=\"scrolly\" value=\"0\">
      <input type=\"submit\" value=\"Create\" onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class='button'>
      </form>";
}
elseif($akcja=='configcreate')
{
$ini = array(
		'Config' => array(
'URI' => "$URI:$Port",
'User' => "$User",
'Pass' => "$Pass",
'Path' => "$NAZWAPLAYER",
'Interval' => "$Interval",
'MinimumAgeOfFile' => "$MAOF
",
'MediaURI' => "$MediaURI:$MediaPort",
'MediaUser' => "$MediaUser",
'MediaPass' => "$MediaPass",
'MediaPath' => '/',
'MediaInterval' => "$MediaInterval",
'MediaMinimumAgeOfFile' => "$MediaMAOF
",
'BillingURI' => "$BillingURI:$BillingPort",
'BillingUser' => "$BillingUser",
'BillingPass' => "$BillingPass",
'BillingInterval' => "$BillingInterval
",
'TVWallPlayer' => "$TVWallPlayer",
'LocalMediaPath' => "$LocalMediaPath",
'MediaPreloading' => "$PRELOADING",
'CloseTime' => "$CloseTime:00",
'AdminPassword' => "$PASSWORD",
'Hostname' => "$GROUP
",
'ExcludedMediaFileMask1' => 'thumbs.db',
'ExcludedMediaFileMask2' => '*.log',
'ExcludedMediaFileMask3' => '*.tmp',
'ExcludedMediaFileMask3' => '*.php',
'ExcludedMediaFileMask4' => '*.txt
'                        ),
                         'SerialPort1' => array(
'Baudrate' => '9600',
'ByteSize' => '8',
'Parity' => 'None',
'StopBits' => 'One',
'FlowControl' => 'None
'
		),
                         'VolumeSchedule' => array(
'Level00:00' => "$volume_1",
'Level08:00' => "$volume_2",
'Level11:00' => "$volume_3",
'Level20:30' => "$volume_4
"
		),
                         'HardwareInfo' => array(
'Info1Class' => "Processor;Procesor",
'Info1Param1' => "Name;Model",
'Info1Param2' => "DataWidth;Szyna",
'Info2Class' => "BaseBoard;Płyta główna",
'Info2Param1' => "Manufacturer;Producent",
'Info3Class' => "PhysicalMemory;Pamięć",
'Info3Param1' => "PartNumber;Numer seryjny
"
		),
		'Footer' => array(
'Check' => 'Ok' )
);

$keys = array(0x38, 0xD2, 0xFC, 0x74);

if (CreateIniFile($ini, "Publish/$NAZWAPLAYER/config.inx", $keys))
echo "<a href=\"Publish/$NAZWAPLAYER/config.inx\">config.inx </a> was created successfully<BR>";
else
echo "INX file create error<BR>";
/*
if (CreateIniFile($ini, "Publish/$NAZWAPLAYER/config.ini", null))
echo "<a href=\"Publish/$NAZWAPLAYER/config.ini\">config.ini </a> was created successfully<BR>";
else
echo "INX file create error<BR>";
*/
}
echo "</tr>";
}

/********************************************************************************************************************************************************************/
if(($akcja=='edit' or $akcja=='editdone') and $idp==$idpb)
{
  
echo "<form enctype=\"multipart/form-data\" action=\"allplayers.php\" method=\"GET\">
      <input type=\"hidden\" name=\"akcja\" value=\"editdone\">
      <input type=\"hidden\" name=\"idp\" value=\"$idp\">";


        	echo "<tr>";
                echo "<td bgcolor='$kolorurlop'  align='center'><font color='grey'></td>";
                echo "<td colspan='11' bgcolor='$kolorurlop'  align='left'>";


$styl="STYLE=\"color: grey; font-family: Verdana; background-color: $kolorurlop;border: thin solid grey;\"";

echo "<br><table bordercolor='$kolorurlop' cellpadding=\"0\" border=0 width='100%'>";
echo"<tr><td>Nazwa</td>";
echo"<td>$NAZWAPLAYER</td></tr>";
echo"<tr><td>Miasto</td>";
echo"<td><input type=\"text\" $styl name=\"miasto\" value=\"$MIASTOPLAYER\"/></td></tr>";
echo"<tr><td>Adres</td>";
echo"<td><TEXTAREA name=\"adres\" $styl COLS=\"20\" ROWS=\"1\" maxlength=\"20\">$ADRESPLAYER</TEXTAREA></td></tr>";

echo"<tr><td>IP/domena</td>";
echo"<td><input type=\"text\" $styl name=\"ip\" value=\"$IPPLAYER\" size=\"15\"/></td></tr>";
echo"<tr><td>Serwery</td></tr>";
echo"<tr><td><font color=$kolorred>Playlist i konfiguracji</font></td>";
echo"<td >
    <SELECT NAME=\"playlist\" TYPE=\"text\" $styl >
    	<OPTION>".$IDS.".".$NameServer;

    	$wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE TYPE='PLAYLIST'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0){while($r1 = mysql_fetch_assoc($wynik1)) {
        echo "<OPTION>".$r1['IDS'].".".$r1['NAME'];}}

echo"</SELECT></td></tr>";

echo"<tr><td><font color=$kolorred>Media</font></td>";

echo"<td >
    <SELECT NAME=\"media\" TYPE=\"text\" $styl>
    	<OPTION>".$MediaIDS.".".$MediaNameServer;

    	$wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE TYPE='MEDIA'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0){while($r1 = mysql_fetch_assoc($wynik1)) {
        echo "<OPTION>".$r1['IDS'].".".$r1['NAME'];}}

echo"</SELECT></td></tr>";

echo"<tr><td><font color=$kolorred>Billing</font></td>";

echo"<td >
    <SELECT NAME=\"billing\" TYPE=\"text\" $styl>
    	<OPTION>".$BillingIDS.".".$BillingNameServer;

    	$wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE TYPE='BILLING'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0){while($r1 = mysql_fetch_assoc($wynik1)) {
        echo "<OPTION>".$r1['IDS'].".".$r1['NAME'];}}

echo"</SELECT></td></tr>";

echo"<tr><td>MediaPath</td>";
echo"<td><input type=\"text\" $styl name=\"mediapath\" value=\"$LocalMediaPath\"/></td></tr>";
echo"<tr><td>Grupa</td>";
echo"<td >
    <SELECT NAME=\"grupa\" TYPE=\"text\" $styl >
    	<OPTION>$Group
        <OPTION>ALEJA
	<OPTION>TVWALL
	<OPTION>DISPLAY
	<OPTION>POK
    </SELECT></td></tr>";
echo"<tr><td>Shutdown</td>";
echo"<td><input type=\"text\" $styl name=\"shutdown\" value=\"$CloseTime\" size=\"5\"/></td></tr>";
echo"<tr><td>Preloading</td>";
echo"<td >
    <SELECT NAME=\"preloading\" TYPE=\"text\" $styl >
    	<OPTION>$preloading
        <OPTION>1
	<OPTION>0
    </SELECT></td></tr>";
echo"<tr><td>Status</td>";
echo"<td >
    <SELECT NAME=\"STATUS\" TYPE=\"text\" $styl >
    	<OPTION>$STATUS
        <OPTION>ENABLE
	<OPTION>DISABLE
	<OPTION>OFF
    </SELECT></td></tr>";
echo"<tr><td>HD</td>";
echo"<td >
    <SELECT NAME=\"HDon\" TYPE=\"text\" $styl >
    	<OPTION>$HDon
        <OPTION>1
	<OPTION>0
    </SELECT></td></tr>";
    echo"<tr><td>Volume</td>";
echo"<td>
     <SELECT NAME=\"volume_1\" TYPE=\"text\" $styl >
     <OPTION>".$volume_1."</OPTION>";
     for($i=0;$i<101;$i++)
     {echo"<OPTION>".$i."</OPTION>";}
echo"</SELECT>
     <SELECT NAME=\"volume_2\" TYPE=\"text\" $styl >
     <OPTION>".$volume_2."</OPTION>";
     for($i=0;$i<101;$i++)
     {echo"<OPTION>".$i."</OPTION>";}
echo"</SELECT>
     <SELECT NAME=\"volume_3\" TYPE=\"text\" $styl >
     <OPTION>".$volume_3."</OPTION>";
     for($i=0;$i<101;$i++)
     {echo"<OPTION>".$i."</OPTION>";}
echo"</SELECT>
     <SELECT NAME=\"volume_4\" TYPE=\"text\" $styl >
     <OPTION>".$volume_4."</OPTION>";
     for($i=0;$i<101;$i++)
     {echo"<OPTION>".$i."</OPTION>";}
echo"</SELECT> * 0-8 : 8-11 : 11-20:30 : 20:30-0
     </td>";
echo"</tr>";
echo"<tr><td>Notatka</td>";
echo"<td><TEXTAREA name=\"notatka\" $styl COLS=\"100\" ROWS=\"5\" maxlength=\"250\">$notatka</TEXTAREA></td></tr>";
echo"<tr><td><input type=\"hidden\" name=\"scrollx\" value=\"0\">
             <input type=\"hidden\" name=\"scrolly\" value=\"0\">
             <input type=\"submit\" value=\"Edit\" onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class='button'></td></tr></table></form>";

echo "</td></tr>";

if($akcja=='editdone')
{
$miasto=    $_GET['miasto'];
$adres=     $_GET['adres'];
$ip=        $_GET['ip'];
$playlist=  $_GET['playlist'];
$media=     $_GET['media'];
$billing=   $_GET['billing'];
$mediapath= $_GET['mediapath'];
$grupa=     $_GET['grupa'];
$shutdown=  $_GET['shutdown'];
$preloading=$_GET['preloading'];
$HDon      =$_GET['HDon'];
$notatka=   $_GET['notatka'];
$STATUS=  $_GET['STATUS'];

$volume_1=  $_GET['volume_1'];
$volume_2=  $_GET['volume_2'];
$volume_3=  $_GET['volume_3'];
$volume_4=  $_GET['volume_4'];

$volume=$volume_1.":".$volume_2.":".$volume_3.":".$volume_4;

$mediapath=addslashes($mediapath);

$idsplaylist = substr($playlist, 0, strrpos($playlist, '.'));
$idsmedia = substr($media, 0, strrpos($media, '.'));
$idsbilling = substr($billing, 0, strrpos($billing, '.'));

$zapytanie = "UPDATE `tvwall_player` SET
`HD` =  '$HDon',
`MIASTO` =  '$miasto',
`ADRES` =  '$adres',
`IP` =  '$ip',
`MEDIA` =  '$idsmedia',
`PLAYLIST` =  '$idsplaylist',
`BILLING` =  '$idsbilling',
`MEDIAPATH` =  '$mediapath',
`GROUP` =  '$grupa',
`CLOSETIME` =  '$shutdown',
`VOLUME` =  '$volume',
`PASSWORD` =  'z',
`PRELOADING` =  '$preloading',
`STATUS` =  '$STATUS',
`NOTATKA` =  '$notatka' WHERE `ID` ='$idp';";
$update = @mysql_query($zapytanie);
if($update){}
else echo "<BR><h2>Error edit player</h2>";
echo "<script>setTimeout('document.location = \"https://prnpolska.nazwa.pl/polsat/allplayers.php\"', 1);</script> ";
}
}
/************************************************************************************************************************************************************************/
}
echo "</table>";
}

echo "<BR><BR><form enctype=\"multipart/form-data\" action=\"allplayers.php\" method=\"GET\">
      <input type=\"hidden\" name=\"akcja\" value=\"configall\">
      <input type=\"hidden\" name=\"scrollx\" value=\"0\">
      <input type=\"hidden\" name=\"scrolly\" value=\"0\">
      <input type=\"submit\" value=\"Create config files\" onclick=\"javascript:saveScrollCoordinates(this.form); this.form.submit();\" class='button'>
      </form>";
/************************************************************************************************************************************************************************/
if($akcja=='configall')
{

echo "<br><h1>Pliki konfiguracyjne</h1>";
echo "<BR><table cellpadding=\"2\" border=0 width=\"100%\">";
$j=0;
$wynikc = mysql_query("SELECT * FROM tvwall_player WHERE '1'ORDER BY Miasto")or die('Błąd zapytania');
if(mysql_num_rows($wynikc) > 0)
{while($rc = mysql_fetch_assoc($wynikc)) {

        $j++;
        $NAZWAPLAYER=$rc['NAZWA'];
        $MIASTOPLAYER=$rc['MIASTO'];
        $ADRESPLAYER=$rc['ADRES'];
        $IPPLAYER=$rc['IP'];
        $idpb=$rc['ID'];
        $media=$rc['MEDIA'];
        $playlist=$rc['PLAYLIST'];
        $billing=$rc['BILLING'];

        $wynik2 = mysql_query("SELECT * FROM tvwall_server WHERE IDS='$playlist'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik2) > 0)
        {
         while($r2 = mysql_fetch_assoc($wynik2)) {

        $IDS = $r2['IDS'];
        $NameServer = $r2['NAME'];
        $URI = $r2['IP'];
        $Port = $r2['PORT'];
        $User = $r2['LOGIN'];
        $Pass = $r2['PASSWORD'];
        $Interval= $r2['INTERVAL'];
        $MAOF = $r2['MAOF'];

        }}

        $wynik1 = mysql_query("SELECT * FROM tvwall_server WHERE IDS='$media'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik1) > 0)
        {
         while($r1 = mysql_fetch_assoc($wynik1)) {

        $MediaIDS = $r1['IDS'];
        $MediaNameServer = $r1['NAME'];
        $MediaURI = $r1['IP'];
        $MediaPort = $r1['PORT'];
        $MediaUser = $r1['LOGIN'];
        $MediaPass = $r1['PASSWORD'];
        $MediaInterval= $r1['INTERVAL'];
        $MediaMAOF = $r1['MAOF'];

        }}

        $wynik3 = mysql_query("SELECT * FROM tvwall_server WHERE IDS='$billing'")or die('Błąd zapytania');
        if(mysql_num_rows($wynik3) > 0)
        {
         while($r3 = mysql_fetch_assoc($wynik3)) {

        $BillingIDS = $r3['IDS'];
        $BillingNameServer = $r3['NAME'];
        $BillingURI = $r3['IP'];
        $BillingPort = $r3['PORT'];
        $BillingUser = $r3['LOGIN'];
        $BillingPass = $r3['PASSWORD'];
        $BillingInterval= $r3['INTERVAL'];

        }}


        $LocalMediaPath=$rc['MEDIAPATH'];
        $Group=$rc['GROUP'];
        $CloseTime=$rc['CLOSETIME'];
        $PRELOADING=$rc['PRELOADING'];
        $PASSWORD=$rc['PASSWORD'];
        $GROUP=$rc['GROUP'];
        $TVWallPlayer=$rc['TVWallPlayer'];

        $volume=$rc['VOLUME'];
        $volume_array=explode(':',$volume);

        $volume_1=$volume_array[0];
        $volume_2=$volume_array[1];
        $volume_3=$volume_array[2];
        $volume_4=$volume_array[3];




$ini = array(
		'Config' => array(
'URI' => "$URI:$Port",
'User' => "$User",
'Pass' => "$Pass",
'Path' => "$NAZWAPLAYER",
'Interval' => "$Interval",
'MinimumAgeOfFile' => "$MAOF
",
'MediaURI' => "$MediaURI:$MediaPort",
'MediaUser' => "$MediaUser",
'MediaPass' => "$MediaPass",
'MediaPath' => '/',
'MediaInterval' => "$MediaInterval",
'MediaMinimumAgeOfFile' => "$MediaMAOF
",
'BillingURI' => "$BillingURI:$BillingPort",
'BillingUser' => "$BillingUser",
'BillingPass' => "$BillingPass",
'BillingInterval' => "$BillingInterval
",
'TVWallPlayer' => "$TVWallPlayer",
'LocalMediaPath' => "$LocalMediaPath",
'MediaPreloading' => "$PRELOADING",
'CloseTime' => "$CloseTime:00",
'AdminPassword' => "$PASSWORD",
'Hostname' => "$GROUP
",
'ExcludedMediaFileMask1' => 'thumbs.db',
'ExcludedMediaFileMask2' => '*.log',
'ExcludedMediaFileMask3' => '*.tmp',
'ExcludedMediaFileMask3' => '*.php',
'ExcludedMediaFileMask4' => '*.txt
'                        ),
                         'VolumeSchedule' => array(
'Level00:00' => "$volume_1",
'Level08:00' => "$volume_2",
'Level11:00' => "$volume_3",
'Level20:30' => "$volume_4
"
		),
		         'HardwareInfo' => array(
'Info1Class' => "Processor;Procesor",
'Info1Param1' => "Name;Model",
'Info1Param2' => "DataWidth;Szyna",
'Info2Class' => "BaseBoard;Płyta główna",
'Info2Param1' => "Manufacturer;Producent",
'Info3Class' => "PhysicalMemory;Pamięć",
'Info3Param1' => "PartNumber;Numer seryjny
"
		),
		'Footer' => array(
'Check' => 'Ok' )
);

$keys = array(0x38, 0xD2, 0xFC, 0x74);

if (CreateIniFile($ini, "Publish/$NAZWAPLAYER/config.inx", $keys))
{
echo "<tr>";
echo "<td bgcolor='$kolormenu' align='center'><font color='white'>".$j."</font></td>";
echo "<td bgcolor='$kolormenu' align='left'><a href=\"Publish/$NAZWAPLAYER/config.inx\">Publish/<font color='$kolorred'>".$NAZWAPLAYER."</font>/config.inx </a> was created successfully<BR></td>";
echo "</tr>";
}
else
{echo "<td bgcolor='$kolormenu' align='left'><font color='darkred'>INX file create error</font></td>";}

}}
echo "</table>";

}
/************************************************************************************************************************************************************************/

/************************************ CSV *******************************************************/
fwrite($fd, $content_csv); //export csv
fclose($fd); //export csv

echo"<table width='100%'><tr><td width='99%'></td><td width='1%'><a href='csv/komputery.csv'>komputery.csv</a></td></tr></table>";        //export csv
/************************************ CSV *******************************************************/

echo "</body>";

mysql_close($connection);

}
?>
</div>

<div id="footer" style="margin-top:50px; width:500px">
&copy; 2008 <a href="#"><strong>MANAGER TV WALL</strong></a>
<br/>  Programmed And Designed by <a href="">PIOTR GRABOWSKI</a>
</div>


</div>

</div>


</body>
</html>
