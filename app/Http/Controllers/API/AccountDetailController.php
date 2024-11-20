<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AccountDetail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AccountDetailController extends Controller
{


    ################################ Damiano's Implementation #######################################
    public function customerPool(Request $request)
    {
        try {
            // Retrieve query parameters with default values
            $page = $request->input('page', 1);
            $type = $request->input('type', null);
            $cuId = $request->input('cuId', null);
            $checkDuplicate = $request->input('checkDuplicate');

            // Start building the query
            $query = AccountDetail::orderBy('txn_date_and_time', 'desc')->latest();

            // Filter by type if provided
            if ($type) {
                $query->where('type', $type);
            }

            // Filter by cuId if provided
            if ($cuId) {
                $query->where('cuId', $cuId);
            }

            // Check for duplicate records if checkDuplicate is explicitly true
            if ($checkDuplicate === true) {
                $latestAccountDetails = $query->get()
                    ->groupBy('cuId')
                    ->map(function ($details) {
                        return $details->first(); // Get the latest record in each group
                    })
                    ->values(); // Convert the result to a flat array
            } else {
                $latestAccountDetails = $query->get();
            }

            // Paginate results based on the page parameter
            $perPage = 50; // Define items per page
            $paginatedResults = $latestAccountDetails->forPage($page, $perPage);

            return response()->json([
                'success' => true,
                'message' => 'Latest account details retrieved successfully',
                'data' => $paginatedResults->values(),
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve account details',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    ############################### OLD IMPLEMENTATION #############################################
    // For All Customer Details
    public function index()
    {
        try {
            // Retrieve the latest record for each recId by grouping and sorting
            $latestAccountDetails = AccountDetail::orderBy('txn_date_and_time', 'desc')
                ->get()
                ->groupBy('cuId')
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

    // For New To Bank /Maintaned Customer Accounts Details
    public function accountTypeList($type)
    {
        try {
            // Retrieve the latest record for each recId by grouping and sorting
            $latestAccountDetails = AccountDetail::orderBy('txn_date_and_time', 'desc')
                ->where('type', $type)
                ->get()
                ->groupBy('cuId')
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


    // For  customers with multiple customer IDs
    public function mulitpleCustomerIDs()
    {
        try {
            // Retrieve the latest record for each recId by grouping and sorting
            $latestAccountDetails = AccountDetail::orderBy('txn_date_and_time', 'desc')
                ->where('checkDuplicate', true)
                ->get()
                ->groupBy('cuId')
                ->map(function ($details) {
                    return $details->first(); // Get the latest record in each group
                })
                ->values(); // Convert the result to a flat array

            return response()->json([
                'success' => true,
                'message' => 'Latest account details with multiple customer IDs retrieved successfully',
                'data' => $latestAccountDetails,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve account details with multiple customer IDs',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
    // end


    // GET a single account detail by ID
    public function show($cuId)
    {
        try {
            // Retrieve all account details associated with the specified recId
            $accountDetails = AccountDetail::where('cuId', $cuId)
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


    // For Single Maintenance Details
    public function maintenaceDetails($cuId)
    {
        try {
            // Retrieve all account details associated with the specified recId
            $accountDetails = AccountDetail::where('cuId', $cuId)
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

            // Ensure status is valid
            if (!in_array($status, ['Primary', 'Secondary', 'Prohibited'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid status provided.'
                ], 400);
            }

            // Find the account by its ID and customerId
            $account = AccountDetail::where('id', $accountId)
                                    ->where('customer_id', $customerId)
                                    ->first();

            if (!$account) {
                return response()->json([
                    'success' => false,
                    'message' => 'Account not found.'
                ], 404);
            }

            // Check for existing 'Primary' or 'Secondary' within the same cuId
            if (in_array($status, ['Primary', 'Secondary'])) {
                $existingLabel = AccountDetail::where('cuId', $account->cuId)
                                               ->where('label', $status)
                                               ->where('id', '!=', $accountId)
                                               ->exists();

                if ($existingLabel) {
                    return response()->json([
                        'success' => false,
                        'message' => "An account with the status '{$status}' already exists for this CU ID."
                    ], 400);
                }
            }

            // Update the status of the specified account
            $account->label = $status;

            if ($status === 'Primary') {
                $account->txn_date_and_time = now();
            }
            $account->save();

            // Only update other accounts if the current account is marked as 'Primary' or 'Secondary'
            if (in_array($status, ['Primary', 'Secondary'])) {
                $accounts = AccountDetail::where('cuId', $account->cuId)
                                         ->where('id', '!=', $account->id)
                                         ->get();

                foreach ($accounts as $accountdetail) {
                    // Update other accounts to 'Prohibited' only if their current label is not 'Primary' or 'Secondary'
                    if (!in_array($accountdetail->label, ['Primary', 'Secondary'])) {
                        $accountdetail->label = 'Prohibited';
                        $accountdetail->save();
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Account status updated successfully.',
                'account' => $account
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to update account status.',
                'error' => $e->getMessage()
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
        $accounts = AccountDetail::where('customer_id', $customerId)->orderBy('txn_date_and_time', 'desc')->latest()->get();

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
