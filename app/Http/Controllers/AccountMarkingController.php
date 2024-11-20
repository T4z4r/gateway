<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AccountMarkingRequest;
use Illuminate\Support\Facades\Validator;

class AccountMarkingController extends Controller
{

    public function index(Request $request)
    {
        // Optionally, you can add pagination here if you have many records
        $requests = AccountMarkingRequest::paginate(10); // Adjust the pagination value as needed

        // Return the data in a structured JSON response
        return response()->json([
            'status' => 'success',
            'message' => 'Account marking requests retrieved successfully.',
            'data' => $requests
        ], 200);
    }

    // For All Marking Requests
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'customer_id' => 'required|string',
            'label' => 'required|string',
            'old_label' => 'required|string',
            'reason' => 'required|string',
            'maker_id' => 'required|integer',
            'status' => 'required|in:pending,approved,rejected',
        ]);

        // If validation fails, return a 400 response with error messages
        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 400);
        }

        // Create a new AccountMarkingRequest
        $accountMarkingRequest = AccountMarkingRequest::create($request->all());

        // Return a successful response with the created data
        return response()->json([
            'status' => 'success',
            'message' => 'Account marking request created successfully.',
            'data' => $accountMarkingRequest
        ], 201);
    }
}
