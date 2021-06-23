<?php
/**
 * Tags fixture.
 */

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Persistence\ObjectManager;

/**
 * Class TagsFixtures.
 */
class TagsFixtures extends AbstractBaseFixtures
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(10, 'tags', function ($i) {
            $tags = new Tags();
            $tags->setName($this->faker->word);

            return $tags;
        });

        $manager->flush();
    }
}