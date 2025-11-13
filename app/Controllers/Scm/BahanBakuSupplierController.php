<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\BahanBaku;
use App\Models\Scm\BahanBakuSupplier;
use App\Models\Scm\Supplier;

class BahanBakuSupplierController extends CrudController {

    protected $model = BahanBakuSupplier::class;

    protected function getTitle()
    {
        return 'Bahan Baku';
    }

    protected function getSlug()
    {
        return 'bahan-baku-supplier';
    }

    protected function getModel()
    {
        $model = new $this->model;
        $model->select('
                        tb_bahan_baku_supplier.*, tb_bahan_baku.nama nama_bahan_baku, tb_bahan_baku.stok_minimum,
                        COALESCE((SELECT SUM(jumlah) FROM tb_bahan_baku_stok WHERE bahan_baku_id = tb_bahan_baku.id), 0) total_stok, 
                        CASE WHEN COALESCE((SELECT SUM(jumlah) FROM tb_bahan_baku_stok WHERE bahan_baku_id = tb_bahan_baku.id),0) < tb_bahan_baku.stok_minimum THEN "<span class=\"badge badge-warning\">Warning</span>" ELSE "<span class=\"badge badge-success\">OK</span>" END as status_stok
                    ')
                ->join('tb_bahan_baku','tb_bahan_baku.id=tb_bahan_baku_supplier.bahan_baku_id', 'LEFT');

        $level = session()->get('level');
        
        if($level == 'Supplier')
        {
            $model = $model->where('tb_bahan_baku_supplier.supplier_id = '.session()->get('id'));
        }

        return $model;
    }

    protected function columns()
    {
        return [
            'nama_bahan_baku' => [
                'label' => 'Nama'
            ],
            'stok' => [
                'label' => 'Stok Supplier'
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
        ];
    }

    protected function fields()
    {   
        $suppliers = (new BahanBaku)->findAll();
        $options = [0 => 'Pilih Bahan Baku'];
        foreach($suppliers as $item)
        {
            $options[$item['id']] = $item['nama'];
        }
        $fields = [
            'bahan_baku_id' => [
                'label' => 'Nama',
                'type' => 'select',
                'options' => $options
            ],
            'stok' => [
                'label' => 'Stok',
                'type' => 'number',
            ],
        ];

        return $fields;
    }

    protected function beforeInsert($data)
    {
        $data['supplier_id'] = session()->get('id');
        return $data;
    }

    public function detailBahanBaku($id, $supplier_id)
    {
        header('content-type:application/json');
        $supplier = (new Supplier)->find($supplier_id);
        $data = (new BahanBakuSupplier)->where('bahan_baku_id', $id)->where('supplier_id', $supplier['user_id'])->first();

        echo json_encode([
            'data' => $data
        ]);
        die;
    }

}