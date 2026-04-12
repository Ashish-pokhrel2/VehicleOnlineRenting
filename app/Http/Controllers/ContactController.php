<?php

namespace App\Http\Controllers;

use App\Enums\ContactStatus;
use App\Http\Requests\Contact\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = Contact::with('user:id,name,email')
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->when($request->unread_only, fn ($q) => $q->unread())
            ->latest();

        $contacts = $request->paginate
            ? $query->paginate($request->per_page ?? 15)
            : $query->get();

        return response()->json([
            'success' => true,
            'data' => $contacts,
        ]);
    }

    public function store(StoreContactRequest $request): JsonResponse
    {
        $contact = Contact::create([
            'user_id' => auth()->id(),
            'subject' => $request->subject,
            'message' => $request->message,
            'status' => ContactStatus::PENDING,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact message sent successfully',
            'data' => $contact->load('user:id,name,email'),
        ], 201);
    }

    public function show(Contact $contact): JsonResponse
    {
        return response()->json([
            'success' => true,
            'data' => $contact->load('user:id,name,email'),
        ]);
    }

    public function markAsRead(Contact $contact): JsonResponse
    {
        $contact->update(['is_read' => true]);

        return response()->json([
            'success' => true,
            'message' => 'Contact marked as read',
            'data' => $contact,
        ]);
    }

    public function updateStatus(Request $request, Contact $contact): JsonResponse
    {
        $request->validate([
            'status' => ['required', 'in:Replied,Closed'],
        ]);

        $contact->update([
            'status' => $request->status,
            'is_read' => true,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Contact status updated successfully',
            'data' => $contact->load('user:id,name,email'),
        ]);
    }

    public function destroy(Contact $contact): JsonResponse
    {
        if ($contact->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized to delete this contact',
            ], 403);
        }

        $contact->delete();

        return response()->json([
            'success' => true,
            'message' => 'Contact deleted successfully',
        ]);
    }
}
