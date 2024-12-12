<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 *   Resource Type of the dataset
 *
 *   This file is part of the Data Catalog project.
 *   Copyright (C) 2016 NYU Health Sciences Library
 *
 *   This program is free software: you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation, either version 3 of the License, or
 *   (at your option) any later version.
 *
 *   This program is distributed in the hope that it will be useful,
 *   but WITHOUT ANY WARRANTY; without even the implied warranty of
 *   MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *   GNU General Public License for more details.
 *
 *   You should have received a copy of the GNU General Public License
 *   along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 *
 * @ORM\Entity
 * @ORM\Table(name="resource_types")
 */
#[UniqueEntity('resource_type')]
class ResourceType {
  /**
   * @ORM\Column(type="integer",name="resource_type_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected ?int $id = null;


  /**
   * @ORM\Column(type="string",length=128, unique=true)
   */
  protected ?string $resource_type = null;

  /**
   * @ORM\Column(type="string",length=128)
   */
  protected ?string $slug = null;
  
  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="resource_types")
   * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\Dataset>
   **/
  protected \Doctrine\Common\Collections\Collection $datasets;

    public function __construct() {
      $this->datasets = new \Doctrine\Common\Collections\ArrayCollection();
    }

  /**
   * Get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->resource_type;
  }


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set resource_type
     *
     * @param string $resource_type
     * @return ResourceType
     */
    public function setResourceType($resource_type)
    {
        $this->resource_type = $resource_type;

        return $this;
    }

    /**
     * Get resource_type
     *
     * @return string 
     */
    public function getResourceType()
    {
        return $this->resource_type;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return ResourceType
     */
    public function setSlug($slug)
    {
        $this->slug = $slug;

        return $this;
    }

    /**
     * Get slug
     *
     * @return string 
     */
    public function getSlug()
    {
        return $this->slug;
    }

    /**
     * Add datasets
     *
     * @param \App\Entity\Dataset $datasets
     * @return ResourceType
     */
    public function addDataset(\App\Entity\Dataset $datasets)
    {
        $this->datasets[] = $datasets;

        return $this;
    }

    /**
     * Remove datasets
     *
     * @param \App\Entity\Dataset $datasets
     */
    public function removeDataset(\App\Entity\Dataset $datasets)
    {
        $this->datasets->removeElement($datasets);
    }

    /**
     * Get datasets
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getDatasets()
    {
        return $this->datasets;
    }
}
