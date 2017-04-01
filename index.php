<?php


// подключим стили и скрипт
function gas_resource(){
    rcl_enqueue_style('gas_style',rcl_addon_url('style.css', __FILE__));
    rcl_enqueue_script('gas_scripts', rcl_addon_url( 'inc/scripts.js', __FILE__ ),false,true);
}
if(!is_admin()){ // в админке они нам не нужны
    add_action('rcl_enqueue_scripts','gas_resource',10); // в момент срабатывания хука
}


// в футер вставим свою кнопку слева
function gas_block(){
    $title = '<div>От улыбки хмурый день светлей,<br/>От улыбки в небе радуга проснется,<br/>'
            .'Поделись улыбкою своей,<br/>И она к тебе не раз еще вернется!</div>'
            .'<div>p.s. жми смайл - и подари улыбку</div>';

    $out = '<div id="gas_smile" class="gas_open" onclick="gasPush(this);return false;">';
        $out .= '<span>Подари улыбку</span>';
        $out .= '<i class="fa fa-smile-o"></i>';
    $out .= '</div>';
    $out .= '<div id="gas_smile_motiv">'.$title.'</div>';

    echo $out;
}
add_action('wp_footer', 'gas_block',100);



// ловим ajax
function gas_catch_smile(){
    rcl_verify_ajax_nonce(); // проверка nonce

    $smiles = get_option('give_a_smile') ? get_option('give_a_smile') : 0; // получаем значение

    update_option('give_a_smile', ++$smiles); // увеличиваем на 1 и обновляем в БД

    // формируем ответ
    $gas_resp['gas_ok'] = '<div class="gas_ok">Спасибо за улыбку!</div>'
                        . '<div class="gas_count">Улыбок в базе: <div class="gas_nmbr">'.$smiles.'</div></div>';

    echo json_encode($gas_resp); // отправляем в скрипт
    wp_die();
}
if(defined('DOING_AJAX') && DOING_AJAX){
    add_action('wp_ajax_nopriv_gas_callback', 'gas_catch_smile');   // No privileges - т.е. гость
    add_action('wp_ajax_gas_callback', 'gas_catch_smile');          // любой залогиненный
}


