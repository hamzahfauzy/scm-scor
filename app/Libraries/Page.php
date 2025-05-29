<?php

namespace App\Libraries;

class Page {

    private $title;
    private $slug;
    private $breadcrumbs;

    function setTitle($title)
    {
        return $this->title = $title;
    }
    
    function setSlug($slug)
    {
        return $this->slug = $slug;
    }

    function setBreadcrumbs($breadcrumbs)
    {
        return $this->breadcrumbs = $breadcrumbs;
    }

    public function render($viewPath, $params = [])
    {
        $params = array_merge($params, [
            'page_title' => $this->title,
            'page_slug' => $this->slug,
            'page_breadcrumbs' => $this->breadcrumbs,
        ]);
        return view($viewPath, $params);
    }

}