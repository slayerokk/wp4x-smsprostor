<?php

if ( ! defined( 'ABSPATH' ) ) { exit; }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if (isset($_POST['smsprostor_login'])) {
        update_option('smsprostor_login', sanitize_text_field($_POST['smsprostor_login']));
    }

    if (isset($_POST['smsprostor_password'])) {
        update_option('smsprostor_password', sanitize_text_field($_POST['smsprostor_password']));
    }

    if (isset($_POST['smsprostor_phone'])) {
        update_option('smsprostor_phone', sanitize_text_field($_POST['smsprostor_phone']));
    }

    if (isset($_POST['smsprostor_sender'])) {
        update_option('smsprostor_sender', sanitize_text_field($_POST['smsprostor_sender']));
    }

    if (isset($_POST['smsprostor_cf7_customer_field'])) {
        update_option('smsprostor_cf7_customer_field', sanitize_text_field($_POST['smsprostor_cf7_customer_field']));
    }

    if (isset($_POST['smsprostor_cf7_send_customer'])) {
        update_option('smsprostor_cf7_send_customer', 'on');
    } else {
        update_option('smsprostor_cf7_send_customer', 'off');
    }

    if (isset($_POST['smsprostor_cf7_customer_message'])) {
        update_option('smsprostor_cf7_customer_message', sanitize_text_field($_POST['smsprostor_cf7_customer_message']));
    }

    if (isset($_POST['smsprostor_cf7_send_admin'])) {
        update_option('smsprostor_cf7_send_admin', 'on');
    } else {
        update_option('smsprostor_cf7_send_admin', 'off');
    }

    if (isset($_POST['smsprostor_cf7_admin_message'])) {
        update_option('smsprostor_cf7_admin_message', sanitize_text_field($_POST['smsprostor_cf7_admin_message']));
    }

    if (isset($_POST['smsprostor_wc_send_customer'])) {
        update_option('smsprostor_wc_send_customer', 'on');
    } else {
        update_option('smsprostor_wc_send_customer', 'off');
    }

    if (isset($_POST['smsprostor_wc_customer_message'])) {
        update_option('smsprostor_wc_customer_message', sanitize_text_field($_POST['smsprostor_wc_customer_message']));
    }

    if (isset($_POST['smsprostor_wc_send_admin'])) {
        update_option('smsprostor_wc_send_admin', 'on');
    } else {
        update_option('smsprostor_wc_send_admin', 'off');
    }

    if (isset($_POST['smsprostor_wc_admin_message'])) {
        update_option('smsprostor_wc_admin_message', sanitize_text_field($_POST['smsprostor_wc_admin_message']));
    }
}

$smsprostor_login = get_option('smsprostor_login');
$smsprostor_password = get_option('smsprostor_password');
$smsprostor_phone = get_option('smsprostor_phone');

$smsprostor_sender = get_option('smsprostor_sender');

$smsprostor_cf7_customer_field = get_option('smsprostor_cf7_customer_field');
$smsprostor_cf7_send_customer = get_option('smsprostor_cf7_send_customer');
$smsprostor_cf7_customer_message = get_option('smsprostor_cf7_customer_message');
$smsprostor_cf7_send_admin = get_option('smsprostor_cf7_send_admin');
$smsprostor_cf7_admin_message = get_option('smsprostor_cf7_admin_message');

$smsprostor_wc_send_customer = get_option('smsprostor_wc_send_customer');
$smsprostor_wc_customer_message = get_option('smsprostor_wc_customer_message');
$smsprostor_wc_send_admin = get_option('smsprostor_wc_send_admin');
$smsprostor_wc_admin_message = get_option('smsprostor_wc_admin_message');

$sms = new gateway();

if ((isset($smsprostor_login)) && (isset($smsprostor_password))) {
    $balance = $sms->get_balance($smsprostor_login, $smsprostor_password);
    $senders = $sms->get_senders($smsprostor_login, $smsprostor_password);
}

$customers = get_users();
$custs = array();
foreach ($customers as $customer) {
    $meta = get_user_meta($customer->ID);
    if (
        (isset($meta['billing_first_name'])) &&
        (isset($meta['billing_last_name'])) &&
        (isset($meta['billing_phone']))
    ) {
        $custs[] = array(
                'firstname' => $meta['billing_first_name'][0],
                'lastname' => $meta['billing_last_name'][0],
                'phone' => $meta['billing_phone'][0]
        );
    }
}

if (isset($_POST['smsprostor_responders'])) {
    $responders = array();
    foreach ($_POST['smsprostor_responders'] as $responder) {
        if ($responder == '*') {
            foreach ($custs as $cust) {
                $responders[] = $sms->clear_phone($cust['phone']);
            }
        } else {
            $responders[] = $sms->clear_phone($responder);
        }
    }
    $responders = array_unique($responders);
    foreach ($responders as $responder) {
        $sms->sms_send($smsprostor_login, $smsprostor_password, $responder, $_POST['smsprostor_responder_message'], $smsprostor_sender);
    }
    $msg = "Рассылка выполнена по ".count($responders)." номерам.";
    //$msg = print_r($_POST['smsprostor_responders'], true);
}
?>

<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css" integrity="sha384-rHyoN1iRsVXV4nD0JutlnGaslCJuC7uwjduW9SVrLvRYooPp2bWYgmgJQIXwl/Sp" crossorigin="anonymous">
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<style>
   .nav-tabs > li > a {
       box-shadow:none;
   }
   .select2-container--default.select2-container--focus .select2-selection--multiple {
       border: inherit;
       outline: 0;
   }
</style>

<form action="options-general.php?page=prostorsms" method="post" class="form-horizontal">

<div class="panel panel-default" style="margin-top: 20px; margin-right: 20px">
    <div class="panel-heading">
        <div class="row">
            <div class="col-sm-6">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> Интеграция шлюза для SMS-уведомлений</h3>
            </div>
            <div class="col-sm-6 text-right">
                Поддержка: <a href="tel:88007007066">8 800 700 70 66</a>&nbsp;&nbsp;
                Ваш баланс:  <b><?=$balance?></b>&nbsp;&nbsp;
                <a href="https://prostor-sms.ru/bill/" target="_blank">[пополнить]</a>&nbsp;&nbsp;
                <a href="https://prostor-sms.ru/" target="_blank">[на сайт]</a>
            </div>
        </div>
    </div>
    <div class="panel-body">
        <?php if (isset($msg)): ?>
        <div class="alert alert-success" role="alert"><?=$msg?></div>
        <?php endif; ?>
        <ul class="nav nav-tabs">
            <li class="active"><a data-toggle="tab" href="#tab_cf7">Интеграция CF7</a></li>
            <li><a data-toggle="tab" href="#tab_wc">Интеграция Woocommerce</a></li>
            <li><a data-toggle="tab" href="#tab_messaging">Рассылка</a></li>
            <li><a data-toggle="tab" href="#tab_setup">Настройка шлюза</a></li>
        </ul>

        <div class="tab-content">
            <div id="tab_cf7" class="tab-pane active">

                <div class="form-group" style="margin-top: 20px">
                    <label class="control-label col-sm-2" for="smsprostor_cf7_customer_field">Поле с телефоном <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Название поля CF7, в которое пользователь будет вводить свой телефон"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Введите логин" id="smsprostor_cf7_customer_field" name="smsprostor_cf7_customer_field" value="<?=$smsprostor_cf7_customer_field?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="smsprostor_cf7_customer_message">СМС Отправителю <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Сообщение для отправителя формы"></span></label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label for="smsprostor_cf7_send_customer">
                                <input type="checkbox" name="smsprostor_cf7_send_customer" id="smsprostor_cf7_send_customer" <?=(($smsprostor_cf7_send_customer=='on')? 'checked':'')?>>
                                Отправлять СМС отправителю
                            </label>
                        </div>
                        <br>
                        <textarea class="form-control" name="smsprostor_cf7_customer_message" id="smsprostor_cf7_customer_message"><?=$smsprostor_cf7_customer_message?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="smsprostor_cf7_admin_message">СМС Админу <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Сообщение для администратора"></span></label>
                    <div class="col-sm-10">
                        <div class="checkbox">
                            <label for="smsprostor_cf7_send_admin">
                                <input type="checkbox" name="smsprostor_cf7_send_admin" id="smsprostor_cf7_send_admin" <?=(($smsprostor_cf7_send_admin=='on')? 'checked':'')?>>
                                Отправлять СМС администратору
                            </label>
                        </div>
                        <br>
                        <textarea class="form-control" name="smsprostor_cf7_admin_message" id="smsprostor_cf7_admin_message"><?=$smsprostor_cf7_admin_message?></textarea>
                    </div>
                </div>
            </div>
            <div id="tab_wc" class="tab-pane">

                <div class="form-group" style="margin-top: 20px">
                    <label class="control-label col-sm-2" for="smsprostor_wc_customer_message">СМС Покупателю <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Сообщение для покупателя"></span></label>
                    <div class="col-sm-10">
                        <div class="btn-toolbar" role="toolbar" style="margin-bottom: 6px">
                            <div class="btn-group pull-left" role="group">
                                <div class="checkbox">
                                    <label for="smsprostor_wc_send_customer">
                                        <input type="checkbox" name="smsprostor_wc_send_customer" id="smsprostor_wc_send_customer" <?=(($smsprostor_wc_send_customer=='on')? 'checked':'')?>>
                                        Отправлять СМС покупателю
                                    </label>
                                </div>
                            </div>
                            <div class="btn-group pull-right btn-group-xs" role="group">
                                <button type="button" class="btn btn-default btni" data-insert="@id" data-target="smsprostor_wc_customer_message">Номер заказа</button>
                                <button type="button" class="btn btn-default btni" data-insert="@total" data-target="smsprostor_wc_customer_message">Сумма</button>
                                <button type="button" class="btn btn-default btni" data-insert="@firstname" data-target="smsprostor_wc_customer_message">Имя</button>
                                <button type="button" class="btn btn-default btni" data-insert="@lastname" data-target="smsprostor_wc_customer_message">Фамилия</button>
                                <button type="button" class="btn btn-default btni" data-insert="@phone" data-target="smsprostor_wc_customer_message">Телефон</button>
                                <button type="button" class="btn btn-default btni" data-insert="@email" data-target="smsprostor_wc_customer_message">Email</button>
                            </div>
                        </div>
                        <textarea class="form-control" name="smsprostor_wc_customer_message" id="smsprostor_wc_customer_message"><?=$smsprostor_wc_customer_message?></textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="smsprostor_wc_admin_message">СМС Админу <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Сообщение для администратора"></span></label>
                    <div class="col-sm-10">
                        <div class="btn-toolbar" role="toolbar" style="margin-bottom: 6px">
                            <div class="btn-group pull-left" role="group">
                                <div class="checkbox">
                                    <label for="smsprostor_wc_send_admin">
                                        <input type="checkbox" name="smsprostor_wc_send_admin" id="smsprostor_wc_send_admin" <?=(($smsprostor_wc_send_admin=='on')? 'checked':'')?>>
                                        Отправлять СМС администратору
                                    </label>
                                </div>
                            </div>
                            <div class="btn-group pull-right btn-group-xs" role="group">
                                <button type="button" class="btn btn-default btni" data-insert="@id" data-target="smsprostor_wc_admin_message">Номер заказа</button>
                                <button type="button" class="btn btn-default btni" data-insert="@total" data-target="smsprostor_wc_admin_message">Сумма</button>
                                <button type="button" class="btn btn-default btni" data-insert="@firstname" data-target="smsprostor_wc_admin_message">Имя</button>
                                <button type="button" class="btn btn-default btni" data-insert="@lastname" data-target="smsprostor_wc_admin_message">Фамилия</button>
                                <button type="button" class="btn btn-default btni" data-insert="@phone" data-target="smsprostor_wc_admin_message">Телефон</button>
                                <button type="button" class="btn btn-default btni" data-insert="@email" data-target="smsprostor_wc_admin_message">Email</button>
                            </div>
                        </div>
                        <textarea class="form-control" name="smsprostor_wc_admin_message" id="smsprostor_wc_admin_message"><?=$smsprostor_wc_admin_message?></textarea>
                    </div>
                </div>
            </div>
            <div id="tab_messaging" class="tab-pane">
                <div class="form-group" style="margin-top: 20px">
                    <label class="control-label col-sm-2" for="smsprostor_responders">Получатели <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Список получателей сообщения"></span></label>
                    <div class="col-sm-10">
                        <select name="smsprostor_responders[]" id="smsprostor_responders" multiple="multiple" class="form-control" style="width: 100%">

                            <option value="*">Все пользователи</option>
                            <?php foreach ($custs as $cust): ?>
                            <option value="<?=$cust['phone']?>"><?=$cust['firstname'].' '.$cust['lastname']?></option>
                            <?php endforeach; ?>

                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="smsprostor_responder_message">Сообщение <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Текст сообщения для рассылки"></span></label>
                    <div class="col-sm-10">
                        <textarea rows="10" class="form-control" name="smsprostor_responder_message" id="smsprostor_responder_message"></textarea>
                    </div>
                </div>

                <button type="submit" class="btn btn-primary pull-right">Отправить</button>

            </div>
            <div id="tab_setup" class="tab-pane">

                <div class="form-group" style="margin-top: 20px">
                    <label class="control-label col-sm-2" for="smsprostor_login">Логин <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Ваш логин на сервисе Простор СМС"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Введите логин" id="smsprostor_login" name="smsprostor_login" value="<?=$smsprostor_login?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="smsprostor_password">Пароль <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Ваш пароль на сервисе Простор СМС"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Введите пароль" id="smsprostor_password" name="smsprostor_password" value="<?=$smsprostor_password?>">
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="smsprostor_sender">Имя отправителя <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Буквенное имя отправителя"></span></label>
                    <div class="col-sm-10">
                        <select name="smsprostor_sender" id="smsprostor_sender" class="form-control" <?=($senders? '': 'disabled')?>>
                            <?php if ($senders): ?>
                                <?php foreach ($senders as $id => $sender): ?>
                                    <option value="<?=$sender?>" <?=( ((isset($smsprostor_sender)) && ($sender == $smsprostor_sender) )? 'selected': '')?>><?=$sender?></option>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-sm-2" for="smsprostor_phone">Тел. админа <span class="dashicons dashicons-editor-help" data-toggle="tooltip" data-placement="top" title="Телефон администратора"></span></label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" placeholder="Введите телефон" id="smsprostor_phone" name="smsprostor_phone" value="<?=$smsprostor_phone?>">
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

    <button type="submit" class="btn btn-primary pull-right" style="margin-right: 20px">Сохранить</button>

</form>

<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<script>
    jQuery(document).ready(function(){

        jQuery('#smsprostor_responders').select2({
            tags: true,
            tokenSeparators: [',']
        });

        jQuery('[data-toggle="tooltip"]').tooltip();

        jQuery.fn.insertAtCaret = function(myValue) {
            return this.each(function() {
                var me = this;
                if (document.selection) { // IE
                    me.focus();
                    sel = document.selection.createRange();
                    sel.text = myValue;
                    me.focus();
                } else if (me.selectionStart || me.selectionStart == '0') { // Real browsers
                    var startPos = me.selectionStart, endPos = me.selectionEnd, scrollTop = me.scrollTop;
                    me.value = me.value.substring(0, startPos) + myValue + me.value.substring(endPos, me.value.length);
                    me.focus();
                    me.selectionStart = startPos + myValue.length;
                    me.selectionEnd = startPos + myValue.length;
                    me.scrollTop = scrollTop;
                } else {
                    me.value += myValue;
                    me.focus();
                }
            });
        };

        jQuery('.btni').click(function(){
            var target = jQuery(this).data('target');
            var text = jQuery(this).data('insert');
            jQuery('#'+target).insertAtCaret(text);
        });

    });
</script>

