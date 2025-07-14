<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller; // ✅ Thêm dòng này
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(10); // hoặc ->get() nếu chưa muốn phân trang
        return view('Admin.contact.index', compact('contacts'));
    }

    // Hiển thị chi tiết liên hệ
    public function show($id)
    {
        $contact = Contact::findOrFail($id);
        return view('Admin.contact.show', compact('contact'));
    }

    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();

        return redirect()->route('Admin.contacts.index')
            ->with('success', 'Xóa liên hệ thành công.');
    }
}
