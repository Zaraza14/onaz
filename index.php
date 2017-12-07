<? require 'lib.php'?>
<!doctype html>
<html>
<head>
    <meta charset="UTF-8">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/css/bootstrap.min.css" integrity="sha384-PsH8R72JQ3SOdhVi3uxftmaW6Vc51MKb0q5P2rRUpPvrszuE4W1povHYgTpBfshb" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">

    <title>ONAZ task</title>
</head>

<body>

<div class="container">
    <div class="row">
        <div class="col-4 mar-auto">
            <form method="post">
                <div class="form-group">
                    <label for="amountOfChushkas">Количество чушек</label>
                    <input type="text" class="form-control" name="amountOfChushkas" value="<?=isset($_POST['amountOfChushkas'])?$_POST['amountOfChushkas']:60?>" id="amountOfChushkas">
                </div>
                <div class="form-group">
                    <label for="amountOfBoxes">Количество котлов</label>
                    <input type="text" class="form-control" name="amountOfBoxes" value="<?=isset($_POST['amountOfBoxes'])?$_POST['amountOfBoxes']:3?>" id="amountOfBoxes">
                </div>
                <div class="form-group">
                    <label for="max_in_box">Максимум чушек в одном котле</label>
                    <input type="text" class="form-control" name="max_in_box" value="<?=isset($_POST['max_in_box'])?$_POST['max_in_box']:20?>" id="max_in_box">
                </div>
                <input type="submit" class="btn btn-primary" value="Задать параметры">
            </form>
        </div>
    </div>
    <? if (isset($_POST) && !empty($_POST)):?>
        <? if (validate($_POST) === false): ?>
            <script>alert('Не корректно выбраны параметры, параметр должен быть цифрой, не равен или меньше нуля, не больше одной тысячи.')</script>
            <? die; ?>
        <? endif; ?>
                <div class="row">
                    <div class="col-5 mar-auto result">
                        <button class="btn btn-primary mar-auto" style="display: block" id="start">Начать</button>
                        <button class="btn btn-primary mar-auto" style="display: block; margin-top: 10px" id="reset">Сбросить</button>
                        <? if (isset($_POST['amountOfBoxes']) && !empty($_POST['amountOfBoxes'])):?>
                            <h4 class="text-center" id="result-area">
                                <?=isset($_POST['amountOfChushkas']) && !empty($_POST['amountOfChushkas'])?
                                    'Чушки '."<span id=\"total-chushk\" data-value=\"".$_POST['amountOfChushkas']."\">".$_POST['amountOfChushkas'].'</span>':
                                    'Укажите кол-во чушек'?>
                                <p id="total-box" data-value="<?=$_POST['amountOfBoxes']?>">Всего котлов <?=$_POST['amountOfBoxes']?></p>
                                <p id="max" data-max-in-box="<?=$_POST['max_in_box']?>">Максимум чушек в одном котле <?=$_POST['max_in_box']?></p>
                            </h4>
                            <? for ($i = 1; $i <= $_POST['amountOfBoxes']; $i++): ?>
                                <div class="box" id="box_<?=$i?>">
                                    <h6 class="text-center">Котёл № <?=$i?></h6>
                                    <p class="text-center">
                                        <span data-box id="in_box_<?=$i?>" data-value="<?=0?>">0</span>
                                    </p>
                                </div>
                            <? endfor; ?>
                        <? endif; ?>
                    </div>
                </div>
    <? endif; ?>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if (document.getElementById('start') != null) {
            document.getElementById('start').addEventListener('click', function () {
                let reset_boxes = document.querySelectorAll('[data-box]');
                for (let reset_box of reset_boxes) {
                    reset_box.setAttribute('data-value', 0);
                    reset_box.innerHTML = 0;
                }

                let el_total_chushk = document.getElementById('total-chushk');
                let value_total_chushk = parseInt(el_total_chushk.getAttribute('data-value'));
                let value_max_in_box = parseInt(document.getElementById('max').getAttribute('data-max-in-box'));
                let value_total_box = parseInt(document.getElementById('total-box').getAttribute('data-value'));
                let amount_of_iterations = Math.ceil(value_total_chushk / value_max_in_box);

                let value_remains = value_total_chushk;

                if (value_total_chushk % value_max_in_box !== 0 && value_total_box >= amount_of_iterations) {
                    var last_iteration_modified = true;
                } else {
                    var last_iteration_modified = false;
                }

                if (amount_of_iterations > value_total_box) {
                    amount_of_iterations = value_total_box;
                }

                for (let i = 1; i <= amount_of_iterations; i++) {
                    setTimeout(function () {
                    if (last_iteration_modified === true && i == amount_of_iterations) {
                        value_max_in_box = value_total_chushk % value_max_in_box;
                    }

                    let box = document.getElementById('in_box_' + i);

                        for (let j = 1; j <= value_max_in_box; j++) {

                            value_remains--;

                            setTimeout(function () {
                                box.innerHTML = j;
                            }, 50 * (i + j));
                        }
                    }, 1000 * i);
                }

                    setTimeout(function () {
                        if (value_remains != 0) {
                            let remains = document.createElement('p');
                            remains.innerHTML = 'Осталось ' + value_remains + ' лишних чушек.';
                            document.getElementById('result-area').appendChild(remains);
                        }
                    }, 1000 * amount_of_iterations + (5 * value_total_chushk));


            });
        }
        if (document.getElementById('reset') != null) {

            document.getElementById('reset').addEventListener('click', function () {
                window.location.replace(document.URL);
            });
        }
        });
</script>

<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.3/umd/popper.min.js" integrity="sha384-vFJXuSJphROIrBnz7yo7oB41mKfc8JzQZiCq4NCceLEaO4IHwicKwpJf9c9IpFgh" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-beta.2/js/bootstrap.min.js" integrity="sha384-alpBpkh1PFOepccYVYDB4do5UnbKysX5WZXm3XxPqe5iKTfUKjNkCk9SaVuEZflJ" crossorigin="anonymous"></script>
</body>
</html>
