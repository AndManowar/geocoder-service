<?php
/**
 * Роутинг
 *
 * Определяем машруты приложения, возвращаем колекцию массивов - каждая строчка один маршрут.
 * Всегда три элемента:
 *   ['HttpMethod', 'pattern', ['Namespace', 'Controller', 'Action']
 *
 * Пример:
 *   ['POST', '/user/info/{id}', '']
 */
return [

    /*
     * Публичные методы для админки
     */
    // Objects
    ['GET', '/admin/objects', ['App\Controllers\Admin', 'Crud', 'index']],
    ['GET', '/admin/object/{id}', ['App\Controllers\Admin', 'Crud', 'getObject']],
    ['POST', '/admin/object', ['App\Controllers\Admin', 'Crud', 'createObject']],
    ['PUT', '/admin/object/{id}', ['App\Controllers\Admin', 'Crud', 'updateObject']],
    ['DELETE', '/admin/object/{id}', ['App\Controllers\Admin', 'Crud', 'deleteObject']],
    ['GET', '/admin/object/recover/{id}', ['App\Controllers\Admin', 'Crud', 'recoverObject']],
    ['GET', '/admin/get-children/{globId}/{type}', ['App\Controllers\Admin', 'Crud', 'getChildren']],
    ['GET', '/admin/get-regions', ['App\Controllers\Admin', 'Crud', 'getRegions']],
    ['GET', '/admin/get-types/{type}', ['App\Controllers\Admin', 'Crud', 'getTypeDescs']],

    // Folk districts
    ['POST', '/admin/folk-district', ['App\Controllers\Admin', 'District', 'createFolkDistrict']],
    ['PUT', '/admin/folk-district/{id}', ['App\Controllers\Admin', 'District', 'updateFolkDistrict']],
    ['POST', '/admin/folk-district/mapping', ['App\Controllers\Admin', 'District', 'createFolkDistrictMapping']],
    ['PUT', '/admin/folk-district/mapping/{id}', ['App\Controllers\Admin', 'District', 'updateFolkDistrictMapping']],
    ['DELETE', '/admin/folk-district/mapping/{id}', ['App\Controllers\Admin', 'District', 'deleteFolkDistrictMapping']],

    //Type Desc
    ['GET', '/admin/type/{id}', ['App\Controllers\Admin', 'Crud', 'getType']],
    ['POST', '/admin/type', ['App\Controllers\Admin', 'Crud', 'createType']],
    ['PUT', '/admin/type/{id}', ['App\Controllers\Admin', 'Crud', 'updateType']],
    ['DELETE', '/admin/type/{id}', ['App\Controllers\Admin', 'Crud', 'deleteType']],
    ['GET', '/admin/type/recover/{id}', ['App\Controllers\Admin', 'Crud', 'recoverType']],

    // Directions
    ['GET', '/admin/direction/{id}', ['App\Controllers\Admin', 'Direction', 'getDirection']],
    ['POST', '/admin/direction', ['App\Controllers\Admin', 'Direction', 'createDirection']],
    ['PUT', '/admin/direction/{id}', ['App\Controllers\Admin', 'Direction', 'updateDirection']],
    ['DELETE', '/admin/direction/{id}', ['App\Controllers\Admin', 'Direction', 'deleteDirection']],
    ['GET', '/admin/direction/recover/{id}', ['App\Controllers\Admin', 'Direction', 'recoverDirection']],

    // Metro
    ['GET', '/admin/metros', ['App\Controllers\Admin', 'Metro', 'index']],
    ['GET', '/admin/metro/{id}', ['App\Controllers\Admin', 'Metro', 'getMetro']],
    ['POST', '/admin/metro', ['App\Controllers\Admin', 'Metro', 'createMetro']],
    ['PUT', '/admin/metro/{id}', ['App\Controllers\Admin', 'Metro', 'updateMetro']],
    ['DELETE', '/admin/metro/{id}', ['App\Controllers\Admin', 'Metro', 'deleteMetro']],
    ['GET', '/admin/metro/recover/{id}', ['App\Controllers\Admin', 'Metro', 'recoverMetro']],

    // MetroLine
    ['GET', '/admin/metro-lines', ['App\Controllers\Admin', 'Metro-Line', 'getLines']],
    ['GET', '/admin/metro-line/{id}', ['App\Controllers\Admin', 'Metro-Line', 'getLine']],
    ['POST', '/admin/metro-line', ['App\Controllers\Admin', 'Metro-Line', 'createLine']],
    ['PUT', '/admin/metro-line/{id}', ['App\Controllers\Admin', 'Metro-Line', 'updateLine']],
    ['DELETE', '/admin/metro-line/{id}', ['App\Controllers\Admin', 'Metro-Line', 'deleteLine']],
    ['GET', '/admin/metro-line/recover/{id}', ['App\Controllers\Admin', 'Metro-Line', 'recoverLine']],


    /*
     * Конец публичных методов для админки
     */

    /*
     * Приватные методы для админки
     */
    // Objects
    ['GET', '/service/objects', ['App\Controllers\Service', 'Crud', 'index']],
    ['GET', '/service/object/{id}', ['App\Controllers\Service', 'Crud', 'getObject']],
    ['POST', '/service/object', ['App\Controllers\Service', 'Crud', 'createObject']],
    ['PUT', '/service/object/{id}', ['App\Controllers\Service', 'Crud', 'updateObject']],
    ['DELETE', '/service/object/{id}', ['App\Controllers\Service', 'Crud', 'deleteObject']],
    ['GET', '/service/object/recover/{id}', ['App\Controllers\Service', 'Crud', 'recoverObject']],
    ['GET', '/service/get-children/{globId}/{type}', ['App\Controllers\Service', 'Crud', 'getChildren']],
    ['GET', '/service/get-regions', ['App\Controllers\Service', 'Crud', 'getRegions']],
    ['GET', '/service/get-types/{type}', ['App\Controllers\Service', 'Crud', 'getTypeDescs']],

    // Folk districts
    ['POST', '/service/folk-district', ['App\Controllers\Service', 'District', 'createFolkDistrict']],
    ['PUT', '/service/folk-district/{id}', ['App\Controllers\Service', 'District', 'updateFolkDistrict']],
    ['POST', '/service/folk-district/mapping', ['App\Controllers\Service', 'District', 'createFolkDistrictMapping']],
    ['PUT', '/service/folk-district/mapping/{id}', ['App\Controllers\Service', 'District', 'updateFolkDistrictMapping']],
    ['DELETE', '/service/folk-district/mapping/{id}', ['App\Controllers\Service', 'District', 'deleteFolkDistrictMapping']],

    //Type Desc
    ['GET', '/service/type/{id}', ['App\Controllers\Service', 'Crud', 'getType']],
    ['POST', '/service/type', ['App\Controllers\Service', 'Crud', 'createType']],
    ['PUT', '/service/type/{id}', ['App\Controllers\Service', 'Crud', 'updateType']],
    ['DELETE', '/service/type/{id}', ['App\Controllers\Service', 'Crud', 'deleteType']],
    ['GET', '/service/type/recover/{id}', ['App\Controllers\Service', 'Crud', 'recoverType']],

    // Directions
    ['GET', '/service/direction/{id}', ['App\Controllers\Service', 'Direction', 'getDirection']],
    ['POST', '/service/direction', ['App\Controllers\Service', 'Direction', 'createDirection']],
    ['PUT', '/service/direction/{id}', ['App\Controllers\Service', 'Direction', 'updateDirection']],
    ['DELETE', '/service/direction/{id}', ['App\Controllers\Service', 'Direction', 'deleteDirection']],
    ['GET', '/service/direction/recover/{id}', ['App\Controllers\Service', 'Direction', 'recoverDirection']],

    // Metro
    ['GET', '/service/metros', ['App\Controllers\Service', 'Metro', 'index']],
    ['GET', '/service/metro/{id}', ['App\Controllers\Service', 'Metro', 'getMetro']],
    ['POST', '/service/metro', ['App\Controllers\Service', 'Metro', 'createMetro']],
    ['PUT', '/service/metro/{id}', ['App\Controllers\Service', 'Metro', 'updateMetro']],
    ['DELETE', '/service/metro/{id}', ['App\Controllers\Service', 'Metro', 'deleteMetro']],
    ['GET', '/service/metro/recover/{id}', ['App\Controllers\Service', 'Metro', 'recoverMetro']],

    // MetroLine
    ['GET', '/service/metro-lines', ['App\Controllers\Service', 'Metro-Line', 'getAll']],
    ['GET', '/service/metro-line/{id}', ['App\Controllers\Service', 'Metro-Line', 'getLine']],
    ['POST', '/service/metro-line', ['App\Controllers\Service', 'Metro-Line', 'createLine']],
    ['PUT', '/service/metro-line/{id}', ['App\Controllers\Service', 'Metro-Line', 'updateLine']],
    ['DELETE', '/service/metro-line/{id}', ['App\Controllers\Service', 'Metro-Line', 'deleteLine']],
    ['GET', '/service/metro-line/recover/{id}', ['App\Controllers\Service', 'Metro-Line', 'recoverLine']],


    /*
     * Конец приватных методов для админки
     */

    /*
     * Методы для работы плагина
     */
    ['GET', '/search/region', ['App\Controllers\Client', 'Search', 'searchRegion']],
    ['GET', '/search/area', ['App\Controllers\Client', 'Search', 'searchArea']],
    ['GET', '/search/city', ['App\Controllers\Client', 'Search', 'searchCity']],
    ['GET', '/search/street', ['App\Controllers\Client', 'Search', 'searchStreet']],

    // CORS
    ['OPTIONS', '/{url:.*}', ['App\Components', 'Base', 'cors']],
];