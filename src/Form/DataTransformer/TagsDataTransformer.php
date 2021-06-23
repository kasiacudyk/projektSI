<?php
/**
 * Tags data transformer.
 */

namespace App\Form\DataTransformer;

use App\Entity\Tags;
use App\Repository\TagsRepository;
use App\Service\TagsService;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Form\DataTransformerInterface;

/**
 * Class TagsDataTransformer.
 */
class TagsDataTransformer implements DataTransformerInterface
{
    /**
     * Tags service.
     *
     * @var \App\Service\TagsService
     */
    private $tagsService;

    /**
     * TagsDataTransformer constructor.
     *
     * @param \App\Service\TagsService $tagsService Tags service
     */
    public function __construct(TagsService $tagsService)
    {
        $this->tagsService = $tagsService;
    }

    /**
     * Transform array of tags to string of names.
     *
     * @param Collection $tags Tags entity collection
     *
     * @return string Result
     */
    public function transform($tags): string
    {
        if (null == $tags) {
            return '';
        }

        $tagNames = [];

        foreach ($tags as $tag) {
            $tagNames[] = $tag->getName();
        }

        return implode(',', $tagNames);
    }

    /**
     * Transform string of tag names into array of Tag entities.
     *
     * @param string $value String of tag names
     *
     * @return array Result
     *
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function reverseTransform($value): array
    {
        $tagName = explode(',', $value);

        $tags = [];

        foreach ($tagName as $tagName) {
            if ('' !== trim($tagName)) {
                $tag = $this->repository->findOneByName(strtolower($tagName));
                if (null == $tag) {
                    $tag = new Tags();
                    $tag->setName($tagName);
                    $this->repository->save($tag);
                }
                $tags[] = $tag;
            }
        }

        return $tags;
    }
}