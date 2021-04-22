$(document).ready(function(){
    $("body").on('click', '.create_edit', function() {
        var id = $(this).attr('id').replace('object_', '');
        $("#globalModal").modal();
        if (id == '0')
            $("#globalModal .modal-title").text($("#modal_title_new").html());
        else
            $("#globalModal .modal-title").text($("#modal_title_edit").html());

        $.ajax({
            url: $("#modal_getformurl").html(),
            type : "GET",
            data : {id: id}
        }).done(function(resp) {
            $("#globalModal .modal-body").html(resp);
        });
    });

    $("body").on('click', '.create_edit_tartozas', function() {
        var id = $(this).attr('id').replace('object_', '');
        $("#globalModal").modal();
        if (id == '0')
            $("#globalModal .modal-title").text($("#modal_title_new").html());
        else
            $("#globalModal .modal-title").text($("#modal_title_edit").html());

        $.ajax({
            url: $("#modal_getformurl").html(),
            type : "GET",
            data : {id: id, 'munkas_id' : $("#munkas_id").val()}
        }).done(function(resp) {
            $("#globalModal .modal-body").html(resp);
        });
    });

    $("#globalModal .btn-primary").click(function(){
        var url = $("#modal_saveformurl").html();

        $.post(url, $("#globalModal form").serialize(), function(resp) {

            //clear all previous errors
            $(".form-group").removeClass('has-error');
            $(".help-block").each(function() {
                $(this).text("");
            });

            if(resp['id'] == undefined) {
                for(respKey in resp) {
                    //readd errors 
                    $("#" + respKey).closest('div.form-group').addClass('has-error');
         
                    //readd error messages
                    for (messageKey in resp[respKey]) {
                        $("#help_" + respKey).append(resp[respKey][messageKey]);
                    }
                }
            } else {
                $("#globalModal").modal('hide');
                $.sticky($("#modal_success").html(), {classList: 'success', autoclose: 4000,  position: 'top-right'});
                
                var saveFormCallbackFuncName = $("#saveFormCallbackFuncName").html();
                window[saveFormCallbackFuncName](resp);
            } 
        });
    });
    
    $("body").on('click', '.torol', function() {
        var $row = $(this).parents('tr');
        if (confirm($("#torol_confirm_text").text())) {
            var id = $(this).attr('id').replace('object_torol_', '');
            var url = $("#delete_url").html();
            $.post(url, {'id' : id}, function(resp) {
                if (resp == 'success') {
                    var oTable = $('.myDataTable').DataTable();
                    oTable.row( $row ).remove().draw();
                    $.sticky($("#modal_success").html(), {classList: 'success', autoclose: 4000,  position: 'top-right'});
                } else if(resp == 'nodelete') {
                    $("#globalModal .modal-body").html($("#modal_nodelete").html());
                    $("button.btn-primary").hide();
                    $("#globalModal").modal();         
                } else {
                    $.sticky($("#ajax_error_message").html(), {classList: 'error', autoclose: 4000,  position: 'top-right'});
                }
            });
        }
    });

    $("body").on('keyup', '#rendszam', function() {
        $(this).val($(this).val().toUpperCase());
    });

    $("[id^='tartozas_lezar_']").click(function() {
        
        var $row = $(this).parents('tr');
        
        if (confirm($("#lezar_confirm_text").text())) {
            var id = $(this).attr('id').replace('tartozas_lezar_', '');
            var url = $("#lezar_tartozas_url").html();
            $.post(url, {'id' : id}, function(resp) {
                if (resp == 'success') {
                    
                    var lezartTable = $('#lezart_tartozasok_table').DataTable();
                    lezartTable.row.add([
                        $row.find('td:eq(0)').html(),
                        makeSortString($row.find('td:eq(0)').html()),
                        $row.find('td:eq(1)').html(),
                        $row.find('td:eq(1)').html().replace(' RON', ''),
                        $row.find('td:eq(2)').html(),
                    ]);
                    lezartTable.draw();
                    
                    var oTable = $('.myDataTable').DataTable();
                    oTable.row( $row ).remove().draw();
                 
                    $.sticky($("#modal_success").html(), {classList: 'success', autoclose: 4000,  position: 'top-right'});
                } else {
                    $.sticky($("#ajax_error_message").html(), {classList: 'error', autoclose: 4000,  position: 'top-right'});
                }
            });
        }
        
    });

});
// END of $(document).ready

function saveMunkasCallback(resp)
{
    var respId = resp['id'];
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
        oTable.cell($tr, 0).data($("#name").val());
        oTable.cell($tr, 1).data(makeSortString($("#name").val()));
        oTable.cell($tr, 2).data($("#munkastipus option:selected").text());
        oTable.cell($tr, 3).data($("#hely option:selected").text());
        oTable.cell($tr, 4).data(makeSortString($("#hely option:selected").text()));
        oTable.cell($tr, 5).data($("#hiredate").val());
        oTable.cell($tr, 6).data($("#birthdate").val());
        oTable.cell($tr, 7).data(resp['fizetes'] + ' RON');
        
        
        
    } else {
        var muveletCell = '';
        
        muveletCell += '<a href="/munkas/egyenleg/'+respId+'" title="Egyenleg">\n' +
                        '<span class="glyphicon glyphicon-usd"></span>\n' +
                    '</a>';        
        
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#name").val(),
            makeSortString($("#name").val()),
            $("#munkastipus option:selected").text(),
            $("#hely option:selected").text(),
            makeSortString($("#hely option:selected").text()),
            $("#hiredate").val(),
            $("#birthdate").val(),
            resp['fizetes'] + ' RON',
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveSzolgaltatasCallback(resp)
{    
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    
    var cegesar;
    if ($("#cegesar").val())
        cegesar = $("#cegesar").val() + " RON";
    else
        cegesar = '-';
    
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
                
        oTable.cell($tr, 0).data($("#nev").val());
        oTable.cell($tr, 1).data(makeSortString($("#nev").val()));
        oTable.cell($tr, 2).data($("#alapar").val() + " RON");
        oTable.cell($tr, 3).data(cegesar);        
    } else {
        var muveletCell = '';
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#nev").val(),
            makeSortString($("#nev").val()),
            $("#alapar").val() + " RON",
            cegesar,
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveKellekCallback(resp)
{    
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
        
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
                
        oTable.cell($tr, 0).data($("#nev").val());
        oTable.cell($tr, 1).data(makeSortString($("#nev").val()));
    } else {
        var muveletCell = '';
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#nev").val(),
            makeSortString($("#nev").val()),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveKavefajtaCallback(resp)
{    
    var respId = resp['id'];
    
    var id = $("#globalModal form #id").val();
        
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
                
        $tr.find('td:nth-child(1)').text($("#nev").val());
		$tr.find('td:nth-child(2)').text($("#mennyiseg").val());
		$tr.find('td:nth-child(3)').text($("#tejmennyiseg").val());
		$tr.find('td:nth-child(4)').text($("#ar").val());
    } else {
        var muveletCell = '';
		muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
						'<span class="glyphicon glyphicon-pencil"></span>\n' +
					'</a>';

		muveletCell += '<a href="javascript:void(0)" class="torol_kavefajta" id="object_torol_'+respId+'" title="Törlés">\n' +
						'<span class="glyphicon glyphicon-remove"></span>\n' +
					'</a>';

		var newRow =	'<tr>\
							<td>'+$("#nev").val()+'</td>\
							<td>'+$("#mennyiseg").val()+'</td>\
							<td>'+$("#tejmennyiseg").val()+'</td>\
							<td>'+$("#ar").val()+'</td>\
							<td>'+muveletCell+'</td>\
						</tr>';
		$("table#kavefajtak_table tbody").append(newRow);
    }
}

function saveKoltsegCallback(resp)
{
    $("#autokoltseg_honap")[0].click();
    
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');

        oTable.cell($tr, 0).data($("#auto option:selected").text());
        oTable.cell($tr, 1).data($("#koltsegnev").val());
        oTable.cell($tr, 2).data(makeSortString($("#koltsegnev").val()));
        oTable.cell($tr, 3).data($("#osszeg").val() + ' RON');
        oTable.cell($tr, 4).data($("#osszeg").val());
        oTable.cell($tr, 5).data($("#datum").val());
    } else {
        var muveletCell = '';
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#auto option:selected").text(),
            $("#koltsegnev").val(),
            makeSortString($("#koltsegnev").val()),
            $("#osszeg").val()+ ' RON',
            $("#osszeg").val(),
            $("#datum").val(),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveMindenKoltsegCallback(resp)
{
    $("#mindenkoltseg_honap")[0].click();
    
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');

        oTable.cell($tr, 0).data($("#koltsegnev").val());
        oTable.cell($tr, 1).data(makeSortString($("#koltsegnev").val()));
        oTable.cell($tr, 2).data($("#osszeg").val() + ' RON');
        oTable.cell($tr, 3).data($("#osszeg").val());
        oTable.cell($tr, 4).data($("#datum").val());
        oTable.cell($tr, 5).data($("#tipus option:selected").text());
        oTable.cell($tr, 6).data(makeSortString($("#tipus option:selected").text()));
    } else {
        var muveletCell = '';
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#koltsegnev").val(),
            makeSortString($("#koltsegnev").val()),
            $("#osszeg").val()+ ' RON',
            $("#osszeg").val(),
            $("#datum").val(),
            $("#tipus option:selected").text(),
            makeSortString($("#tipus option:selected").text()),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveUgyintezoFizetesCallback(resp)
{
    $("#ugyintezofizetes_honap")[0].click();
    
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');

        oTable.cell($tr, 0).data($("#osszeg").val() + ' RON');
        oTable.cell($tr, 1).data($("#osszeg").val());
        oTable.cell($tr, 1).data($("#datum").val());
    } else {
        var muveletCell = '';
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#osszeg").val()+ ' RON',
            $("#osszeg").val(),
            $("#datum").val(),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveTartozasCallback(resp)
{
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');

        oTable.cell($tr, 0).data($("#tartozasnev").val());
        oTable.cell($tr, 1).data(makeSortString($("#tartozasnev").val()));
        oTable.cell($tr, 2).data($("#osszeg").val() + ' RON');
        oTable.cell($tr, 3).data($("#osszeg").val());
        oTable.cell($tr, 4).data($("#datum").val());
    } else {
        var muveletCell = '';
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit_tartozas" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedClose").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" id="tartozas_lezar_'+respId+'" class="btn btn-default">\n' +
                            'Lezár\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#tartozasnev").val(),
            makeSortString($("#tartozasnev").val()),
            $("#osszeg").val()+ ' RON',
            $("#osszeg").val(),
            $("#datum").val(),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveAutoCallback(resp)
{
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
        oTable.cell($tr, 0).data($("#rendszam").val());
    } else {
        var muveletCell = '';
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#rendszam").val(),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveCegCallback(resp)
{
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    
    var automatikus_hosszabbitas;
    if ($("#automatikushosszabbitas").is(':checked'))
        automatikus_hosszabbitas = 'Igen';
    else
        automatikus_hosszabbitas = 'Nem';
    
    if (id) {
        var $tr = $("#object_" + id).closest('tr');

        oTable.cell($tr, 0).data($("#cegname").val());
        oTable.cell($tr, 1).data(makeSortString($("#cegname").val()));
        oTable.cell($tr, 2).data($("#szerzodesdatum").val());
        oTable.cell($tr, 3).data($("#idotartam").val() + ' hónap');
        oTable.cell($tr, 4).data(automatikus_hosszabbitas);
        oTable.cell($tr, 5).data($("#fizetesihatarido option:selected").text());
    } else {
        var muveletCell = '';
        
        muveletCell += '<a href="/ceg/view/'+respId+'" title="Részletek">\n' +
                        '<span class="glyphicon glyphicon-eye-open"></span>\n' +
                    '</a>';        
        
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }

        oTable.row.add([
            $("#cegname").val(),
            makeSortString($("#cegname").val()),
            $("#szerzodesdatum").val(),
            $("#idotartam").val() + ' hónap',
            automatikus_hosszabbitas,
            $("#fizetesihatarido option:selected").text(),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveAktivitasCallback(resp)
{    
    var respId = resp['id'];
    
    var nev;
    if ($("#szolgaltatas").val() == '0') {
        nev = $("#nev").val();
    } else {
        nev = $("#szolgaltatas option:selected").text();
    }

    if ($("#viasz").is(':checked'))
        nev += ' + Viasz';

    if ($("#sma").is(':checked'))
        nev += ' + Spălat motor cu aburi';

    if ($("#smac").is(':checked'))
        nev += ' + Spălat motor cu aburi chimic';
	
    if ($("#ss").is(':checked'))
        nev += ' + Spălat șasiu';
	
    var ceg;
    if ($("#ceg").val() == '') {
        ceg = '-';
    } else {
        ceg = $("#ceg option:selected").text();
    }
    
    var alkalmazottak = new Array();
    $("[id^='munkasok_']:checked").each(function(){
        alkalmazottak.push($(this).parent().find('label').text());
    });
    var alkalmazottakString = alkalmazottak.join(', ');

    var kellekek = new Array();
    $("[id^='kellekek_']:checked").each(function(){
        var id = $(this).attr('id').replace('kellekek_', '');
        
        var kellekText = $(this).parent().find('label').text();
        if ($("#kellek_munkas_" +id).val() != '0') {
            kellekText += '(' + $("#kellek_munkas_" + id +" option:selected").text() + ')';
        }
        
        kellekek.push(kellekText);
    });
    var kellekekString = kellekek.join(', ');
    
    
    var muveletCell = '';
    if ($("#isAllowedEdit").val() == '1') {
        muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                        '<span class="glyphicon glyphicon-pencil"></span>\n' +
                    '</a>';
    }

    if ($("#isAllowedDelete").val() == '1') {
        muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                        '<span class="glyphicon glyphicon-remove"></span>\n' +
                    '</a>';
    }

    if($("#isAllowedFizet").val() == '1' && $("#status").is(':checked') == false)
        muveletCell += '<a href="javascript:void(0)" id="aktivitas_fizet_'+respId+'" class="btn btn-default">\n' +
                            'Fizet\n' +
                       '</a>';
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');

        oTable.cell($tr, 0).data(respId);
        oTable.cell($tr, 1).data(nev);
        oTable.cell($tr, 2).data(makeSortString($("#nev").val()));
        oTable.cell($tr, 3).data($("#ar").val() + ' RON');
        oTable.cell($tr, 4).data($("#ar").val());
        oTable.cell($tr, 5).data($("#idopont").val());
        oTable.cell($tr, 6).data($("#rendszam").val());
        oTable.cell($tr, 7).data(ceg);
        oTable.cell($tr, 8).data(makeSortString(ceg));
        oTable.cell($tr, 9).data(alkalmazottakString);
        oTable.cell($tr, 10).data(kellekekString);
        oTable.cell($tr, 11).data($("#tipus option:selected").text());
        oTable.cell($tr, 12).data($("#hely option:selected").text());
        oTable.cell($tr, 13).data(makeSortString($("#hely option:selected").text()));
        oTable.cell($tr, 14).data(muveletCell);
    } else {
        oTable.row.add([
            respId,
            nev,
            makeSortString($("#nev").val()),
            $("#ar").val() + ' RON',
            $("#ar").val(),
            $("#idopont").val(),
            $("#rendszam").val(),
            ceg,
            makeSortString(ceg),
            alkalmazottakString,
            kellekekString,
            $("#tipus option:selected").text(),
            $("#hely option:selected").text(),
            makeSortString($("#hely option:selected").text()),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveTeendoCallback(resp)
{    
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');

        oTable.cell($tr, 0).data($("#user option:selected").text());
        oTable.cell($tr, 1).data($("#description").val());
    } else {
        var muveletCell = '<input type="checkbox" id="lezar_teendo_"'+respId+'" />';
        oTable.row.add([
            $("#user option:selected").text(),
            $("#description").val(),
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveUserCallback(resp)
{
    var respId = resp['id'];
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    
    var roles = new Array();
    $("#roles option:selected").each(function(){
        roles.push($(this).text());
    });
    var roleString = roles.join(', ');
    
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
        oTable.cell($tr, 0).data($("#username").val());
        oTable.cell($tr, 1).data(roleString);
    } else {
        var muveletCell = '';
        
        if ($("#isAllowedEdit").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
        }

        if ($("#isAllowedDelete").val() == '1') {
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';
        }
        
        oTable.row.add([
            $("#username").val(),
            roleString,
            muveletCell,
        ]);
    }

    oTable.draw();
}

function saveAruCallback(resp)
{
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
		oTable.cell($tr, 0).data(respId);
		oTable.cell($tr, 1).data($("#nev").val());
		oTable.cell($tr, 2).data(makeSortString($("#nev").val())),
		oTable.cell($tr, 3).data($("#vetelar").val());
		oTable.cell($tr, 4).data($("#eladasiar").val());
		oTable.cell($tr, 5).data($("#darab").val());
    } else {
        var muveletCell = '';
        
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';

        oTable.row.add([
			respId,
            $("#nev").val(),
            makeSortString($("#nev").val()),
			$("#vetelar").val(),
			$("#eladasiar").val(),
			$("#darab").val(),
            muveletCell,
        ]);
    }

    oTable.draw();
	
	$("#aruforgas_get").trigger('click');
}

function saveArulasCallback(resp)
{
    var respId = resp['id'];
    
    var oTable = $('.myDataTable').DataTable();
    var id = $("#globalModal form #id").val();
	
	if ($("#kavefajta").val() != "") {
		var nev = $("#kavefajta option:selected").text();
		if ($("#cukor").val() > '0')
			nev += ' +'+$("#cukor").val()+'cukor';
		if ($("#pohar").is(':checked'))
			nev += ' +1pohár';
		if ($("#poharfedo").is(':checked'))
			nev += ' +1pohárfedő';
	} else {
		var nev = $("#aru option:selected").text();
	}
	
    if (id) {
        var $tr = $("#object_" + id).closest('tr');
		oTable.cell($tr, 0).data(respId);
		oTable.cell($tr, 1).data(nev);
		oTable.cell($tr, 2).data(makeSortString(nev));		
		oTable.cell($tr, 3).data($("#darab").val());
		oTable.cell($tr, 4).data($("#tipus option:selected").text());
		oTable.cell($tr, 5).data(makeSortString($("#tipus option:selected").val()));
		oTable.cell($tr, 6).data($("#megjegyzes").val());
		oTable.cell($tr, 7).data(resp['created_at']);
    } else {
        var muveletCell = '';
        
            muveletCell += '<a href="javascript:void(0)" class="create_edit" id="object_'+respId+'" title="Módosítás">\n' +
                            '<span class="glyphicon glyphicon-pencil"></span>\n' +
                        '</a>';
            muveletCell += '<a href="javascript:void(0)" class="torol" id="object_torol_'+respId+'" title="Törlés">\n' +
                            '<span class="glyphicon glyphicon-remove"></span>\n' +
                        '</a>';

        oTable.row.add([
			respId,
            nev,
            makeSortString(nev),
			$("#darab").val(),
			$("#tipus option:selected").text(),
			makeSortString($("#tipus option:selected").val()),
			$("#megjegyzes").val(),
			makeSortString($("#megjegyzes").val()),
			resp['created_at'],
            muveletCell,
        ]);
    }

    oTable.draw();
	$("#aruforgas_osszesito").load('/bar/osszesito');
}

function makeSortString(s)
{
    var translate_re = /[éáűőúöüóíÉÁŰPŐÚÖÜÓÍăĂîÎșȘțȚâÂ);]/g;
    
    if(!makeSortString.translate_re)
        makeSortString.translate_re = /[éáűőúöüóíÉÁŰPŐÚÖÜÓÍăĂîÎșȘțȚâÂ]/g;
    
    var translate = {
        "é": "e", "á": "a", "ű": "u", "ő": "o", "ú": "u", "ö": "o", "ü": "u", "ó": "o", "í": "i", 'ă' : 'a', 'î' : 'i', 'ș' : 's', 'ț' : 't', 'â' : 'a',
        "É": "E", "Á": "A", "Ű": "U", "Ő": "O", "Ú": "U", "Ö": "O", "Ü": "U", "Ó": "O", "Í": "I", 'Ă' : 'A', 'Î' : 'I', 'Ș' : 'S', 'Ț' : 'T', 'Â' : 'A'
    };    
    
    return (s.replace(makeSortString.translate_re, function(match) { 
        return translate[match]})
    );
}

function getAjaxLoader()
{
    return '<div class="ajax_loader"></div>'
}