<?php

namespace App\Http\Controllers\Client;

use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\Routing\Controller;

class ContactController extends Controller
{
    public function show()
    {
        return view('Client.Contact.contact');
    }

    public function submit(Request $request)
    {
        // Validate dữ liệu (nếu cần)
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email',
            'phone' => 'nullable|string|max:20',
            'message' => 'required|string',
        ]);

        // Lưu vào database
        Contact::create($request->only('name', 'email', 'phone', 'message'));

        // Redirect kèm thông báo
        return redirect()->back()->with('success', 'Cảm ơn bạn đã liên hệ với chúng tôi!');
    }
}
