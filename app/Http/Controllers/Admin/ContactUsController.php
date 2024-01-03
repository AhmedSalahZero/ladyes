<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ContactUs;
use Illuminate\Http\Request;

class ContactUsController extends Controller
{
    public function index(){
        $contacts = ContactUs::orderBy('id','DESC')->get();
        return view('admin.contact.index',compact('contacts'));
    }

    public function show($id){
        $contact = ContactUs::find($id);
        return view('admin.contact.show',compact('contact'));
    }

    public function update(Request $request,$id){
        $contact = ContactUs::find($id);
        if ($contact){
            $contact->is_read = $request->is_read == 'on' ? 1 : 0;
            $contact->save();
            if ($request->save == 1)
                return redirect()->route('admin.contacts.show', $contact->id)->with('success', __('msg.created_success'));
            else
                return redirect()->route('admin.contacts.index')->with('success', __('msg.created_success'));
        }
    }

    public function delete(Request $request){
        $contact = ContactUs::find($request->id);
        if ($contact){
            $contact->delete();
            return response()->json([
                'status' => true,
                'id' => $request->id,
            ]);
        }
    }
}
