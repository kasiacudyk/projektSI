<?php
/**
 * ToDoList fixtures.
 */

namespace App\DataFixtures;

use App\Entity\ToDoList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

/**
 * Class ToDoListFixtures.
 */
class ToDoListFixtures extends Fixture
{
    /**
     * Faker.
     *
     * @var \Faker\Generator
     */
    protected $faker;

    /**
     * Persistence object manager.
     *
     * @var ObjectManager
     */
    protected $manager;

    /**
     * Load.
     *
     * @param ObjectManager $manager Persistence object manager
     */
    protected function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        for ($i = 0; $i < 10; ++$i) {
            $toDoList = new ToDoList();
            $toDoList->setTitle($this->faker->sentence);
            $toDoList->setDescription($this->faker->paragraph(10));

            $this->manager->persist($this);
        }

        $manager->flush();
    }
}