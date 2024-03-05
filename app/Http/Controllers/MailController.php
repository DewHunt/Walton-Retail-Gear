<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Mail;
use validation;

class MailController extends Controller
{
    public function index()
    {
    	return view('mail_form');
    }
    
	public function basic_email() 
	{

		$data = array('name'=>"Saidul Haque Sayed");
		Mail::send(['text'=>'mail'], $data, function($message) {
			$message->to('sayed.giantssoft@gmail.com', 'Giants Soft')->subject('Laravel Basic Testing Mail');
			$message->from('demoadmin@manush.co.uk','Saidul Haque Sayed');
		});
		echo "Basic Email Sent. Check your inbox.";
	}
	
	public function html_email() 
	{

		$data = array('name'=>"Saidul Haque Sayed");
		Mail::send('mail', $data, function($message) {
			$message->to('sayed.giantssoft@gmail.com', 'Giants Soft')->subject('Laravel HTML Testing Mail');
			$message->from('demoadmin@manush.co.uk','Saidul Haque Sayed');
		});
		echo "HTML Email Sent. Check your inbox.";
	}
	
	public function attachment_email() 
	{

		$data = array('name'=>"Saidul Haque Sayed");
		Mail::send('mail', $data, function($message) {
			$message->to('sayed.giantssoft@gmail.com','Giants Soft')->subject('Laravel Testing Mail with Attachment');
			$message->attach('https://manush.co.uk/retail_gear/public_html/retail_gear/public/laravel.pdf');
			$message->from('demoadmin@manush.co.uk','Saidul Haque Sayed');
		});
		echo "Email Sent with attachment. Check your inbox.";
	}
	
	public function mail_attachment_email(Request $request) 
	{
		$this->validate($request,[
		    'email'=>'required|email',
		    'phone'=>'required',
		    'a_file'=>'mimes:jpeg,png,jpg,gif,svg,txt,pdf,ppt,docx,doc,xls'
		]);
		$data = array(
		    'name'=>$request->input('name'),
			'email'=>$request->input('email'),
			'phone'=>$request->input('phone'),
			'a_file'=>$request->file('cv')
		);
		Mail::send('mail', $data, function($message) use ($data)
		{
		    $message->to('sayed.giantssoft@gmail.com');
		    $message->subject('Contact Us Form With Attachment');
		    $message->from('demoadmin@manush.co.uk');
		    $message->attach($data['a_file']->getRealPath(), array(
		        'as'=>'a_file.'.$data['a_file']->getClientOriginalExtension(),
		        'mime'=>$data['a_file']->getMimeType())
		    );
		});
		echo "Email Sent with attachment. Check your inbox.";
	}

}
