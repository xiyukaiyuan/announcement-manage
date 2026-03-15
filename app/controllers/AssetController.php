<?php
declare(strict_types=1);

namespace App\Controllers;

final class AssetController
{
    public function css(): void
    {
        require APP_ROOT . '/app/views/assets-css.php';
        exit;
    }

    public function js(): void
    {
        require APP_ROOT . '/app/views/assets-js.php';
        exit;
    }
}
