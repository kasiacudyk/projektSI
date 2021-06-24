<?php
/**
 * Category fixture.
 */

namespace App\DataFixtures;

use App\Entity\Categories;
use Doctrine\Persistence\ObjectManager;

/**
 * Class CategoriesFixtures.
 */
class CategoriesFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'categories', function ($i) {
            $categories = new Categories();
            $categories->setName($this->faker->word);

            return $categories;
        });

        $manager->flush();
    }
}
