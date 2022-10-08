<?php

namespace App\Traits\Pagination;

use Illuminate\Support\Facades\URL;

trait PaginationTrait{

    public $myPaginationLink = '';

    public function myPagination($mainPaginationObject, $urlEtension = '/view-bank?page=', $linkNo = 3, $returnPrevAndNextLinkOnly = false){

        $returnPrevAndNextLinkOnly === false ?
        $this->firstPage($urlEtension)
        ->previousPage($mainPaginationObject, $urlEtension)
        ->moreLinksA($mainPaginationObject, $linkNo)
        ->currentPageLink($mainPaginationObject)
        ->moreLinksB($mainPaginationObject, $linkNo)
        ->nextPage($mainPaginationObject, $urlEtension)
        ->lastPage($mainPaginationObject, $urlEtension) : $this->previousPage($mainPaginationObject, $urlEtension)->nextPage($mainPaginationObject, $urlEtension);

        return $this->myPaginationLink;
    }

    private function firstPage($urlEtension){
        $pageNumber = 1;
        $baseUrl = URL::to('/');
        $this->myPaginationLink .= "<li class='page-item'><a class='page-link' href='$baseUrl$urlEtension$pageNumber'>First</a></li>";
        return $this;
    }

    private function previousPage($mainPaginationObject, $urlEtension){

        if(!$mainPaginationObject->onFirstPage()){
            $previousPageNumber = $mainPaginationObject->currentPage()-1;
            $baseUrl = URL::to('/');
            $this->myPaginationLink .= "<a href='$baseUrl$urlEtension$previousPageNumber' class='col-dark-gray waves-effect m-r-20' title='previous'
                                    data-toggle='tooltip'><i class='material-icons'>navigate_before</i></a>";
        }else{
            $this->myPaginationLink .= "<a href='javascript:;' class='col-dark-gray waves-effect m-r-20' title='previous'
                                    data-toggle='tooltip'><i class='material-icons'>navigate_before</i></a>";
        }
        return $this;
    }

    private function moreLinksA($mainPaginationObject, $linkNo){
        $pageLinksMinus = $mainPaginationObject->getUrlRange($mainPaginationObject->currentPage()-1, $mainPaginationObject->currentPage()-$linkNo);
        $minuser = $mainPaginationObject->currentPage()-1;
        foreach($pageLinksMinus as $m => $eachLink){
            if ($minuser > 1){
                $mNo = $mainPaginationObject->currentPage()-1;
                $this->myPaginationLink .= "<li class='page-item'><a class='page-link' href='$eachLink'>$mNo</a></li>";
            }
            $minuser--;
        }
        return $this;
    }

    private function currentPageLink($mainPaginationObject){
        $currentPage = $mainPaginationObject->currentPage();
        $this->myPaginationLink .= "<li class='page-item'><span class='page-link text-primary'> $currentPage </span></li>";
        return $this;
    }

    private function moreLinksB($mainPaginationObject, $linkNo){
        //s$this->myPaginationLink .= "";
        $pageLinksPlus = $mainPaginationObject->getUrlRange($mainPaginationObject->currentPage()+1, $mainPaginationObject->currentPage()+$linkNo);
        $add = $mainPaginationObject->currentPage()+1;
        foreach($pageLinksPlus as $m => $eachLink){
            if ($add < $mainPaginationObject->lastPage()){
                $no = $mainPaginationObject->currentPage()+1;
                $this->myPaginationLink .= "<li class='page-item'><a class='page-link' href='$eachLink'>$no</a></li>";
            }
            $add++;
        }
        return $this;
    }

    private function nextPage($mainPaginationObject, $urlEtension){
        if($mainPaginationObject->currentPage() < $mainPaginationObject->lastPage()){
            $nextPageNumber = $mainPaginationObject->currentPage()+1;
            $baseUrl = URL::to('/');
            $this->myPaginationLink .= "<a href='$baseUrl$urlEtension$nextPageNumber' class='col-dark-gray waves-effect m-r-20' title='next'
                                    data-toggle='tooltip'><i class='material-icons'>navigate_next</i></a>";
        }else{
            $this->myPaginationLink .= "<a href='javascript:;' class='col-dark-gray waves-effect m-r-20' title='next'
                                    data-toggle='tooltip'><i class='material-icons'>navigate_next</i></a>";
        }
        return $this;
    }

    private function lastPage($mainPaginationObject, $urlEtension){
        $pageNumber = $mainPaginationObject->lastPage();
        $baseUrl = URL::to('/');
        $this->myPaginationLink .= "<li class='page-item'><a class='page-link' href='$baseUrl$urlEtension$pageNumber'>Last</a></li>";
        return $this;
    }

}