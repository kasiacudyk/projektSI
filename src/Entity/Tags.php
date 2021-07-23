<?php
/**
 * Tags entity.
 */

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass="App\Repository\TagsRepository")
 * @ORM\Table(name="tags")
 *
 * @UniqueEntity(fields={"name"})
 */
class Tags
{
    /**
     * Primary key.
     *
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * Code.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=64,
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\Length(
     *     min="3",
     *     max="64",
     * )
     *
     * @Gedmo\Slug(fields={"name"})
     */
    private $code;

    /**
     * Name.
     *
     * @var string
     *
     * @ORM\Column(
     *     type="string",
     *     length=64,
     * )
     *
     * @Assert\Type(type="string")
     * @Assert\NotBlank
     * @Assert\Length(
     *     min="3",
     *     max="64",
     * )
     */
    private $name;

    /**
     * Notes.
     *
     * @var ArrayCollection|Notes[] Notes
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\Notes",
     *     mappedBy="tags"
     * )
     */
    private $notes;

    /**
     * To Do List.
     *
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\ToDoList[] ToDoList
     *
     * @ORM\ManyToMany(
     *     targetEntity="App\Entity\ToDoList",
     *     mappedBy="tags")
     */
    private $todolist;

    /**
     * Tags constructor.
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
        $this->todolist = new ArrayCollection();
    }

    /**
     * Getter for Id.
     *
     * @return int|null Result
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * Getter for Code.
     *
     * @return string|null Code
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * Setter for Code.
     *
     * @param string $code Code
     */
    public function setCode(string $code): void
    {
        $this->code = $code;
    }

    /**
     * Getter for Name.
     *
     * @return string|null Name
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Setter for Name.
     *
     * @param string $name Name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * Getter for ToDoList.
     *
     * @return \Doctrine\Common\Collections\Collection|\App\Entity\ToDoList[] ToDoList collection
     */
    public function getToDoList(): Collection
    {
        return $this->todolist;
    }

    /**
     * Add ToDoList to collection.
     *
     * @param \App\Entity\ToDoList $todolist ToDoList entity
     */
    public function addToDoList(ToDoList $todolist): void
    {
        if (!$this->todolist->contains($todolist)) {
            $this->todolist[] = $todolist;
            $todolist->addTag($this);
        }
    }

    /**
     * Remove ToDoList from collection.
     *
     * @param \App\Entity\ToDoList $todolist ToDoList entity
     */
    public function removeToDoList(ToDoList $todolist): void
    {
        if ($this->todolist->contains($todolist)) {
            $this->todolist->removeElement($todolist);
            $todolist->removeTag($this);
        }
    }
}
