<?php
 = require __DIR__ . '/console.php';
if (isset(['bootstrap']) && is_array(['bootstrap'])) {
    ['bootstrap'] = array_values(array_filter(['bootstrap'], function(){ return  !== 'gii'; }));
}
if (isset(['modules']['gii'])) {
    unset(['modules']['gii']);
}
return ;
