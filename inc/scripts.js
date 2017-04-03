/* global Rcl */
function gasPush(e){
    if(jQuery(e).hasClass('gas_result')) return false; // если уже один раз отработало
    jQuery(e).addClass('gas_result'); // ставим маркер что нажали один раз
    rcl_preloader_show(jQuery('#gas_smile'),25); // покажем спиннер (иконка лоадера)
    
    // формируем массив передаваемых данных
    var gasData = {
        action : "gas_callback", // коллбек для динамического хука
        ajax_nonce : Rcl.nonce   // проверочный ключ безопасности
    };

    // ajax post запрос
    jQuery.post({
        url: Rcl.ajaxurl,        // путь до вордпресс обработчика ajax
        dataType: "json",        // тип данных с которыми работаем
        data: gasData,           // массив наших данных (сформировали выше)
        success: function(data){ // при успешном возврате
            if(data.gas_ok){     // gas_ok - ключ ответа что мы отправили в PHP функции
                setTimeout(function(){     // у меня ajax слишком быстро работает - кручу спиннер еще 1000 миллисекунд
                    rcl_preloader_hide();  // скрываю спиннер
                    jQuery('#gas_smile_motiv').remove(); // удаляю блок со стишком
                    jQuery(e).removeClass('gas_open').empty().html(data.gas_ok); // очищаю кнопку и добавляю ответ что вернула PHP функция
                },1000);
            }
        },
        complete: function(){        // ajax-запрос завершился
            setTimeout(function(){   // покажу результат на 8 секунд
                jQuery(e).hide(400); // и скрою блок
            },8000);
        }
    });
    return false;
}

