<?php

namespace App\Tests;

use App\Entity\Category;
use Doctrine\DBAL\Exception\UniqueConstraintViolationException;

class CategoryWebTest extends DatabaseDependantWebTestCase
{
    /** @test */
    public function differentCategoriesCanBeAdded()
    {
        $category1 = new Category();
        $category1->setTextID('home');

        $category2 = new Category();
        $category2->setTextID('about-me');


        $this->entityManager->persist($category1);
        $this->entityManager->persist($category2);
        $this->entityManager->flush();

        $categoryRepository = $this->entityManager->getRepository(
            Category::class
        );
        $category1Record = $categoryRepository->findOneBy(['textID' => 'home']);
        $category2Record = $categoryRepository->findOneBy(['textID' => 'about-me']);


        $this->assertEquals('home', $category1Record->getTextID(),);
        $this->assertEquals('about-me', $category2Record->getTextID(),);
    }

    /** @test */
    public function categoryIsUniqueByTextID()
    {
        $this->expectException(UniqueConstraintViolationException::class);

        $category1 = new Category();
        $category1->setTextID('about-me');

        $category2 = new Category();
        $category2->setTextID('about-me');


        $this->entityManager->persist($category1);
        $this->entityManager->persist($category2);
        $this->entityManager->flush();
    }

}