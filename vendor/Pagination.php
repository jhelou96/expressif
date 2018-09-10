<?php
namespace vendor;

class Pagination {
	private $_nbMsgsPerPage;
	private $_actualPage;
	private $_nbPages;
	private $_currentIndex;
	private $_nbRecords;
	
	public function __construct() {
		$this->_nbMsgsPerPage = 10;
		$this->_nbRecords = 0;
		$this->_actualPage = 1;
	}
	
	public function execute() {
		$this->_nbPages = ceil($this->_nbRecords/$this->_nbMsgsPerPage);
		
		if($this->_nbRecords == 0)
			$this->_nbPages = 1;
		
		if($this->_actualPage > $this->_nbPages)
			$this->_actualPage = $this->_nbPages;
		

		$this->_currentIndex = ($this->_actualPage-1) * $this->_nbMsgsPerPage; 
	}
	
	public function parse() {
		$url = parse_url('http://'.$_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI']);
		$path = $url['path'];
		
		if(preg_match('#/page/[0-9]#', $path) == 1)
			$path = preg_replace('#/page/[0-9]#', '/page/', $path);
		else
			$path .= '/page/';
		
		$html = '<center><ul class="pagination">';
		
		if($this->_actualPage != 1) {
			$html .= '<li>
				<a href=" ' . $path . ($this->_actualPage-1) . '" aria-label="Previous">
					<span aria-hidden="true">&laquo;</span>
				</a>
			</li>';
		}						
		
		for($page = 1; $page <= $this->_nbPages; $page++) {
			if($page == $this->_actualPage)
				$html .= '<li class="active"><span>' . $page . '</span></li>';
			else
				$html .= '<li><a href="' . $path . $page . '">' . $page . '</a></li>';
		}			
					
		if($this->_actualPage != $this->_nbPages) {
			$html .= '<li>
				<a href="' . $path . ($this->_actualPage+1) . '" aria-label="Next">
					<span aria-hidden="true">&raquo;</span>
				</a>
			</li>';
		}
				
		$html .= '</ul></center>';
		
		return $html;
	}
	
	public function sqlLimit() {
		return ('LIMIT ' . (int) $this->_currentIndex . ', ' . (int) $this->_nbMsgsPerPage . '');
	}
	
	//GETTERS AND SETTERS
	public function getNbMsgsPerPage() {
		return $this->_nbMsgsPerPage;
	}
	
	public function setNbMsgsPerPage($nbMsgsPerPage) {
		if(is_numeric($nbMsgsPerPage))
			$this->_nbMsgsPerPage = $nbMsgsPerPage;
		else
			throw new Exception("NbMsgsPerPage: Value should be numeric");
	}
	
	public function getActualPage() {
		return $this->_actualPage;
	}
	
	public function setActualPage($actualPage) {
		if(is_numeric($actualPage))
			$this->_actualPage = $actualPage;
		else
			throw new Exception("ActualPage: Value should be numeric");
	}
	
	public function getNbRecords() {
		return $this->_nbRecords;
	}
	
	public function setNbRecords($nbRecords) {
		if(is_numeric($nbRecords))
			$this->_nbRecords = $nbRecords;
		else
			throw new Exception("nbRecords: Value should be numeric");
	}
	
	public function getNbPages() {
		return $this->_nbPages;
	}
}