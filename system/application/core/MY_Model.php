<?php
 /*
All application code, styles and layouts
Copyright 2013 Phil Stephens
All rights reserved
phil@othertribe.com for more information
*/
 
/**
 * A base model to provide the basic CRUD
 * actions for all models that inherit from it.
 *
 * @package CodeIgniter
 * @subpackage MY_Model
 * @license GPLv3 <http://www.gnu.org/licenses/gpl-3.0.txt>
 * @link http://github.com/philsturgeon/codeigniter-base-model
 * @version 1.3
 * @author Jamie Rumbelow <http://jamierumbelow.net>
 * @modified Phil Sturgeon <http://philsturgeon.co.uk>
 * @modified Dan Horrigan <http://dhorrigan.com>
 * @modified Phil Stephens <http://rezioapp.com>
 * @copyright Copyright (c) 2009, Jamie Rumbelow <http://jamierumbelow.net>
 */

class MY_Model extends CI_Model  
{
	/**
	 * The database table to use, only
	 * set if you want to bypass the magic
	 *
	 * @var string
	 */
	protected $_table;

	/**
	 * The primary key, by default set to
	 * `MODELNAME_id`, for use in some functions.
	 *
	 * @var string
	 */
	protected $primary_key;

	/**
	 * An array of functions to be called before
	 * a record is created.
	 *
	 * @var array
	 */
	protected $before_create = array();
	protected $before_update = array();

	/**
	 * An array of functions to be called after
	 * a record is created.
	 *
	 * @var array
	 */
	protected $after_create = array();
	protected $after_update = array();

	/**
	 * An array of validation rules
	 *
	 * @var array
	 */
	protected $validate = array();

	/**
	 * Skip the validation
	 *
	 * @var bool
	 */
	protected $skip_validation = FALSE;
	
	/**
	 * The 'created_at' field, if present
	 *
	 * @var string
	 */
	protected $created_at;
	protected $deleted_at;
	
	protected $account_id_field;
	protected $account_id;

	protected $paranoid = TRUE;


	/**
	 * The class constructer, tries to guess
	 * the table name.
	 *
	 * @author Jamie Rumbelow
	 * @modified Phil Stephens
	 */

	public function __construct()
	{
		parent::__construct();
		$this->load->helper('inflector');
		$this->_fetch_table();
		$this->_fetch_primary_key();
		$this->_fetch_created_at();
		$this->_fetch_account_id();

		if($this->paranoid)
		{
			$this->_fetch_deleted_at();
		}
	}

	/**
	 * Get a single record by creating a WHERE clause with
	 * a value for your primary key
	 *
	 * @param string $primary_value The value of your primary key
	 * @return object
	 * @author Phil Sturgeon
	 */
	public function get($primary_value)
	{
		$this->is_paranoid();

		return $this->db->where($this->primary_key, $primary_value)
			->get($this->_table)
			->row();
	}

	public function is_paranoid()
	{
		if ($this->paranoid && $this->db->field_exists($this->deleted_at, $this->_table))
		{
			$this->db->where($this->deleted_at, 0);
		}
	}

	/**
	 * Get a single record by creating a WHERE clause with
	 * the key of $key and the value of $val.
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return object
	 * @author Phil Sturgeon
	 */
	public function get_by()
	{
		$where = func_get_args();
		$this->_set_where($where);

		$this->is_paranoid();

		$result = $this->db->get($this->_table)
			->row();
		
		//echo $this->db->last_query();

		return $result;
	}

	/**
	 * Similar to get_by(), but returns a result array of
	 * many result objects.
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return array
	 * @author Phil Sturgeon
	 */
	public function get_many($primary_value)
	{
		$this->is_paranoid();

		$this->db->where($this->primary_key, $primary_value);
		return $this->get_all();
	}

	/**
	 * Similar to get_by(), but returns a result array of
	 * many result objects.
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return array
	 * @author Phil Sturgeon
	 */
	public function get_many_by()
	{
		$this->is_paranoid();

		$where = func_get_args();
		$this->_set_where($where);

		return $this->get_all();
	}

	/**
	 * Get all records in the database
	 *
	 * @param	string 	Type object or array
	 * @return 	mixed
	 * @author 	Jamie Rumbelow
	 */
	public function get_all()
	{
		$this->is_paranoid();

		return $this->db->get($this->_table)->result();
	}

	/**
	 * Similar to get_by(), but returns a result array of
	 * many result objects.
	 *
	 * @param string $key The key to search by
	 * @param string $val The value of that key
	 * @return array
	 * @author Phil Sturgeon
	 */
	public function count_by()
	{
		$this->is_paranoid();

		$where = func_get_args();
		$this->_set_where($where);

		return $this->db->count_all_results($this->_table);
	}

	/**
	 * Get all records in the database
	 *
	 * @return array
	 * @author Phil Sturgeon
	 */
	public function count_all()
	{
		$this->is_paranoid();

		return $this->db->count_all($this->_table);
	}

	/**
	 * Insert a new record into the database,
	 * calling the before and after create callbacks.
	 * Returns the insert ID.
	 *
	 * @param array $data Information
	 * @return integer
	 * @author Jamie Rumbelow
	 * @modified Dan Horrigan
	 */
	public function insert($data, $skip_validation = FALSE)
	{
		$valid = TRUE;
		if($skip_validation === FALSE)
		{
			$valid = $this->_run_validation($data);
		}

		if($valid)
		{
			$data = $this->_run_before_create($data);
			
			
			if(empty($data[$this->created_at]) && $this->db->field_exists($this->created_at, $this->_table))
			{
				$this->db->set($this->created_at, 'NOW()', FALSE);
			}
			
			$this->db->insert($this->_table, $data);
			
			$id = $this->db->insert_id();

			$this->_run_after_create($data, $id);

			$this->skip_validation = FALSE;

			return $id;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Similar to insert(), just passing an array to insert
	 * multiple rows at once. Returns an array of insert IDs.
	 *
	 * @param array $data Array of arrays to insert
	 * @return array
	 * @author Jamie Rumbelow
	 * @modified Phil Stephens
	 */
	public function insert_many($data, $skip_validation = FALSE)
	{
		$ids = array();

		foreach ($data as $row)
		{
			$ids[] = $this->insert($row, $skip_validation);
		}

		$this->skip_validation = FALSE;
		return $ids;
	}

	/**
	 * Update a record, specified by an ID.
	 *
	 * @param integer $id The row's ID
	 * @param array $array The data to update
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function update($primary_value, $data, $skip_validation = FALSE)
	{
		$valid = TRUE;
		if($skip_validation === FALSE)
		{
			$valid = $this->_run_validation($data);
		}

		if($valid)
		{
			$data = $this->_run_before_update($data);
			
			$this->skip_validation = FALSE;
			
			$updated = $this->db->where($this->primary_key, $primary_value)
				->set($data)
				->update($this->_table);


			$this->_run_after_update($data, $primary_value);

			return $updated;
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Update a record, specified by $key and $val.
	 *
	 * @param string $key The key to update with
	 * @param string $val The value
	 * @param array $array The data to update
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function update_by()
	{
		$args = func_get_args();
		$data = array_pop($args);
		$this->_set_where($args);

		if($this->_run_validation($data))
		{
			$this->skip_validation = FALSE;
			return $this->db->set($data)
				->update($this->_table);
		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Updates many records, specified by an array
	 * of IDs.
	 *
	 * @param array $primary_values The array of IDs
	 * @param array $data The data to update
	 * @return bool
	 * @author Phil Sturgeon
	 */
	public function update_many($primary_values, $data, $skip_validation)
	{
		$valid = TRUE;
		if($skip_validation === FALSE)
		{
			$valid = $this->_run_validation($data);
		}

		if($valid)
		{
			$this->skip_validation = FALSE;
			return $this->db->where_in($this->primary_key, $primary_values)
				->set($data)
				->update($this->_table);

		}
		else
		{
			return FALSE;
		}
	}

	/**
	 * Updates all records
	 *
	 * @param array $data The data to update
	 * @return bool
	 * @since 1.1.3
	 * @author Phil Sturgeon
	 */
	public function update_all($data)
	{
		return $this->db->set($data)
			->update($this->_table);
	}

	/**
	 * Delete a row from the database table by the
	 * ID.
	 *
	 * @param integer $id
	 * @return bool
	 * @author Jamie Rumbelow
	 */
	public function delete($id)
	{
		$this->db->where($this->primary_key, $id);

		if ($this->paranoid && $this->db->field_exists($this->deleted_at, $this->_table))
		{
			$this->db->set($this->deleted_at, 'NOW()', FALSE);
			return $this->db->update($this->_table);
		} else
		{
			return $this->db->delete($this->_table);	
		}
	}

	/**
	 * UNdelete a row from the database table by the
	 * ID.
	 *
	 * @param integer $id
	 * @return bool
	 * @author Phil Stephens
	 */
	public function undelete($id)
	{
		$this->db->where($this->primary_key, $id);

		if ($this->paranoid && $this->db->field_exists($this->deleted_at, $this->_table))
		{
			$this->db->set($this->deleted_at, '0000-00-00 00:00:00');
			return $this->db->update($this->_table);
		} else
		{
			return FALSE;	
		}
	}

	/**
	 * Delete a row from the database table by the
	 * key and value.
	 *
	 * @param string $key
	 * @param string $value
	 * @return bool
	 * @author Phil Sturgeon
	 */
	public function delete_by()
	{
		$where =& func_get_args();
		$this->_set_where($where);

		if ($this->paranoid && $this->db->field_exists($this->deleted_at, $this->_table))
		{
			$this->db->set($this->deleted_at, 'NOW()', FALSE);
			return $this->db->update($this->_table);
		} else
		{
			return $this->db->delete($this->_table);	
		}
	}

	/**
	 * Delete many rows from the database table by
	 * an array of IDs passed.
	 *
	 * @param array $primary_values
	 * @return bool
	 * @author Phil Sturgeon
	 */
	public function delete_many($primary_values)
	{
		$this->db->where_in($this->primary_key, $primary_values);

		if ($this->paranoid && $this->db->field_exists($this->deleted_at, $this->_table))
		{
			$this->db->set($this->deleted_at, 'NOW()', FALSE);
			return $this->db->update($this->_table);
		} else
		{
			return $this->db->delete($this->_table);	
		}
	}
		
	function dropdown()
	{
		$args =& func_get_args();

		if(count($args) == 2)
		{
			list($key, $value) = $args;
		} else
		{
			$key = $this->primary_key;
			$value = $args[0];
		}
		
		$this->is_paranoid();

		$query = $this->db->select(array($key, $value))
			->get($this->_table);

		$options = array();
		
		foreach ($query->result() as $row)
		{
			$options[$row->{$key}] = $row->{$value};
		}

		return $options;
	}

	/**
	* Orders the result set by the criteria,
	* using the same format as CI's AR library.
	*
	* @param string $criteria The criteria to order by
	* @return object	$this
	* @since 1.1.2
	* @author Jamie Rumbelow
	*/
	public function order_by($criteria, $order = 'ASC')
	{
		$this->db->order_by($criteria, $order);
		return $this;
	}

	/**
	* Limits the result set by the integer passed.
	* Pass a second parameter to offset.
	*
	* @param integer $limit The number of rows
	* @param integer $offset The offset
	* @return object	$this
	* @since 1.1.1
	* @author Jamie Rumbelow
	*/
	public function limit($limit, $offset = 0)
	{
		$limit =& func_get_args();
		$this->_set_limit($limit);
		return $this;
	}

	/**
	* Removes duplicate entries from the result set.
	*
	* @return object	$this
	* @since 1.1.1
	* @author Phil Sturgeon
	*/
	public function distinct()
	{
		$this->db->distinct();
		return $this;
	}

	/**
	 * Runs the before create actions.
	 *
	 * @param array $data The array of actions
	 * @return void
	 * @author Jamie Rumbelow
	 */
	private function _run_before_create($data)
	{
		foreach ($this->before_create as $method)
		{
			$data = call_user_func_array(array($this, $method), array($data));
		}

		return $data;
	}
	
	private function _run_before_update($data)
	{
		foreach ($this->before_update as $method)
		{
			$data = call_user_func_array(array($this, $method), array($data));
		}

		return $data;
	}

	/**
	 * Runs the after create actions.
	 *
	 * @param array $data The array of actions
	 * @return void
	 * @author Jamie Rumbelow
	 */
	private function _run_after_create($data, $id)
	{
		foreach ($this->after_create as $method)
		{
			call_user_func_array(array($this, $method), array($data, $id));
		}
	}

	/**
	 * Runs the after update actions.
	 *
	 * @param array $data The array of actions
	 * @return void
	 * @author Phil Stephens
	 */
	private function _run_after_update($data, $id)
	{
		foreach ($this->after_update as $method)
		{
			call_user_func_array(array($this, $method), array($data, $id));
		}
	}

	/**
	 * Runs validation on the passed data.
	 *
	 * @return bool
	 * @author Dan Horrigan
	 */
	private function _run_validation($data)
	{
		if($this->skip_validation)
		{
			return TRUE;
		}
		if(!empty($this->validate))
		{
			foreach($data as $key => $val)
			{
				$_POST[$key] = $val;
			}
			$this->load->library('form_validation');
			if(is_array($this->validate))
			{
				$this->form_validation->set_rules($this->validate);
				return $this->form_validation->run();
			}
			else
			{
				$this->form_validation->run($this->validate);
			}
		}
		else
		{
			return TRUE;
		}
	}

	/**
	 * Fetches the table from the pluralised model name.
	 *
	 * @return void
	 * @author Jamie Rumbelow
	 * @modified Phil Stephens
	 */
	private function _fetch_table()
	{
		if ($this->_table == NULL)
		{
			$class = preg_replace('/(' . MODEL_SUFFIX . ')?$/', '', get_class($this));

			$this->_table = plural(strtolower($class));
		}
	}
	
	/**
	 * Guesses the primary key from the model name.
	 *
	 * @return void
	 * @author Phil Stephens
	 */
	private function _fetch_primary_key()
	{
		if ($this->primary_key == NULL)
		{
			$class = preg_replace('/(' . MODEL_SUFFIX . ')?$/', '', get_class($this));

			$this->primary_key = strtolower($class) . '_id';
		}
	}

	/**
	 * Guesses the created_at field from the model name.
	 *
	 * @return void
	 * @author Phil Stephens
	 */
	private function _fetch_created_at()
	{
		if ($this->created_at == NULL)
		{
			$class = preg_replace('/(' . MODEL_SUFFIX . ')?$/', '', get_class($this));

			$this->created_at = strtolower($class) . '_created_at';
		}
	}

	private function _fetch_deleted_at()
	{
		if ($this->deleted_at == NULL)
		{
			$class = preg_replace('/(' . MODEL_SUFFIX . ')?$/', '', get_class($this));

			$this->deleted_at = strtolower($class) . '_deleted_at';
		}
	}


	/**
	 * Sets where depending on the number of parameters
	 *
	 * @return void
	 * @author Phil Sturgeon
	 */
	protected function _set_where($params)
	{
		if(count($params) == 1)
		{
			$this->db->where($params[0]);
		}

		else
		{
			$this->db->where($params[0], $params[1]);
		}
	}

	/**
	 * Sets limit depending on the number of parameters
	 *
	 * @return void
	 * @author Phil Sturgeon
	 */
	private function _set_limit($params)
	{
		if(count($params) == 1)
		{
			if(is_array($params[0]))
			{
				$this->db->limit($params[0][0], $params[0][1]);
			}

			else
			{
				$this->db->limit($params[0]);
			}
		}

		else
		{
			$this->db->limit( (int) $params[0], (int) $params[1]);
		}
	}

	/**
	 * Guesses the account_id field from the model name.
	 *
	 * @return void
	 * @author Phil Stephens
	 */
	private function _fetch_account_id()
	{
		if ($this->account_id_field == NULL)
		{
			$class = preg_replace('/(' . MODEL_SUFFIX . ')?$/', '', get_class($this));

			$this->account_id_field = strtolower($class) . '_account_id';
		}
	}

	public function _set_account_id($account_id = null)
	{
		$this->account_id = ( ! empty($account_id)) ? $account_id : $this->account->val('id');
	}

	protected function model($name)
	{
		$name = $name . MODEL_SUFFIX;
		
		// is there a module involved
		$model_name = explode('/', $name);
		
		if ( ! isset($this->{end($model_name)}) )
		{
			$this->load->model($name, '', TRUE);
		}

		return $this->{end($model_name)};
	}
}