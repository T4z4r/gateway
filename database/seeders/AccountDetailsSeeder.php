<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Faker\Factory as Faker;

class AccountDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create();

        // Array to track used customer IDs and account numbers for repetition
        $usedCustomerIds = [];
        $usedAccountNumbers = [];
        $usedRecIds = [];

        // Create 50 account details records
        foreach (range(1, 50) as $index) {
            // Randomly decide whether this record will have a repeated or unique customer ID and account number
            $isRepeated = $faker->boolean(70); // 70% chance of repetition

            // For repeated values, select from previously used values
            $customerId = $isRepeated && count($usedCustomerIds) > 0 ? $faker->randomElement($usedCustomerIds) : $faker->unique()->numerify('CUST####');
            $accountNumber = $isRepeated && count($usedAccountNumbers) > 0 ? $faker->randomElement($usedAccountNumbers) : $faker->unique()->numerify('ACC########');
            $recId = $isRepeated && count($usedRecIds) > 0 ? $faker->randomElement($usedRecIds) : $faker->uuid();

            // Add the new unique or repeated values to the used arrays
            if (!in_array($customerId, $usedCustomerIds)) {
                $usedCustomerIds[] = $customerId;
            }
            if (!in_array($accountNumber, $usedAccountNumbers)) {
                $usedAccountNumbers[] = $accountNumber;
            }
            if (!in_array($recId, $usedRecIds)) {
                $usedRecIds[] = $recId;
            }

            // Insert the record into the database
            DB::table('account_details')->insert([
                'txn_date_and_time' => $faker->dateTimeThisYear(),
                'channel_id' => $faker->word(),
                'type' => $faker->randomElement(['debit', 'credit']),
                'acct_type' => $faker->randomElement(['savings', 'checking']),
                'customer_id' => $customerId,
                'customer_ic' => $faker->numerify('IC#####'),
                'account_number' => $accountNumber,
                'account_currency' => $faker->randomElement(['USD', 'EUR', 'KES', 'GBP']),
                'language' => $faker->randomElement(['en', 'fr', 'sw']),
                'account_opened_date' => $faker->date(),
                'txn_product_code' => $faker->word(),
                'risk_level' => $faker->randomElement(['01', '02', '03']),
                'screening' => $faker->randomElement(['Y', 'N']),
                'account_customer_first_name' => $faker->firstName(),
                'account_customer_middle_name' => $faker->lastName(),
                'account_customer_last_name' => $faker->lastName(),
                'account_customer_gender' => $faker->randomElement(['M', 'F']),
                'martial_status' => $faker->randomElement(['single', 'married']),
                'id_type' => $faker->randomElement(['passport', 'id_card']),
                'id_number' => $faker->unique()->numerify('ID########'),
                'nationality' => $faker->countryCode(),
                'district' => $faker->city(),
                'state' => $faker->state(),
                'region' => $faker->word(),
                'branch' => $faker->word(),
                'country_of_birth' => $faker->country(),
                'account_mobile_number' => $faker->phoneNumber(),
                'customer_type' => $faker->randomElement(['individual', 'corporate']),
                'id_issue_date' => $faker->date(),
                'id_expiry_date' => $faker->date(),
                'birth_date' => $faker->date(),
                'customer_photo' => $faker->randomElement(['Y', 'N']),
                'customer_signature' => $faker->randomElement(['Y', 'N']),
                'account_email_address' => $faker->unique()->email(),

                // Additional Fields
                'cuId' => $recId,
                'label' => $faker->word(),
                'deadId' => 'N', // Default 'N' as per your schema
            ]);
        }
    }
}
