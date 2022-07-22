<?php

namespace App\Tests\Entity;

use App\Tests\DatabaseDependantWebTestCase;

class UserTest extends DatabaseDependantWebTestCase
{

    /** @test */
    public function adminUserCanBeAddedToDatabase()
    {
        $adminUser = $this->getAdminTestUser('admin1');
        self::assertEquals('admin', $adminUser->getUsername());
        self::assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $adminUser->getRoles());
    }
}
