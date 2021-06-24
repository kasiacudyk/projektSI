<?php
/**
to * Categories entity.
 */

namespace App\Entity;

use App\Repository\CategoriesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Gedmo\Mapping\Annotation as Gedmo;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Class Categories.
 *
 * @ORM\Entity(repositoryClass="App\Repository\CategoriesRepository", repositoryClass=CategoriesRepository::class)
 *
 * @UniqueEntity(fields={"name"})
 */
class Categories
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
     * Name.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=45)
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
     * @var \Doctrine\Common\Collections\ArrayCollection|\App\Entity\Notes[] $notes Notes
     *
     * @ORM\OneToMany(targetEntity=Notes::class, mappedBy="categories")
     *
     * @ORM\OneToMany(
     *     targetEntity="App\Entity\Task",
     *     mappedBy="category",
     * )
     */
    private $notes;

    /**
     * Code.
     *
     * @var string
     *
     * @ORM\Column(type="string", length=45)
     *
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
     * Categories constructor.
     */
    public function __construct()
    {
        $this->notes = new ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return Collection|Notes[]
     */
    public function getNotes(): Collection
    {
        return $this->notes;
    }

    /**
     * @param Notes $note
     */
    public function addNote(Notes $note): void
    {
        if (!$this->notes->contains($note)) {
            $this->notes[] = $note;
            $note->setCategories($this);
        }
    }

    /**
     * @param Notes $note
     */
    public function removeNote(Notes $note): void
    {
        if ($this->notes->removeElement($note)) {
            // set the owning side to null (unless already changed)
            if ($note->getCategories() === $this) {
                $note->setCategories(null);
            }
        }
    }

    /**
     * @return string|null
     */
    public function getCode(): ?string
    {
        return $this->code;
    }

    /**
     * @param string $code
     *
     * @return $this
     */
    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }
}
