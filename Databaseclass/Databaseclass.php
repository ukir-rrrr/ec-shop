<?php

function sanitize($before) {
    foreach($before as $key => $value) {
        $after[$key] = htmlspecialchars($value, ENT_QUOTES,"UTF-8");
    }
    return $after;
}

function pulldown_cate() {
    echo "<select name='cate'>";
    echo "<option value='メンズ'>メンズ</option>";
    echo "<option value='ウィメンズ'>ウィメンズ</option>";
    echo "<option value='キッズ'>キッズ</option>";
    echo "</select>";
}
?>
