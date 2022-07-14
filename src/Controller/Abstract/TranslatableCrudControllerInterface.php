<?php

namespace App\Controller\Abstract;

interface TranslatableCrudControllerInterface extends CrudControllerInterface
{

    public function setTranslatableEntityFieldsFromForm($form);

}