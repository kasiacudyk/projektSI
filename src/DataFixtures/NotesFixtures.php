<?php
/**
 * Notes fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Notes;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class NotesFixtures.
 */
class NotesFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'notes', function ($i) {
            $notes = new Notes();
            $notes->setTitle($this->faker->sentence);
            $notes->setDescription($this->faker->paragraph(10));
            $notes->setCategories($this->getRandomReference('categories'));

            return $notes;
        });

        $manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return array Array of dependencies
     */
    public function getDependencies(): array
    {
        return [CategoriesFixtures::class];
    }
}