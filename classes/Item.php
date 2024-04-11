<?php
class Item
{
    // Properties
    private $id;
    private $name;
    private $price;
    private $amount;
    private $image;

    // Konstruktor
    public function __construct($id, $name, $price, $amount, $image)
    {
        $this->id = $id;
        $this->name = $name;
        $this->price = $price;
        $this->amount = $amount;
        $this->image = $image;
    }

    // Returns the ID of an item
    public function get_id()
    {
        return $this->id;
    }

    // Returns the name of an item
    public function get_name()
    {
        return $this->name;
    }

    // Returns the price of an item
    public function get_price()
    {
        return $this->price;
    }

    // Returns the amount of an item
    public function get_amount()
    {
        return $this->amount;
    }

    // Returns the image of an item
    public function get_image()
    {
        return $this->image;
    }

    // Sets the ID to a new value
    public function set_sifra($id)
    {
        return $this->id = $id;
    }

    // Sets the name to a new value
    public function set_name($name)
    {
        return $this->name = $name;
    }

    // Sets the price to a new value
    public function set_price($price)
    {
        $this->price = $price;
    }

    // Sets the amount to a new value
    public function set_amount($amount)
    {
        return $this->amount = $amount;
    }

    // Sets the image to a new value
    public function set_image($image)
    {
        return $this->image = $image;
    }
}
