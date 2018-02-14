<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PortionRepository")
 */
class Portion
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var float
     *
     * @ORM\Column(name="quantity", type="float")
     */
    private $quantity;

    /**
     * @var string
     *
     * @ORM\Column(name="unity", type="string", length=50)
     */
    private $unity;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Ingredient", mappedBy="portion", cascade={"all"})
     * @ORM\JoinTable(name="ingredient_portion")
     */
    private $ingredient;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Recipe", inversedBy="portion")
     * @ORM\JoinTable(name="portion_recipe")
     */
    private $recipe;

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->quantity . ' ' . $this->unity . ' ' . $this->ingredient ;
    }

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return float
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param float $quantity
     */
    public function setQuantity(float $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return string
     */
    public function getUnity()
    {
        return $this->unity;
    }

    /**
     * @param string $unity
     */
    public function setUnity(string $unity)
    {
        $this->unity = $unity;
    }

    /**
     * @return mixed
     */
    public function getIngredient()
    {
        return $this->ingredient;
    }

    /**
     * @param mixed $ingredient
     */
    public function setIngredient($ingredient)
    {
        $this->ingredient = $ingredient;
    }

    /**
     * Add recipe.
     *
     * @param \App\Entity\Recipe $recipe
     *
     * @return Recipe
     */
    public function addRecipe(\App\Entity\Recipe $recipe)
    {
        $this->recipe[] = $recipe;

        return $this;
    }

    /**
     * Remove recipe.
     *
     * @param \App\Entity\Recipe $recipe
     *
     * @return boolean TRUE if this collection contained the specified element, FALSE otherwise.
     */
    public function removeRecipe(\App\Entity\Recipe $recipe)
    {
        return $this->recipe->removeElement($recipe);
    }

    /**
     * Get recipe.
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getRecipe()
    {
        return $this->recipe;
    }

}
