<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;



/**
 * The subject of study covered by this dataset
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
#[UniqueEntity('subject_of_study')]
#[ORM\Entity]
#[ORM\Table(name: 'subject_of_study')]
class SubjectOfStudy {
  #[ORM\Column(type: 'integer', name: 'subject_of_study_id')]
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'AUTO')]
  protected ?int $id = null;

  #[ORM\Column(type: 'string', length: 255, unique: true)]
  protected ?string $subject_of_study = null;

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  protected ?string $species = null;

  #[ORM\Column(type: 'string', length: 255, nullable: true)]
  protected ?string $tissue_cell_line = null;

  #[ORM\Column(type: 'string', length: 256)]
  protected ?string $slug = null;


  /**
   * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\Dataset>
   **/
  #[ORM\ManyToMany(targetEntity: \Dataset::class, mappedBy: 'subject_of_study')]
  protected \Doctrine\Common\Collections\Collection $datasets;

  /**
   * get name for display
   *
   * @return string
   */
  public function getDisplayName() {
    return $this->subject_of_study;
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
     * Set subject_of_study
     *
     * @param string $subject_of_study
     * @return SubjectOfStudy
     */
    public function setSubjectOfStudy($subject_of_study)
    {
        $this->subject_of_study = $subject_of_study;

        return $this;
    }

    /**
     * Get subject_of_study
     *
     * @return string 
     */
    public function getSubjectOfStudy()
    {
        return $this->subject_of_study;
    }

    /**
     * Set species
     *
     * @param string $species
     * @return SubjectOfStudy
     */
    public function setSpecies($species)
    {
        $this->species = $species;

        return $this;
    }

    /**
     * Get tissue_cell_line
     *
     * @return string 
     */
    public function getTissueCellLine()
    {
        return $this->tissue_cell_line;
    }

    /**
     * Set tissue_cell_line
     *
     * @param string $tissue_cell_line
     * @return SubjectOfStudy
     */
    public function setTissueCellLine($tissue_cell_line)
    {
        $this->tissue_cell_line = $tissue_cell_line;

        return $this;
    }

    /**
     * Get species
     *
     * @return string 
     */
    public function getSpecies()
    {
        return $this->species;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return SubjectOfStudy
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
     * @return SubjectOfStudy
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
        return ['subject_of_study'=>$this->subject_of_study, 'species'=>$this->species, 'tissue_cell_line' => $this->tissue_cell_line];
    }
}
