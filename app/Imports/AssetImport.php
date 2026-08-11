<?php

namespace App\Imports;

use App\Models\Asset;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class AssetImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    { $oldSymbol = trim($row['old_symbol'] ?? '');
        $newSymbol = trim($row['new_symbol'] ?? '');
        $nameOnWebtrader = trim($row['name_on_webtrader'] ?? '');
        $category = trim($row['category'] ?? '');

        // Define fields to update dynamically based on presence
        $updateData = [];
        
        if ($newSymbol !== '') {
            $updateData['symbol'] = $newSymbol;
        }
        if ($nameOnWebtrader !== '') {
            $updateData['name'] = $nameOnWebtrader;
        }
        if ($category !== '') {
            $updateData['category'] = $category;
            $updateData['type'] = $category;
        }

        // If there are no values to update or insert, skip the row
        if (empty($updateData)) {
            return null;
        }

        // CASE 1: Old symbol is '0' or empty -> Create a new record
        if ($oldSymbol === '0' || $oldSymbol === '') {
            return new Asset($updateData);
        }

        // CASE 2: Old symbol exists -> Update existing records matching the old symbol
        // We use an update query. We return null so Maatwebsite doesn't try to insert a new row.
        Asset::where('symbol', $oldSymbol)->update($updateData);

        return null;
    }

    /**
     * Read the file in chunks of 500 rows to keep memory usage low.
     */
    public function chunkSize(): int
    {
        return 500;
    }
}
