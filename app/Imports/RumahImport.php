<?php

namespace App\Imports;

use App\Models\Rumah;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RumahImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $ke = 1;

        foreach($collection as $row){
            if($ke > 1){

                $type_rumah = !empty($row[0]) ? $row[0] : '';

                if(!$type_rumah){
                    break;
                }

                $data['user_id']            = auth()->user()->id;
                $data['type_rumah']         = $type_rumah;
                $data['harga_rumah']        = $row[1];
                $data['lokasi_rumah']       = $row[2];

                Rumah::create($data);
            }

            $ke++;
        }
    }
}
