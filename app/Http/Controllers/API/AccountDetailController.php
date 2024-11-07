<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AccountDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AccountDetailController extends Controller
{
    public function index()
    {
        try {
            // Retrieve the latest record for each recId by grouping and sorting
            $latestAccountDetails = AccountDetail::orderBy('txn_date_and_time', 'desc')
                ->get()
                ->groupBy('recId')
                ->map(function ($details) {
                    return $details->first(); // Get the latest record in each group
                })
                ->values(); // Convert the result to a flat array

            return response()->json([
                'success' => true,
                'message' => 'Latest account details retrieved successfully',
                'data' => $latestAccountDetails,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve account details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    // GET a single account detail by ID
    public function show($recId)
    {
        try {
            // Retrieve all account details associated with the specified recId
            $accountDetails = AccountDetail::where('recId', $recId)
                ->orderBy('txn_date_and_time', 'desc') // Order by date to show recent records first
                ->get();

            // Check if records are found
            if ($accountDetails->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'No account details found for the specified recId',
                ], 404);
            }

            return response()->json([
                'success' => true,
                'message' => 'Account details retrieved successfully',
                'data' => $accountDetails,
            ], 200);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve account details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }



    public function updateAccountStatus(Request $request)
    {
        try {
            $accountId = $request->accountId;
            $customerId = $request->customerId;
            $status = $request->status;

            // Ensure status is either 'Primary' or 'Prohibited'
            if (!in_array($status, ['Primary', 'Prohibited','Secondary'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status provided.'
                ], 400);
            }

            // Find the account by its ID and update the status
            $account = AccountDetail::where('id',$accountId)->where('customer_id',$customerId)->first();

            $account->label = $status;
            $account->save();

            return response()->json([
                'success' => true,
                'message' => 'Account status updated successfully'.$account
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update account status.',
                'error' => $e
            ], 500);
        }
    }


    // For Fetch By Custiner ID
     /**
     * Get accounts by customer ID
     */
    public function getAccountsByCustomer(Request $request)
    {
        $customerId = $request->input('customer_id');

        // Validate customer_id
        if (!$customerId) {
            return response()->json(['error' => 'Customer ID is required'], 400);
        }

        // Fetch the accounts for the given customer_id
        $accounts = AccountDetail::where('customer_id', $customerId)->get();

        if ($accounts->isEmpty()) {
            return response()->json(['message' => 'No accounts found for this customer'], 404);
        }

        // Return the accounts data as JSON response
        return response()->json([
            'success' => true,
            'message' => 'Accounts retrieved successfully',
            'data' => $accounts,
        ]);
    }

    // POST: Create new account details with DB transaction
    public function store(Request $request)
    {
        $validated = $request->validate([
            'customer_id' => 'required|string|max:20',
            'account_number' => 'nullable|string|max:20',
            'account_customer_first_name' => 'nullable|string|max:100',
            'account_customer_last_name' => 'nullable|string|max:100',
            // Add your other validation rules here
        ]);

        DB::beginTransaction();  // Start transaction
        try {
            $accountDetail = AccountDetail::create($validated);
            DB::commit();  // Commit the transaction

            return response()->json($accountDetail, 201); // Created
        } catch (\Exception $e) {
            DB::rollBack();  // Roll back the transaction if there is an error
            return response()->json(['message' => 'Error creating account details', 'error' => $e->getMessage()], 500);
        }
    }

    // PUT: Update account details with DB transaction
    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'account_customer_first_name' => 'nullable|string|max:100',
            'account_customer_last_name' => 'nullable|string|max:100',
            // Add your other validation rules here
        ]);

        DB::beginTransaction();  // Start transaction
        try {
            $accountDetail = AccountDetail::findOrFail($id);
            $accountDetail->update($validated);

            DB::commit();  // Commit the transaction
            return response()->json($accountDetail, 200); // OK
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  // Roll back the transaction if the model is not found
            return response()->json(['message' => 'Account detail not found'], 404); // Not Found
        } catch (\Exception $e) {
            DB::rollBack();  // Roll back the transaction if any other error occurs
            return response()->json(['message' => 'Error updating account detail', 'error' => $e->getMessage()], 500);
        }
    }

    // DELETE: Remove account details with DB transaction
    public function destroy($id)
    {
        DB::beginTransaction();  // Start transaction
        try {
            $accountDetail = AccountDetail::findOrFail($id);
            $accountDetail->delete();

            DB::commit();  // Commit the transaction
            return response()->json(['message' => 'Account detail deleted'], 200); // OK
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            DB::rollBack();  // Roll back the transaction if the model is not found
            return response()->json(['message' => 'Account detail not found'], 404); // Not Found
        } catch (\Exception $e) {
            DB::rollBack();  // Roll back the transaction if any other error occurs
            return response()->json(['message' => 'Error deleting account detail', 'error' => $e->getMessage()], 500);
        }
    }
}
