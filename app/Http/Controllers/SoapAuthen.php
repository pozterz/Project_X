<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Artisaninweb\SoapWrapper\Facades\SoapWrapper;

class SoapAuthen extends Controller {

	public function login(Request $request)
	{

	    SoapWrapper::add(function ($service) {
	       $service
	       ->name('PSU_authentication')
	       ->wsdl('https://passport.psu.ac.th/authentication/authentication.asmx?wsdl')
	       ->trace(true);
	     });
	
		$data = [
	        	'username' => $request->input('username'),
	        	'password' => $request->input('password')
		];

		// Using the added service
		SoapWrapper::service('PSU_authentication', function ($service) use ($data,$request) {
			if($authenticated = $service->call('Authenticate', [$data])->AuthenticateResult){
				$response = $service->call('GetUserDetails', [$data])->GetUserDetailsResult;
				foreach($response->string as $result){
					echo $result . "<br/>";
				}
				if($this->isStudent($authenticated,$response->string)){
					echo "Students";
				}
			}
		});
	}
	
	public function isStudent($authenticated,$response)
	{
		if (!$authenticated) return false;
	
		if (preg_match("/OU=Students/", $response[14]) > 0)
			return true;
		else
			return false;
	}
}