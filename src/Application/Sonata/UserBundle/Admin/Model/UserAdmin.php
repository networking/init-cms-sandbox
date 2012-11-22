<?php

/*
 * This file is part of the Sonata package.
 *
 * (c) Thomas Rabaix <thomas.rabaix@sonata-project.org>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Application\Sonata\UserBundle\Admin\Model;

use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Admin\AdminExtension;


class UserAdmin extends AdminExtension
{

	/**
	 * {@inheritdoc}
	 */
	public function configureFormFields(FormMapper $formMapper)
	{
		$formMapper
				->with('Profile')
				->add('dateOfBirth', 'date', array('required' => false, 'years' => range(date('Y') - 90, date('Y')-20)))
				->end();


	}
}