<?php

namespace App\Imports;

use App\User;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class UsersImport implements ToCollection, WithHeadingRow
{

    public $result;
    /**
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function collection(Collection $rows)
    {
        $rowNumber = 0;
        $results = array();
        foreach ($rows as $row) {

            $rowNumber++;
            $row = $row->toArray();
            $rules = Arr::only(User::RULES,['name', 'password','position']);
            $validator = Validator::make($row, User::RULES+['action'=>'required | in:Create,Update,Delete','email'=>'required|string|email|max:255']);

            if ($validator-> fails()){
                $error = $validator->errors();
                $results[] = [$rowNumber, $error];


            }else{
                $action = $row['action'];
                if($action == 'Create' || $action == 'Update'){
                    User::updateOrCreate(['email' => $row['email']],
                        [
                            'name' => $row['name'],
                            'position' => $row['position'],
                            'password' => bcrypt($row['password'])
                        ]);
                    $results[]=[
                        'row'=>$rowNumber,
                        'message'=>'Create/Update Successful'];
                }
                elseif($action == 'Delete'){
                    $user = User::where('email', $row['email'])->First();
                    $user -> delete();
                    $results[]=['row'=>$rowNumber, 'message'=>'Delete Successful'];
                }
                else{
                    $results[]=['row'=>$rowNumber, 'message'=>'Failed'];
                }

            }
        }

        $this -> result = $results; //return response
    }
}

