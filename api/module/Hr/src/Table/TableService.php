<?php
/**
 * Created by PhpStorm.
 * User: pawel
 * Date: 22.12.2016
 * Time: 17:45
 */

namespace Hr\Table;

use Configuration\Entity\ObjectGroup;
use Configuration\Object\ObjectService;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator as ORMPaginator;
use DoctrineORMModule\Paginator\Adapter\DoctrinePaginator as PaginatorAdapter;
use Hr\Repository\SearchableInterface;
use Laminas\Cache\Storage\Adapter\AbstractAdapter;
use Laminas\Paginator\Paginator as LaminasPaginator;

/**
 * Generates HTML data table.
 *
 * Call order:
 * - setId()
 * - configureColumns()
 * - setRepository()
 * - setOption() (optional)
 *
 * @package Hr\Table
 */
class TableService
{
    const OPT_SHOW_EDIT = 'showEdit';
    const OPT_SHOW_DETAILS = 'showDetails';
    const OPT_SHOW_DELETE = 'showDelete';

    const RECORDS_PER_PAGE = [2, 5, 10, 50, 100, 200, 500, 1000];

    /**
     * Number of records displayed per page.
     *
     */
    private int $recordsPerPage = 10;

    private ObjectService $objectService;

    private AbstractAdapter $cacheAdapter;

    private string $id;

    private LaminasPaginator $paginator;

    private array $queryParams;

    /**
     * Data table columns
     *
     */
    private array $columns;

    /**
     * Record edit route
     *
     */
    private ?string $editRoute = null;

    /**
     * Table row's attributes. Array key is attribute name, value is a value (string or callable).
     *
     */
    private array $rowAttributes = [];

    /**
     * Table's attributes.
     *
     */
    private array $attributes = [];

    /**
     * Row actions configuration.
     *
     */
    private array $rowActions = [];

    /**
     * Data table options.
     *
     */
    private array $options = [
        self::OPT_SHOW_EDIT => true, // show edit link?
        self::OPT_SHOW_DETAILS => true, // show details link?
        self::OPT_SHOW_DELETE => true, // show delete link?
    ];

    private array $optionsConditions;

    /**
     * Resource name to check for table rows to be selectable.
     *
     */
    private ?string $resourceSelectable = null;

    /**
     * @var string Icon size (as defined in Bootstrap CSS)
     */
    private string $iconSize = '1x';

    /**
     * TableService constructor.
     *
     * @param ObjectService   $objectService
     * @param AbstractAdapter $cacheAdapter
     */
    public function __construct(ObjectService $objectService, AbstractAdapter $cacheAdapter)
    {
        $this->objectService = $objectService;
        $this->cacheAdapter = $cacheAdapter;
    }

    public static function getRecordsPerPageList()
    {
        $tmp = [];
        foreach (self::RECORDS_PER_PAGE as $r) {
            $tmp[$r] = $r;
        }

        return $tmp;
    }

    /**
     * @return string
     */
    public function getIconSize(): string
    {
        return $this->iconSize;
    }

    /**
     * @param string $iconSize
     * @return TableService
     */
    public function setIconSize(string $iconSize): TableService
    {
        $this->iconSize = $iconSize;

        return $this;
    }

    /**
     * @return array
     */
    public function getRowActions(): array
    {
        return $this->rowActions;
    }

    public function getResourceSelectable(): ?string
    {
        return $this->resourceSelectable;
    }

    /**
     * @param string $resourceSelectable
     * @return TableService
     */
    public function setResourceSelectable(string $resourceSelectable): TableService
    {
        $this->resourceSelectable = $resourceSelectable;

        return $this;
    }

    public function setOptionCondition(string $option, callable $condition)
    {
        $this->optionsConditions[$option] = $condition;
    }

    public function getOptionCondition(string $option): callable
    {
        return $this->optionsConditions[$option] ?? function () {
                return true;
            };
    }

    public function getId(): string
    {
        return $this->id ?? '';
    }

    /**
     * Sets table id (stored in fieldOrder table).
     *
     * @param string $id
     */
    public function setId(string $id)
    {
        $this->id = $id;
    }

    public function getPaginator(): LaminasPaginator
    {
        return $this->paginator;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    /**
     * @return array
     */
    public function getOptions(): array
    {
        return $this->options;
    }

    /**
     * Sets specified option to a value.
     *
     * @param string $option Option name
     * @param bool   $value  Option value
     * @throws \Exception Thrown if trying to set unknown option
     */
    public function setOption(string $option, bool $value)
    {
        if (!isset($this->options[$option])) {
            throw new \Exception("Unknown option `$option`");
        }

        $this->options[$option] = $value;
    }

    /**
     * Gets specified option.
     *
     * @param string $option Option name
     * @return mixed
     * @throws \Exception Thrown if trying to get unknown option
     */
    public function getOption(string $option)
    {
        if (!isset($this->options[$option])) {
            throw new \Exception("Unknown option `$option`");
        }

        return $this->options[$option];
    }

    /**
     * @return int
     */
    public function getRecordsPerPage(): int
    {
        return $this->recordsPerPage;
    }

    /**
     * @param int $recordsPerPage
     */
    public function setRecordsPerPage(int $recordsPerPage)
    {
        $this->recordsPerPage = $recordsPerPage;
    }

    /**
     * Sets specified attribute to a value. $value can be either string or callable.
     *
     * @param string $name Attribute name
     * @param mixed  $value
     */
    public function setRowAttribute(string $name, $value)
    {
        $this->rowAttributes[$name] = $value;
    }

    /**
     * Return row attributes.
     *
     * @return array
     */
    public function getRowAttributes(): array
    {
        return $this->rowAttributes;
    }

    /**
     * Set table attribute.
     *
     * @param string $name
     * @param        $value
     */
    public function setAttribute(string $name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * Get table attributes.
     *
     * @return array
     */
    public function getAttributes(): array
    {
        return $this->attributes;
    }

    /**
     * Gets specified attribute.
     *
     * @param string $name
     * @return mixed|string
     */
    public function getAttribute(string $name)
    {
        return $this->attributes[$name] ?? '';
    }

    /**
     * @return string
     */
    public function getEditRoute(): ?string
    {
        return $this->editRoute;
    }

    /**
     * @param string $editRoute
     */
    public function setEditRoute(string $editRoute)
    {
        $this->editRoute = $editRoute;
    }

    /**
     * Configures data table's columns.
     * Every $columns element should have field name as a key.
     * Element's value is an array and can consist of keys:
     * - label (column label)
     * - helper (optional) view helper to format a value; string or an array with keys `name` and `params` (also an array)
     * - value (optional, value to display - by default it'll display entity's field (using getter);
     *          can be a closure returning string)
     * - sort (optional, field name to sort - with query alias ex. c.name)
     *
     * @param array $columns
     * @throws \Exception Thrown if table id is empty
     */
    public function configureColumns(array $columns)
    {
        if (empty($this->id)) {
            throw new \Exception("Table id cannot be empty");
        }

        $cacheKey = $this->objectService->generateObjectCacheKey(ObjectGroup::TYPE_TABLE, $this->id);
        if ($this->cacheAdapter->hasItem($cacheKey)) {
            $tmp = $this->cacheAdapter->getItem($cacheKey);
        } else {
            $object = $this->objectService->getAndFillObjectDetails(
                ObjectGroup::TYPE_TABLE,
                $this->id,
                array_keys($columns),
                array_column($columns, 'label')
            );
            $fields = $this->objectService->getDeviceFields($object->getFields()->toArray());

            $tmp = [];
            foreach ($fields as $key => $value) {
                // set label
                if (isset($columns[$key]['label']) && isset($value['label'])) {
                    $columns[$key]['label'] = $value['label'];
                }

                if (isset($columns[$key])) {
                    $tmp[$key] = $columns[$key];
                }
            }

            $this->cacheAdapter->setItem($cacheKey, $tmp);
        }

        $this->columns = $tmp;
    }

    /**
     * Performs search with given repository and passed search params.
     * Sets up a Paginator object.
     *
     * @param EntityRepository $repository Doctrine repository
     * @param array            $params     Query string (search) params
     * @throws \Exception Thrown if passed repository doesn't implement SearchableInterface
     */
    public function setRepository(EntityRepository $repository, array $params)
    {
        if (!$repository instanceof SearchableInterface) {
            throw new \Exception("Repository class must implement Hr\\Repository\\SearchableInterface interface");
        }

        $query = $repository->search($params);

        $page = $params['page'] ?? 1;
        if (isset($params['page'])) {
            unset($params['page']);
        }

        $params['perPage'] = $params['perPage'] ?? $this->recordsPerPage;
        $this->queryParams = $params;

        $this->paginator = new LaminasPaginator(new PaginatorAdapter(new ORMPaginator($query)));
        $this->paginator->setItemCountPerPage($params['perPage'])->setCurrentPageNumber($page);
    }

    public function setActivationActions(string $route)
    {
        $this->addRowAction(
            function ($record) use ($route) {
                return [
                    'title' => 'Aktywuj',
                    'icon' => 'play',
                    'class' => 'a-activate',
                    'route' => $route,
                    'route-params' => ['action' => 'activate', 'id' => $record->getId()],
                ];
            }, function ($record) {
            return !$record->getIsActive();
        }
        );
        $this->addRowAction(
            function ($record) use ($route) {
                return [
                    'title' => 'Dezaktywuj',
                    'icon' => 'pause',
                    'class' => 'a-deactivate',
                    'route' => $route,
                    'route-params' => ['action' => 'deactivate', 'id' => $record->getId()],
                ];
            }, function ($record) {
            return $record->getIsActive();
        }
        );
    }

    /**
     * Adds row action
     *
     * @param \Closure $params    Params closure
     * @param          $condition Condition closure
     */
    public function addRowAction(\Closure $params, $condition = null)
    {
        if (!is_callable($condition)) {
            $condition = function () {
                return true;
            };
        }
        $this->rowActions[] = ['params' => $params, 'condition' => $condition];
    }
}