<?php
class tab
{

    function __construct($arr, $desc)
    {
        $this->arr = $arr;
        $this->desc = $desc;
    }
    private $arr;
    private $desc;
    private function get_max_len($field)
    {
        $max_len = strlen($field);
        global $arr;
        foreach ($this->arr as $value) {
            $len_val = strlen($value[$field]);
            $max_len  = $len_val < $max_len ? $max_len : $len_val;
        }
        return $max_len;
    }
    private function get_space($count)
    {
        $space = "";
        for ($i = 0; $i < $count; $i++) {
            $space .= " ";
        }
        return $space;
    }
    private function get_field_out($value, $fields, $head = false)
    { // if head === true align only center
        global $desc;
        $span_s = $span_e = "";
        $max_len = $desc["fields"][$fields];
        $align = $head === true ? "" : $this->desc["align_body"];
        $delta_color_len = 0;
        if ($fields === "Color" && $head === false) {
            $span_s = "<span style='color:" . $this->desc["color"][$value] . "'>";
            $span_e = "</span>";
            $delta_color_len = strlen($span_s) + strlen($span_e);
        }

        switch ($align) {
            case "left": {
                    $left = $this->desc["tab_s"];
                    $right = $max_len - strlen($value) - $left;
                    break;
                }

            case "right": {
                    $right = $desc["tab_s"];
                    $left = $max_len - strlen($value) - $right;
                    break;
                }
            default: {
                    $left = $right = intdiv(($max_len - strlen($value)), 2);
                }
        }
        $out = $this->get_space($left) . $span_s . $value . $span_e . $this->get_space($right);
        if (strlen($out) < $max_len + $delta_color_len) {
            $out .= " ";
        }
        return $out;
    }
    public function get_html_tab()
    {
        foreach ($this->desc['fields'] as $key => $value) {
            global $desc;
            $max_len = $this->get_max_len($key) + $this->desc["tab_s"] * 2; // 2 => before spase & after spase
            $desc['fields'][$key] = $max_len;
        }

        $w_delimiter = "";
        foreach ($desc['fields'] as $key => $value) {
            $w_delimiter .= "+";
            for ($i = 0; $i < $value; $i++) {
                $w_delimiter .= "-";
            }
        }
        $w_delimiter .= "+";


        $head = "!";
        foreach ($desc['fields'] as $key => $value) {
            $head .= $this->get_field_out($key, $key, true) . "!";
        }
        ob_start();
        echo "<pre style='display:block; margin:auto; width: fit-content; '>";
        echo $w_delimiter . "</br>";
        echo $head . "</br>";
        echo $w_delimiter . "</br>";

        foreach ($this->arr as $item) {
            $head = "!";
            foreach ($this->desc['fields'] as $key => $value) {
                $head .= $this->get_field_out($item[$key], $key) . "!";
            }
            echo $head . "<br>";
            echo $w_delimiter . "<br>";
        }
        echo "</pre>";
        return ob_get_clean();
    }
}


$arr = array(
    array(
        'Name' => 'Trixie',
        'Color' => 'Green',
        'Element' => 'Earth',
        'Likes' => 'Flowers'
    ),
    array(
        'Name' => 'Tinkerbell',
        'Element' => 'Air',
        'Likes' => 'Singning',
        'Color' => 'Blue'
    ),
    array(
        'Element' => 'Water',
        'Likes' => 'Dancing',
        'Name' => 'Blum',
        'Color' => 'Pink'
    ),
);
$desc = array(
    "fields" => array(  //  the order in which the fields are displayed in the table
        "Name" => 0,
        "Color" => 0,
        "Element" => 0,
        "Likes" => 0
    ),
    "color" => array(    // describe color #ffffff or #fff or White
        "Green" => "green",
        "Blue" => "blue",
        "Pink" => "pink"
    ),
    "align_body" => "left", // left,right, center=> default (any)
    "tab_s" => 2  //  tab_s = (before = after)spase body fields
);


///////////////////////////////////////////////////

$tab_html = new tab($arr, $desc);
echo $tab_html->get_html_tab();
