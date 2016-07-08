<?php
/*
SiXhEaD Pagination - Pagination
Copyright (c) 2005, Chairath Soonthornwiphat (sixhead.com). All rights reserved.
Code licensed under the BSD License: (license.txt)
Version 4.3

> MySQLi
*/
class Pagination
{
    public $debug = false;
    public $db_connection = false;
    public $db_table = "";
    public $order_by = "";
    public $primary_key = "";
    public $select = "*";
    public $custom_sql = "";
    public $custom_param = "";
    public $per_page = 10;
    public $page_param = "p";
    public $order_param = "order";
    public $page_first = "&laquo;";
    public $page_last = "&raquo;";
    public $page_prev = "&#8249;";
    public $page_next = "&#8250;";
    public $css = "pagination";
    public $no_range = 3;
    
    protected $order_uri = "";
    protected $order_value = "";
    protected $all_page = 0;
    protected $script_file = "";
    protected $current_page = 0;
    protected $current_prev = 0;
    protected $current_next = 0;
    protected $record_all = 0;
    protected $page_link = "";
    
    public function __construct()
    {
    }
    
    public function start()
    {
        $this->script_file = $_SERVER["PHP_SELF"];
        $this->getTotalRecord();
        $this->setPage();
        $this->setSelectSql();
    }
    
    protected function setPage()
    {
        $this->current_page = 1;
        if (array_key_exists("$this->page_param", $_REQUEST)) {
            $this->current_page = (int)$_REQUEST["$this->page_param"];
            if ($this->current_page == 0) {
                $this->current_page = 1;
            }
        }
        
        $this->order = "";
        if (array_key_exists("$this->order_param", $_REQUEST)) {
            $this->order = $_REQUEST["$this->order_param"];
        }
        
        $this->per_page = (int)$this->per_page;
        if ($this->per_page == 0) {
            $this->per_page = 10;
        }
        $this->all_page = ceil($this->record_all / $this->per_page);
        if ($this->current_page > $this->all_page) {
            $this->current_page = $this->all_page;
        }
        
        $this->current_prev = $this->current_page - 1;
        $this->current_next = $this->current_page + 1;
    }
    
    protected function getTotalRecord()
    {
        $sql = "SELECT $this->select FROM $this->db_table $this->custom_sql";
        if ($this->debug == true) {
            $this->setDebug($sql);
        }
        $result = $this->db_connection->query($sql);
        $this->record_all = $result->num_rows;
        $result->free();
    }
    
    protected function setSelectSql()
    {
        if ($this->current_page == 1) {
            $start = 0;
        } else {
            $start = ($this->current_page - 1) * $this->per_page;
        }
        if ($start < 0) {
            $start = 0;
        }
        
        $this->sql = "SELECT $this->select FROM $this->db_table $this->custom_sql ORDER BY $this->order_by LIMIT $start,$this->per_page";
        if ($this->debug == true) {
            $this->setDebug($this->sql);
        }
    }
    
    public function getRow()
    {
        $rows = array();
        
        $result = $this->db_connection->query($this->sql);
        if (!$result) {
            if ($this->debug == true) {
                $this->setDebug($this->sql);
            } else {
                $this->setDebug('SQL error');
            }
            exit;
        }
        
        while ($row = $result->fetch_assoc()) {
            $rows[] = $row;
        }
        $result->free();
        
        return $rows;
    }
    
    protected function setStyle()
    {
        $page_links = array();
        
        $min = $this->current_page - $this->no_range;
        $max = $this->current_page + $this->no_range;
        
        if ($min <= 0) {
            $min = 1;
        }
        if ($max >= $this->all_page) {
            $max = $this->all_page;
        }
        
        if ($this->current_page == 1) {
            $max = $max + $this->no_range;
        } else if ($this->current_page <= $this->no_range) {
            $max = $max + (($this->no_range - $this->current_page) + 1);
        } else if ($this->current_page == $this->all_page) {
            $min = $min - $this->no_range;
        } else if (($this->all_page - $this->current_page) < $this->no_range) {
            $min = $min - ($this->no_range - ($this->all_page - $this->current_page));
        }
        
        if ($min <= 0) {
            $min = 1;
        }
        if ($max >= $this->all_page) {
            $max = $this->all_page;
        }
        
        $page_link_all = "";
        
        for ($i = $min; $i <= $max; $i++) {
            if ($i == $this->current_page) {
                $page_link_all.= "\n<li class=\"active\"><a href=\"javascript:;\">$i</a></li>";
            } else {
                $page_link_all.= "\n<li><a href=\"$this->script_file?&amp;$this->page_param=$i$this->custom_param&amp;$this->order_param=$this->order\" class=\"other\">$i</a></li>";
            }
        }
        
        $_page_first = "";
        $_page_last = "";
        $_page_prev = "";
        $_page_next = "";
        
        if ($this->current_page == 1) {
            $_page_first = "\n<li class=\"first disabled\"><a href=\"javascript:;\">$this->page_first</a></li>";
            $_page_prev = "\n<li class=\"prev disabled\"><a href=\"javascript:;\">$this->page_prev</a></li>";
        } else {
            $_page_first = "\n<li><a href=\"$this->script_file?&amp;$this->page_param=1$this->custom_param&amp;$this->order_param=$this->order\" class=\"first\">$this->page_first</a></li>";
            $_page_prev = "\n<li><a href=\"$this->script_file?&amp;$this->page_param=$this->current_prev$this->custom_param&amp;$this->order_param=$this->order\" class=\"prev\">$this->page_prev</a></li>";
        }
        
        if ($this->current_page == $this->all_page) {
            $_page_next = "\n<li class=\"next disabled\"><a href=\"javascript:;\">$this->page_next</a></li>";
            $_page_last = "\n<li class=\"last disabled\"><a href=\"javascript:;\">$this->page_last</a></li>";
        } else {
            $_page_next = "\n<li><a href=\"$this->script_file?&amp;$this->page_param=$this->current_next$this->custom_param&amp;$this->order_param=$this->order\" class=\"next\">$this->page_next</a></li>";
            $_page_last = "\n<li><a href=\"$this->script_file?&amp;$this->page_param=$this->all_page$this->custom_param&amp;$this->order_param=$this->order\" class=\"last\">$this->page_last</a></li>";
        }
        
        $page_link = "$_page_first$_page_prev$page_link_all$_page_next$_page_last";
        $this->page_link = '<ul class="' . $this->css . '">' . $page_link . '</ul>';
    }
    
    protected function getOrderUri()
    {
        //$this->order_uri  =   $_SERVER["PHP_SELF"] . "?" . $_SERVER["QUERY_STRING"];
        $custom_param_clean = preg_replace("/&amp;/", "&", $this->custom_param);
        $this->order_uri = $_SERVER["REQUEST_URI"];
        $this->order_uri = preg_replace("/(&|)$this->page_param=(.*?)&/", "&", $this->order_uri);
        $this->order_uri = preg_replace("/(&|)$this->page_param=(.*?)$/", "", $this->order_uri);
        $this->order_uri = preg_replace("/$custom_param_clean/", "", $this->order_uri);
        
        if (!preg_match("@\?@", $this->order_uri)) {
            $this->order_uri.= "?";
        }
        $this->order_value = "";
        if (preg_match("/&$this->order_param=(.*?)&/", $this->order_uri, $matches)) {
            $this->order_value = urldecode($matches[1]);
            $this->order_uri = preg_replace("/&$this->order_param=(.*?)&/", "&", $this->order_uri);
        } else if (preg_match("/&$this->order_param=(.*?)$/", $this->order_uri, $matches)) {
            $this->order_value = urldecode($matches[1]);
            $this->order_uri = preg_replace("/&$this->order_param=(.*?)$/", "", $this->order_uri);
        }
        
        $this->order_uri = preg_replace("/&/", "&amp;", $this->order_uri);
    }
    
    public function getSortLink($text, $key_ASC, $key_DESC, $default_sort = "DESC")
    {
        $default_sort = strtoupper($default_sort);
        $image_link = "";
        $new_order = "";
        $sort_link = "";
        if ($this->order_value == $key_ASC) {
            $new_order = $key_DESC;
            $image_link = "<img src=\"" . $this->sort_icon["ASC"] . "\" style=\"boder:0\" alt=\"ASC\">";
        } else if ($this->order_value == $key_DESC) {
            $new_order = $key_ASC;
            $image_link = "<img src=\"" . $this->sort_icon["DESC"] . "\" style=\"boder:0\" alt=\"DESC\">";
        } else {
            $new_order = $ {
                "key_$default_sort"
            };
            $image_link = "";
        }
        
        $sort_link = "<a href=\"$this->order_uri&amp;$this->order_param=$new_order$this->custom_param\">$text</a>$image_link";
        
        return $sort_link;
    }
    
    public function setSortIcon($sort_icon_asc, $sort_icon_desc)
    {
        $this->sort_icon["ASC"] = $sort_icon_asc;
        $this->sort_icon["DESC"] = $sort_icon_desc;
        $this->getOrderUri();
    }
    
    public function getLink()
    {
        $this->setStyle();
        if ($this->record_all <= $this->per_page) {
            $this->page_link = "";
        }
        
        return $this->page_link;
    }
    
    public function getCount()
    {
        
        return $this->record_all;
    }
    
    public function getNext()
    {
        if ($this->current_page == $this->all_page) {
            
            return false;
        } else {
            
            return "$this->script_file?&amp;$this->page_param=$this->current_next$this->custom_param";
        }
    }
    
    public function getPrev()
    {
        if ($this->current_prev <= 0) {
            
            return false;
        } else {
            
            return "$this->script_file?&amp;$this->page_param=$this->current_prev$this->custom_param";
        }
    }
    
    public function getSql()
    {
        
        return $this->sql;
    }
    
    public function setDebug($str)
    {
        echo "<pre>";
        print_r($str);
        echo "</pre>";
    }
}
