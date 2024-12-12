<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Form builder for Dataset Relationship entry
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
class DatasetRelationshipType extends AbstractType {

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('related_dataset_uid',TextType::class,['label'=>false, 'required'=>true, 'attr'=>['placeholder'=>'* Related Dataset UID']]
      );
    $builder->add('relationship_attributes',ChoiceType::class,['label'=>false, 'required'=>false, 'attr'=>['data-placeholder'=>'Relationship'], 'choices'=>['Superseded by'=>'Superseded by', 'Preceded by'  =>'Preceded by', 'Sibling of'  =>'Sibling of', 'Derived from'  =>'Derived from', 'Transformed to'  =>'Transformed to', 'Same publisher as'  =>'Same publisher as', 'Linkage dataset between'  =>'Linkage dataset between']]
      );
    $builder->add('relationship_notes',TextareaType::class,['label'=>false, 'required'=>false, 'attr'=>['placeholder'=>'Relationship Notes']]
      );
  }

  /**
   * Set defaults
   *
   * @param OptionsResolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(['data_class' => \App\Entity\DatasetRelationship::class]);
  }

  public function getName() {
    return 'datasetRelationship';
  }

}

