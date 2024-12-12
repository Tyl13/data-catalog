<?php
namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use EWZ\Bundle\RecaptchaBundle\Form\Type\EWZRecaptchaType;

/**
 * Form builder for Contact Us form
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
 */
class ContactFormEmailType extends AbstractType {

	protected $options;

  protected $affiliationOptions;

  /**
   * Build institutional affiliation options list
   */
  public function __construct()
  {
  }

  /**
   * Build the form
   *
   * @param FormBuilderInterface
   */
  public function buildForm(FormBuilderInterface $builder, array $options) {
    $builder->add('full_name', TextType::class, ['required' => true, 'label_attr'=>['class'=>'asterisk']]);
    $builder->add('email_address', EmailType::class, ['label_attr'=>['class'=>'asterisk']]);
    $builder->add('school_center', TextType::class, ['required' => false, 'label'=> 'School/Center', 'label_attr'=>['class'=>'no-asterisk']]);
    $builder->add('department', TextType::class, ['required' => false, 'label'=> 'Department', 'label_attr'=>['class'=>'no-asterisk']]);

    $builder->add('reason', ChoiceType::class, ['expanded'=>true, 'required' => true, 'label_attr'=>['class'=>'no-asterisk'], 'choices' =>['General inquiry'    => 'General question or comments', 'Technical problem' => 'Technical problem'], 'multiple'=>false]
    );
    $builder->add('message_body', TextareaType::class, ['required' => false, 'attr' => ['rows'=>'5'], 'label_attr'=>['class'=>'no-asterisk'], 'label'=>'Please provide some details about your question/comment']);

    $builder->add('recaptcha', EWZRecaptchaType::class, ['label' => false]);

    $builder->add('save',SubmitType::class,['label'=>'Send']);
  }

  /**
   * Set defaults
   *
   * @param OptionsResolver
   */
  public function configureOptions(OptionsResolver $resolver) {
    $resolver->setDefaults(['data_class' => \App\Entity\ContactFormEmail::class, 'affiliationOptions' => null]);
  }


  public function getName() {
    return 'contactFormEmail';
  }

}
