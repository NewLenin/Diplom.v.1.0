

function formAction(form, action) {
    action.preventDefault();
    let idForm = $(form).attr('id');
    $.post({
        url: $(form).attr('action'),
        data: $(form).serialize(),
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        success: (res) => {
            // console.log(res);
            if (res.register == 'success' || res.auth == 'success') {
                window.location.href = '/';
            }
            if (res.purpose == 'success') {
                window.location.href = '/purpose';
            }
            if (res.finance == 'success') {
                window.location.href = '/finance';
            }
            if (res.diary == 'success') {
                window.location.href = '/diary';
            }
        }, error: (res) => {
            // console.log(res);

            idError = '';
            $('form#' + idForm + ' div.invalid-feedback').text('');
            $('form#' + idForm + ' input').removeClass('is-invalid');
            $('form#' + idForm + ' textarea').removeClass('is-invalid');

            $.each(res.responseJSON, (index, value) => {
                if (index == 0) {
                    idError = value;
                }
                $('form#' + idForm + ' div#' + index + 'Error').text(value);
                $('form#' + idForm + ' input#' + index + 'Input').addClass('is-invalid');
                $('form#' + idForm + ' textarea#' + index + 'Input').addClass('is-invalid');
                if (index == 'errors') {
                    $.each(res.responseJSON['errors'], function (index, value) {
                        $('div#formError' + idError).text(value).slideDown(300);
                    });
                }
            })

        }
    })
}

$('.task-status-checkbox').click(function (e) {
    var data = {
        'id': $(this).attr('value')
    }
    // console.log(data);
    e.preventDefault();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/purpose/changeTask',
        type: 'POST',
        data: data,
        success: function (res) {
            window.location.href = '/purpose';

        },
        error: function (res) {
            // console.log();
        }
    })
});


$('input[name="invoice-color-input"]').change(function (e) {
    id = $(this).attr('id');
    color = document.querySelector('.invoice-color-input' + id).value;
    var data = {
        'color': color,
        'id': id
    }
    // console.log(data);
    e.preventDefault();
    $.ajax({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        },
        url: '/finance/changeColor',
        type: 'POST',
        data: data,
        success: function (res) {
            $('circle#' + id).attr('fill', color);
        }
    })
});

$('input[name="day-status-checkbox"]').click(function (e) {
    if ($(this).attr('id') == 'day-status-checkbox') {
        if (document.getElementById('day-status-checkbox').checked) {
            // console.log('day-status-checkbox');
            $('select#status-select').slideDown(300);

        } else {
            $('select#status-select').slideUp(300);
            $('select#status-select').prop('selectedIndex', 0);
        }
    }
    if ($(this).attr('id') == 'day-warning-checkbox') {
        if (document.getElementById('day-warning-checkbox').checked) {
            // console.log('day-warning-checkbox');
            $('input#warning').slideDown(300);
        } else {
            $('input#warning').slideUp(300);
            $('input#warning').val(null);
        }
    }
})

google.charts.load("current", { packages: ["corechart"] });
google.charts.setOnLoadCallback(drawChart);

function drawChart() {
    var jsonData = $.ajax({
        url: '/finance/diagram',
        dataType: "json",
        async: false
    }).responseJSON;
    // console.log(jsonData);
    var data = new google.visualization.DataTable();
    data.addColumn('string', 'name');
    data.addColumn('number', 'percent');
    const rows = [];
    const colors = [];
    $.each(jsonData.data, function (index, value) {
        rows.push([
            value.name,
            value.percent]
        )
    });
    data.addRows(
        rows
    )
    // console.log(colors);
    var options = {
        height: '100%',
        width: '100%',
        legend: 'none',
        colors: jsonData.color,
        pieHole: 0.4,
    };
    var chart = new google.visualization.PieChart(document.getElementById('diagram'));
    chart.draw(data, options);
}

// function drawChart() {
//     var jsonData = $.ajax({
//         url: '/profile/diagram',
//         dataType: "json",
//         async: false
//     }).responseJSON;
//     console.log(jsonData);
//     var data = new google.visualization.DataTable();
//     data.addColumn('string', 'name');
//     data.addColumn('number', 'percent');
//     const rows = [];
//     const colors = [];
//     $.each(jsonData.data, function (index, value) {
//         rows.push([
//             value.name,
//             value.percent]
//         )
//     });
//     data.addRows(
//         rows
//     )
//     console.log(colors);
//     var options = {
//         height: '100%',
//         width: '100%',
//         legend: 'none',
//         colors: jsonData.color,
//         pieHole: 0.4,
//     };
//     var chart = new google.visualization.PieChart(document.getElementById('diagram'));
//     chart.draw(data, options);
// }

    $(document).ready(function (params) {
        //данные из календаря
        date = document.getElementById('calendar-diary').value;
        var data = {
            'date': document.getElementById('calendar-diary').value
        }
        $.get({
            url: '/diary/showNote',
            data: data,
            success: (res) => {
                // console.log(res.warningValue);

                // Статистика
                $('span#text-success').text(' ' + res.goodDays);
                $('span#text-warning').text(' ' + res.normalDays);
                $('span#text-danger').text(' ' + res.badDays);
                $('span#text-primary').text(' ' + res.nonDays);
                $('span#monthValue').text(' ' + res.monthValue);
                max = 0;
                $.each(res.warningValue, function (index, value) {
                    if (max > value) {
                        max = max;

                    } else {
                        max = value;
                    }
                    // console.log(max);
                    if (max == value) {
                        $('span#warningValue').text(index);
                    }
                });

                // Всё остальное
                $('textarea#messageInput').val(res.message);
                $('span#date-info').text(res.dating);
                if (res.status != null) {
                    $('input#day-status-checkbox').prop('checked', true);
                    $('select#status-select').slideDown(300);
                    if (res.status == 'Отличный') {
                        $('select#status-select').prop('selectedIndex', 1);
                    }
                    if (res.status == 'Нормальный') {
                        $('select#status-select').prop('selectedIndex', 2);
                    }
                    if (res.status == 'Плохой') {
                        $('select#status-select').prop('selectedIndex', 3);
                    }
                }
                else {
                    $('select#status-select').slideUp(300);
                    $('select#status-select').prop('selectedIndex', 0);
                    $('input#day-status-checkbox').prop('checked', false);
                }
                if (res.warning != null) {
                    $('input#day-warning-checkbox').prop('checked', true);
                    $('input#warning').slideDown(300);
                    $('input#warning').val(res.warning);
                } else {
                    $('input#day-warning-checkbox').prop('checked', false);
                    $('input#warning').slideUp(300);
                    $('input#warning').val('');
                }


            }, error: (res) => {
                // Пустой месяц
                // Статистика
                $('span#text-success').text('');
                $('span#text-warning').text('');
                $('span#text-danger').text('');
                $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                $('span#warningValue').text('');
                $('span#monthValue').text(' ' + res.responseJSON.monthValue);
                // Всё остальное
                $('textarea#messageInput').val('');
                $('span#date-info').text(date);
                $('input#warning').val('');
                $('input#day-warning-checkbox').prop('checked', false);
                $('input#day-status-checkbox').prop('checked', false);
                $('select#status-select').prop('selectedIndex', 0);
                $('input#warning').slideUp(300);
                $('select#status-select').slideUp(300);

                // Месяц с данными
                if (res.responseJSON.errors == 'Error') {
                    // Статистика
                    $('span#monthValue').text(' ' + res.responseJSON.monthValue);
                    $('span#text-success').text(' ' + res.responseJSON.goodDays);
                    $('span#text-warning').text(' ' + res.responseJSON.normalDays);
                    $('span#text-danger').text(' ' + res.responseJSON.badDays);
                    $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                    max = 0;
                    $.each(res.responseJSON.warningValue, function (index, value) {
                        if (max > value) {
                            max = max;

                        } else {
                            max = value;
                        }

                        if (max == value) {
                            $('span#warningValue').text(index);
                        }
                    });
                    // Всё остальное
                    $('textarea#messageInput').val('');
                    $('span#date-info').text(date);
                    $('input#warning').val('');
                    $('input#day-warning-checkbox').prop('checked', false);
                    $('input#day-status-checkbox').prop('checked', false);
                    $('select#status-select').prop('selectedIndex', 0);
                    $('input#warning').slideUp(300);
                    $('select#status-select').slideUp(300);
                }
            }
        })
    })
    $('input#calendar-diary').change(function (params) {
        date = document.getElementById('calendar-diary').value;
        var data = {
            'date': document.getElementById('calendar-diary').value
        }
        $.get({
            url: '/diary/showNote',
            data: data,
            success: (res) => {

                // Статистика
                if (window.location.href == '/diary' ) {
                $('span#text-success').text(' ' + res.goodDays);
                $('span#text-warning').text(' ' + res.normalDays);
                $('span#text-danger').text(' ' + res.badDays);
                $('span#text-primary').text(' ' + res.nonDays);
                $('span#monthValue').text(' ' + res.monthValue);
                max = 0;
                $.each(res.warningValue, function (index, value) {
                    if (max > value) {
                        max = max;

                    } else {
                        max = value;
                    }
                    // console.log(max);
                    if (max == value) {
                        $('span#warningValue').text(index);
                    }
                });
                // Всё остальное
                $('textarea#messageInput').val(res.message);
                $('span#date-info').text(res.dating);
                if (res.status != null) {
                    $('input#day-status-checkbox').prop('checked', true);
                    $('select#status-select').slideDown(300);
                    if (res.status == 'Отличный') {
                        $('select#status-select').prop('selectedIndex', 1);
                    }
                    if (res.status == 'Нормальный') {
                        $('select#status-select').prop('selectedIndex', 2);
                    }
                    if (res.status == 'Плохой') {
                        $('select#status-select').prop('selectedIndex', 3);
                    }
                }
                else {
                    $('select#status-select').slideUp(300);
                    $('select#status-select').prop('selectedIndex', 0);
                    $('input#day-status-checkbox').prop('checked', false);
                }
                if (res.warning != null) {
                    $('input#day-warning-checkbox').prop('checked', true);
                    $('input#warning').slideDown(300);
                    $('input#warning').val(res.warning);
                } else {
                    $('input#day-warning-checkbox').prop('checked', false);
                    $('input#warning').slideUp(300);
                    $('input#warning').val('');
                }
                // console.log(res);
            }} , error: (res) => {
                if (window.location.href == '/diary' ) {
                // Пустой месяц
                // Статистика
                $('span#text-success').text('');
                $('span#text-warning').text('');
                $('span#text-danger').text('');
                $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                $('span#warningValue').text('');
                $('span#monthValue').text(' ' + res.responseJSON.monthValue);
                // Всё остальное
                $('textarea#messageInput').val('');
                $('span#date-info').text(date);
                $('input#warning').val('');
                $('input#day-warning-checkbox').prop('checked', false);
                $('input#day-status-checkbox').prop('checked', false);
                $('select#status-select').prop('selectedIndex', 0);
                $('input#warning').slideUp(300);
                $('select#status-select').slideUp(300);
                // Месяц с данными
                if (res.responseJSON.errors == 'Error') {
                    // Статистика
                    $('span#monthValue').text(' ' + res.responseJSON.monthValue);
                    $('span#text-success').text(' ' + res.responseJSON.goodDays);
                    $('span#text-warning').text(' ' + res.responseJSON.normalDays);
                    $('span#text-danger').text(' ' + res.responseJSON.badDays);
                    $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                    max = 0;
                    $.each(res.responseJSON.warningValue, function (index, value) {
                        if (max > value) {
                            max = max;

                        } else {
                            max = value;
                        }

                        if (max == value) {
                            $('span#warningValue').text(index);
                        }
                    });
                    // Всё остальное
                    $('textarea#messageInput').val('');
                    $('span#date-info').text(date);
                    $('input#warning').val('');
                    $('input#day-warning-checkbox').prop('checked', false);
                    $('input#day-status-checkbox').prop('checked', false);
                    $('select#status-select').prop('selectedIndex', 0);
                    $('input#warning').slideUp(300);
                    $('select#status-select').slideUp(300);
                }
            }}
        })
    })


    $(document).ready(function (params) {
        //данные из календаря
        date = document.getElementById('calendar-diary-profile').value;
        var data = {
            'date': document.getElementById('calendar-diary-profile').value
        }
        $.get({
            url: '/profile/showNote',
            data: data,
            success: (res) => {
                // console.log(res.warningValue);
                // Статистика

                $('span#text-success').text(' ' + res.goodDays);
                $('span#text-warning').text(' ' + res.normalDays);
                $('span#text-danger').text(' ' + res.badDays);
                $('span#text-primary').text(' ' + res.nonDays);
                $('span#monthValue').text(' ' + res.monthValue);
                max = 0;
                $.each(res.warningValue, function (index, value) {
                    if (max > value) {
                        max = max;

                    } else {
                        max = value;
                    }
                    // console.log(max);
                    if (max == value) {
                        $('span#warningValue').text(index);
                    }
                });



            }, error: (res) => {


                // Пустой месяц
                // Статистика
                $('span#text-success').text('');
                $('span#text-warning').text('');
                $('span#text-danger').text('');
                $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                $('span#warningValue').text('');
                $('span#monthValue').text(' ' + res.responseJSON.monthValue);

                // Месяц с данными
                if (res.responseJSON.errors = 'Error') {
                    // Статистика
                    $('span#monthValue').text(' ' + res.responseJSON.monthValue);
                    $('span#text-success').text(' ' + res.responseJSON.goodDays);
                    $('span#text-warning').text(' ' + res.responseJSON.normalDays);
                    $('span#text-danger').text(' ' + res.responseJSON.badDays);
                    $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                    max = 0;
                    $.each(res.responseJSON.warningValue, function (index, value) {
                        if (max > value) {
                            max = max;

                        } else {
                            max = value;
                        }

                        if (max == value) {
                            $('span#warningValue').text(index);
                        }
                    });


                }
            }
        })
    })
    $('input#calendar-diary-profile').change(function (params) {
        date = document.getElementById('calendar-diary-profile').value;
        var data = {
            'date': document.getElementById('calendar-diary-profile').value
        }
        $.get({
            url: '/profile/showNote',
            data: data,
            success: (res) => {
                // console.log(res.warningValue);

                // Статистика
                $('span#text-success').text(' ' + res.goodDays);
                $('span#text-warning').text(' ' + res.normalDays);
                $('span#text-danger').text(' ' + res.badDays);
                $('span#text-primary').text(' ' + res.nonDays);
                $('span#monthValue').text(' ' + res.monthValue);
                max = 0;
                $.each(res.warningValue, function (index, value) {
                    if (max > value) {
                        max = max;

                    } else {
                        max = value;
                    }
                    // console.log(max);
                    if (max == value) {
                        $('span#warningValue').text(index);
                    }
                });

            }, error: (res) => {
                // Пустой месяц
                // Статистика
                $('span#text-success').text('');
                $('span#text-warning').text('');
                $('span#text-danger').text('');
                $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                $('span#warningValue').text('');
                $('span#monthValue').text(' ' + res.responseJSON.monthValue);

                // Месяц с данными
                if (res.responseJSON.errors == 'Error') {
                    // Статистика
                    $('span#monthValue').text(' ' + res.responseJSON.monthValue);
                    $('span#text-success').text(' ' + res.responseJSON.goodDays);
                    $('span#text-warning').text(' ' + res.responseJSON.normalDays);
                    $('span#text-danger').text(' ' + res.responseJSON.badDays);
                    $('span#text-primary').text(' ' + res.responseJSON.nonDays);
                    max = 0;
                    $.each(res.responseJSON.warningValue, function (index, value) {
                        if (max > value) {
                            max = max;

                        } else {
                            max = value;
                        }

                        if (max == value) {
                            $('span#warningValue').text(index);
                        }
                    });


                }
            }
        })
    })







function addEl() {
    let inputs = document.querySelectorAll("input.task")
    let lastNum = ((inputs[inputs.length - 1]).getAttribute('name'));
    let lastNumero = ((inputs[inputs.length - 1]).getAttribute('name'));
    if (lastNumero == 0) {
        lastNumero = 1;
    }

    let nextNum = Number(lastNumero) + 1;
    let elem = document.createElement("p");
    elem.innerHTML = `<input type="text" id="${nextNum}Input" class="task" name="${nextNum}" placeholder="Задача ${nextNum}" />`;
    let parentGuest = document.getElementById(lastNum + "Input");
    parentGuest.parentNode.insertBefore(elem, parentGuest.nextSibling);
}





// $(document).ready(function (params) {
//     $.get({
//         url: '/finance/diagram',
//         // data: data,
//         success: (res) => {

//             const PieChart ={
//                 data: [
//                     ['Task', '21'],
//                     res
//                 ],
//                 element: '#donutchart',
//                 options: {
//                     width: 500,
//                     height: 300
//                 }
//             };

//             const init = () =>{
//                 PieChart.chart = new google.visualization.PieChart(
//                     document.querySelector(PieChart.element)
//                 );
//                 PieChart.chart.draw(
//                     google.visualization.arrayToDataTable(PieChart.data),
//                     PieChart.options
//                 );
//             };
//             google.charts.load("current", {packages:["corechart"], callback: init});
//             // var data = google.visualization.arrayToDataTable([
//             //     ['Task', '21'],
//             //     res
//             //   ]);

//             //   var options = {
//             //     title: 'My Daily Activities',
//             //     pieHole: 0.4,
//             //   };

//             //   var chart = new google.visualization.PieChart(document.getElementById('donutchart'));
//             //   chart.draw(data, options);
//         }
//     })
// })









// $('select#filter').change(function () {
//     let status = $(this).val();
//     $.ajax({
//         url: '/profile/filter',
//         type: 'POST',
//         data: {status: status},
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (res) {
//             $('div#orders').html(res);
//         }, error: function (res) {
//             console.log(res);
//         }
//     })
// });



// $('form#addOrder').submit( function(e){
//     e.preventDefault();
//     let infoOrder = new FormData ($('form#addOrder').get(0));
//     $.ajax({
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType: 'json',
//         url: '/profile/addOrder',
//         type: 'POST',
//         data: infoOrder,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (res) {
//             window.location.href = '/profile';
//         }, error: function (res) {
//             $('input').removeClass('is-invalid');
//             $('textarea').removeClass('is-invalid');
//             $.each(res.responseJSON['errors'], function (index, value) {
//                 console.log(index);
//                 if (index == 'desk') {
//                     $('textarea[name="desk"]').addClass('is-invalid');
//                     $('div#' + index + 'Error').text(value);
//                 }else{
//                 $('input[name="' + index + '"]').addClass('is-invalid');
//                 $('div#' + index + 'Error').text(value);}
//             });
//         }
//     })
// });



// $('select#switchCat').change(function () {
//     let info = $(this).val();

//     let id = $(this).attr('data-order')
//     if(info == 'Принято в работу'){
//         $('input#com' + id).slideDown(300);
//         $('input#photoFile' + id).slideUp(300);
//     }else if (info == 'Выполнено') {
//         $('input#com' + id).slideUp(300);
//         $('input#photoFile' + id).slideDown(300);
//     }else{
//         $('input#com' + id).slideUp(300);
//         $('input#photoFile' + id).slideUp(300);
//     }

// });



// $('form#switchForm').submit( function(e){
//     e.preventDefault();
//     let id = $(this).attr('data-order')
//     let info = new FormData ($('form#switchForm[data-order="'+id+'"]').get(0));
//     $.ajax({
//         cache: false,
//         contentType: false,
//         processData: false,
//         dataType: 'json',
//         url: '/superadmin/switch',
//         type: 'POST',
//         data: info,
//         headers: {
//             'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
//         },
//         success: function (res) {

//              window.location.href = '/superadmin';
//         }, error: function (res) {
//             console.log(res);
//         }
//     })
// });

// $('form#reg').on('submit', function (e) {
//     e.preventDefault();

//     let info = $(this).serializeArray();

//     $.ajax({
//         url: $(this).attr('action'),
//         type: $(this).attr('method'),
//         data: info,
//         success: function (res) {
//             window.location.href = '/';

//         },
//         error: function (res) {
//             // console.log(res);
//             $('input').removeClass('is-invalid');
//             $.each(res.responseJSON['errors'], function (index, value) {

//                 $('form#reg input[name="' + index + '"]').addClass('is-invalid');
//                 $('div#' + index + 'Error').text(value);
//             });
//         }
//     });

// });


// $('form#auth').on('submit', function (e) {
//     e.preventDefault();

//     let info = $(this).serializeArray();
//     console.log(info);
//     $.ajax({
//         url: $(this).attr('action'),
//         type: $(this).attr('method'),
//         data: info,
//         success: function (res) {

//             window.location.href = '/';
//         },
//         error: function (res) {
//             $('input').removeClass('is-invalid');
//             $('div#formError').slideUp(300);
//             $.each(res.responseJSON['errors'], function (index, value) {
//                 if (index == 'form') {
//                     $('div#formError').text(value).slideDown(300);
//                 } else {
//                     $('form#auth input[name="' + index + '"]').addClass('is-invalid');
//                     $('div#' + index + 'Error').text(value);
//                 }
//             });

//         }
//     });

// });
// $('form#addpurpose').on('submit', function (e) {
//     e.preventDefault();

//     let info = $(this).serializeArray();

//     $.ajax({
//         url: $(this).attr('action'),
//         type: $(this).attr('method'),
//         data: info,
//         success: function (res) {
//             window.location.href = '/purpose';
//         },
//         error: function (res) {
//             $('input').removeClass('is-invalid');
//             $.each(res.responseJSON['errors'], function (index, value) {
//                 $('form#addpurpose input[name="' + index + '"]').addClass('is-invalid');
//                 $('div#' + index + 'Error').text(value);
//             });
//             console.log(res);
//         }
//     });
// });
