<?php

/**
 * @copyright 2014-2016 Sentora Project (http://www.sentora.org/) 
 * Sentora is a GPL fork of the ZPanel Project whose original header follows:
 *
 * ZPanel - A Cross-Platform Open-Source Web Hosting Control panel.
 * 
 * @package ZPanel
 * @version $Id$
 * @author Bobby Allen - ballen@zpanelcp.com
 * @copyright (c) 2008-2011 ZPanel Group - http://www.zpanelcp.com/
 * @license http://opensource.org/licenses/gpl-3.0.html GNU Public License v3
 *
 * This program (ZPanel) is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 * Converted to Sentora by TGates http://www.sentora.org
 *
 */
 
/* 
error_reporting(E_ALL);
ini_set("display_errors", 1); 
*/

class module_controller {
	/* Customs requests */
    static function getCurrentServerIP() {
		chmod("/etc/sentora/panel/modules/shoutcast_admin/srv/",0777);
		chmod("/etc/sentora/panel/modules/shoutcast_admin/sc_serv",0777);
		$externalContent = file_get_contents('http://checkip.dyndns.com/');
		preg_match('/Current IP Address: \[?([:.0-9a-fA-F]+)\]?/', $externalContent, $m);
		$externalIp = $m[1];
		return $externalIp;
		//return $_SERVER['SERVER_ADDR'];
    }
	static function getCurrentServersList() {
		$root = scandir("/etc/sentora/panel/modules/shoutcast_admin/srv/"); 
		$return=array();
		$x=0;
	    foreach($root as $value) { 
	        if($value === '.' || $value === '..') {
				continue;
			} 
			else{
				$tm=explode(".",$value);
				$tmp=explode("_",$tm[0]);
				if($tm[1]=="conf"){
					$return[$x]['username']=$tmp[0];
					$return[$x]['port']=$tmp[1];
					$x++;
				}
				continue;
			} 
    	} 
		return $return;
	}
	static function getThisUsername() {
		global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		return $urlvars['username'];
	}	
	static function getThisPort() {
		global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		return $urlvars['port'];
	}	
	static function getThisMaxusers() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		$username=$urlvars['username'];
		$port=$urlvars['port'];
		$root = "/etc/sentora/panel/modules/shoutcast_admin/srv/"; 
		include($root.$username."_".$port.".php");
		return $maxusers;
	}
	static function getThisDumpuser() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		$username=$urlvars['username'];
		$port=$urlvars['port'];
		$root = "/etc/sentora/panel/modules/shoutcast_admin/srv/"; 
		include($root.$username."_".$port.".php");
		return $dump_user;
	}
	static function getThisDumpsource() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		$username=$urlvars['username'];
		$port=$urlvars['port'];
		$root = "/etc/sentora/panel/modules/shoutcast_admin/srv/"; 
		include($root.$username."_".$port.".php");
		return $dump_source;
	}
	static function getThisRelay() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		$username=$urlvars['username'];
		$port=$urlvars['port'];
		$root = "/etc/sentora/panel/modules/shoutcast_admin/srv/"; 
		include($root.$username."_".$port.".php");
		return (($relay=="yes") ? true : false);
	}
	static function getThisRelaypublic() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		$username=$urlvars['username'];
		$port=$urlvars['port'];
		$root = "/etc/sentora/panel/modules/shoutcast_admin/srv/"; 
		include($root.$username."_".$port.".php");
		return (($relay_public=="yes") ? true : false);
	}			
	static function getThisDJpass() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		$username=$urlvars['username'];
		$port=$urlvars['port'];
		$root = "/etc/sentora/panel/modules/shoutcast_admin/srv/"; 
		include($root.$username."_".$port.".php");
		return $pass_dj;
	}
	static function getThisAdminpass() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
		$username=$urlvars['username'];
		$port=$urlvars['port'];
		$root = "/etc/sentora/panel/modules/shoutcast_admin/srv/"; 
		include($root.$username."_".$port.".php");
		return $pass_admin;
	}			
	/* Defaults triggers */	
	static function getModuleName() {
		$module_name = ui_module::GetModuleName();
        return $module_name;
    }
	static function getModuleIcon() {
		global $controller;
		$module_icon = "modules/" . $controller->GetControllerRequest('URL', 'module') . "/assets/icon.png";
        return $module_icon;
    }
	static function getModuleDesc() {
		$message = ui_language::translate(ui_module::GetModuleDescription());
        return $message;
    }
    static function getResult() {
		global $controller;
        $formvars = $controller->GetAllControllerRequests('FORM');	
        if ($formvars['saved']=="true") {
            return ui_sysmessage::shout(ui_language::translate("Server added!"));
        }
		elseif(!empty($formvars['saved'])) {
		    return ui_sysmessage::shout(ui_language::translate($formvars['saved']));
        }
        return;
    }	
    static function getisAddServer() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
        if ((isset($urlvars['show'])) && ($urlvars['show'] == "Add"))
            return true;
        return false;
    }	
    static function getisEditServer() {
        global $controller;
        $urlvars = $controller->GetAllControllerRequests('URL');
        if ((isset($urlvars['show'])) && ($urlvars['show'] == "Edit"))
            return true;
        return false;
    }		
	/* Tasks triggers */
	
	static function doAddServer(){
        global $controller;
        header("location: ./?module=" . $controller->GetCurrentModule() . "&show=Add");
        exit;
        return;	
	}
	static function doEditServer(){
        global $controller;
        header("location: ./?module=" . $controller->GetCurrentModule() . "&show=Edit");
        exit;
        return;	
	}	
	static function doTaskServer(){
		global $controller;
        $formvars = $controller->GetAllControllerRequests('FORM');
		switch($formvars['InTask']) {
			case 'kickServer':
				$return = self::taskKickServer($formvars['port'],$formvars['username']);
				break;
			case 'startServer':
				$return = self::taskStartServer($formvars['port'],$formvars['username']);
				break;
			case 'stopServer':
				$return = self::taskStopServer($formvars['port'],$formvars['username']);
				break;
			case 'deleteServer':
				$return = self::taskDeleteServer($formvars['port'],$formvars['username']);
				break;
			case 'editServer':
        		header("location: ./?module=" . $controller->GetCurrentModule() . "&show=Edit&username=".$formvars['username']."&port=".$formvars['port']);
		        exit;
				break;				
			default:
				$return = "Inavalid command";
				break;
		}	
		header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=".$return);
		exit;
	}
	static function doSaveServer(){
		global $controller;
        $formvars = $controller->GetAllControllerRequests('FORM');
		if(empty($formvars['username'])){ 
			header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=Missing username");
		}
		elseif(empty($formvars['pass_admin'])){ 
			header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=Missing admin password");
		}
		elseif(empty($formvars['pass_dj'])){ 
			header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=Missing DJ password");		
		}
		elseif(!is_numeric($formvars['port'])){ 
			header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=Missing server port");		
		}
		elseif(!is_numeric($formvars['maxusers'])){ 
			header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=Missing Maximum users limit");		
		}
		elseif(file_exists("/etc/sentora/panel/modules/shoutcast_admin/servers/".$formvars['username']."_".$formvars['port'].".conf")){ 
			header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=Server exists");		
		}
		else{
			if(file_exists("/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".conf")){
				self::deletefile("/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".conf");
				self::deletefile("/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".php");		
			}
			$fp = fopen("/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".conf", 'w+');
$input=";
; Auto-generated by SHOUTcast manager
;
AdminPassword=".$formvars['pass_admin']."
MaxUser=".$formvars['maxusers']."
Password=".$formvars['pass_dj']."
PortBase=".$formvars['port']."
RealTime=1
ScreenLog=0
ShowLastSongs=10
W3CEnable=yes
SrcIP=ANY
DestIP=ANY
Yport=80
NameLookups=0
AutoDumpUsers=".$formvars['dump_user']."
AutoDumpSourceTime=".$formvars['dump_source']."
PublicServer=default
AllowRelay=".$formvars['relay']."
AllowPublicRelay=".$formvars['relay_public']."
BanFile=/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".ban
LogFile=/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".log
W3CLog=/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".w3c.log
MetaInterval=32768";		
			fwrite($fp, $input);
			fclose($fp);			
			$fp = fopen("/etc/sentora/panel/modules/shoutcast_admin/srv/".$formvars['username']."_".$formvars['port'].".php", 'w+');
$input="<?php
\$maxusers='".$formvars['maxusers']."';
\$pass_admin='".$formvars['pass_admin']."';
\$pass_dj='".$formvars['pass_dj']."';
\$dump_user='".$formvars['dump_user']."';
\$dump_source='".$formvars['dump_source']."';
\$relay='".$formvars['relay']."';
\$relay_public='".$formvars['relay_public']."';
?>";		
			fwrite($fp, $input);
			fclose($fp);		
	        header("location: ./?module=" . $controller->GetCurrentModule() . "&saved=true");
		}		
        exit;		
	}	
	/* Tasks */
	static function taskDeleteServer($port,$user){
		if ($pid = trim(self::findPID($port,$user))) {
		   	shell_exec("kill -9 ".$pid);
		}
		self::deletefile("/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".pid");
		self::deletefile("/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".log");
		self::deletefile("/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".conf");
		self::deletefile("/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".php");
		return true;
	}	
	static function taskStartServer($port,$user){
		$sc_conf = "/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".conf";
		if(file_exists($sc_conf)) {
    		$cmdstr = "nohup /etc/sentora/panel/modules/shoutcast_admin/sc_serv ".$sc_conf;
		    $cmdstr .= " > /dev/null & echo $!";
    		$pid = shell_exec($cmdstr);
	    	$cleanpid = trim($pid);
	    	$fd = fopen("/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".pid", "w+");
		    fputs($fd, $cleanpid);
    		fclose($fd);
			return true;
		}	
		else{
			return false;
		}
	}
	static function taskStopServer($port,$user){
		if ($pid = trim(self::findPID($port,$user))) {
    		shell_exec("kill -9 ".$pid);
	  	}
	  	return self::deletefile("/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".pid");	
	}
	static function taskKickServer($port,$user){
		$timeout = 5;
		$ip="127.0.0.1";
		@include("/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".php");	
		$fp = @fsockopen($ip, $port, $errno, $errstr, $timeout); 
		if($fp) {   
			fputs($fp,"GET /admin.cgi?pass=".$pass_admin."&mode=kicksrc  HTTP/1.0\r\nUser-Agent: XML Getter (Mozilla Compatible)\r\n\r\n"); 
			return true;
		}
		else{
			return false;
		}	
	}
	/* functions */
	function findPID($port,$user) {
		$filename = "/etc/sentora/panel/modules/shoutcast_admin/srv/".$user."_".$port.".pid";
		if (file_exists($filename)) {
	    	$handle = fopen($filename, "r");
    		$contents = fread($handle, filesize($filename));
		    fclose($handle);
    		return trim($contents);
	  	} 
		else { 
    		return false;
  		}
	}
	function findRadio($port) {
		$fp = @fsockopen ("127.0.0.1", $port, $errno, $errstr, 30);
		if(!$fp){ $sc_state="offline"; } 
		else {
			$sc_state="online";
			fputs ($fp, "GET / / HTTP/1.0\r\nUser-Agent: Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)\r\nHost: 212.43.196.210:8000\r\n\r\n");
				while (!feof($fp)) {
				$line = fgets ($fp,21048);
			  	eregi( "<font class=default>Stream Status: </font></td><td><font class=default><b>Stream is up at (.*) with", $line, $regs );
			  	$sc_stream = $regs[1];
			  	$sc_stream=str_replace("<B>","",$sc_stream);
			  	eregi( "<font class=default>Stream Title: </font></td><td><font class=default><b>(.*)</b></td></tr><tr><td width=100 nowrap><font class=default>Content Type: </font></td>", $line, $regs );
			  	$sc_title = $regs[1];
			  	eregi( "<font class=default>Current Song: </font></td><td><font class=default><b>(.*)</b></td></tr></table>", $line, $regs );
			  	$sc_song = $regs[1];
			  	eregi( "kbps with(.*)listeners", $sc_stream, $regs );
			  	$sc_users = $regs[1];
			  	$sc_users=str_replace(" of ","/",$sc_users);
		 	}
		 	fclose($fp);
		}	
		$GLOBALS["sc_stream"]=$sc_stream;
		$GLOBALS["sc_title"]=$sc_title;
		$GLOBALS["sc_song"]=$sc_song;
		$GLOBALS["sc_users"]=$sc_users;
		$GLOBALS["sc_state"]=$sc_state;		
	}
	function deleteFile($path) {
		if (file_exists($path)) {
    		unlink($path);
    		return true;
  		} 
		else {
    		return false;
  		}
	}	
}

?>