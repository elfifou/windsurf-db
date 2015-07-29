<?php

namespace Windsurfdb\UserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class WindsurfdbUserBundle extends Bundle
{
	public function getParent()
	{
		return 'FOSUserBundle';
	}
}
