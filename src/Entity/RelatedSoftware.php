<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * Software used to produce or analyze the dataset
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
 * @ORM\Table(name="related_software")
 */
class RelatedSoftware {
  /**
   * @ORM\Column(type="integer",name="related_software_id")
   * @ORM\Id
   * @ORM\GeneratedValue(strategy="AUTO")
   */
  protected ?int $id = null;

  /**
   * @ORM\Column(type="string",length=128, unique=false)
   */
  protected ?string $software_name = null;

  /**
   * @ORM\Column(type="string",length=512, unique=false, nullable=true)
   */
  protected ?string $software_description = null;

  /**
   * @ORM\Column(type="string",length=512, unique=false, nullable=true)
   */
  protected ?string $software_version = null;

  /**
   * @ORM\Column(type="string",length=512, unique=false, nullable=true)
   */
  protected ?string $software_url = null;

  /**
   * @ORM\Column(type="string",length=256)
   */
  protected ?string $slug = null;


  /**
   * @ORM\ManyToMany(targetEntity="Dataset", mappedBy="related_software")
   * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\Dataset>
   **/
  protected \Doctrine\Common\Collections\Collection $datasets;

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
  	
  	$sdn=$this->software_name;
  	if ($this->software_version !== null && $this->software_version !== '' && $this->software_version !== '0') {
  		$sdn.=", ".$this->software_version;
  	}
  
    return $sdn;
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
     * Set software_name
     *
     * @param string $software_name
     * @return RelatedSoftware
     */
    public function setSoftwareName($software_name)
    {
        $this->software_name = $software_name;

        return $this;
    }

    /**
     * Get software_name
     *
     * @return string 
     */
    public function getSoftwareName()
    {
        return $this->software_name;
    }

    /**
     * Get software_version
     *
     * @return string 
     */
    public function getSoftwareVersion()
    {
        return $this->software_version;
    }

    /**
     * Set software_version
     *
     * @param string $software_version
     * @return RelatedSoftware
     */
    public function setSoftwareVersion($software_version)
    {
        $this->software_version = $software_version;

        return $this;
    }


    /**
     * Set software_description
     *
     * @param string $software_description
     * @return RelatedSoftware
     */
    public function setSoftwareDescription($software_description)
    {
        $this->software_description = $software_description;

        return $this;
    }

    /**
     * Get software_description
     *
     * @return string 
     */
    public function getSoftwareDescription()
    {
        return $this->software_description;
    }

    /**
     * Set software_url
     *
     * @param string $software_url
     * @return RelatedSoftware
     */
    public function setSoftwareUrl($software_url)
    {
        $this->software_url = $software_url;

        return $this;
    }

    /**
     * Get software_url
     *
     * @return string 
     */
    public function getSoftwareUrl()
    {
        return $this->software_url;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return RelatedSoftware
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
 
    public function __construct() {
      $this->datasets = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add datasets
     *
     * @return RelatedSoftware
     */
    public function addDataset(\App\Entity\Dataset $datasets)
    {
        $this->datasets[] = $datasets;

        return $this;
    }

    /**
     * Remove datasets
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

    /**
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
        return ['software_name'=>$this->software_name, 'software_description'=>$this->software_description, 'software_url'=>$this->software_url];
    }

}
