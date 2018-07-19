<?php
/**
 * Created by PhpStorm.
 * User: manowartop
 * Date: 12.06.18
 * Time: 14:55
 */

use App\Models\DB\ObjectModel;
use App\Models\Managers\ObjectManager;
use Phalcon\Cli\Task;

include __DIR__ . '/../../vendor/topnlab/phalcon-base/src/ModelManager.php';


/**
 * Добавление округов и районов
 *
 * Class CountiesTask
 */
class CountiesTask extends Task
{

    /**
     * Дефолтные данные округов
     *
     * @var array
     */
    private $defaultCountyData = [
        'type_id'    => 273,  // округ
        'parentData' => [
            ['glob_id' => 77]   // Москва
        ]
    ];

    /**
     * Дефолтные данные районов
     *
     * @var array
     */
    private $defaultDistrictData = [
        'type_id'    => 11,  // р-н
        'parentData' => [
            ['glob_id' => 77]   // Москва
        ]
    ];

    /**
     * Округа и их районы
     *
     * @var array
     */
    private $counties = [
        [
            'name'      => 'Восточный АО',
            'districts' => [
                'Богородское',
                'Вешняки',
                'Восточное Измайлово',
                'Восточный',
                'Гольяново',
                'Ивановское',
                'Измайлово',
                'Косино-Ухтомский',
                'Метрогородок',
                'Новогиреево',
                'Новокосино',
                'Перово',
                'Преображенское',
                'Северное Измайлово',
                'Соколиная Гора',
                'Сокольники'
            ]
        ],
        [
            'name'      => 'Западный АО',
            'districts' => [
                'Внуково',
                'Дорогомилово',
                'Крылатское',
                'Кунцево',
                'Можайский',
                'Ново-Переделкино',
                'Очаково-Матвеевское',
                'Проспект Вернадского',
                'Раменки',
                'Солнцево',
                'Тропарево-Никулино',
                'Филевский парк',
                'Фили-Давыдково'
            ]
        ],
        [
            'name'      => 'Зеленоградский АО',
            'districts' => [
                'Зеленоград',
            ]
        ],
        [
            'name'      => 'Новомосковский',
            'districts' => [
                'Внуковское',
                'Воскресенское',
                'Десеновское',
                'Кокошкино',
                'Марушкинское',
                'Московский',
                'Мосрентген',
                'Рязановское',
                'Сосенское',
                'Филимонковское',
                'Щербинка'
            ]
        ],
        [
            'name'      => 'Северный АО',
            'districts' => [
                'Аэропорт',
                'Беговой',
                'Бескудниковский',
                'Войковский',
                'Восточное Дегунино',
                'Головинский',
                'Дмитровский',
                'Западное Дегунино',
                'Коптево',
                'Левобережный',
                'Савеловский',
                'Сокол',
                'Тимирязевский',
                'Ховрино',
                'Хорошевский'
            ]
        ],
        [
            'name'      => 'Северо-Восточный АО',
            'districts' => [
                'Алексеевский',
                'Алтуфьевский',
                'Бабушкинский',
                'Бибирево',
                'Бутырский',
                'Лианозово',
                'Лосиноостровский',
                'Марфино',
                'Марьина Роща',
                'Останкинский',
                'Отрадное',
                'Ростокино',
                'Свиблово',
                'Северное Медведково',
                'Северный',
                'Южное Медведково',
                'Ярославский'
            ]
        ],
        [
            'name'      => 'Северо-Западный АО',
            'districts' => [
                'Куркино',
                'Митино',
                'Покровское-Стрешнево',
                'Северное Тушино',
                'Строгино',
                'Хорошево-Мневники',
                'Щукино',
                'Южное Тушино'
            ]
        ],
        [
            'name'      => 'Троицкий',
            'districts' => [
                'Вороновское',
                'Кленовское',
                'Краснопахорское',
                'Михайлово-Ярцевское',
                'Новофедоровское',
                'Первомайское',
                'Роговское',
                'Троицк',
                'Щаповское'
            ]
        ],
        [
            'name'      => 'Центральный АО',
            'districts' => [
                'Арбат',
                'Басманный',
                'Замоскворечье',
                'Красносельский',
                'Мещанский',
                'Пресненский',
                'Таганский',
                'Тверской',
                'Хамовники',
                'Якиманка'
            ]
        ],
        [
            'name'      => 'Юго-Восточный',
            'districts' => [
                'Выхино-Жулебино',
                'Капотня',
                'Кузьминки',
                'Лефортово',
                'Люблино',
                'Марьино',
                'Некрасовка',
                'Нижегородский',
                'Печатники',
                'Рязанский',
                'Текстильщики',
                'Южнопортовый'
            ]
        ],
        [
            'name'      => 'Юго-Западный АО',
            'districts' => [
                'Академический',
                'Гагаринский',
                'Зюзино',
                'Коньково',
                'Котловка',
                'Ломоносовский',
                'Обручевский',
                'Северное Бутово',
                'Теплый Стан',
                'Черемушки',
                'Южное Бутово',
                'Ясенево'
            ]
        ],
        [
            'name'      => 'Южный АО',
            'districts' => [
                'Бирюлево-Восточное',
                'Бирюлево-Западное',
                'Братеево',
                'Даниловский',
                'Донской',
                'Зябликово',
                'Москворечье-Сабурово',
                'Нагатино-Садовники',
                'Нагатинский Затон',
                'Нагорный',
                'Орехово-Борисово Северное',
                'Орехово-Борисово Южное',
                'Царицыно',
                'Чертаново Северное',
                'Чертаново Центральное',
                'Чертаново Южное'
            ]
        ]
    ];

    /**
     * @var array
     */
    private $districtData;

    /**
     * Создание округов и районов в них
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
        foreach ($this->counties as $county) {

            echo "Start creating county {$county['name']}" . PHP_EOL;

            if (!$this->createCountyAndGetRecord(array_merge($this->defaultCountyData, ['name' => $county['name'], 'type' => ObjectManager::TYPE_COUNTY]))) {
                echo "ERROR while creating county {$county['name']}" . PHP_EOL;
                die();
            }

            foreach ($county['districts'] as $district) {
                $objectManager = new ObjectManager();

                if (!$objectManager->createObject(array_merge($this->districtData, ['name' => $district, 'type' => ObjectManager::TYPE_DISTRICT]))) {
                    echo "ERROR while creating district {$district}" . PHP_EOL;
                    die();
                }

                echo "District {$district} successfully created" . PHP_EOL;
            }

            echo "County {$county['name']} and its districts are successfully created" . PHP_EOL;
        }
    }


    /**
     * Создание округа и добавление даты для района в массив
     *
     * @param array $data
     * @return bool
     * @throws Exception
     * @throws \App\Exceptions\InvalidLevelValueException
     * @throws \App\Exceptions\InvalidParentDataException
     * @throws \App\Exceptions\ObjectNotFoundException
     * @throws \App\Exceptions\ParentDataNotFoundException
     * @throws \App\Exceptions\TypeNotFoundException
     */
    private function createCountyAndGetRecord(array $data): bool
    {
        $objectManager = new ObjectManager();

        if (!$objectManager->createObject($data)) {
            return false;
        }

        /** @var ObjectModel $record */
        $record = ObjectModel::query()
            ->where("name = '{$data['name']}' and type_id={$data['type_id']} and status=1 and region_id=77")
            ->execute()
            ->getFirst();

        $this->districtData = $this->defaultDistrictData;
        $this->districtData['parentData'][]['glob_id'] = $record->glob_id;

        return true;
    }
}