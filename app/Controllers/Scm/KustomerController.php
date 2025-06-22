<?php

namespace App\Controllers\Scm;

use App\Controllers\CrudController;
use App\Models\Scm\Kustomer;
use App\Models\User;

class KustomerController extends CrudController {

    protected $model = Kustomer::class;

    protected function getModel()
    {
        $model = new $this->model;

        $model->select('tb_kustomer.*, users.email')
                ->join('users','users.id=tb_kustomer.user_id', 'LEFT');

        return $model;
    }

    protected function getTitle()
    {
        return 'Kustomer';
    }

    protected function getSlug()
    {
        return 'kustomer';
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
        $kustomer = (new Kustomer)->find($data['id']);
        $userData = [
            'name' => $data['nama'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_BCRYPT),
        ];

        if(empty($data['password']))
        {
            unset($userData['password']);
        }

        if(empty($kustomer['user_id']))
        {
            $user = (new User)->insert($userData);
            $data['user_id'] = $user;
        }
        else
        {
            (new User)->update($kustomer['user_id'], $userData);
        }

        unset($data['email']);
        unset($data['password']);

        return $data;
    }

}