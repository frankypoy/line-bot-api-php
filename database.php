<?php
	date_default_timezone_set("Asia/Bangkok");
	ini_set('error_reporting', E_ALL & ~E_NOTICE);
	error_reporting(E_ALL & ~E_NOTICE);

class Database 
{

	public static $dbName = 'MMM_Residence' ; 
	public static $dbHost = 'webbase3.egat.co.th' ;
	public static $dbUsername = 'cmusd';
	public static $dbUserPassword = 'cmusd2017*';

/*	public static $dbName = 'mmm_golf_survey' ; 
	public static $dbHost = 'localhost' ;
	public static $dbUsername = 'root';
	public static $dbUserPassword = 'php88';*/
	
	private static $cont  = null;
	
	public function __construct() {
		exit('Init function is not allowed');
	}
	
	public static function connect()
	{
	   // One connection through whole application
       if ( null == self::$cont )
       {      
        try 
        {
          self::$cont =  new PDO( "mysql:host=".self::$dbHost.";"."dbname=".self::$dbName, self::$dbUsername, self::$dbUserPassword);  
        }
        catch(PDOException $e) 
        {
          die($e->getMessage());  
        }
       } 
       return self::$cont;
	}
	
	public static function disconnect()
	{
		self::$cont = null;
	}
}


	$M_THAI_LONG = array("","มกราคม","กุมภาพันธ์","มีนาคม","เมษายน","พฤษภาคม","มิถุนายน","กรกฎาคม","สิงหาคม","กันยายน","ตุลาคม","พฤศจิกายน","ธันวาคม");
	$M_THAI_SHORT = array("","ม.ค.","ก.พ.","มี.ค.","เม.ย.","พ.ค.","มิ.ย.","ก.ค.","ส.ค.","ก.ย.","ต.ค.","พ.ย.","ธ.ค.");	
	
	function show_long_date($tmp) {
		if($tmp!="0000-00-00" && $tmp!=""  && $tmp!="0000-00-00 00:00:00") {
			global $M_THAI_LONG;
			list($date,$time) = explode(" ",$tmp);
			list($y,$m,$d) = explode("-",$date);
			$y += 543;
			return intval($d)." ".$M_THAI_LONG[intval($m)]." ".$y;
		}
	}

	function show_short_date_org($tmp) {
		if($tmp!="0000-00-00" && $tmp!=""  && $tmp!="0000-00-00 00:00:00") {
			global $M_THAI_SHORT;
			list($date,$time) = explode(" ",$tmp);
			list($y,$m,$d) = explode("-",$date);
			$y += 543;
			return intval($d)."-".$M_THAI_SHORT[intval($m)]."-".($y-2500);
		}
	}

	function show_short_date($tmp) {
		if($tmp!="0000-00-00" && $tmp!=""  && $tmp!="0000-00-00 00:00:00") {
			global $M_THAI_SHORT;
			list($date,$time) = explode(" ",$tmp);
			list($y,$m,$d) = explode("-",$date);
			$y += 543;
			return intval($d)." ".$M_THAI_SHORT[intval($m)]." ".($y);
		}
	}


	function alert($msg,$link) {
		print "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"> \n
					<script>  \n
					<!-- Begin  \n
						alert('".$msg."');  \n";

		if($link=="next") {
			print "			// End -->  \n
					</script>  \n";

		} else if($link=="") { 
			print "	history.back();  \n
						// End -->  \n
					</script>  \n";
			exit();	

		} else { 
			print "	window.location.href='".$link."' \n
					// End -->  \n
					</script>  \n";
			exit();	
		}
	}	


?>