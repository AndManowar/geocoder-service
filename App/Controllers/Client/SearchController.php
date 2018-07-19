<?php

namespace App\Controllers\Client;

use App\Components\Filters\SimpleTextAlphanumeric;
use App\Service\SearchService;
use Phalcon\Mvc\Controller;
use Topnlab\PhalconBase\v2\Components\ApiRequest;
use Topnlab\PhalconBase\v2\Components\ApiResponse;

/**
 * Контроллер для поиска
 *
 * Search service controller
 * Class SearchController
 *
 * @package App\Controllers
 *
 * @property ApiResponse $response
 * @property ApiRequest $request
 */
class SearchController extends Controller
{
    /**
     * @var SearchService
     */
    private $searchService;

    /**
     * @inheritdoc
     */
    public function initialize(): void
    {
        $this->searchService = new SearchService($this->modelsManager);
    }

    /**
     * Поиск региона по имени
     *
     * @return ApiResponse
     */
    public function searchRegionAction(): ApiResponse
    {
        // Добавляем свой фильтр
        $this->filter->add('simpleTextAlphanumeric', new SimpleTextAlphanumeric);
        // Получение параметров запроса
        $name = $this->request->getQuery('name', 'simpleTextAlphanumeric');

        if (mb_strlen($name) < 2) {
            return $this->response->sendError("Вы должны ввести минимум 2 символа");
        } else {
            return $this->response->sendSuccess($this->searchService->searchRegion($name));
        }
    }

    /**
     * Поиск района по имени внутри региона
     *
     * @return ApiResponse
     */
    public function searchAreaAction(): ApiResponse
    {
        // Добавляем свой фильтр
        $this->filter->add('simpleTextAlphanumeric', new SimpleTextAlphanumeric);
        // Получение параметров запроса
        $name = $this->request->getQuery('name', 'simpleTextAlphanumeric');
        $regionId = $this->request->getQuery('region_id', 'int', 0);

        if (mb_strlen($name) < 2) {
            return $this->response->sendError("Вы должны ввести минимум 2 символа");
        } else {
            return $this->response->sendSuccess($this->searchService->searchArea($name, $regionId));
        }
    }

    /**
     * Поиск города по имени внутри региона + района
     *
     * @return ApiResponse
     */
    public function searchCityAction(): ApiResponse
    {
        // Добавляем свой фильтр
        $this->filter->add('simpleTextAlphanumeric', new SimpleTextAlphanumeric);
        // Получение параметров запроса
        $name = $this->request->getQuery('name', 'simpleTextAlphanumeric');
        $regionId = $this->request->getQuery('region_id', 'int', 0);
        $areaId = $this->request->getQuery('area_id', 'int', 0);

        if (mb_strlen($name) < 2) {
            return $this->response->sendError("Вы должны ввести минимум 2 символа");
        } else {
            return $this->response->sendSuccess($this->searchService->searchCity($name, $regionId, $areaId));
        }
    }

    /**
     * Поиск адреса по имени внутри региона + района + города
     *
     * @return ApiResponse
     */
    public function searchStreetAction(): ApiResponse
    {
        // Добавляем свой фильтр
        $this->filter->add('simpleTextAlphanumeric', new SimpleTextAlphanumeric);
        // Получение параметров запроса
        $name = $this->request->getQuery('name', 'simpleTextAlphanumeric');
        $regionId = $this->request->getQuery('region_id', 'int', 0);
        $areaId = $this->request->getQuery('area_id', 'int', null);
        $cityId = $this->request->getQuery('city_id', 'int', null);
        $placeId = $this->request->getQuery('place_id', 'int', null);

        if (mb_strlen($name) < 2) {
            return $this->response->sendError("Вы должны ввести минимум 2 символа");
        } else {
            return $this->response->sendSuccess($this->searchService->searchStreet($name, $regionId, $areaId, $cityId, $placeId));
        }
    }
}