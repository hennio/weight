<?php

	class Controller_Api_Weights extends Abstract_Controller
	{

		public function GET()
		{
			$policy = new Policy_LoggedIn($this->app);
			$app = Config::get('app');

			$userid = $policy->getData();
			$request = $this->app->request();

			if (!$userid)
			{
				throw new Exception_Api("Unable to authenticate.");
			}

			$days_back = trim($request->get('days_back'));

			if (!is_numeric($days_back) && $days_back != 'all' && $days_back != 'ytd')
			{
				throw new Exception_Api('Missing or invalid days_back field.');
			}

			$weight_mapper = new Mapper_Weight();
			$weights = $weight_mapper->getWeightsForUser($userid, $days_back);

			$formatted_weights = array();
			foreach ($weights as $weight)
			{
				$formatted_weights[] =
					array(
						'date'    => $weight['create_time'],
						'weight'  => $weight['weight'],
						'comment' => htmlentities($weight['comment'])
					);
			}

			return array('data' => $formatted_weights, 'units' => $app->weight_units);
		}

	}
