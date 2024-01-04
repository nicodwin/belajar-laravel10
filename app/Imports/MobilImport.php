<?php

namespace App\Imports;

use App\Models\Mobil;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class MobilImport implements ToCollection
{
    /**
    * @param Collection $collection
    */
    public function collection(Collection $collection)
    {
        $ke = 1;

        foreach($collection as $row){
            if($ke > 1){

                $nama_mobil = !empty($row[0]) ? $row[0] : '';

                if(!$nama_mobil){
                    break;
                }

                $data['user_id']            = auth()->user()->id;
                $data['type_mobil']         = $nama_mobil;
                $data['tahun_pembelian']    = $row[1];
                $data['harga_mobil']        = $row[2];

                Mobil::create($data);

            }

            $ke++;
        }
    }
}
