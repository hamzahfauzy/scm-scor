<?php

namespace App\Libraries;

class Form
{
    static function generate(array $fields, array $data = []): string
    {
        $html = '';

        foreach ($fields as $name => $props) {
            $type = $props['type'] ?? 'text';
            $label = $props['label'] ?? ucwords(str_replace('_', ' ', $name));
            $class = 'form-control ' . ($props['class'] ?? '');
            $value = old($name, isset($props['value']) ? $props['value'] : ($data[$name] ?? (isset($props['default_value']) ? $props['default_value'] : '')));
            $isReadonly = isset($props['readonly']) ? 'readonly="'.$props['readonly'].'"' : '';

            $html .= "<div style=\"margin-bottom:1rem;\">";
            $html .= "<label for=\"$name\">$label</label><br>";

            switch ($type) {
                case 'textarea':
                    $html .= "<textarea name=\"$name\" id=\"$name\" class=\"$class\" rows=\"4\" cols=\"50\" $isReadonly>$value</textarea>";
                    break;

                case 'select':
                    $html .= "<select name=\"$name\" id=\"$name\" class=\"$class\" $isReadonly>";
                    foreach ($props['options'] ?? [] as $optValue => $optLabel) {
                        $selected = $value == $optValue ? 'selected' : '';
                        $html .= "<option value=\"$optValue\" $selected>$optLabel</option>";
                    }
                    $html .= "</select>";
                    break;

                case 'checkbox':
                    $name .= "[]";
                    foreach ($props['options'] ?? [] as $optValue => $optLabel) {
                        $value = explode(',',$value);
                        $checked = in_array($value, $optValue) ? 'checked' : '';
                        $html .= "<div><input type=\"checkbox\" name=\"$name\" id=\"$name\" class=\"$class\" value=\"$optValue\" $checked> $optLabel</div>";
                    }
                    break;
                
                case 'radio':
                    foreach ($props['options'] ?? [] as $optValue => $optLabel) {
                        $checked = $value == $optValue ? 'checked' : '';
                        $html .= "<div><input type=\"radio\" name=\"$name\" id=\"$name\" class=\"$class\" value=\"$optValue\" $checked> $optLabel</div>";
                    }
                    break;

                default: // text, number, date, etc.
                    $html .= "<input type=\"$type\" name=\"$name\" id=\"$name\" class=\"$class\" value=\"$value\" ".($type == 'number' ? 'step="0.1"' : '')." $isReadonly>";
            }

            if (session('errors') && session('errors')->get($name)) {
                $html .= '<br><small style="color:red">' . session('errors')->get($name) . '</small>';
            }

            $html .= "</div>";
        }

        return $html;
    }
}