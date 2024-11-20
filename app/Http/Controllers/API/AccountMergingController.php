<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Models\AccountDetail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\AccountMergingRequest;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;


class AccountMergingController extends Controller
{

    // For Retrieving Latest Requests
    public function index()
    {
        try {
            // Retrieve the latest record for each unique recId
            $latestMergingRequests = AccountMergingRequest::latest('created_at')
                ->get()
                ->values(); // Convert the result to a flat array

            return response()->json([
                'success' => true,
                'message' => 'Latest account merging requests retrieved successfully',
                'data' => $latestMergingRequests,
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to retrieve account merging requests',
                'error' => $e->getMessage(),
            ], 500);
        }
    }


    // for processing account merging
    public function processAccountMerge(Request $request)
    {
        // Step 1: Validate the input
        $validatedData = $request->validate([
            'cuId' => 'required',
            'customerID' => 'required',
            'accountNumber' => 'required|string',
            'existing_ids' => 'required',
            'merged_to' => 'required',
        ]);

        $cuId = $validatedData['cuId'];
        $customerID = $validatedData['customerID'];
        $accountNumber = $validatedData['accountNumber'];
        $existingIdsJson = $validatedData['existing_ids']; // Raw JSON from the request
        $mergeTo = $validatedData['merged_to'];

        // Step 2: Decode the JSON array
        $existingIds = json_decode($existingIdsJson, true); // Decode the outer JSON array

        if (json_last_error() !== JSON_ERROR_NONE) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid JSON format for existing_ids.'
            ], 400);
        }

        // Step 3: Extract account numbers from the decoded JSON array
        $accountNumbers = array_map(function ($item) {
            $decodedItem = json_decode($item, true); // Decode the inner JSON string

            // Check if decoding was successful and return the account number
            return $decodedItem['account_number'] ?? 'N/A'; // Default to 'N/A' if account_number is not available
        }, $existingIds);

        // Step 4: Fetch account details
        $accountDetails = AccountDetail::whereIn('account_number', $accountNumbers)->get();

        // Step 5: Verify data integrity
        $allHaveSameCuId = $accountDetails->every(fn($detail) => $detail->cuId === $cuId);

        if (!$allHaveSameCuId) {
            return response()->json([
                'success' => false,
                'message' => 'All accounts must belong to the same cuId.'
            ], 400);
        }

        // Step 6: Process the data
        try {
            DB::transaction(function () use ($accountDetails, $customerID, $accountNumber) {
                // Step 6a: Mark existing accounts as dead and update checkDuplicate
                foreach ($accountDetails as $account) {
                    $account->update([
                        'deadId' => 'Y',
                        'checkDuplicate' => 1

                    ]);

                    // Step 6b: Create a new account entry for each merged account
                    $newAccount = new AccountDetail();
                    $newAccount->cuId = $account->cuId;
                    $newAccount->label = $account->label;
                    $newAccount->deadId = 'N'; // Set to 'Y' for merged account
                    $newAccount->checkDuplicate = 1;
                    $newAccount->type = 'ETB'; // Assuming 'ETB' is the account type
                    $newAccount->customer_id = $customerID; // Set the new customer ID
                    $newAccount->account_number = $account->account_number; // Set the new account number
                    $newAccount->account_currency = $account->account_currency;
                    $newAccount->language = $account->language;
                    $newAccount->account_opened_date = $account->account_opened_date;
                    $newAccount->txn_product_code = $account->txn_product_code;
                    $newAccount->risk_level = $account->risk_level;
                    $newAccount->screening = $account->screening;
                    $newAccount->account_customer_first_name = $account->account_customer_first_name;
                    $newAccount->account_customer_middle_name = $account->account_customer_middle_name;
                    $newAccount->account_customer_last_name = $account->account_customer_last_name;
                    $newAccount->account_customer_gender = $account->account_customer_gender;
                    $newAccount->martial_status = $account->martial_status;
                    $newAccount->id_type = $account->id_type;
                    $newAccount->id_number = $account->id_number;
                    $newAccount->nationality = $account->nationality;
                    $newAccount->district = $account->district;
                    $newAccount->state = $account->state;
                    $newAccount->region = $account->region;
                    $newAccount->branch = $account->branch;
                    $newAccount->country_of_birth = $account->country_of_birth;
                    $newAccount->account_mobile_number = $account->account_mobile_number;
                    $newAccount->customer_type = $account->customer_type;
                    $newAccount->id_issue_date = $account->id_issue_date;
                    $newAccount->id_expiry_date = $account->id_expiry_date;
                    $newAccount->birth_date = $account->birth_date;
                    $newAccount->customer_photo = $account->customer_photo;
                    $newAccount->customer_signature = $account->customer_signature;
                    $newAccount->account_email_address = $account->account_email_address;

                    // Save the new account
                    $newAccount->save();
                }
            });

            // Step 7: Return success response
            return response()->json([
                'success' => true,
                'message' => 'Account merge processed successfully.'
            ], 200);
        } catch (\Exception $e) {
            // Rollback transaction if anything fails
            DB::rollBack();

            // Return error response
            return response()->json([
                'success' => false,
                'message' => 'An error occurred during processing.',
                'error' => $e->getMessage()
            ], 500);
        }
    }


    // For Storing Requests
    public function store(Request $request)
    {
        try {
            // Validate incoming request


            $validatedData = $request->validate([
                'existing_ids' => 'required|array',
                'merged_to' => 'required',
                'reason' => 'required|string',
            ]);


            // Begin DB transaction
            DB::beginTransaction();

            // Create a new account merging request
            $mergeRequest = AccountMergingRequest::create([
                'existing_ids' => json_encode($validatedData['existing_ids']),
                'merged_to' => $validatedData['merged_to'],
                'reason' => $validatedData['reason'],
                'maker_id' =>  1, // Assumes user ID is from auth session, falls back if not authenticated
            ]);

            // Commit transaction
            DB::commit();

            // Return success response
            return response()->json([
                'success' => true,
                'message' => 'Merging request created successfully!',
                'data' => $mergeRequest,
            ], Response::HTTP_CREATED);
        } catch (ValidationException $e) {
            // Rollback transaction
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $e->errors(),
            ], Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (ModelNotFoundException $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Account to be merged not found',
            ], Response::HTTP_NOT_FOUND);
        } catch (QueryException $e) {
            DB::rollBack();
            Log::error('Database error during merging request creation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Database error occurred. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        } catch (\Exception $e) {
            // Catch any other exceptions
            DB::rollBack();
            Log::error('Unexpected error during merging request creation: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred. Please try again later.',
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // Method for the checker to approve the request

    public function approve($id)
    {
        $mergeRequest = AccountMergingRequest::findOrFail($id);

        // Ensure the current user is authorized to approve
        if ($mergeRequest->status != 'pending') {
            return response()->json(['error' => 'This request has already been processed.'], 400);
        }

        $mergeRequest->update([
            'status' => 'approved',
            'checker_id' => 1, // Checker is the logged-in user
        ]);

        return response()->json(['success' => true, 'message' => 'Merging request approved']);
    }

    public function reject($id)
    {
        $mergeRequest = AccountMergingRequest::findOrFail($id);

        // Ensure the current user is authorized to reject
        if ($mergeRequest->status != 'pending') {
            return response()->json(['error' => 'This request has already been processed.'], 400);
        }

        $mergeRequest->update([
            'status' => 'rejected',
            'checker_id' => 1, // Checker is the logged-in user
        ]);

        return response()->json(['success' => true, 'message' => 'Merging request rejected']);
    }
}
