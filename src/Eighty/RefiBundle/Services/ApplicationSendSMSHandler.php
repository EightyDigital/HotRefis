<?php

namespace Eighty\RefiBundle\Services;

class ApplicationSendSMSHandler
{
	public function sendSMS($report_hashed_url, $transactionId, $amicus_person_id)
	{
		sleep(3);
		
		// generate tiny url from google
		$headers = array(
			"Content-type: application/json",
		);
		
		$c = curl_init();
		curl_setopt($c, CURLOPT_URL, "https://www.googleapis.com/urlshortener/v1/url");
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($c, CURLOPT_TIMEOUT, 10);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, '{"longUrl": "'.$report_hashed_url.'"}');
		curl_setopt($c, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($c);
		curl_close($c);

		$report_tiny_url = json_decode($response);

		// send SMS request
		$soapUrl = "http://sms.dncfilter.com/SMS.asmx";
        $xml_post_string = '<?xml version="1.0" encoding="utf-8"?>
							<soap:Envelope xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema" xmlns:soap="http://schemas.xmlsoap.org/soap/envelope/">
							  <soap:Body>
								<sendSMS xmlns="http://tempuri.org/">
								  <username>hotrefi</username>
								  <password>348DjR09!Wkk9s</password>
								  <smsTitle>Free Report</smsTitle>
								  <Message>See how: '.$report_tiny_url->id.'</Message>
								  <unsubNumber>82015620</unsubNumber>
								  <amicusID>'.$amicus_person_id.'</amicusID>
								  <TransactionID>'.$transactionId.'</TransactionID>
								  <senderNumber>82015620</senderNumber>
								</sendSMS>
							  </soap:Body>
							</soap:Envelope>';

		$headers = array(
			"Content-type: text/xml;charset=\"utf-8\"",
			"SOAPAction: http://tempuri.org/sendSMS",
			"Content-length: ".strlen($xml_post_string),
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $soapUrl);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $xml_post_string);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

		$response = curl_exec($ch);
		curl_close($ch);
		
		return true;
	}

}