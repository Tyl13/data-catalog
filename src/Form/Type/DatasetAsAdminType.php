<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Doctrine\ORM\EntityRepository;

use Symfony\Component\Form\Extension\Core\ChoiceList\ChoiceList;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Form\DataTransformer\SubjectKeywordToStringTransformer;
use Doctrine\ORM\EntityManager;



/**
 * Form builder for dataset entry as an Admin user
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
class DatasetAsAdminType extends AbstractType {

  protected $years;
  protected $yearsIncludingPresent;
  protected $options;

  public function __construct(array $options = []) {
    $this->years = range(date('Y'),1790);
    $yearList = range(date('Y'),1790);
    array_unshift($yearList, "Present");
    $this->yearsIncludingPresent = array_combine($yearList, $yearList);

    $resolver = new OptionsResolver();
    $this->configureOptions($resolver);

    $this->options = $resolver->resolve($options);
  }
  
  /**
   * Build the form
   *
   * @param FormBuilderInterface
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    //identifying information
    $builder->add('dataset_uid', TextType::class, ['disabled' => true, 'data'     => $options['datasetUid'], 'attr'     => ['data-tak-id-element' => '1'], 'label'    => 'Dataset ID']);
    $builder->add('title', TextType::class, ['required' => true, 'label'    => 'Dataset Title']);
    $builder->add('dataset_alternate_titles', CollectionType::class, ['entry_type'      => DatasetAlternateTitleType::class, 'required' => false, 'label'     => 'Alternate Titles', 'by_reference'=>false, 'prototype' => true, 'allow_delete' => true, 'allow_add' => true]);
    $builder->add('origin',ChoiceType::class,['required'=> true, 'label'   => 'Origin', 'choices' => ['Internal'=>'Internal', 'External'=>'External'], 'expanded'=>true, 'label_attr'=>['class'=>'form-choicetype']]);
    $builder->add('description',  TextareaType::class, ['required' => true, 'attr'=>['rows'=>'7', 'placeholder'=>'Please provide a brief description of the dataset'], 'label'    => 'Description']);
    $builder->add('published', ChoiceType::class, ['required' => false, 'expanded' => true, 'empty_data' => false, 'placeholder'=>false, 'label'    => 'Published to Data Catalog?', 'choices'=> ['Yes' => true, 'Not yet'=>false]]);
    $builder->add('publishers', EntityType::class, ['class'   => 'App:Publisher', 'choice_label'=> 'publisher_name', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.publisher_name','ASC'), 'attr'=>['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Publishers']);
    $builder->add('access_restrictions', EntityType::class, ['class'    => 'App:AccessRestriction', 'choice_label' => 'restriction', 'attr'=>['style'=>'width:100%'], 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.restriction','ASC'), 'required' => false, 'by_reference'=>false, 'multiple' => true, 'label'     => 'Access Restrictions']);
    $builder->add('access_instructions',  TextareaType::class, ['attr'=>['rows'=>'7', 'placeholder'=>'Provide any information on restrictions or conditions for gaining access to data'], 'label'    => 'Access Instructions']);
    //accession information
    $builder->add('data_locations', CollectionType::class, ['entry_type'      => DataLocationType::class, 'required' => true, 'by_reference'=>false, 'label'     => 'Data Location', 'prototype' => true, 'allow_delete' => true, 'allow_add' => true]);
    $builder->add('pubmed_search', TextType::class, ['required' => false, 'label'    => 'PubMed Search URL']);
    $builder->add('date_archived', DateType::class, ['years'  => $this->years, 'required' => false, 'label'    => 'Date Archived']);
    $builder->add('other_resources', CollectionType::class, ['entry_type' => OtherResourceType::class, 'required' => true, 'by_reference'=>false, 'label'     => 'Other Resources', 'prototype' => true, 'allow_delete' => true, 'allow_add' => true]);
    //technical details
    $builder->add('related_equipment', EntityType::class, ['class'   => 'App:RelatedEquipment', 'choice_label'=> 'related_equipment', 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.related_equipment','ASC'), 'required' => false, 'attr'    => ['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Equipment used to collect/create the dataset']);
    $builder->add('related_software', EntityType::class, ['class'   => 'App:RelatedSoftware', 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.software_name','ASC'), 'choice_label' => fn($related_software) => $related_software->getDisplayName(), 'required' => false, 'attr'    => ['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Software used to create, collect or analyze the dataset']);
    $builder->add('dataset_formats', EntityType::class, ['class'   => 'App:DatasetFormat', 'choice_label'=> 'format', 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.format','ASC'), 'required' => false, 'attr'    => ['id'=>'dataset_subject_population_ages', 'style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Dataset File Format']);

		$rt_array=['class'   => 'App:ResourceType', 'choice_label'=> 'resource_type', 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.resource_type','ASC'), 'required' => false, 'attr'    => ['id'=>'dataset_resource_types', 'style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Resource Type'];

    //if ($options['data']->getResourceTypes() instanceof \Doctrine\Common\Collections\ArrayCollection) {
    //  $rt_array['data'] = new \Doctrine\Common\Collections\ArrayCollection(array($this->container->get('doctrine.orm.entity_manager')->getReference("App:ResourceType", 11)));
		//}

    $builder->add('resource_types', EntityType::class, $rt_array);

		//print_r(($options['data']->getResourceTypes() instanceof \Doctrine\Common\Collections\ArrayCollection));
		// print_r($this->container->get('doctrine.orm.entity_manager')->getReference("AppBundle:ResourceType", 4));

    $builder->add('dataset_size', TextType::class, ['required' => false, 'label'    => 'Dataset Size']);
    $builder->add('data_collection_instruments', EntityType::class, ['class'   => 'App:DataCollectionInstrument', 'choice_label'=> 'data_collection_instrument_name', 'required' => false, 'attr'=>['style'=>'width:100%', 'placeholder'=>''], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Data Collection Instruments']);
    $builder->add('data_types', EntityType::class, ['class'   => 'App:DataType', 'choice_label' => 'data_type', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.data_type','ASC'), 'attr'=>['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Data Types']);
    //people and relations
    $builder->add('publications', EntityType::class, ['class' => 'App:Publication', 'choice_label'=>'citation', 'required' => false, 'attr'=>['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Publications describing the collection or use of the dataset']);
    $builder->add('awards', EntityType::class, ['class'   => 'App:Award', 'choice_label'=> 'award', 'required' => false, 'attr'    => ['id'=>'dataset_awards', 'style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Grants']);
    $builder->add('projects', EntityType::class, ['class'   => 'App:Project', 'choice_label'=> 'project_name', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.project_name','ASC'), 'attr'=>['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Related Projects']);
    $builder->add('related_datasets', CollectionType::class, ['entry_type' => DatasetRelationshipType::class, 'required' => true, 'by_reference'=>false, 'prototype' => true, 'label'     => 'Related Datasets', 'allow_delete' => true, 'allow_add' => true]);
    //content information
    $builder->add('authorships', CollectionType::class, ['entry_type' => PersonAssociationType::class, 'prototype' => true, 'required'=>true, 'by_reference'=>false, 'label'=>'Authors', 'allow_delete'=>true, 'allow_add'=>true]);
    $builder->add('local_experts', EntityType::class, ['class' => 'App:Person', 'choice_label'=>'full_name', 'required'=>false, 'attr'=>['style'=>'width:100%'], 'multiple'=>true, 'by_reference'=>false, 'label'=>'Local Experts']);
    $builder->add('subject_domains', EntityType::class, ['class' => 'App:SubjectDomain', 'choice_label'=>'subject_domain', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.subject_domain','ASC'), 'attr'=>['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Subject Domains']);
    $builder->add('subject_start_date', ChoiceType::class, ['choices'  => $this->yearsIncludingPresent, 'required' => false, 'label'    => 'Year Data Collection Started']);
    $builder->add('subject_end_date', ChoiceType::class, ['choices'  => $this->yearsIncludingPresent, 'required' => false, 'label'    => 'Year Data Collection Ended']);
    $builder->add('subject_genders', EntityType::class, ['class'      => 'App:SubjectGender', 'choice_label'   => 'subject_gender', 'multiple'   => true, 'expanded'   => true, 'required' => false, 'by_reference'=>false, 'label'     => 'Subject Genders']);
    $builder->add('subject_sexes', EntityType::class, ['class'      => 'App:SubjectSex', 'choice_label'   => 'subject_sex', 'multiple'   => true, 'expanded'   => true, 'required' => false, 'by_reference'=>false, 'label'     => 'Subject Sexes']);
    $builder->add('subject_population_ages', EntityType::class, ['class'   => 'App:SubjectPopulationAge', 'choice_label'=> 'age_group', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.age_group','ASC'), 'attr'=>['style'=>'width:100%'], 'multiple' => true, 'by_reference'=>false, 'label'     => 'Subject Population Age']);
    $builder->add('subject_geographic_areas', EntityType::class, ['class'   => 'App:SubjectGeographicArea', 'attr'=>['style'=>'width:100%'], 'choice_label'=> 'geographic_area_name', 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.geographic_area_name','ASC'), 'required' => false, 'multiple'=> true, 'by_reference'=>false, 'label'     => 'Subject Geographic Areas']);
    $builder->add('subject_geographic_area_details', EntityType::class, ['class'   => 'App:SubjectGeographicAreaDetail', 'attr'=>['style'=>'width:100%'], 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.geographic_area_detail_name','ASC'), 'choice_label'=> 'geographic_area_detail_name', 'required' => false, 'multiple'=> true, 'by_reference'=>false, 'label'     => 'Subject Geographic Area Details']);
    $builder->add('study_types', EntityType::class, ['class'    => 'App:StudyType', 'choice_label' => 'study_type', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.study_type','ASC'), 'expanded' => true, 'multiple' => true, 'attr'=>['style'=>'width:100%'], 'by_reference'=>false, 'label'     => 'Study Type']);
    $builder->add('subject_of_study', EntityType::class, ['class'    => 'App:SubjectOfStudy', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.subject_of_study','ASC'), 'choice_label' => function ($subject_of_study) {
      $sos=$subject_of_study->getSubjectofStudy();
      if ($subject_of_study->getSpecies()) {
      	$sos.=", ".$subject_of_study->getSpecies();
      }
      if ($subject_of_study->getTissueCellLine()) {
      	$sos.=", ".$subject_of_study->getTissueCellLine();
      }
      return $sos;
  	}, 'multiple' => true, 'attr'=>['style'=>'width:100%'], 'by_reference'=>false, 'label'     => 'Subject of Study']);
    
    $builder->add('subject_keywords', EntityType::class, ['class'   => 'App:SubjectKeyword', 'choice_label'=> 'keyword', 'required' => false, 'query_builder'=> fn(EntityRepository $er) => $er->createQueryBuilder('u')->orderBy('u.keyword','ASC'), 'multiple' => true, 'attr'=>['style'=>'width:100%'], 'by_reference'=>false, 'label'     => 'Subject Keywords']);
     
    $builder->add('erd_url', TextType::class, ['required' => false, 'label'    => 'ERD URL']);
    $builder->add('library_catalog_url', TextType::class, ['required' => false, 'label'    => 'Library Catalog URL']);
    $builder->add('licensing_details',  TextareaType::class, ['required' => false, 'label'    => 'Licensing Details']);
    $builder->add('license_expiration_date', DateType::class, ['required' => false, 'label'    => 'License Expiration Date']);
    $builder->add('subscriber', TextType::class, ['required' => false, 'label'    => 'Subscriber']);
    $builder->add('archived', ChoiceType::class, ['required' => false, 'expanded' => true, 'placeholder'=>false, 'label'    => 'Archive this Dataset?', 'choices'=> ['Yes' => true, 'No' => false]]);
    $builder->add('archival_notes',  TextareaType::class, ['required' => false, 'label'    => 'Archival Notes']);
    $builder->add('last_edit_notes',  TextareaType::class, ['required' => false, 'data'     => '', 'label'    => 'Notes about this edit']);
    $builder->add('save',SubmitType::class, ["label"=>"Submit", 'attr'=>['class'=>'spacer']]);

  }

  public function getName() {
    return 'dataset';
  }


  /**
   * Set defaults
   *
   * @param array 
   */
  public function configureOptions(OptionsResolver $resolver) {

    $resolver->setDefaults(['data_class' => \App\Entity\Dataset::class, 'datasetUid' => null]);

  }

}
