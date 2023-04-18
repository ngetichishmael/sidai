<?php

namespace App\Imports;

use App\Models\Area;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use App\Models\Region;
use Illuminate\Support\Str;
use App\Models\Relationship;
use App\Models\Subregion;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RegionalImport implements ToCollection, WithHeadingRow
{
   /**
    * @param Collection $collection
    */
   public function collection(Collection $rows)
   {
      foreach ($rows as $row) {
         $region = Region::UpdateOrCreate(
            [

               'name' => ucwords(strtolower($row['zone'])),
            ],
            [
               'primary_key' => Str::random(20)
            ]
         );
         Relationship::UpdateOrCreate(
            [

               'region_id' => $region->id,
               'name' => ucwords(strtolower($row['zone'])),
            ],
            [
               'has_children' => false,
               'parent_id' => null,
               'level_id' => 0,
            ]
         );
         Relationship::where('name', $region->name)->update([
            'has_children' => true,
         ]);

         $subregion = Subregion::UpdateOrcreate(
            [

               'name' => ucwords(strtolower($row["region"])),
            ],
            [
               'region_id' => $region->id,
               'primary_key' => Str::random(20)
            ]
         );
         Relationship::UpdateOrCreate(
            [

               'name' => ucwords(strtolower($row["region"])),
            ],
            [
               'has_children' => false,
               'region_id' => $subregion->region_id,
               'parent_id' => $subregion->id,
               'level_id' => 1,
            ]
         );
         Area::UpdateOrCreate(
            [
               'name' => ucwords(strtolower($row["route"])),

            ],
            [
               'subregion_id' => $subregion->id,
               'primary_key' => Str::random(20)
            ]
         );
         Relationship::where('name', $subregion->name)->update([
            'has_children' => true,
         ]);
      }
   }
}
