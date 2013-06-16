<?php

use Symfony\Component\Routing\RouteCollection;
use Symfony\Component\Routing\Route;

$collection = new RouteCollection();

$collection->add('no', new Route('/', array(
    '_controller' => 'SiteSavalizeBundle:ProductBrand:index',
)));

$collection->add('no_show', new Route('/{id}/show', array(
    '_controller' => 'SiteSavalizeBundle:ProductBrand:show',
)));




return $collection;
