<?php

declare(strict_types=1);

namespace App\Controller;

use Cake\Cache\Cache;
use Cake\Http\Response;
use Wsi\PrestashopApi\Dmix\CategoriesResource;

class HomeController extends AppController
{
    public function display(): ?Response
    {
        $categories = (new CategoriesResource())->getBranchedCategories();

        $this->set(compact('categories'));

        return $this->render('home');
    }
}
