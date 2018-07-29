<?php 
namespace App\Modules;

class smsSenderFactory
{


public function sendRequest($site_name,$send_xml,$header_type) {

    	//die('SITENAME:'.$site_name.'SEND XML:'.$send_xml.'HEADER TYPE '.var_export($header_type,true));
    	$ch = curl_init();
    	curl_setopt($ch, CURLOPT_URL,$site_name);
    	curl_setopt($ch, CURLOPT_POST, 1);
    	curl_setopt($ch, CURLOPT_POSTFIELDS,$send_xml);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,2);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,0);
    	curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
    	curl_setopt($ch, CURLOPT_HTTPHEADER,$header_type);
    	curl_setopt($ch, CURLOPT_HEADER, 0);
    	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    	curl_setopt($ch, CURLOPT_TIMEOUT, 120);

    	$result = curl_exec($ch);

    	return $result;
	}



	public function sendSMS($message,$numbers){
		$username   = 'your-username';
		$password   = 'your-pw';
		$orgin_name = 'orgin_name';
		$tonumbers = '';
		foreach ($numbers as $number) {
			$tonumbers .= '<number>'.$number.'</number>';
		}
		$xml = <<<EOS
   		 <request>
   			 <authentication>
   				 <username>{$username}</username>
   				 <password>{$password}</password>
   			 </authentication>

   			 <order>
   	    		 <sender>{$orgin_name}</sender>
   	    		 <message>
   	        		 <text>{$message}</text>
   	        		 <receipents>
   	            		 {$tonumbers}
   	        		 </receipents>
   	    		 </message>
   			 </order>
   		 </request>
EOS;
$result = $this->sendRequest('http://api.maxeticaret.com/v1/send-sms',$xml,array('Content-Type: text/xml'));
$p = xml_parser_create();
xml_parse_into_struct($p, $result, $vals, $index);
xml_parser_free($p);

$code = $vals[2]['value'];
return $code;
	}




}