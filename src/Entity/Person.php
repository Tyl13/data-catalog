<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Doctrine\Common\Collections\ArrayCollection;



/**
 * An entity describing a person
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
#[UniqueEntity('kid')]
#[UniqueEntity('slug')]
#[ORM\Entity]
#[ORM\Table(name: 'person')]
class Person {
  #[ORM\Column(type: 'integer', name: 'person_id')]
  #[ORM\Id]
  #[ORM\GeneratedValue(strategy: 'AUTO')]
  protected ?int $id = null;

  #[ORM\Column(type: 'string', length: 128)]
  protected ?string $full_name = null;

  #[ORM\Column(type: 'string', length: 128, unique: true)]
  protected ?string $slug = null;


  #[ORM\Column(type: 'string', length: 128, nullable: true)]
  protected ?string $last_name = null;

  #[ORM\Column(type: 'string', length: 128, nullable: true)]
  protected ?string $first_name = null;


  #[ORM\Column(type: 'string', length: 16, nullable: true, unique: true)]
  protected ?string $kid = null;


  #[ORM\Column(type: 'string', length: 128, nullable: true, unique: true)]
  protected ?string $orcid_id = null;


  #[ORM\Column(type: 'string', length: 1028, nullable: true)]
  protected ?string $bio_url = null;


  #[ORM\Column(type: 'string', length: 256, nullable: true)]
  protected ?string $email = null;


  #[ORM\Column(type: 'boolean', options: ['default' => false], nullable: true)]
  protected ?bool $works_here = null;


  /**
   * @var \Doctrine\Common\Collections\Collection<int, \App\Entity\PersonAssociation>
   */
  #[ORM\OneToMany(targetEntity: \PersonAssociation::class, mappedBy: 'person')]
  protected \Doctrine\Common\Collections\Collection $dataset_associations;

  #[ORM\Column(type: 'boolean', length: 128)]
  protected ?bool $is_institution_author = false;

	/**
	 * Constructor
	 */
    public function __construct()
    {
      $this->dataset_associations = new ArrayCollection();
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
     * Set full_name
     *
     * @param string $fullName
     * @return Person
     */
    public function setFullName($fullName)
    {
        $this->full_name = $fullName;

        return $this;
    }

		/**
		 * Get name for display
		 *
		 * @return string
		 */
		public function getDisplayName() {
			return $this->full_name;
		}

    /**
     * Get full_name
     *
     * @return string 
     */
    public function getFullName()
    {
        return $this->full_name;
    }

    /**
     * Set last_name
     *
     * @param string $lastName
     * @return Person
     */
    public function setLastName($lastName)
    {
        $this->last_name = $lastName;

        return $this;
    }

    /**
     * Get last_name
     *
     * @return string 
     */
    public function getLastName()
    {
        return $this->last_name;
    }

    /**
     * Set first_name
     *
     * @param string $firstName
     * @return Person
     */
    public function setFirstName($firstName)
    {
        $this->first_name = $firstName;

        return $this;
    }

    /**
     * Get first_name
     *
     * @return string 
     */
    public function getFirstName()
    {
        return $this->first_name;
    }

    /**
     * Set kid
     *
     * @param string $kid
     * @return Person
     */
    public function setKid($kid)
    {
        $this->kid = $kid;

        return $this;
    }

    /**
     * Get kid
     *
     * @return string 
     */
    public function getKid()
    {
        return $this->kid;
    }

    /**
     * Set bio_url
     *
     * @param string $bioUrl
     * @return Person
     */
    public function setBioUrl($bioUrl)
    {
        $this->bio_url = $bioUrl;

        return $this;
    }

    /**
     * Get bio_url
     *
     * @return string 
     */
    public function getBioUrl()
    {
        return $this->bio_url;
    }


    /**
     * Set email
     *
     * @param string $email
     * @return Person
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

   /**
     * Set is institution author
     *
     * @param string $institution
     * @return Person
     */
    public function setIsInstitutionAuthor($institution)
    {
        $this->is_institution_author = $institution;
        return $this;
    }

    /**
     * Get is institution author
     *
     * @return string 
     */
    public function getIsInstitutionAuthor()
    {
        return $this->is_institution_author;
    }

    /**
     * Set slug
     *
     * @param string $slug
     * @return Person
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
     * Set orcid_id
     *
     * @param string $orcidId
     * @return Person
     */
    public function setOrcidId($orcidId)
    {
        $this->orcid_id = $orcidId;

        return $this;
    }

    /**
     * Get orcid_id
     *
     * @return string 
     */
    public function getOrcidId()
    {
        return $this->orcid_id;
    }


    /**
     * Set works_here
     *
     * @param string $worksHere
     * @return Person
     */
    public function setWorksHere($worksHere)
    {
        $this->works_here = $worksHere;

        return $this;
    }


    /**
     * Get works_here
     *
     * @return string 
     */
    public function getWorksHere()
    {
        return $this->works_here;
    }


    public function getDatasetAssociations()
    {
      return $this->dataset_associations->toArray();
    }

    public function addDatasetAssociation(PersonAssociation $assoc) 
    {
      if (!$this->dataset_associations->contains($assoc)) {
        $this->dataset_associations->add($assoc);
      }

      return $this;
    }

    public function removeDatasetAssociation(PersonAssociation $assoc)
    {
      if ($this->dataset_associations->contains($assoc)) {
        $this->dataset_associations->removeElement($assoc);
      }

      return $this;
    }

    public function getAssociatedDatasets()
    {
      return array_map(
        fn($association) => $association->getDataset(),
        $this->dataset_associations->toArray()
      );
    }

    /**
     * Serialize all properties
     *
     * @return array
     */
    public function getAllProperties() {
      return ['full_name'=>$this->full_name, 'last_name'=>$this->last_name, 'first_name'=>$this->first_name, 'orcid_id'=>$this->orcid_id, 'bio_url'=>$this->bio_url, 'email'=>$this->email, 'works_here'=>$this->works_here];
    }
}
