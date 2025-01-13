<?php

namespace Database\Seeders;

use App\Models\Supplier;
use Illuminate\Database\Seeder;

class SupplierSeeder extends Seeder
{
    public function run()
    {
        $suppliers = [
            [
                'nom' => 'Tech Distributors',
                'email' => 'contact@techdist.com',
                'telephone' => '+33 1 23 45 67 89',
                'adresse' => '123 Rue de la Tech, 75001 Paris, France',
                'active' => true,
            ],
            [
                'nom' => 'Office Supply Co',
                'email' => 'sales@officesupply.com',
                'telephone' => '+33 1 98 76 54 32',
                'adresse' => '456 Avenue des Fournitures, 69001 Lyon, France',
                'active' => true,
            ],
            [
                'nom' => 'Digital Solutions',
                'email' => 'info@digitalsolutions.com',
                'telephone' => '+33 1 45 67 89 01',
                'adresse' => '789 Boulevard Digital, 13001 Marseille, France',
                'active' => true,
            ],
        ];

        foreach ($suppliers as $supplier) {
            Supplier::create($supplier);
        }
    }
} 