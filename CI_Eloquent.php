<?php
/**
 * @author  anthony.ras
 * Codeigniter 3 Eloquent ORM module
 */
class CI_Eloquent extends CI_Model
{
	protected $attributes = NULL;
	protected $retrievable = [];
	protected $except 	   = [];
	protected $config;

	function __construct($config = NULL) 
	{
		if( is_null( $this->attributes ) ) 
		{
			$this->attributes = new \stdClass;
		}
		if( !is_null($config )) 
		{
			$this->config = $config;
		}
	}

	function __set($key, $value)
	{
		$this->attributes{$key} = $value; 	
	}

	/**
	 * 	everytime you call the object it will check if there is a ci key on that
	 * 	if none it will check the attributes.
	 * @param  [type] $key [description]
	 * @return [type]      [description]
	 */
	function __get($key)
	{
		if(property_exists(get_called_class(), $key))
		{
			return $this->{$key};
		}
		return $this->attributes->{$key};
	}

	/**
	 * @author  anthony.ras
	 *
	 * This will still be effiecient since we are still using the singleton method of CI
	 *  
	 * @param  String $method 		name of the method called statically
	 * @param  Array $args   		Arguments being passed.
	 * @return Object newInstance   returns the new instance of the object.
	 */
	static function __callStatic($method, $args)
	{
		$class = get_called_class();
		$caller = $method;
		if(!method_exists($class, $caller))
		{
			throw new \Error("Method Not Found {$caller}", 1);
									
		}
		$class_instance = new $class;
		return $class_instance->{$caller}( ...$args );
		
	}

	private function find(int $id = 0) 
	{
		$ci =& get_instance();
		$this->attributes = $ci->db
			->where($this->primary_key, $id)
			->get($this->table)
			->row();
		return $this;
	}

	public function get_attributes()
	{
		return $this->attributes;
	}
}
