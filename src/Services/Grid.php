<?php

namespace Joesama\VueGrid\Services;

use Illuminate\Database\Eloquent\Builder;
use Joesama\VueGrid\Traits\DataModeller;
use Joesama\VueGrid\Traits\GridAction;

/**
 * Services to generate datagrid using vue.js.
 *
 * @author joharijumali@gmail.com
 **/
class Grid
{
    use DataModeller,GridAction;

    /**
     * Table Title.
     */
    protected $title;

    /**
     * Columns To Be Previews.
     */
    protected $columns;

    /**
     * Query Builder To Be Generate.
     */
    protected $builder;

    /**
     * Paginate Items.
     */
    protected $items = null;

    /**
     * Data API Path URL.
     */
    protected $api;

    /**
     * Add Button.
     */
    protected $add = null;
    protected $addDesc = null;

    /**
     * Actions Button.
     */
    protected $actions = false;
    protected $simple = false;

    /**
     * Search Display.
     */
    protected $search = true;

    /**
     * Data Filtering.
     */
    protected $autoFilter = false;

    /**
     * Paginate Numbers.
     */
    public $paginate = 20;

    /**
     * Style Row.
     */
    public $styleRow;

    /**
     * Set Table Title.
     *
     * @param string $title
     *
     **/
    public function setTitle($title)
    {
        $this->title = strtoupper($title);
    }

    /**
     * Generate Columns For Table.
     *
     * @param array $columns
     *
     **/
    public function setColumns(array $columns)
    {
        if (empty($columns)):
            throw new \Exception('Please Define Fields Want To Be Display', 404);
        endif;

        $this->columns = $columns;
    }

    /**
     * Generate Data Model.
     *
     * @param Illuminate\Database\Eloquent\Builder $model
     * @param array                                $columns
     *
     **/
    public function setModel($model, $columns = [])
    {
        $this->columns = (empty($columns)) ? $this->columns : $columns;

        $this->items = $model;
    }

    /**
     * URI for Data API.
     *
     * @param string $url
     **/
    public function apiUrl($url)
    {
        $this->api = $url;
    }

    /**
     * Edit action.
     *
     * @param array $actions
     **/
    public function action($actions, $simple = true)
    {
        $this->actions = $this->checkingActions($actions);
        $this->simple = $simple;
    }

    /**
     * TODO : checboxes.
     *
     * @return void
     *
     * @author
     **/
    public function checkboxes()
    {
    }

    /**
     * Display Search Function.
     *
     * @return void
     *
     * @author
     **/
    public function showSearch($okay = true)
    {
        $this->search = $okay;
    }

    /**
     * Auto Filter Function.
     *
     * @return void
     *
     * @author
     **/
    public function autoFilter($okay = true)
    {
        $this->autoFilter = $okay;
    }

    /**
     * Add action.
     *
     * @param string $url
     **/
    public function add($url, $urlDesc = null)
    {
        $this->add = $url;
        $this->addDesc = $urlDesc;
    }

    /**
     * Define Row Style Configuration.
     *
     * @param string $style, $field = NULL, $definer = NULL
     *
     **/
    public function rowStyler($style, $field = null, $definer = null)
    {
        $this->styleRow = collect(['style' => $style, 'field' => $field, 'definer' => $definer]);
    }

    /**
     * Build And Generate Data grid Table.
     *
     * @return void
     **/
    public function build()
    {
        // @TODO IF Sources Is Collection / Builder Build Pagination
        // if(!is_null($this->items)):
        // 	$items = $this->buildPaginators($this->items);
        // endif;

        $data = [
            'swalert' => [
                'confirm' => [
                    'title'   => trans('joesama/vuegrid::datagrid.delete.confirm.title'),
                    'text'    => trans('joesama/vuegrid::datagrid.delete.confirm.text'),
                    'proceed' => trans('joesama/vuegrid::datagrid.delete.confirm.proceed'),
                    'failed'  => trans('joesama/vuegrid::datagrid.delete.confirm.failed'),
                ],
                'cancel' => [
                    'title' => trans('joesama/vuegrid::datagrid.delete.cancel.title'),
                    'text'  => trans('joesama/vuegrid::datagrid.delete.cancel.text'),
                ],
            ],
            'autoFilter'        => $this->autoFilter,
            'title'        		   => $this->title,
            'search'            => $this->search,
            'column'            => $this->columns,
            'api'               => $this->api,
            'add'               => $this->add,
            'addDesc'           => $this->addDesc,
            'actions'           => $this->actions,
            'simple'            => $this->simple,
            'rowCss'            => $this->styleRow,
            'data'              => $this->items->items(),
            'data_total'        => $this->items->total(),
            'data_per_page'     => $this->items->perPage(),
            'data_current_page' => $this->items->currentPage(),
            'data_last_page'    => $this->items->lastPage(),
            'data_from'         => $this->items->firstItem(),
            'data_to'           => $this->items->lastItem(),
        ];

        return view('joesama/vuegrid::list', compact('data'));
    }
} // END class VueDatagrid
