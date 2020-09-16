<?php 

namespace App\Form;

use App\Entity\Laureat;
use Craue\FormFlowBundle\Form\FormFlow;
use Craue\FormFlowBundle\Form\FormFlowInterface;
use Form\LaureatRegistrationFormType;

class LaureatRegistrationFlow extends FormFlow {

	protected function loadStepsConfig() {
		return [
			[
				'label' => 'personal Information',
				'form_type' => LaureatRegistrationFormType::class,
			],
			[
				'label' => 'engine',
				'form_type' => LaureatRegistrationFormType::class,
				'skip' => function($estimatedCurrentStepNumber, FormFlowInterface $flow) {
					return $estimatedCurrentStepNumber > 1 && !$flow->getFormData()->canHaveEngine();
				},
			],
			[
				'label' => 'confirmation',
			],
		];
	}

}