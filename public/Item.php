<?php

/**
 * Class Item
 * @author Elias
 * @see facebook.com/elias_champi
 * @version 0.1.2019
 */
class Item
{
    /**
    * @var string $name
    */
    private $name;

     /**
    * @var string $price
    */
    private $price;

     /**
    * @var bool $sign
    */

    private $sign;

    /**
    * construct
    * @param string $name
    * @param string $price
    * @param bool $sign
    */
    public function __construct($name = '', $price = '', $sign = false)
    {
        $this->name = $name;
        $this->price = $price;
        $this->sign = $sign;
    }
    

    public function __toString()
    {
        $rightCols = 10;
        $leftCols = 38;

        if ($this->sign) {
            $leftCols = $leftCols / 2 - $rightCols / 2;
        }
        $left = str_pad($this->name, $leftCols);
        
        $sign = ($this->sign ? 'S:/ ' : '');

        $right = str_pad($sign . $this->price, $rightCols, ' ', STR_PAD_LEFT);
        return "$left$right\n";
    }
}