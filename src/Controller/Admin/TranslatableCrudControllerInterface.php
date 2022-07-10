<?php

namespace App\Controller\Admin;

interface TranslatableCrudControllerInterface extends CrudControllerInterface
{

    public function setTranslatableEntityFieldsFromForm($form);

}