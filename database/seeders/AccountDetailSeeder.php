<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;

class AccountDetailSeeder extends Seeder
{
    public function run()
    {
        // Path to the JSON file
        $jsonFilePath = database_path('seeders/data/response_1731910928068.json');

        // Ensure the file exists
        if (!File::exists($jsonFilePath)) {
            $this->command->error("File not found: $jsonFilePath");
            return;
        }

        // Decode JSON data
        $jsonData = json_decode(File::get($jsonFilePath), true);

        if (!isset($jsonData['data']['data'])) {
            $this->command->error("Invalid JSON structure in file: $jsonFilePath");
            return;
        }

        // Iterate over each account and insert it into the database
        foreach ($jsonData['data']['data'] as $account) {
            DB::table('account_details')->insert([
                'cuId' => $account['cuId'] ?? null,
                'label' => $account['label'] ?? null,
                'deadId' => $account['deadId'] ?? 'N',
                'checkDuplicate' => $account['checkDuplicate'] ?? false,
                'txn_date_and_time' => now(),
                'channel_id' => $account['channelID'] ?? null,
                'type' => $account['type'] ?? null,
                'acct_type' => $account['acctType'] ?? null,
                'customer_id' => $account['customerID'],
                'customer_ic' => $account['customerIC'] ?? null,
                'account_number' => $account['accountNumber'] ?? null,
                'account_currency' => $account['accountCurrency'] ?? null,
                'language' => $account['language'] ?? null,
                'account_opened_date' => isset($account['accountOpenedDate']) ? date('Y-m-d', strtotime($account['accountOpenedDate'])) : null,
                'txn_product_code' => $account['txnProductCode'] ?? null,
                'risk_level' => $account['riskLevel'] ?? null,
                'screening' => $account['screening'] ?? null,
                'account_customer_first_name' => $account['accountCustomerFirstName'] ?? null,
                'account_customer_middle_name' => $account['accountCustomerMiddleName'] ?? null,
                'account_customer_last_name' => $account['accountCustomerLastName'] ?? null,
                'account_customer_gender' => $account['accountCustomerGender'] ?? null,
                'martial_status' => $account['martialStatus'] ?? null,
                'id_type' => $account['idType'] ?? null,
                'id_number' => $account['idNumber'] ?? null,
                'nationality' => $account['nationality'] ?? null,
                'district' => $account['district'] ?? null,
                'state' => $account['state'] ?? null,
                'region' => $account['region'] ?? null,
                'branch' => $account['branch'] ?? null,
                'country_of_birth' => $account['countryOfBirth'] ?? null,
                'account_mobile_number' => $account['accountMobileNumber'] ?? null,
                'customer_type' => $account['customerType'] ?? null,
                'id_issue_date' => isset($account['idIssueDate']) ? date('Y-m-d', strtotime($account['idIssueDate'])) : null,
                'id_expiry_date' => isset($account['idExpiryDate']) ?? null,
                'birth_date' => isset($account['birthDate']) ? date('Y-m-d', strtotime($account['birthDate'])) : null,
                'customer_photo' => $account['customerPhoto'] ?? null,
                'customer_signature' => $account['customerSignature'] ?? null,
                'account_email_address' => $account['accountEmailAddress'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        $this->command->info("Account details seeded successfully.");
    }
}
