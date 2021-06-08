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
    public function load(ObjectManager $manager): void
    {
        $this->faker = Factory::create();
        $this->manager = $manager;

        for ($i = 0; $i < 10; ++$i) {
            $to_do_list = new ToDoList();
            $to_do_list->setTitle($this->faker->sentence);
            $to_do_list->setDescription($this->faker->paragraph(10));

            $this->manager->persist($to_do_list);
        }

        $manager->flush();
    }
}