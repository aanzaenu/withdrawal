<?php

namespace App\Exports;

use App\Withdrawal;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithColumnWidths;

class WithdrawalExport implements FromCollection, WithHeadings, WithMapping, WithColumnWidths
{
    function __construct($from, $to, $operator, $banks, $status) {
        $this->from = $from;
        $this->to = $to;
        $this->operator = $operator;
        $this->banks = $banks;
        $this->status = $status;
    }
    public function collection()
    {
        $model = Withdrawal::with('banks');
        if(!empty($this->operator))
        {
            $model->where('operator', $this->operator);
        }        
        if($this->banks)
        {
            $rol = $this->banks;
            $model->whereHas('banks', function($query) use ($rol){
                $query->where('banks.id', $rol);
            });
        }
        if(!empty($this->status) || $this->status == 0)
        {
            if($this->status !== 'all')
            {
                $model->where('status', $this->status);
            }
        }
        if(!empty($this->from) && !empty($this->to))
        {
            $from = $this->from.' 00:00:01';
            $to = $this->to.' 23:23:59';
            $d_from = strtotime($from);
            $d_to = strtotime($to);
            $sfrom = date('Y-m-d H:i:s', $d_from);
            $sto = date('Y-m-d H:i:s', $d_to);

            $model->where('time', '>=', $sfrom);
            $model->where('time', '<=', $sto);
        }
        $model->orderBy('id', 'DESC');

        $lists = $model->get();
        foreach($lists as $key=> $val)
        {
            $nomer = $key+1;
            $lists[$key]->nomer = $nomer;
            if($val->status == 0)
            {
                $lists[$key]->status = 'Belum Diproses';
            }else{
                $lists[$key]->status = 'Done';
            }
            if($val->op)
            {
                $lists[$key]->operator = $val->op->name;
            }else{
                $lists[$key]->operator = '-';
            }
            $lists[$key]->created_at = '';
            $lists[$key]->updated_at = '';
            $lists[$key]->namerec = $val->bank.' A.N '.$val->bankname.' - '.$val->bankrec;
            $lists[$key]->nominal = 'Rp. '.number_format($val->nominal);
            $lists[$key]->wdbank = $val->banks()->first() ? $val->banks()->first()->name : '-';
            $lists[$key]->tanggal = $val->time ? date('d, M Y H:i', strtotime($val->time)) : '-';
            $lists[$key]->biayaadmin = 'Rp. '.number_format($val->fee);
            
        }
        return $lists;
    }
    public function map($user): array
    {
        return [
            $user->nomer,
            $user->operator,
            $user->namerec,
            $user->nominal,
            $user->wdbank,
            $user->biayaadmin,
            $user->tanggal,
            $user->status,
        ];
    }
    public function headings(): array
    {

        return [
            '#',
            'Operator',
            'Nama dan Rec',
            'Nominal',
            'Bank',
            'Biaya Admin',
            'Waktu Transfer',
            'Status'
        ];
    }
    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 20,
            'C' => 80,
            'D' => 20,
            'E' => 20,
            'F' => 20,
            'G' => 30,
            'H' => 30,
        ];
    }
}
