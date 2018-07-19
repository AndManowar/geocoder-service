<?php

use App\Models\Managers\FolkDistrictMappingManager;
use App\Models\Managers\ObjectManager;
use Phalcon\Cli\Task;

include __DIR__ . '/../../vendor/topnlab/phalcon-base/src/ModelManager.php';

/**
 * Создание и привязка народных районов
 *
 * Class FolkDistrictsTask
 */
class FolkDistrictsTask extends Task
{
    /**
     * Массив народный районов и маппинга
     *
     * @var array
     */
    private $districts = [
        'formalDistricts' => [
            [                                                                       // Рязань
                'formalDistrictGlobId' => '69a331d7-d158-450e-84d3-44a6373ed89e',   // Глоб айди формального района (Железнодорожный)
                'data'                 => [                                         // Стандартные данные для народного района
                    'type_id'    => 28,
                    'parentData' => [
                        ['glob_id' => '963073ee-4dfc-48bd-9a70-d2dfc6bd1f31'],      // Рязанская
                        ['glob_id' => '86e5bae4-ef58-4031-b34f-5e9ff914cd55'],      // Рязань
                    ],
                ],
                'folkDistricts'        => [     // Названия народных районов
                    'Божатково',
                    'Горроща',
                    'Дашки военные',
                    'Центр',
                    'Михайловский',
                    'Михайловское шоссе',
                    'Октябрьский городок',
                    'Ситники',
                    'Сысоево',
                    'Сысоево-2',
                    'Храпово',
                    'Центральный промузел',
                    'Южный',
                ]
            ],
//            [                                                                       // Рязань
//                'formalDistrictGlobId' => 'Советского района нет',                  // Советский
//                'data'                 => [                                         // Стандартные данные для народного района
//                    'type_id'    => 28,
//                    'parentData' => [
//                        ['glob_id' => '963073ee-4dfc-48bd-9a70-d2dfc6bd1f31'],      // Рязанская
//                        ['glob_id' => '963073ee-4dfc-48bd-9a70-d2dfc6bd1f31'],      // Рязань
//                    ],
//                ],
//                'folkDistricts'        => [     // Названия народных районов
//                    'Борки',
//                    'Бутырки',
//                    'Варские',
//                    'Кальное',
//                    'Поляны',
//                    'Солотча',
//                ]
//            ],
            [                                                                       // Рязань
                'formalDistrictGlobId' => 'ce54cf41-77e6-4bac-8b0f-9e4d4c9ce46b',   // Московский
                'data'                 => [
                    'type_id'    => 28,
                    'parentData' => [
                        ['glob_id' => '963073ee-4dfc-48bd-9a70-d2dfc6bd1f31'],      // Рязанская
                        ['glob_id' => '86e5bae4-ef58-4031-b34f-5e9ff914cd55'],      // Рязань
                    ],
                ],
                'folkDistricts'        => [
                    'Ворошиловка',
                    'Дягилево',
                    'Канищево',
                    'Мервино',
                    'Московский',
                    'Недостоево',
                    'Приокский',
                    'Северо-Западный промузел',
                    'Семчино',
                    'Элеватор',
                ]
            ],
            [                                                                       // Рязань
                'formalDistrictGlobId' => 'c5eb066b-beed-4b66-90c1-bf9aa14e7171',   // Октябрьский
                'data'                 => [
                    'type_id'    => 28,
                    'parentData' => [
                        ['glob_id' => '963073ee-4dfc-48bd-9a70-d2dfc6bd1f31'],      // Рязанская
                        ['glob_id' => '86e5bae4-ef58-4031-b34f-5e9ff914cd55'],      // Рязань
                    ],
                ],
                'folkDistricts'        => [
                    'Восточный промузел',
                    'Голенчино',
                    'ДП',
                    'Дядьково',
                    'Карцево',
                    'Мирный',
                    'Никуличи',
                    'Новорязанская ТЭЦ',
                    'Соколовка',
                    'Строитель',
                    'Хамбушево',
                    'Ряжское шоссе',
                    'Шереметьево',
                    'Шлаковый',
                    'Южный промузел',
                    'Агропром',
                ]
            ],
//            [                                                                       // Пенза
//                'formalDistrictGlobId' => 'Не нашел в базе',   // Железнодорожный
//                'data'                 => [
//                    'type_id'    => 28,
//                    'parentData' => [
//                        ['glob_id' => 'c99e7924-0428-4107-a302-4fd7c0cca3ff'],      // Пензенская
//                        ['glob_id' => 'ff3292b1-a1d2-47d4-b35b-ac06b50555cc'],      // Пенза
//                    ],
//                ],
//                'folkDistricts'        => [
//                    'Автовокзал',
//                    'Ахуны',
//                    'Барковка',
//                    'ГПЗ-24',
//                    'Засурье',
//                    'КПД (Пенза-4)',
//                    'Лесной, поселок',
//                    'Маньчжурия',
//                    'Маяк',
//                    'Медпрепараты (Биосинтез)',
//                    'Монтажный, поселок',
//                    'Нахаловка',
//                    'Новоказанская',
//                    'Пенза-2',
//                    'Пенза-3',
//                    'Светлая поляна',
//                    'Согласие',
//                    'Сосновка',
//                    'Стрела',
//                    'Чемодановка',
//                    'Шуист',
//                ]
//            ],
//            [                                                                       // Пенза
//                'formalDistrictGlobId' => '...',   // Ленинский
//                'data'                 => [
//                    'type_id'    => 28,
//                    'parentData' => [
//                        ['glob_id' => 'c99e7924-0428-4107-a302-4fd7c0cca3ff'],      // Пензенская
//                        ['glob_id' => 'ff3292b1-a1d2-47d4-b35b-ac06b50555cc'],      // Пенза
//                    ],
//                ],
//                'folkDistricts'        => [
//                    '8-Марта, ул.',
//                    'Автодром',
//                    'Арбеково, разъезд',
//                    'Бугровка',
//                    'Буратино, маг-н',
//                    'Глобус',
//                    'Памятник Победы',
//                    'Пески',
//                    'Райки',
//                    'Суворовский, ТЦ',
//                    'Центр',
//                ]
//            ],
//            [                                                                       // Пенза
//                'formalDistrictGlobId' => 'Не нашел',   // Октябрьский
//                'data'                 => [
//                    'type_id'    => 28,
//                    'parentData' => [
//                        ['glob_id' => 'c99e7924-0428-4107-a302-4fd7c0cca3ff'],      // Пензенская
//                        ['glob_id' => 'ff3292b1-a1d2-47d4-b35b-ac06b50555cc'],      // Пенза
//                    ],
//                ],
//                'folkDistricts'        => [
//                    'Арбеково (Запрудный)',
//                    'Арбеково ближнее',
//                    'Арбеково дальнее',
//                    'Арбековская Застава',
//                    'Заводской район',
//                    'Заря, совхоз',
//                    'Заря-1, поселок',
//                    'Заря-2, поселок',
//                    'ЗИФ, поселок',
//                    'Мясокомбинат',
//                    'Побочино, поселок',
//                    'Север',
//                    'Северная поляна, поселок',
//                    'Черкассы',
//                ]
//            ],
//            [                                                                       // Пенза
//                'formalDistrictGlobId' => '...',   // Первомайский
//                'data'                 => [
//                    'type_id'    => 28,
//                    'parentData' => [
//                        ['glob_id' => 'c99e7924-0428-4107-a302-4fd7c0cca3ff'],      // Пензенская
//                        ['glob_id' => 'ff3292b1-a1d2-47d4-b35b-ac06b50555cc'],      // Пенза
//                    ],
//                ],
//                'folkDistricts'        => [
//                    'Аэропорт',
//                    'Бригадирский мост',
//                    'Веселовка',
//                    'Военный городок',
//                    'Горизонт',
//                    'Дон, магазин',
//                    'Западная поляна',
//                    'Засека',
//                    'Засечное',
//                    'КП "Дубрава"',
//                    'Кривозерье',
//                    'Ленинский лесхоз',
//                    'Окружная',
//                    'Политехнический, ПГУ',
//                    'Совхоз техникум',
//                    'Тамбовская застава',
//                    'Тепличный',
//                    'Терновка',
//                    'Терновка. Гидрострой',
//                    'Терновка. Междуречье',
//                    'Терновка. Спутник',
//                    'Южная поляна',
//                ]
//            ],
            [                                                                       // Краснодар
                'formalDistrictGlobId' => '506251ad-ff8a-48b3-9283-0f4090760e5a',   // Центральный
                'data'                 => [
                    'type_id'    => 28,
                    'parentData' => [
                        ['glob_id' => 'd00e1013-16bd-4c09-b3d5-3cb09fc54bd8'],      // Краснодарский
                        ['glob_id' => '7dfa745e-aa19-4688-b121-b655c11e482f'],      // Краснодар
                    ],
                ],
                'folkDistricts'        => [
                    'Школьная ул. мкр.',
                    'Горгаз (Сити Центр)',
                    'Масложиркомбинат',
                    'Табачная фабрика',
                    'Центр',
                    'Старый центр',
                    'Аврора',
                ]
            ],
            [                                                                       // Краснодар
                'formalDistrictGlobId' => '0c7e8cfe-01c3-4d33-ae6e-c6718a369110',   // Прикубанский
                'data'                 => [
                    'type_id'    => 28,
                    'parentData' => [
                        ['glob_id' => 'd00e1013-16bd-4c09-b3d5-3cb09fc54bd8'],      // Краснодарский
                        ['glob_id' => '7dfa745e-aa19-4688-b121-b655c11e482f'],      // Краснодар
                    ],
                ],
                'folkDistricts'        => [
                    'Завод измерительных приборов',
                    'Рубероидный завод',
                    'Славянский мкр.',
                    'Фестивальный мкр.',
                    '9 км. Ростовского шоссе',
                    'Краевая клиническая больница',
                    'Клиника микрохирургии глаза',
                    'Российская ул.',
                    'Краснодарский пос.',
                    'Ипподром',
                    'Авиагородок',
                    '2-я площадка',
                    'Энка',
                    '40 лет Победы ул.',
                    '9-я Тихая ул.',
                    'Березовый пос.',
                    'Витаминкомбинат',
                    'Южный пос.',
                    'Прогресс',
                    'Репино',
                    'Северный п.',
                    'Немецкая деревня',
                    'Западный обход',
                    'Вавилова ул.',
                    'Баскет-холл',
                    'Восточно-Кругликовская ул.',
                    'Московский мкр.',
                    'Музыкальный мкр.',
                    'Горхутор',
                    'Молодежный мкр.',
                    'Индустриальный пос.',
                    'Лазурный пос.',
                ]
            ],
            [                                                                       // Краснодар
                'formalDistrictGlobId' => '3f1e82a8-0d7d-4afe-a5f3-f393ca80be99',   // Западный
                'data'                 => [
                    'type_id'    => 28,
                    'parentData' => [
                        ['glob_id' => 'd00e1013-16bd-4c09-b3d5-3cb09fc54bd8'],      // Краснодарский
                        ['glob_id' => '7dfa745e-aa19-4688-b121-b655c11e482f'],      // Краснодар
                    ],
                ],
                'folkDistricts'        => [
                    'Юбилейный мкр.',
                    'Сельскохозяйственная академия',
                    'Кожевенная ул.',
                ]
            ],
            [                                                                       // Краснодар
                'formalDistrictGlobId' => 'b46a9410-41ef-40bc-9482-f18bdb3b5193',   // Карасунский
                'data'                 => [
                    'type_id'    => 28,
                    'parentData' => [
                        ['glob_id' => 'd00e1013-16bd-4c09-b3d5-3cb09fc54bd8'],      // Краснодарский
                        ['glob_id' => '7dfa745e-aa19-4688-b121-b655c11e482f'],      // Краснодар
                    ],
                ],
                'folkDistricts'        => [
                    'Аэропорт',
                    'Гидростроителей п.',
                    'Комсомольский мкр.',
                    'Камвольно-суконный комбинат',
                    'Пашковский п.',
                    'Ремонтно-механический завод',
                    'Хлопчатобумажный комбинат',
                    'Черемушки мкр.',
                    'Теплоэнергоцентраль',
                    'Знаменский п.',
                    'Новознаменский п.',
                    'Лорис пос.',
                    'Ленина х.',
                ]
            ],
        ],
    ];

    /**
     * Создание и привязка народный районов
     *
     * @throws Exception
     * @throws \App\Exceptions\InvalidLevelValueException
     * @throws \App\Exceptions\InvalidParentDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     * @throws \App\Exceptions\ParentDataNotFoundException
     * @throws \App\Exceptions\TypeNotFoundException
     */
    public function mainAction(): void
    {
        foreach ($this->districts['formalDistricts'] as $district) {

            echo "Formal District {$district['formalDistrictGlobId']} Start" . PHP_EOL;

            foreach ($district['folkDistricts'] as $folkDistrict) {

                $objectManager = new ObjectManager();
                $folkDistrictMappingManager = new FolkDistrictMappingManager();

                $data = array_merge($district['data'], ['name' => $folkDistrict, 'type' => ObjectManager::TYPE_DISTRICT]);

                if (!$objectManager->setFolkDistrict()->createObject($data)) {
                    echo "ERROR WHILE CREATING {$folkDistrict}!" . PHP_EOL;
                    die();
                }

                $mappingData = [
                    'folk_district_glob_id'   => $objectManager->folkDistrictGlobId,
                    'formal_district_glob_id' => $district['formalDistrictGlobId']
                ];

                if (!$folkDistrictMappingManager->createMapping($mappingData)) {
                    echo "ERROR WHILE MAPPING {$folkDistrict}!" . PHP_EOL;
                    die();
                }

                echo "Folk District {$folkDistrict} Successfully created and mapped" . PHP_EOL;
            }

            echo "Formal District {$district['formalDistrictGlobId']} Finish" . PHP_EOL;
        }
    }
}