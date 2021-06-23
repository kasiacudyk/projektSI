<?php
/**
 * To Do List fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ToDoList;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;

/**
 * Class ToDoListFixtures.
 */
class ToDoListFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @param \Doctrine\Persistence\ObjectManager $manager Persistence object manager
     */
    public function loadData(ObjectManager $manager): void
    {
        $this->createMany(50, 'to_do_list', function ($i) {
            $to_do_list = new ToDoList();
            $to_do_list->setTitle($this->faker->sentence);

            $tags = $this->getRandomReferences(
                'tags',
                $this->faker->numberBetween(2, 3)
            );

            foreach($tags as $tags){
                $to_do_list->addTag($tags);
            }

            $to_do_list->setAuthor($this->getRandomReference('users'));

            return $to_do_list;
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
        return [TagsFixtures::class, UserFixtures::class];
    }
}