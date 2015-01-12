<?php
namespace Admin\View\Helper;

use Zend\View\Helper\AbstractHelper;

class PaginationHelper extends AbstractHelper
{
    private $resultsPerPage;
    private $totalResults;
    private $results;
    private $baseUrl;
    private $paging;
    private $page;

    public function __invoke($pagedResults, $page, $baseUrl, $resultsPerPage = 10)
    {
        $this->resultsPerPage = $resultsPerPage;
        $this->totalResults = $pagedResults->count();
        $this->results = $pagedResults;
        $this->baseUrl = $baseUrl;
        $this->page = $page;

        return $this->generatePaging();
    }

    /**
     * Generate paging html
     */
    private function generatePaging()
    {
        # Get total page count
        $pages = ceil($this->totalResults / $this->resultsPerPage);

        # Don't show pagination if there's only one page
        if($pages == 1)
        {
            return;
        }

        $this->paging = '<ul class="pagination pagination-sm">';
        # Show back to first page if not first page
        if($this->page != 1)
        {
            $this->paging .= "<li><a href='" . $this->baseUrl . "page/1'> First </a></li>";
            $this->paging .= "<li><a href=" . $this->baseUrl . "page/" . ($this->page - 1) . "> « </a></li>";
        }

        # Calculating range 
        if(($pageCount = $this->page - 3) < 1)
            $pageCount = 1;

        if(($this->page + 3) > $pages)
            if(($pageCount = $pages - 6) < 1)
                $pageCount = 1;

        if((3 - $this->page) < 0)
            $range = 3;
        else
            $range = 7 - $this->page;

        if(($maxPage = $this->page + $range) > $pages)
            $maxPage = $pages;

        # Create a link for each page in rage
        while($pageCount <= $maxPage)
        {
            if($pageCount == $this->page)
                $this->paging .= '<li class="active"><a href="#">' . $pageCount . '</a></li>';
            else
                $this->paging .= "<li><a href=" . $this->baseUrl . "page/" . $pageCount . "> " . $pageCount . " </a></li>";
            $pageCount++;
        }

        # Show go to last page option if not the last page
        if($this->page != $pages)
        {
            $this->paging .= "<li><a href=" . $this->baseUrl . "page/" . ($this->page + 1) . "> » </a></li>";
            $this->paging .= "<li><a href='" . $this->baseUrl . "page/" . $pages . "'> Last </a></li>";
        }

        $this->paging .= "</ul>";

        return $this->paging;
    }
}

