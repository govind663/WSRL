<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DistributorsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Distributors data
        $distributors = [
            [
                'distributor_code' => '2024-000001',
                'distributor_gstin' => '24AAKCA1203E1ZF',
                'distributor_pos' => 'GUJARAT',
                'distributor_name' => 'ABSOLUTE DISTRIBUTION SOLUTIONS PRIVATE LIMITED',
                'contact_person' => rand(1000000000, 9999999999),
                'email' => 'ahmedabad@adsmedi.com',
                'city' => 'AHMEDABAD',
                'state' => 'GUJARAT',
                'postal_code' => '380007',
                'country' => 'INDIA',
                'division' => 'DERMA',
                'address' => 'OPP MADHUSUDAN HOUSE FF 108',
                'other_address' => 'SAHAIL COMPLEX C G ROAD',
                'status' => 'active',
                'inserted_by' => 1,
                'inserted_at' => now(),
            ],
            [
                'distributor_code' => '2024-000002',
                'distributor_gstin' => '04BTXPS4000F1ZO',
                'distributor_pos' => 'CHANDIGARH',
                'distributor_name' => 'MEDICLOUD HEALTHCARE',
                'contact_person' => rand(1000000000, 9999999999),
                'email' => 'medicloudhealthcare@gmail.com',
                'city' => 'CHANDIGARH',
                'state' => 'CHANDIGARH',
                'postal_code' => '160047',
                'country' => 'INDIA',
                'division' => 'DERMA',
                'address' => '2ND FLOOR SCO 158A CABIN NO 7',
                'other_address' => 'NEAR CIVIL HOSPITAL BURAIL SECTOR 45',
                'status' => 'active',
                'inserted_by' => 2,
                'inserted_at' => now(),
            ],
            [
                'distributor_code' => '2024-000003',
                'distributor_gstin' => '36ANHPK3492N1ZF',
                'distributor_pos' => 'TELANGANA',
                'distributor_name' => 'DRUG MART PHARMACEUTICAL DISTRIBUTORS',
                'contact_person' => rand(1000000000, 9999999999),
                'email' => 'drugmart@ymail.com',
                'city' => 'HYDERABAD',
                'state' => 'TELANGANA',
                'postal_code' => '500003',
                'country' => 'INDIA',
                'division' => 'DERMA',
                'address' => '1 FLOOR 9 1 127 1 F3 AMSRI CLASSIC',
                'other_address' => 'COMPLEX S D ROAD SECUNDERABAD',
                'status' => 'active',
                'inserted_by' => 3,
                'inserted_at' => now(),
            ],
            [
                'distributor_code' => '2024-000004',
                'distributor_gstin' => '09ADPPA9779E1Z0',
                'distributor_pos' => 'UTTAR PRADESH',
                'distributor_name' => 'SHIVAM ORGANICS',
                'contact_person' => rand(1000000000, 9999999999),
                'email' => 'jayarora1979@gmail.com',
                'city' => 'LUCKNOW',
                'state' => 'UTTAR PRADESH',
                'postal_code' => '226007',
                'country' => 'INDIA',
                'division' => 'DERMA',
                'address' => '509/019 KALA KANKAR COLONY PURANA HAYDRABAD',
                'other_address' => 'BIRBAL SAHANI MARG',
                'status' => 'active',
                'inserted_by' => 4,
                'inserted_at' => now(),
            ],
            [
                'distributor_code' => '2024-000005',
                'distributor_gstin' => '33AAAFO7574R1ZL',
                'distributor_pos' => 'TAMIL NADU',
                'distributor_name' => 'OM SAKTHI MEDI CENTRE',
                'contact_person' => rand(1000000000, 9999999999),
                'email' => 'omsakthimedi@gmail.com',
                'city' => 'CHENNAI',
                'state' => 'TAMIL NADU',
                'postal_code' => '600002',
                'country' => 'INDIA',
                'division' => 'DERMA',
                'address' => 'OLD NO 104 AND 107 NEW 163 ANNA SALAI',
                'other_address' => 'NEAR L I C MOUNT ROAD',
                'status' => 'active',
                'inserted_by' => 5,
                'inserted_at' => now(),
            ],
            [
                'distributor_code' => '2024-000006',
                'distributor_gstin' => '32AAKCA1203E1ZI',
                'distributor_pos' => 'KERALA',
                'distributor_name' => 'ABSOLUTE DISTRIBUTION SOLUTIONS PRIVATE LIMITED',
                'contact_person' => rand(1000000000, 9999999999),
                'email' => 'cochin@adsmedi.com',
                'city' => 'COCHIN',
                'state' => 'KERALA',
                'postal_code' => '682035',
                'country' => 'INDIA',
                'division' => 'DERMA',
                'address' => '1ST FLOOR 66/4129 CHAKOS TOWER',
                'other_address' => 'PADMA PULLEPADY ROAD',
                'status' => 'active',
                'inserted_by' => 6,
                'inserted_at' => now(),
            ],
        ];

        // Insert the distributors data into the table
        DB::table('distributors')->insert($distributors);
    }
}
