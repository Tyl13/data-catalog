<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * An entity describing the subject areas that are covered by our datasets
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
 */
#[UniqueEntity('subject_domain')]
#[ORM\Entity]
#[ORM\Table(name: 'subject_domains')]
class SubjectDomain {
  #[ORM\Column(type: 'integer', name: 'subject_domain_id')]
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'AUTO')]
  protected ?int $id = null;

  #[ORM\Column(type: 'string', length: 255, unique: true)]
  protected ?string $subject_domain = null;

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  protected ?string $mesh_code = null;

  #[ORM\Column(type: 'string', length: 256)]
  protected ?string $slug = null;

  /**
   * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\Dataset>
   **/
  #[ORM\ManyToMany(targetEntity: \Dataset::class, mappedBy: 'subject_domains')]
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
    return $this->subject_domain;
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
     * Set subject_domain
     *
     * @param string $subjectDomain
     * @return SubjectDomain
     */
    public function setSubjectDomain($subjectDomain)
    {
        $this->subject_domain = $subjectDomain;

        return $this;
    }

    /**
     * Get subject_domain
     *
     * @return string 
     */
    public function getSubjectDomain()
    {
        return $this->subject_domain;
    }

    /**
     * Set mesh_code
     *
     * @param string $meshCode
     * @return SubjectDomain
     */
    public function setMeshCode($meshCode)
    {
        $this->mesh_code = $meshCode;

        return $this;
    }

    /**
     * Get mesh_code
     *
     * @return string 
     */
    public function getMeshCode()
    {
        return $this->mesh_code;
    }


    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectDomain
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
     * @return SubjectDomain
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
        return ['subject_domain'=>$this->subject_domain, 'mesh_code'=>$this->mesh_code];
    }
}
