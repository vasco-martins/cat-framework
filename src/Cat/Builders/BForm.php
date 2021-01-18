<?php


namespace Cat\Builders;


class BForm
{

    public static function input(string $type, string $name, string $label = '' ,string $value = '', string|null $error = null) {

        $isInvalid = $error == null ? '' : 'is-invalid';

        echo "<div class=\"mb-3\">";
        if($label) {
            echo "<label>$label</label>";
        }
        echo "<input type=\"$type\"";
        echo ' class="form-control ' . $isInvalid  . '"';
        echo " value=\"" . $value . "\"";
        echo " name=\"" . $name . "\">";



        if($error) {

            echo " <div class=\"invalid-feedback\">
        $error
      </div>";

        }
        echo "</div>";

    }




    public static function error($error) {
        echo "<div class=\"is-invalid\"></div>";

        echo " <div class=\"invalid-feedback\">
         $error
        </div>";
    }

}