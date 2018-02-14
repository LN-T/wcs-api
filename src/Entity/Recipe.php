<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\RecipeRepository")
 */
class Recipe
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=50)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="description", type="string", length=250)
     */
    private $description;

    /**
     * @var string
     *
     * @ORM\Column(name="image", type="string", length=250)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Portion", mappedBy="recipe")
     * @ORM\JoinTable(name="portion_recipe")
     */
    private $portion;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->name;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(string $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription(string $description)
    {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * @param string $image
     */
    public function setImage(string $image)
    {
        $this->image = $image;
    }

    /**
     * Add portion.
     *
     * @param \App\Entity\Portion $portion
     *
     * @return Portion
     */
    public function addPortion(\App\Entity\Portion $portion)
    {
        $this->portion[] = $portion;

        return $this;
    }

    /**
     * Remove portion.
     *
     * @param \App\Entity\Portion $portion
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removePortion(\App\Entity\Portion $portion)
    {
        return $this->portion->removeElement($portion);
    }

    /**
     * Get portion.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPortion()
    {
        return $this->portion;
    }


}
