<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\BahanBaku;
use App\Models\Scm\Supplier;

class BahanBakuController extends CrudController {

    protected $model = BahanBaku::class;

    protected function getTitle()
    {
        return 'Bahan Baku';
    }

    protected function getSlug()
    {
        return 'bahan-baku';
    }

    protected function getModel()
    {
        $model = new $this->model;
        $model->select('tb_bahan_baku.*, 
                            tb_supplier.nama nama_supplier,
                            COALESCE((SELECT SUM(jumlah) FROM tb_bahan_baku_stok WHERE bahan_baku_id = tb_bahan_baku.id), 0) total_stok, 
                            CASE WHEN COALESCE((SELECT SUM(jumlah) FROM tb_bahan_baku_stok WHERE bahan_baku_id = tb_bahan_baku.id),0) < 3 THEN "<span class=\"badge badge-warning\">Warning</span>" ELSE "<span class=\"badge badge-success\">OK</span>" END as status_stok')
                ->join('tb_supplier','tb_supplier.user_id=tb_bahan_baku.supplier_id', 'LEFT');

        $level = session()->get('level');
        
        if($level == 'Supplier')
        {
            $model = $model->where('tb_bahan_baku.supplier_id = '.session()->get('id'));
        }

        return $model;
    }

    protected function columns()
    {
        $level = session()->get('level');
        
        if($level == 'Supplier')
        {
            return [
                'nama' => [
                    'label' => 'Nama'
                ],
                'satuan' => [
                    'label' => 'Satuan'
                ],
                'stok_supplier' => [
                    'label' => 'Stok'
                ],
            ];
        }
        else
        {
            return [
                'nama' => [
                    'label' => 'Nama'
                ],
                'satuan' => [
                    'label' => 'Satuan'
                ],
                'total_stok' => [
                    'label' => 'Stok'
                ],
                'stok_minimum' => [
                    'label' => 'Stok Minimum'
                ],
                'status_stok' => [
                    'label' => 'Status'
                ],
                'nama_supplier' => [
                    'label' => 'Supplier'
                ],
            ];
        }
    }

    protected function fields()
    {
        $level = session()->get('level');
        
        if($level == 'Supplier')
        {
            return [
                'stok_supplier' => [
                    'label' => 'Stok',
                    'type' => 'number',
                ],
            ];
        }
        else
        {
            $suppliers = (new Supplier)->findAll();
            $options = [0 => 'Pilih Supplier'];
            foreach($suppliers as $item)
            {
                $options[$item['user_id']] = $item['nama'];
            }
            return [
                'nama' => [
                    'label' => 'Nama',
                    'type' => 'text',
                ],
                'satuan' => [
                    'label' => 'Satuan',
                    'type' => 'text',
                ],
                'stok_minimum' => [
                    'label' => 'Stok Minimum',
                    'type' => 'number',
                ],
                'supplier_id' => [
                    'label' => 'Supplier',
                    'type' => 'select',
                    'options' => $options
                ],
            ];
        }
    }

    protected function detailButton($data)
    {
        $level = session()->get('level');
        
        if($level == 'Supplier')
        {
            return '';
        }

        return '<a href="/stok?filter[bahan_baku_id]='.$data['id'].'" class="btn btn-sm btn-info">Stok</a>';
    }

    public function detailBahanBaku($id)
    {
        header('content-type:application/json');
        $data = (new BahanBaku)->find($id);

        echo json_encode([
            'data' => $data
        ]);
        die;
    }

}