<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Mail\MailContact;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Exception;

class ContactController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Lưu thông tin liên hệ
            $contact = new Contact();
            $contact->name = $request->input("name");
            $contact->email = $request->input("email");
            $contact->phone = $request->input("phone");
            $contact->message = $request->input("message");
            $contact->isReplied = 0;
            $contact->save();

            // Gửi email
            Mail::to("ducle090891999@gmail.com")
            ->queue(new MailContact(
                $request->input("name"),
                $request->input("phone"),
                $request->input("message")
            ));
        

            return response()->json([
                "message" => "Mail đã được gửi thành công",
                "data" => $contact,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'message' => 'Đã xảy ra lỗi khi gửi mail.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
