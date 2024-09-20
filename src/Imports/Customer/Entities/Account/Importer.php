<?php

namespace MyDpo\Imports\Customer\Entities\Account;

use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use MyDpo\Models\Customer\Departments\Department;
use MyDpo\Models\Authentication\User;
use MyDpo\Models\Customer\Accounts\Account;
use MyDpo\Models\Authentication\RoleUser;
use MyDpo\Events\Customer\Entities\Account\CreateAccountActivation;

class Importer implements ToCollection {

    protected $lines = NULL;

    /**
     * departamentele clientului
     */
    public $departamente = NULL;

    public function __construct($input) {
        $this->input = $input;

        $this->departamente = Department::where('customer_id', $this->input['customer_id'])->pluck('id', 'departament')->toArray();
    }

    public function collection(Collection $rows) {

        $this->CreateLines($rows);

        $this->Process();
    }

    private function ValidRecord($record) {
        
        return !! $record['last_name'] && !! $record['first_name'] && !! $record['email'];
    }

    private function RowToRecord($row) {      
        $department = Department::CreateIfNotExists($this->input['customer_id'], trim($row[3]));

        return [
            'last_name' => trim($row[0]),
            'first_name' => trim($row[1]),
            'email' =>  trim($row[2]),
            'department_id'=> $department->id,
            'role_id' => strtolower(trim($row[4])) == 'master' ? 4 : 5,
            'phone' => trim($row[5]),
        ];
    }

    private function ProcessLine($line) {

        $user = User::whereEmail($line['email'])->first();

        if(! $user )
        {
            $user = User::create([
                'last_name' => $line['last_name'],
                'first_name' => $line['first_name'],
                'email' => $line['email'],
                'type' => 'b2b',
                'password' => \Hash::make(\Str::random(8)),
            ]);
        }

        $account = Account::create([
            'customer_id' => $this->input['customer_id'],
            'user_id' => $user->id,
            'role_id' => $line['role_id'],
            'department_id' => $line['department_id'],
            'phone' => $line['phone'],
        ]);

        $role = RoleUser::CreateAccountRole($this->input['customer_id'], $user->id, $account->role_id);

        $account->setDefaultDashboardItemsVisibility();
        $account->setInitialFoldersAccess();
        
        $customers = [
            $this->input['customer_id'] . '#' . $user->id,
        ];

        event(new CreateAccountActivation(
            'account.activation', 
            [
                'customers' => $customers, 
                'account' => $account, 
                'role' => $role
            ]
        ));

    }    

    protected function Process() {

        foreach($this->lines as $i => $line) 
        {
            $this->ProcessLine($line);
        }
    }

    protected function CreateLines($rows) {
        /**
         * Din input se ia start_row = linia de inceput
         */
        $start_row = 1 * $this->input['start_row'];

        $this->lines = $rows->map(function($row, $i){

            /**
             * Se ataseaza numarul de linie
             */
            return [
                ...$row,
            ];

        })->filter( function($row, $i) use ($start_row) {
            /**
             * Se incepe de la linia $start_row
             */
            return $i >= $start_row; 
        
        })->map( function($row, $i) {

            /**
             * Se convertesc randurile la tabela din BD
             */
            return $this->RowToRecord($row);
        
        })->filter( function($record, $i) {

            /**
             * Logica de validare a recordurilor
             */
            return $this->ValidRecord($record);

        })->values()->toArray();
    }

}