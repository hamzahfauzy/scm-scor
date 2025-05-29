<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\Page;
use App\Libraries\Traits\CrudTrait;

class CrudController extends BaseController
{
    use CrudTrait;

    protected $record = null;

    public function index()
    {
        $data = $this->getModel()->findAll();

        $page = new Page;
        $page->setTitle('Data ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => $this->getTitle(),
                'url' => '/'.$this->getSlug()
            ],
            [
                'label' => 'List',
                'url' => false
            ],
        ]);

        return $page->render('crud/index', [
            'data' => $data,
            'detail_button' => function($data){
                return $this->detailButton($data);
            },
            'columns' => $this->columns()
        ]);
    }

    public function show($id)
    {
        $data = $this->getModel()->find($id);

        $this->record = $data;

        $page = new Page;
        $page->setTitle('Data ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => $this->getTitle(),
                'url' => '/'.$this->getSlug()
            ],
            [
                'label' => 'Detail Data',
                'url' => false
            ],
            [
                'label' => $id,
                'url' => false
            ],
        ]);

        return $page->render('crud/show', [
            'data' => $data,
            'details' => $this->details()
        ]);
    }

    public function create()
    {
        $page = new Page;
        $page->setTitle('Tambah ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => $this->getTitle(),
                'url' => '/'.$this->getSlug()
            ],
            [
                'label' => 'Tambah Data',
                'url' => false
            ],
        ]);

        return $page->render('crud/form', [
            'data' => [],
            'fields' => $this->fields(),
            'columns' => $this->columns()
        ]);
    }
    
    public function edit($id)
    {
        $data = $this->getModel()->find($id);

        $this->record = $data;

        $page = new Page;
        $page->setTitle('Edit ' . $this->getTitle());
        $page->setSlug($this->getSlug());
        $page->setBreadcrumbs([
            [
                'label' => $this->getTitle(),
                'url' => '/'.$this->getSlug()
            ],
            [
                'label' => 'Edit Data',
                'url' => false
            ],
            [
                'label' => $id,
                'url' => false
            ],
        ]);

        return $page->render('crud/form', [
            'data' => $data,
            'fields' => $this->fields()
        ]);
    }

    public function store()
    {
        $data = $this->request->getPost();
        $data = $this->beforeInsert($data);
        if ($data = $this->getModel()->insert($data)) {
            $this->afterInsert($this->request->getPost(), $data);
            return redirect()->to('/'. $this->getSlug() . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''));
        }
        return redirect()->back()->withInput()->with('errors', $this->getModel()->errors());
    }

    public function update($id)
    {
        $data = $this->request->getPost();
        $data = $this->beforeUpdate($data);
        if ($this->getModel()->update($id, $data)) {
            $this->afterUpdate($id, $this->request->getPost());
            return redirect()->to('/'. $this->getSlug() . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''));
        }
        return redirect()->back()->withInput()->with('errors', $this->getModel()->errors());
    }

    public function delete($id)
    {
        $data = $this->beforeDelete($id);
        if ($this->getModel()->delete($id)) {
            $this->afterDelete($data);
            return redirect()->to('/'. $this->getSlug() . (isset($_GET['filter']) ? '?'.http_build_query($_GET) : ''));
        }
        return redirect()->back()->withInput()->with('errors', 'Gagal menghapus data');
    }
}