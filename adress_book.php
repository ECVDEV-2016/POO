<?php 

$DB = array(
		'people' => [],
		'organization' => [],
		'people_organization' => []
);

interface MyIterator extends Traversable{
	 public function current();
	 public function key();
	 public function next();
	 public function rewind();
	 public function valid();
}

class Person {
	public $given_name;
	public $last_name;
	public $email;
	
	function __construct($properties = []){
		$this->given_name = $properties['given_name'];
		$this->last_name = $properties['last_name'];
		$this->email = $properties['email'];
	}
	
	static public function create($db, $options = []){
		$instance = new self($options);
		array_push($db['people'], $instance);
		return $instance;
	}
	
	public function getOrganizationCollection(){
		
	}
}

class Organization {
	public  $name;
	public  $url;
	
	function __construct($properties = []){
		$this->name = $properties['name'];
		$this->url = $properties['url'];
	}
	
	function getPeopleCollection(){
		
	}
}

class PersonOrganization {
	private $_person;
	private $_organization;
	
	function __construct($person, $organization){
		$this->_person = $person;
		$this->_organization = $organization;
	}
	
	public function getOrganization(){
		return $this->_organization;
	}
	
	public function getPerson(){
		return $this->_person;
	}
}
abstract class Collection implements Iterator{
	private $_elements;
	
	function __construct(){
		$this->_elements = [];
	}
	
	public function current(){
		return $this->_elements[$this->_position];
	}
	
	public function key(){
		return $this->_position;
	}
	
	public function next(){
		++$this->_position;
	}
	
	public function rewind(){
		$this->_position = 0;
	}
	
	public function valid(){
		return isset($this->_elements[$this->_position]);
	}
	
	public function append($item){
		array_push($this->_elements, $item);
	}
	public function where($query = array()){
		$result = new self();
		foreach ($this->all() as $obj){
			$doAppend = true;
			foreach ($query as $field => $value){
				if($obj->$field != $value){
					$doAppend = false;
				}
			}
			if($doAppend){
				$result->append($obj);
			}
		}
		return $result;
	}
	public function all(){
		return $this->_elements;
	}	
}
class PersonCollection extends Collection{
	
}

class OrganizationCollection extends Collection{
	
}

class PersonOrganizationCollection extends Collection{
	
}

$ecv = new Organization(array('name' => 'ECV', 'url' => 'http://ecvdigital.fr'));
$rober = new Person(array('given_name' => 'Robert', 'last_name' => 'de Niro', 'email' => 'robert@de.niro.movie'));
$roberEcv = new PersonOrganization($rober, $ecv);
$paramount = new Organization(array('name' => 'paramount', 'url' => 'http://paramount.fr'));

$allOrganization  = new OrganizationCollection();
$allOrganization->append($paramount);
$allOrganization->append($ecv);

$rober->getOrganizationCollection();
$ecv->getPeopleCollection();

foreach ($allOrganization as $key => $value){
	var_dump($key,$value);
}

?>