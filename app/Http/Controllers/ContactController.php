<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        // Get all contacts
        $contacts = Contact::all();
        return response()->json($contacts);
    }

    public function show($id)
    {
        // Get a specific contact by ID
        $contact = Contact::find($id);
        return response()->json($contact);
    }

    public function store(Request $request)
    {
        // Create a new contact
        $contact = Contact::create($request->all());
        return response()->json($contact);
    }

    public function update(Request $request, $id)
    {
        // Update a contact
        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
        return response()->json($contact);
    }

    public function destroy($id)
    {
        // Delete a contact
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return response()->json(['message' => 'Contact deleted']);
    }
}
