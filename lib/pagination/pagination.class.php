<?php

class pagination
{
    
    //The registry reference
    private $registry;
    
    //The query to be paginated
    private $query="";
    
    //The query after pagination 
    private $paginatedQuery="";
    
    //The number of results per page
    private $limit=12;
    
    //The page number we are on
    private $offset=0;
    
    //Is this the first page?
    private $isFirst;
    
    //Is this the last page?
    private $isLast;
    
    //the current page number
    private $currentPage;
    
    //The total number of rows in the query
    private $numRows;
    
    //The number of pages in the query
    private $numPages;
    
    //The number of rows on this page (last page might have less rows than expected)
    private $numRowsPage;
    
    //The method of pagination, 'cache' or 'direct'
    private $method='direct';
    
    //The cache reference where we want to save the query
    private $cache;
    
    //The query holder where the results of pagination using direct method will be stored
    private $results;
    
    public function __construct(Registry $registry)
    {
        $this->registry=$registry;
    }
    
    public function setMethod($method)
    {
        $this->method=$method;
    }
    
    public function setOffset($offset)
    {
        $this->offset=$offset;
    }
    
    public function setLimit($limit)
    {
        $this->limit=$limit;
    }
    
    public function setQuery($query)
    {
        $this->query=$query;
    }
    
    public function generatePagination()
    {
        $tempquery=$this->query;
        $this->registry->getObject('db')->executeQuery($tempquery);
        $this->numRows=$this->registry->getObject('db')->numRows();
        $limit=" limit ";
        $limit.=($this->offset*$this->limit)." , ".$this->limit;
        $tempquery.=$limit;
        
        $this->paginatedQuery=$tempquery;
        
        if($this->method=='cache')
        {
            $this->cache=$this->registry->getObject('db')->cacheQuery($this->paginatedQuery);
        }
        elseif($this->method=='direct')
        {
            $this->registry->getObject('db')->executeQuery($this->paginatedQuery);
            $this->results=$this->registry->getObject('db')->getRows();
        }
        
        $this->numPages=ceil((int)$this->numRows/(int)$this->limit);
        
        $this->isFirst=($this->offset==0)?true:false;
        
        $this->isLast=($this->offset+1==$this->numPages)?true:false;
        
        $this->currentPage=($this->numRows==0)?0:$this->offset+1;
        
        $this->numRowsPage=$this->registry->getObject('db')->numRows();
        
        if($this->numRowsPage==0)
        {
            return false;
        }
        else
        {
            return true;
        }        
    }
    
       public function getCache()
        {
            return $this->cache;
        }
        
        public function getResults()
        {
            return $this->results;
        }
        
        public function getNumPages()
        {
            return $this->numPages;
        }
        
        public function isFirst()
        {
            return $this->isFirst;
        }
        
        public function isLast()
        {
            return $this->isLast;
        }
        
        public function getCurrentPage()
        {
            return $this->currentPage;
        }
		
		public function getNumRowsPage()
		{
			return $this->numRowsPage;
		}
}

?>
