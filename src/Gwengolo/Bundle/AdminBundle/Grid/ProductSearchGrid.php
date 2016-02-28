<?php

namespace Gwengolo\Bundle\AdminBundle\Grid;

use APY\DataGridBundle\Grid\Source\Source;
use APY\DataGridBundle\Grid\Mapping as GRID;

class ProductSearchGrid extends Grid
{
	public function initialize()
    {
        $this->setSource(new Entity('GwengoloProductBundle:Product'));
    }
}