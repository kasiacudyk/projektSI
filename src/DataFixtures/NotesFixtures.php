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
            $notes->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-1 days'));
            $notes->setCategories($this->getRandomReference('categories'));

            $notes->setAuthor($this->getRandomReference('users'));

            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(1, 3)
            );

            foreach ($tags as $tags) {
                $notes->addTag($tags);
            }

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
        return [CategoriesFixtures::class, UserFixtures::class, TagsFixtures::class];
    }
}
