<?php

namespace App\Exports;

use App\Models\Party;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PartyExport implements FromCollection, WithHeadings, WithMapping
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public $i = 0;
    public function headings(): array
    {
        return ['Sr', 'Code', 'Name', 'AG', 'AG 2', 'AG 3']; // add any other columns needed
    }

    public function map($row): array
    {

        $fields = [
            ++$this->i,
            $row->code,
            $row->party_name,
            $row->account_group->name,
            $row->account_group2->name,
            $row->account_group3->name,
        ];
        return $fields;
    }

    public function collection()
    {
        $party = Party::with('account_group2:id,name')
            ->with('account_group3:id,name')
            ->whereNotIN('party_name', ['CASH IN HAND', 'PURCHASE ACCOUNT', 'SALE ACCOUNT'])
            ->OrderBy('code', 'asc')
            ->get();

        return $party;
    }
}