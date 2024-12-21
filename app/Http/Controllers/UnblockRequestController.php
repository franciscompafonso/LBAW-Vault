<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\UnblockRequest;
use Illuminate\Support\Facades\Log;

class UnblockRequestController extends Controller
{
    public function create()
    {
        $this->authorize('sendUnblockRequest', auth()->user());

        return view('unblock_requests.create');
    }

    public function store(Request $request)
    {
        $this->authorize('sendUnblockRequest', auth()->user());

        $request->validate([
            'reason' => 'required|string|max:255',
        ]);


        UnblockRequest::create([
            'requested_by_id' => auth()->id(),
            'status' => 'pending',
            'decision' => $request->reason,
            'requested_at' => now(),
        ]);

        return redirect()->route('pages.home')->with([
            'success' => true,
            'message' => 'Your unblock request has been submitted.'
        ]);
    }


    public function index()
    {
        $requests = UnblockRequest::with('requestedBy')->paginate(5);
        $requests = UnblockRequest::where('status', 'pending')->paginate(5);

        return view('unblock_requests.index', compact('requests'));
    }


    public function approve($id)
    {
        $request = UnblockRequest::findOrFail($id);

        if ($request->status !== 'pending') {
            return redirect()->back()->withErrors('This request has already been processed.');
        }

        $request->update([
            'status' => 'approved',
            'decision_by_id' => auth()->id(),
            'processed_at' => now(),
        ]);

        Log::info("Updated decision_by_id: {$request->decision_by_id}");

        $user = $request->requestedBy; 
        if ($user) {
            $user->isblocked = false;
            $user->save();
        } 

        return redirect()->route('admin.unblock.requests')->with([
            'success' => true,
            'message' => 'Request approved, and user has been unblocked.'
        ]);
    }



    public function reject($id)
    {
        $request = UnblockRequest::findOrFail($id);
        $request->reject(auth()->id());

        return redirect()->back()->with([
            'success' => true,
            'message' => 'Unblock request rejected.'
        ]);
    }
}
