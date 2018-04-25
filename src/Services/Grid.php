<?php 
namespace Joesama\VueGrid\Services;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\Builder;
use Joesama\VueGrid\Traits\DataModeller;
use JavaScript;

/**
 * Services to generate datagrid using vue.js
 *
 * @package joesama/vuegrid
 * @author joharijumali@gmail.com
 **/
class Grid 
{
	use DataModeller;

    /**
     * Columns To Be Previews
     */
    protected $columns;

    /**
     * Query Builder To Be Generate
     */
    protected $builder;

    /**
     * Paginate Items
     */
    protected $items = NULL;

    /**
     * Data API Path URL
     */
    protected $api;

    /**
     * Add Button
     */
    protected $add = NULL;
    protected $addDesc = NULL;

    /**
     * Actions Button
     */
    protected $actions = FALSE;
    protected $simple = FALSE;

    /**
     * Search Display
     */
    protected $search = TRUE;

    /**
     * Data Filtering
     */
    protected $autoFilter = FALSE;


    /**
     * Paginate Numbers
     */
    public $paginate = 20;

    /**
     * Style Row
     */
    public $styleRow;


	/**
	 * Generate Columns For Table
	 *
	 * @param array $columns
	 *
	 **/
	public function setColumns(Array $columns)
	{
		$this->columns = $columns;
	}


	/**
	 * Generate Data Model
	 *
	 * @param Illuminate\Database\Eloquent\Builder $model
	 * @param array $columns
	 * 
	 **/
	public function setModel($model, $columns = [])
	{
		$this->columns = (empty($columns)) ? $this->columns  : $columns;

		$this->items = $model;


	}

	/**
	 * URI for Data API
	 *
	 * @param string $url
	 **/
	public function apiUrl($url)
	{
		$this->api = $url;
	}

	/**
	 * Edit action
	 *
	 * @param array $actions
	 **/
	public function action($actions, $simple = FALSE)
	{
		$this->actions = $actions;
		$this->simple = $simple;
	}

	/**
	 * TODO : checboxes
	 *
	 * @return void
	 * @author 
	 **/
	public function checkboxes()
	{
	}

	/**
	 * Display Search Function
	 *
	 * @return void
	 * @author 
	 **/
	public function showSearch($okay = TRUE)
	{
		$this->search = $okay;
	}

	/**
	 * Auto Filter Function
	 *
	 * @return void
	 * @author 
	 **/
	public function autoFilter($okay = TRUE)
	{
		$this->autoFilter = $okay;
	}

	/**
	 * Add action
	 *
	 * @param string $url
	 **/
	public function add($url,$urlDesc = NULL)
	{
		$this->add = $url;
		$this->addDesc = $urlDesc;
	}

	/**
	 * Define Row Style Configuration
	 *
	 * @param string  $style, $field = NULL, $definer = NULL
	 *
	 **/
	public function rowStyler($style, $field = NULL, $definer = NULL)
	{
		$this->styleRow = collect([ 'style' => $style,'field' => $field,'definer' => $definer ]);
	}

	/**
	 * Build And Generate Data grid Table
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
					'title' => trans('joesama/vuegrid::datagrid.delete.confirm.title'),
					'text' => trans('joesama/vuegrid::datagrid.delete.confirm.text'),
					'proceed' => trans('joesama/vuegrid::datagrid.delete.confirm.proceed')
				],
				'cancel' => [
					'title' => trans('joesama/vuegrid::datagrid.delete.cancel.title'),
					'text' => trans('joesama/vuegrid::datagrid.delete.cancel.text')
				]
			],
			'autoFilter' => $this->autoFilter,
			'search' => $this->search,
	        'column' => $this->columns,
	        'api' => $this->api,
	        'add' => $this->add,
	        'addDesc' => $this->addDesc,
	        'actions' => $this->actions,
	        'simple' => $this->simple,
	        'rowCss' => $this->styleRow,
	       	'data' => $this->items->items(),
	        'data_total' => $this->items->total(),
	        'data_per_page' => $this->items->perPage(),
	        'data_current_page' => $this->items->currentPage(),
	        'data_last_page' => $this->items->lastPage(),
	        'data_from' => $this->items->firstItem(),
	        'data_to' => $this->items->lastItem()
	    ];


	    return view('joesama/vuegrid::datagrid',compact('data'));
	}



} // END class VueDatagrid 