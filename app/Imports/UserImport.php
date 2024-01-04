<?php

namespace App\Imports;

use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToCollection;

class UserImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $indexKe = 1;

        foreach($collection as $row){
            if($indexKe > 1){

                $data['email']      = !empty($row[1]) ? $row[1] : '';
                $data['name']       = !empty($row[0]) ? $row[0] : '';
                $data['password']   = !empty($row[2]) ? Hash::make($row[2]) : '';

                User::create($data);
            }

            $indexKe++;
        }
    }
}
