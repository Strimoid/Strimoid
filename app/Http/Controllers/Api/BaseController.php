<?php namespace Strimoid\Http\Controllers\Api;

use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use League\Fractal\Resource\Item;
use Response;

class BaseController extends Controller
{
    use ValidatesRequests;

    /**
     * @param Manager $fractal
     */
    public function __construct(Manager $fractal)
    {
        $this->fractal = $fractal;
    }

    /**
     * Respond with transformed item.
     *
     * @param $item
     * @param $callback
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithItem($item, $callback)
    {
        $resource = new Item($item, $callback);
        $rootScope = $this->fractal->createData($resource);

        return Response::json($rootScope->toArray());
    }

    /**
     * Respond with transformed collection.
     *
     * @param $collection
     * @param $callback
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    protected function respondWithCollection($collection, $callback)
    {
        $resource = new Collection($collection, $callback);
        $rootScope = $this->fractal->createData($resource);

        return Response::json($rootScope->toArray());
    }
}
