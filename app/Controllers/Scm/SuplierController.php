<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Supplier;
use App\Models\User;

class SuplierController extends CrudController {

    protected $model = Supplier::class;

    protected function getModel()
    {
        $model = new $this->model;

        $model->select('tb_supplier.*,users.email')
                ->join('users','users.id=tb_supplier.user_id', 'LEFT');

        return $model;
    }

    protected function getTitle()
    {
        return 'Suplier';
    }

    protected function getSlug()
    {
        return 'suplier';
    }

    protected function columns()
    {
        return [
            'nama' => [
                'label' => 'Nama'
            ],
            'no_hp' => [
                'label' => 'No. HP'
            ],
            'alamat' => [
                'label' => 'Alamat'
            ],
            'email' => [
                'label' => 'Email'
            ],
        ];
    }

    protected function details()
    {
        return [];
    }

    protected function fields()
    {
        $columns = [
            'nama' => [
                'label' => 'Nama',
                'type' => 'text',
            ],
            'no_hp' => [
                'label' => 'No. HP',
                'type' => 'text',
            ],
            'alamat' => [
                'label' => 'Alamat',
                'type' => 'textarea',
            ],
            'email' => [
                'label' => 'Email',
                'type' => 'text',
            ],
            'password' => [
                'label' => 'Password',
                'type' => 'password',
            ],
        ];

        return $columns;
    }

    protected function beforeInsert($data)
    {
        $user = (new User)->insert([
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ]);

        unset($data['email']);
        unset($data['password']);

        $data['user_id'] = $user;

        return $data;
    }
    
    protected function beforeUpdate($data)
    {
        $supplier = (new Supplier)->find($data['id']);
        $userData = [
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ];

        if(empty($data['password']))
        {
            unset($userData['password']);
        }

        if(empty($supplier['user_id']))
        {
            $data['user_id'] = (new User)->insert($userData);
        }
        else
        {
            (new User)->update($supplier['user_id'], $userData);
        }

        unset($data['email']);
        unset($data['password']);

        return $data;
    }

}