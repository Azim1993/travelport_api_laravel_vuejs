<?php

namespace App\Http\Controllers;

use \Exception;
use DOMDocument;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function flightSearch(Request $request)
    {
        $message = $this->xmlMessage($request->all());
        $file = "001-" . config('travelport.provider') . "_AirAvailabilityReq.xml"; // file name to save the request xml for test only(if you want to save the request/response)
        $this->prettyPrint($message, $file);//call function to pretty print xml

        $auth = base64_encode(config('travelport.auth.credentials'));
        $soap_do = curl_init(config('travelport.url')."/AirService");
        $header = array(
            "Content-Type: text/xml;charset=UTF-8",
            "Accept: gzip,deflate",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "SOAPAction: \"\"",
            "Authorization: Basic $auth",
            "Content-length: " . strlen($message),
        ); 
        //curl_setopt($soap_do, CURLOPT_CONNECTTIMEOUT, 30); 
        //curl_setopt($soap_do, CURLOPT_TIMEOUT, 30); 
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($soap_do, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($soap_do, CURLOPT_POST, true);
        curl_setopt($soap_do, CURLOPT_POSTFIELDS, $message);
        curl_setopt($soap_do, CURLOPT_HTTPHEADER, $header);
        curl_setopt($soap_do, CURLOPT_RETURNTRANSFER, true); // this will prevent the curl_exec to return result and will let us to capture output
        $return = curl_exec($soap_do);

        $file = "001-" . config('travelport.provider') . "_AirAvailabilityRsp.xml"; // file name to save the response xml for test only(if you want to save the request/response)
        $content = $this->prettyPrint($return, $file);
        return $this->parseXMLOutput($content);
    }

    public function xmlMessage($info) {
        return '<soapenv:Envelope xmlns:soapenv="http://schemas.xmlsoap.org/soap/envelope/">'.
            '<soapenv:Header/>'.
            '<soapenv:Body>'.
                '<air:AvailabilitySearchReq TraceId="trace" AuthorizedBy="user" TargetBranch="'.config('travelport.branch').'" xmlns:air="http://www.travelport.com/schema/air_v29_0" xmlns:com="http://www.travelport.com/schema/common_v29_0">'.
                    '<com:BillingPointOfSaleInfo OriginApplication="UAPI"/>'.
                    '<air:SearchAirLeg>'.
                        '<air:SearchOrigin>'.
                        '<com:Airport Code="DAC"/>'. // $info[' origin '][' code ']
                        '</air:SearchOrigin>'.
                        '<air:SearchDestination>'.
                        '<com:Airport Code="SIN"/>'. // $info['destination']['code']
                        '</air:SearchDestination>'.
                        '<air:SearchDepTime PreferredTime="'. $info['start'].'">'.
                        '</air:SearchDepTime>'.
                    '</air:SearchAirLeg>'.
                    '<air:AirSearchModifiers>'.
                        '<air:PreferredProviders>'.
                        '<com:Provider Code="'. config('travelport.provider') .'"/>'.
                        '</air:PreferredProviders>'.
                    '</air:AirSearchModifiers>'.
                '</air:AvailabilitySearchReq>'.
            '</soapenv:Body>'.
            '</soapenv:Envelope>';
    }
    
    protected function parseXMLOutput($xml_response)
    {
        //parse the Search response to get values to use in detail request

        $response = array();

        $AirAvailabilitySearchRsp = $xml_response;

        $xml = simplexml_load_String($AirAvailabilitySearchRsp, null, null, 'SOAP', true);

        if (!$xml) {
            throw new \Exception("Invalid XML response");
        }


        $Results = $xml->children('SOAP', true);

        foreach ($Results->children('SOAP', true) as $fault) {

            if (strcmp($fault->getName(), 'Fault') == 0) {
                throw new \Exception("Error occurred in request/response processing!");
            }
        }

        $count = 0;
        foreach ($Results->children('air', true) as $nodes) {
            foreach ($nodes->children('air', true) as $hsr) {
                if (strcmp($hsr->getName(), 'AirSegmentList') == 0) {
                    foreach ($hsr->children('air', true) as $hp) {
                        if (strcmp($hp->getName(), 'AirSegment') == 0) {
                            $count = $count + 1;
							// file_put_contents($fileName,"\r\n"."Air Segment ".$count."\r\n"."\r\n", FILE_APPEND);
                            foreach ($hp->attributes() as $a => $b) {

                                $val = (array)$b;

                                $response[$count][$a] = $val[0];
									//echo "$a"." : "."$b";
									// file_put_contents($fileName,$a." : ".$b."\r\n", FILE_APPEND);
                            }
                        }
                    }
                }
				//break;
            }
        }
		//$message defined in the 'required' file
        return $response;

    }

    public function prettyPrint($result, $file)
    {
        $dom = new DOMDocument;
        $dom->preserveWhiteSpace = false;
        $dom->loadXML($result);
        $dom->formatOutput = true;		
	    //call function to write request/response in file	
        $this->outputWriter($file, $dom->saveXML());
        return $dom->saveXML();
    }
    public function outputWriter($file, $content)
    {
        file_put_contents($file, $content); // Write request/response and save them in the File
    }
}
