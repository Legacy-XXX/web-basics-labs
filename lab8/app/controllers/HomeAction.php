<?php
declare(strict_types=1);

// Головна сторінка: макет зі списком покупок (лабораторні №2–3)
class HomeAction extends Controller
{
    public function handle(): void
    {
        $this->view->render('home', ['scripts' => ['assets/js/shopping.js']], 'Головна', 'home');
    }
}
