<?php

namespace App\Libraries\Traits;

trait CrudTrait {

    protected $model;
    protected $slug = 'crud';
    protected $title = 'Crud';
    protected $canAdd = true;
    protected $canEdit = true;
    protected $canDelete = true;

    protected function getModel()
    {
        return new $this->model;
    }
    
    protected function getTitle()
    {
        return $this->title;
    }

    protected function getSlug()
    {
        return $this->slug;
    }

    protected function fields()
    {
        return [];
    }
    
    protected function columns()
    {
        return [];
    }
    
    protected function details()
    {
        return [];
    }

    protected function detailButton($data)
    {
        return false;
    }

    protected function beforeInsert($data)
    {
        return $data;
    }
    
    protected function afterInsert($request, $data)
    {
        
    }
    
    protected function beforeUpdate($data)
    {
        return $data;
    }
    
    protected function afterUpdate($id, $data)
    {

    }
    
    protected function beforeDelete($data)
    {
        return $data;
    }
    
    protected function afterDelete($data)
    {
        
    }
}