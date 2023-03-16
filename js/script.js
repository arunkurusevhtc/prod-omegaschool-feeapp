$(document).ready(function() {
    var current_page_URL = location.href;
    $("#top_menu a").each(function() {
        if ($(this).attr("href") !== "#") {
            var target_URL = $(this).prop("href");
            if (target_URL == current_page_URL) {
                $('nav a').parents('li, ul').removeClass('active');
                $(this).parent('li').addClass('active');
                return !1
            }
        }
    });
    if ($('#manualentry').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
            minDate: 0
        })
    }
    if ($('.passchk').length > 0) {
        var myInput = document.getElementById("password");
        var letter = document.getElementById("letter");
        var number = document.getElementById("number");
        var length = document.getElementById("length");
        document.getElementById("password_confirmation").disabled = !0;
        myInput.onfocus = function() {
            document.getElementById("message").style.display = "block";
            document.getElementById("password_confirmation").disabled = !0;
            document.getElementById("ok").disabled = !0
        }
        myInput.onkeyup = function() {
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid")
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid")
            }
            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid")
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid")
            }
            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid")
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid")
            }
            if (myInput.value.length > 7) {
                length.classList.remove("invalid");
                length.classList.add("valid")
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid")
            }
            if ((myInput.value.length > 7) && (myInput.value.match(numbers)) && (myInput.value.match(upperCaseLetters)) && (myInput.value.match(lowerCaseLetters))) {
                correct.classList.remove("invalid");
                correct.classList.add("valid");
                myInput.onblur = function() {
                    document.getElementById("message").style.display = "none";
                    document.getElementById("password_confirmation").disabled = !1;
                    $('#password_confirmation').focus()
                }
            } else {
                correct.classList.remove("valid");
                correct.classList.add("invalid");
                myInput.onblur = function() {
                    document.getElementById("message").style.display = "block";
                    document.getElementById("password_confirmation").disabled = !0;
                    document.getElementById("ok").disabled = !0
                }
            }
        }
    }
    $('#user_registered input').on('keyup', function() {
        if ($("#password_confirmation").val() != '') {
            validatePassword();
            $("#error").text(validatePassword())
        }
    });
    $("#addMyStudent").on('click', function() {
        $('#addstudent').submit()
    });
    if ($(".studData").length > 0) {
        getStudentData();
        $('#instuctionsmodal').modal('show');
        $.ajax({
            url: 'sql_actions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'checkStudentInfo'
            },
            success: function(response) {
                console.log(response);
                if (response != 1) {
                    $('#myModal').modal('show')
                }
            }
        })
    }
    if ($(".nonfeestudData").length > 0) {
        getNonFeeStudentData()
    }
    if ($(".studentdata").length > 0) {
        fetchstudentdata()
    }
    $('#phone').on('keyup', function() {
        phone();
        $("#phoneerror").text(phone())
    });
    $('#mobile').on('keyup', function() {
        check();
        $("#moberror").text(check())
    });
    $('#mobile').on('keyup', function() {
        $mobile = $(this).val();
        $("#mobileok").val($mobile);
        formatPhone()
    });
    $('#signupok').on('click', function() {
        if (("#phoneerror" != '') && ("#moberror" != '') && ("#error" != '')) {
            return !1
        } else {
            return !0
        }
    });
    $(".closed").click(function() {
        $("#addstudent")[0].reset()
    });
    comments();
    $(document).on("click", ".paymodal", function() {
        var studId = $(this).data('id');
        var id = this.id;
        var feegroup = $(this).data('feegroup');
        $.ajax({
            url: 'sql_actions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getChallanData',
                'studId': studId,
                'cid': id,
                'feegroup': feegroup
            },
            success: function(response) {
                console.log(response);
                $("#challanData").html('');
                var html = '<input type="hidden" name="studId" id="studId" value="' + studId + '"><input type="hidden" name="sem" id="sem" value="' + response.term + '"><input type="hidden" name="class" id="class" value="' + response.clid + '"><input class="grand_tot" type="hidden" name="grand_tot"/><input type="hidden" name="challanNo" id="challanNo" value="' + response.challanNo + '"><input type="hidden" name="stream"  value="' + response.stream + '"/><input type="hidden" name="acayear"  value="' + response.academic_yr + '"/><input type="hidden" name="sfsextrautilities" id="sfsutilitiesinput" /><input type="hidden" name="schoolextrautilities" id="schoolutilitiesinput" /><input type="hidden" name="sfsextrautilitiesamount" id="sfsutilitiesinputamount"/><input type="hidden" name="schoolextrautilitiesamount" id="schoolutilitiesinputamount"/><input type="hidden" name="sfsextrautilitiesqty" id="sfsutilitiesinputqty"/><input type="hidden" name="schoolextrautilitiesqty" id="schoolutilitiesinputqty"/><table class="table table-striped"><tr><td colspan="2"><label> School Name: </label> LMOIS - ' + response.steamname + '</td></tr><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Academic Year: </label> ' + response.academicYear + '( Semester - ' + response.term + ' )</td></tr><tr><td><label>ID: </label> ' + response.studentId + '</td><td><label>Class: </label> ' + response.class_list + '</td></tr><tr><td colspan="2">';
                html += '<table class="table table-striped"><tr class="innerborder1"><td colspan="2"><label>Challan Number: </label> ' + response.challanNo + '</td><td><label>Due Date: </label> ' + response.duedate + '</td></tr>';
                var amount = 0;
                $.each(response.feeData, function(i, row) {
                    html += '<tr><input type="hidden" name="paygroup[]" value="' + i + '" /><td colspan="2"><label>' + i + '</label></td><td></td></tr>';
                    var pamt = 0;
                    var wpamt = 0;
                    $.each(row, function(index, el) {
                        if (index == 'waived' && el != 0) {
                            console.log(el.length);
                            html += '<tr><td colspan="2"><b>Waiver</b> - ' + el[0].waiver_type + '</td><td class="text-right">' + el[0].waiver_total + '</td></tr>';
                            wpamt = parseInt(el[0].waiver_total)
                        } else {
                            if ($.trim(el[0]) != 0) {
                                html += '<tr><td colspan="2">' + el[1] + '</td><td class="text-right">' + el[0] + '</td></tr>';
                                pamt += parseInt(el[0])
                            }
                        }
                    });
                    amount += pamt;
                    amount -= wpamt;
                    var string1 = "SFS";
                    if (($.trim(i)).toLowerCase().indexOf(string1.toLowerCase()) != -1) {
                        html += '<tr><td>If you need any other SFS Utilities please select Here :</td><td><select id="sfsutilites" class="form-control"><option value="">--SELECT--</option>';
                        $.each(response.sfsutilityotherfees, function(index, val) {
                            html += '<option value="' + index + '">' + val + '</option>'
                        });
                        html += '</select></td><td><select id = "sfsextraqty" class="form-control"><option value="">--SELECT--</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td></tr>';
                        html += '</td></tr><tr><td colspan="3"><table id="sfsutilitestbl" class="table table-striped"> <tbody> </tbody></table></td></tr>'
                    }
                    var string2 = "SCHOOL UTILITY";
                    if (($.trim(i)).toLowerCase().indexOf(string2.toLowerCase()) != -1) {
                        html += '<tr><td>If you need any other School Utilities please select Here :</td><td colspan="2"><select id="schoolutilites" class="form-control"><option value="">--SELECT--</option>';
                        $.each(response.schoolotherFees, function(index, val) {
                            html += '<option value="' + index + '">' + val + '</option>'
                        });
                        html += '</select></td>';
                        html += '</td></tr><tr><td colspan="3"><table id="schoolutilitestbl" class="table table-striped"> <tbody> </tbody></table></td></tr>'
                    }
                    html += '<tr class="innerborder2"><td colspan="2"><input type="hidden" id="grouptotal" name="grouptotal" value="' + (pamt - wpamt) + '" /><strong>Total: </strong></td><td class="text-right"><span id="grouptot">' + (pamt - wpamt) + '</span></td></tr>';
                    html += '<tr><input type="hidden" name="paygroup_amt[]" id="paygroup_amt" value="' + (pamt - wpamt) + '" /></tr>'
                });
                var feegroups = [];
                $.each(response.feeData, function(key, value) {
                    feegroups.push($.trim(key))
                });
                if (jQuery.inArray("SFS UTILITIES FEE", feegroups) == -1) {
                    html += '<tr><input type="hidden" name="paygroup[]" value="SFS UTILITIES FEE" /><td colspan="2"><label>SFS UTILITIES FEE</label></td><td></td></tr>';
                    html += '<tr><td>If you need any other SFS Utilities please select Here :</td><td><select id="sfsutilites" class="form-control"><option value="">--SELECT--</option>';
                    $.each(response.sfsutilityotherfees, function(index, val) {
                        html += '<option value="' + index + '">' + val + '</option>'
                    });
                    html += '</select></td><td><select id = "sfsextraqty" class="form-control"><option value="">--SELECT--</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td></tr>';
                    html += '</td></tr><tr><td colspan="3"><table id="sfsutilitestbl" class="table table-striped"> <tbody> </tbody></table></td></tr>';
                    html += '<tr class="innerborder2"><td colspan="2"><input type="hidden" id="grouptotal" name="grouptotal" value="0" /><strong>Total: </strong></td><td class="text-right"><span id="grouptot">0</span></td></tr>';
                    html += '<tr><input type="hidden" name="paygroup_amt[]" id="paygroup_amt" value="0" /></tr>'
                }
                if (jQuery.inArray("SCHOOL UTILITY FEE", feegroups) == -1) {
                    html += '<tr><input type="hidden" name="paygroup[]" value="SCHOOL UTILITY FEE" /><td colspan="2"><label>SCHOOL UTILITY FEE</label></td><td></td></tr>';
                    html += '<tr><td>If you need any other School Utilities please select Here :</td><td><select id="schoolutilites" class="form-control"><option value="">--SELECT--</option>';
                    $.each(response.schoolotherFees, function(index, val) {
                        html += '<option value="' + index + '">' + val + '</option>'
                    });
                    html += '</td></tr><tr><td colspan="3"><table id="schoolutilitestbl" class="table table-striped"> <tbody> </tbody></table></td></tr>';
                    html += '<tr class="innerborder2"><td colspan="2"><input type="hidden" id="grouptotal" name="grouptotal" value="0" /><strong>Total: </strong></td><td class="text-right"><span id="grouptot">0</span></td></tr>';
                    html += '<tr><input type="hidden" name="paygroup_amt[]" id="paygroup_amt" value="0" /></tr>'
                }
                html += '<input type="hidden" name="tot" id="tot" value="' + amount + '" /> ';
                $(".grand_tot").val(amount);
                html += '<tr><td></td><td colspan ="2"><p class="tot" >Grand Total: <span id="grand_tot">' + amount + '</span></p></td></tr></table><tr><td><strong>Remarks: </strong></td><td>' + response.remarks + '</td></tr><tr><td><strong> Payment Mode</strong></td><td><input type="radio" class="payment_mode" name="payment_mode" value="online" checked>Online</input></td></tr></table>';
                $("#challanData").append(html)
            }
        })
    });
    $(document).on('click', '.payment_mode', function() {
        $("#manualentry").hide();
        if (this.value == 'manual') {
            $("#manualentry").show()
        }
    });
    $(document).on('change', '.transfee', function() {
        var selected = $(this).find("option:selected").val();
        if ($("#transId").val() != '') {
            var grand_tot = parseInt($("#tot").val()) - parseInt($("#transId").val());
            $("#tot").val(grand_tot);
            $("#grand_tot").html(grand_tot);
            $(".grand_tot").val(grand_tot)
        }
        if (selected != '') {
            var grand_tot = parseInt($("#tot").val()) + parseInt(selected);
            $("#tot").val(grand_tot);
            $("#grand_tot").html(grand_tot);
            $(".grand_tot").val(grand_tot);
            $("#transId").val(selected)
        } else {
            $("#transId").val('')
        }
    });
    $(document).on('change', '#sfsutilites', function() {
        var selected = $(this).find("option:selected").val();
        var data = $("#studDataModal").serializeArray();
        var selectedtype = $(this).find("option:selected").text();
        var str1 = "TRANSPORT";
        if (selectedtype.toLowerCase().indexOf(str1.toLowerCase()) != -1) {
            $("#sfsextraqty").hide()
        } else {
            $("#sfsextraqty").show();
            $("#sfsextraqty").focus()
        }
    });
    $(document).on('change', '#sfsextraqty', function() {
        var selected = $("#sfsutilites").find("option:selected").val();
        var sfsqty = $("#sfsextraqty").find("option:selected").val();
        var selectedtype = $("#sfsutilites").find("option:selected").text();
        var data = $("#studDataModal").serializeArray();
        $('#sfsutilites').prop('selectedIndex', 0);
        $.ajax({
            url: 'sql_actions.php',
            type: 'POST',
            data: {
                'submit': 'getOtherFeeData',
                'id': selected,
                'data': data,
                'feegroup': 'SFS',
                'qty': sfsqty
            },
            dataType: 'json',
            success: function(response) {
                if (response != null && response != 1) {
                    var finalamount = response.amount * sfsqty;
                    var tormve = $('table#sfsutilitestbl tr.' + selected).find('td:eq(1)').text();
                    if (tormve != '') {
                        rmveSfsUtility(tormve, selected, sfsqty)
                    }
                    var html = '<tr class="' + response.id + '"><td>' + response.feeType + '</td><td>' + finalamount + '</td><td><i title="remove" class="fa fa-close" onclick="rmveSfsUtility(' + finalamount + ',' + response.id + ', ' + sfsqty + ')" ></i></td></tr>';
                    $("#sfsutilitestbl tbody").append(html);
                    var extra_tot = 0;
                    $("#sfsutilitestbl").find('tr').each(function(i, el) {
                        var $tds = $(this).find('td');
                        extra_tot = extra_tot + parseInt($tds.eq(1).text(), 10)
                    });
                    var group_total = $("#sfsutilitestbl").closest('tr').next('tr').find('#grouptotal').val();
                    var grand_tot = parseInt($("#tot").val()) + parseInt(finalamount);
                    var total = parseInt(extra_tot) + parseInt(group_total);
                    $("#tot").val(grand_tot);
                    $("#grand_tot").html(grand_tot);
                    $(".grand_tot").val(grand_tot);
                    $("#sfsutilitestbl").closest('tr').next('tr').find('#grouptot').html(total);
                    $("#sfsutilitestbl").closest('tr').next('tr').next('tr').find('#paygroup_amt').val(total);
                    $('#sfsutilitiesinput').val(function(i, val) {
                        return val + (!val ? '' : ', ') + response.id
                    })
                    $('#sfsutilitiesinputamount').val(function(i, val) {
                        return val + (!val ? '' : ', ') + finalamount
                    });
                    $('#sfsutilitiesinputqty').val(function(i, val) {
                        return val + (!val ? '' : ', ') + sfsqty
                    });
                    $('#sfsextraqty').prop('selectedIndex', 0)
                } else if (response != null && response == 1) {
                    var html = '<tr><td colspan="2">' + selectedtype + ' Fee Type is already added. Please contact the Admin.</td><td></td><td></td></tr>';
                    $("#sfsutilitestbl tbody").append(html)
                } else {
                    var html = '<tr><td>' + selectedtype + ' Fee Type is not configured. Please contact the Admin.</td><td></td><td></td></tr>';
                    $("#sfsutilitestbl tbody").append(html)
                }
            }
        })
    });
    $("#manualentry").hide();
    $('.collapse').collapse();
    $(document).on('change', '#schoolutilites', function() {
        console.log($(this).find("option:selected").val());
        var selected = $(this).find("option:selected").val();
        var data = $("#studDataModal").serializeArray();
        var selectedtype = $(this).find("option:selected").text();
        $.ajax({
            url: 'sql_actions.php',
            type: 'POST',
            data: {
                'submit': 'getOtherFeeData',
                'id': selected,
                'data': data,
                'feegroup': 'UTI',
                'qty': '1'
            },
            dataType: 'json',
            success: function(response) {
                console.log($("#tot").val());
                console.log(response);
                if (response != null && response != 1) {
                    var html = '<tr class="' + response.id + '"><td>' + response.feeType + '</td><td>' + response.amount + '</td><td><i title="remove" class="fa fa-close" onclick="rmveSchoolUtility(' + response.amount + ',' + response.id + ')" ></i></td></tr>';
                    var grand_tot = parseInt($("#tot").val()) + parseInt(response.amount);
                    var total = parseInt($("#schoolutilitestbl").closest('tr').next('tr').find('#grouptotal').val()) + parseInt(response.amount);
                    $("#tot").val(grand_tot);
                    $("#grand_tot").html(grand_tot);
                    $(".grand_tot").val(grand_tot);
                    $("#schoolutilitestbl").closest('tr').next('tr').find('#grouptotal').val(total);
                    $("#schoolutilitestbl").closest('tr').next('tr').find('#grouptot').html(total);
                    $("#schoolutilitestbl").closest('tr').next('tr').next('tr').find('#paygroup_amt').val(total);
                    $("#schoolutilitestbl tbody").append(html);
                    $('#schoolutilitiesinput').val(function(i, val) {
                        return val + (!val ? '' : ', ') + response.id
                    })
                    $('#schoolutilitiesinputamount').val(function(i, val) {
                        return val + (!val ? '' : ', ') + response.amount
                    })
                } else if (response != null && response == 1) {
                    var html = '<tr><td colspan="2">' + selectedtype + ' Fee Type is already added. Please contact the Admin.</td><td></td><td></td></tr>';
                    $("#schoolutilitestbl tbody").append(html)
                } else {
                    var html = '<tr><td colspan="2">' + selectedtype + ' Fee Type is not configured. Please contact the Admin.</td><td></td><td></td></tr>';
                    $("#schoolutilitestbl tbody").append(html)
                }
            }
        })
    });
    $("#manualentry").hide();
    $('.collapse').collapse();
    $("#otpfrm").hide();
    $("#updateparentdata").submit(function() {
        event.preventDefault();
        var email = $("#pemail").val();
        var mnum = $("#pmnumber").val();
        $.ajax({
            url: 'sql_actions.php',
            type: 'POST',
            data: {
                'submit': 'updateparentdata',
                'email': email,
                'mnum': mnum
            },
            dataType: 'json',
            success: function(response) {
                if (response != 0) {
                    $("#updateparentdata").hide();
                    $("#otpfrm").show();
                    $(".msg").html('<input type="hidden" name="id" value="' + response + '" /><div class="success-msg">You will receive an SMS with OTP in a few minutes.</div>')
                }
            }
        })
    });
    $(".stddetailscheck").hide();
    $(document).on('click', '.addMyStudent', function() {
        var stdid = $(".stdid").val();
        $.ajax({
            type: 'post',
            url: 'sql_actions.php',
            dataType: 'json',
            data: {
                'submit': 'getstudentdetails',
                'stdid': stdid
            },
            success: function(response) {
                if (response != 'null') {
                    var studname = response.studentName;
                    var studclass = response.class + ' - ' + response.section;
                    var studsection = response.section;
                    $(".stddetailscheck").show();
                    $(".stdname").html(studname);
                    $(".stdclass").html(studclass);
                    $(".addMyStudent").html('Add Student');
                    $(".addMyStudent").removeClass('addMyStudent').addClass('addMyChild');
                    $("#studentdetailschecked").attr('action', 'sql_actions.php');
                    $(".addMyChild").prop("type", "submit")
                } else {
                    $("#student_number").val('');
                    alert("Please enter valid studentId.")
                }
            }
        })
    })
    $('#myModal').on('hidden.bs.modal', function() {
        $("#studentdetailschecked")[0].reset();
        $(".addMyChild").html('Check');
        $(".addMyChild").prop("type", "button");
        $(".addMyChild").addClass('addMyStudent').removeClass('addMyChild');
        $(".stddetailscheck").hide()
    });
    $("#topupamtchk").on("change", function() {
        if (this.value == 'other') {
            $("#topupamtdiv").show();
            $('#topupamt').attr('name', 'topupamt');
            $('#topupamt').attr('required', 'required')
        } else {
            $("#topupamtdiv").hide();
            $('#topupamt').removeAttr('name');
            $('#topupamt').removeAttr('required')
        }
    });
    $(document).on("click", ".view_topup", function() {
        var studId = $("#studId").val();
        var amt = $("input[name=topupamt]").val();
        if (typeof amt === "undefined") {
            var amt = $('select[name="topupamt"]').val()
        }
        if (studId == '') {
            alert("Please enter Student ID.");
            $("#studId").focus();
            return !1
        } else if (amt == '') {
            alert("Please enter Amount.");
            $("#topupamtchk").focus();
            return !1
        }
        $.ajax({
            url: 'sql_actions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getNonFeeData',
                'studId': studId
            },
            success: function(response) {
                console.log(response);
                $("#nonfeechallanData").html('');
                $("#confirmpay").html('');
                if (response == 'no_records') {
                    $("#nonfeechallanData").html("<div class='error-msg'>Please Enter valid Student ID.</div>")
                } else {
                    var html = '<input type="hidden" name="studId" id="studId" value="' + studId + '"><input type="hidden" name="sem" id="sem" value="' + response.term + '"><input type="hidden" name="class" id="class" value="' + response.class + '"><input class="grand_tot" type="hidden" name="grand_tot"/><input type="hidden" name="stream"  value="' + response.stream + '"/><table class="table table-striped"><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Semester: </label> ' + response.term + '</td></tr><tr><td><label>ID: </label> ' + response.studentId + '</td><td><label>Class: </label> ' + response.class_list + ' - ' + response.section + '</td></tr>';
                    html += '<tr><input type="hidden" name="paygroup" value="NON-FEE GROUP" /><td><label>NON-FEE GROUP</label></td><td></td></tr>';
                    html += '<tr><td>Card Top-up</td><td class="text-right">' + amt + '</td></tr>';
                    html += '<input type="hidden" name="tot" id="tot" value="' + amt + '" /> ';
                    $(".grand_tot").val(amt);
                    html += '<tr><td></td><td><p class="tot" >Grand Total: <span id="grand_tot">' + amt + '</span></p></td></tr><tr><td><strong> Payment Mode</strong></td><td><input type="radio" class="payment_mode" name="payment_mode" value="online" checked>Online</input></td></tr></table>';
                    $("#nonfeechallanData").append(html);
                    $("#confirmpay").append('<button type="submit" name="pay_topup" value="pay" class="btn btn-primary" >Confirm Payment</button>')
                }
                $("#paynfModal").modal("show")
            }
        })
    });
    $(document).on("click", ".nonpayModal", function() {
        var studId = $(this).data('id');
        var id = this.id;
        $.ajax({
            url: 'sql_actions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getNonFeeChallanData',
                'studId': studId,
                'cid': id,
            },
            success: function(response) {
                console.log(response);
                $("#nonfeechallanData").html('');
                var html = '<input type="hidden" name="studId" id="studId" value="' + studId + '"><input type="hidden" name="sem" id="sem" value="' + response.term + '"><input type="hidden" name="class" id="class" value="' + response.clid + '"><input class="grand_tot" type="hidden" name="grand_tot"/><input type="hidden" name="challanNo" id="challanNo" value="' + response.challanNo + '"><input type="hidden" name="stream"  value="' + response.stream + '"/><input type="hidden" name="sfsextrautilities" id="sfsutilitiesinput" /><input type="hidden" name="schoolextrautilities" id="schoolutilitiesinput" /><input type="hidden" name="sfsextrautilitiesamount" id="sfsutilitiesinputamount"/><input type="hidden" name="schoolextrautilitiesamount" id="schoolutilitiesinputamount"/><input type="hidden" name="sfsextrautilitiesqty" id="sfsutilitiesinputqty"/><input type="hidden" name="schoolextrautilitiesqty" id="schoolutilitiesinputqty"/><table class="table table-striped"><tr><td colspan="2"><label> School Name: </label> LMOIS - ' + response.steamname + '</td></tr><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Semester: </label> ' + response.term + '</td></tr><tr><td><label>ID: </label> ' + response.studentId + '</td><td><label>Class: </label> ' + response.class_list + '</td></tr><tr><td colspan="2">';
                html += '<table class="table table-striped"><tr class="innerborder1"><td colspan="2"><label>Challan Number: </label> ' + response.challanNo + '</td><td><label>Due Date: </label> ' + response.duedate + '</td></tr>';
                var amount = 0;
                $.each(response.feeData, function(i, row) {
                    html += '<tr><input type="hidden" name="paygroup[]" value="' + i + '" /><td colspan="2"><label>' + i + '</label></td><td></td></tr>';
                    var pamt = 0;
                    $.each(row, function(index, el) {
                        $.each(el, function(am, val) {
                            if ($.trim(val[0]) != 0) {
                                html += '<tr><td colspan="2">' + val[1] + '</td><td>' + val[0] + '</td></tr>';
                                pamt += parseInt(val[0])
                            }
                        });
                        $.each(response.waivedData, function(j, rowj) {
                            if ($.trim(i) == $.trim(j) && rowj[0] != 0) {
                                html += '<tr><td><strong>Waived: </strong></td><td> -' + rowj[0] + '</td></tr>';
                                pamt = pamt - rowj[0]
                            }
                        })
                    });
                    amount += pamt;
                    html += '<tr class="innerborder2"><td colspan="2"><input type="hidden" id="grouptotal" name="grouptotal" value="' + pamt + '" /><strong>Total: </strong></td><td><span id="grouptot">' + pamt + '</span></td></tr>';
                    html += '<tr><input type="hidden" name="paygroup_amt[]" id="paygroup_amt" value="' + pamt + '" /></tr>'
                });
                html += '<input type="hidden" name="tot" id="tot" value="' + amount + '" /> ';
                $(".grand_tot").val(amount);
                html += '<tr><td></td><td colspan ="2"><p class="tot" >Grand Total: <span id="grand_tot">' + amount + '</span></p></td></tr></table><tr><td><strong>Remarks: </strong></td><td>' + response.remarks + '</td></tr><tr><td><strong> Payment Mode</strong></td><td><input type="radio" class="payment_mode" name="payment_mode" value="online" checked>Online</input></td></tr></table>';
                $("#nonfeechallanData").append(html)
            }
        })
    });
    $("#paynfbtn").on("click", function() {
        var tbl_id = $("#paynfbtn").attr('data-id');
        var studentId = $("#paynfbtn").attr('data-sid');
        var amt = $("#paynfbtn").attr('data-amt');
        alert();
        $.ajax({
            url: 'sql_actions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'paynonfee': 'confirm',
                'studId': studentId,
                'cid': tbl_id,
                'amt': amt,
            },
            success: function(response) {
                console.log(response)
            }
        })
    })
    $('.error_stdid').hide();
    $('.nonfeestudid').on('change', function() {
        var stdid = $(this).val();
        // alert(stdid);
        if (stdid.length != 0) {
            $.ajax({
                url: 'sql_actions.php',
                method: 'post',
                dataType: 'json',
                data: {
                    'submit': 'geteventnames',
                    'data': stdid
                },
                success: function(response) {
                    if(response == 0){
                        $('.error_stdid').show();
                        $('.error_stdid').html("<p class='error-msg'>Given Student ID has not been configured with any event. Contact Admin.</p>");
                        $amount = 0;
                        $('#amountofevent').val($amount);
                        $('option:selected', '.eventname').remove();
                        $('.eventdetails').hide();
                    }
                    else{
                        $('.eventdetails').show();
                        $('.error_stdid').hide();
                        $('#studentidfornonfee').val(stdid);
                        console.log(response);
                        var options = '<option value="">-Select Event-</option>';
                        $.each(response, function(i, val) {
                            options += '<option value="' + i + '">' + val + '</option>'
                        });
                        $(".eventname").html(options);
                    }
                }
            })
        } else {
            $(".eventname").html('<option value="">Event Name</option>')
        }
    });

    $('#eventnameid').on('change', function() {
        var eventid = $(this).val();
        var stdid = $('#studentidfornonfee').val();
        if (stdid.length != 0) {
            $.ajax({
                url: 'sql_actions.php',
                method: 'post',
                dataType: 'json',
                data: {
                    'submit': 'geteventamount',
                    'eventid': eventid,
                    'stdid':stdid
                },
                success: function(response) {
                    console.log(response);
                    $('#amountofevent').val(response.amount);
                },  
                error: function(response) {
                    $amount = 0;
                    $('#amountofevent').val($amount);
                }
            })
        }
    });
});

function comments() {
    var pageURL = window.location.pathname.split('/')
    getUiComments()
}

function getUiComments() {
    $.ajax({
        type: 'post',
        url: 'sql_actions.php',
        dataType: 'json',
        data: {
            'submit': 'getComments'
        },
        success: function(response) {
            var pageURL = window.location.pathname.split('/')
            var name = pageURL[2];
            for (var i = 0; i < response.length; i++) {
                if ($.trim(response[i].pageName) == name) {
                    $(".comment").append("<p class='comment'>" + response[i].comments + "</p>");
                    break
                }
            }
        }
    })
}

function getStudentData() {
    $.ajax({
        type: 'post',
        url: 'sql_actions.php',
        dataType: 'json',
        data: {
            'submit': 'getStudentData'
        },
        success: function(response) {
            if (response == '0') {
                $(".opayment").append("<tr><td colspan='8'><center>No Data Available.</center></td></tr>")
            } else {
                $.each(response, function(i, row) {
                    $(".opayment").append("<tr><td>" + row.studentId + "</td><td>" + row.challanNo + "</td><td>" + row.studentName + "</td><td>" + row.class_list + "</td><td>" + row.section + "</td><td>" + row.term + "</td><td>" + row.fee + "</td><td>" + "<button type='button' data-id='" + row.studentId + "' data-feegroup='" + $.trim(row.feeGroup) + "'class='btn btn-info btn-sm paymodal' id='" + row.challanNo + "' data-toggle='modal' data-target='#payModal'>PAY CHALLANS</button>" + "</td></tr>")
                })
            }
        }
    })
}

function getNonFeeStudentData() {
    $.ajax({
        type: 'post',
        url: 'sql_actions.php',
        dataType: 'json',
        data: {
            'submit': 'getNonFeeStudentData'
        },
        success: function(response) {
            console.log(response);
            if (response == '0') {
                $(".ononfeepayment").append("<tr><td colspan='8'><center>No Data Available.</center></td></tr>")
            } else {
                $.each(response, function(i, row) {
                    $(".ononfeepayment").append("<tr><td>" + (i + 1) + "</td><td>" + row.studentName + "</td><td>" + row.class_list + "</td><td>" + row.section + "</td><td>" + row.term + "</td><td>" + row.feename + "</td><td>" + row.fee + "</td><td>" + "<button type='button' data-id='" + row.studentId + "' class='btn btn-info btn-sm nonpayModal' id='" + row.challanNo + "' data-toggle='modal' data-target='#nonpayModal'>PAY CHALLANS</button>" + "</td></tr>")
                })
            }
        }
    })
}

function validatePassword() {
    var err_msg = '';
    if ($("#password").val() != $("#password_confirmation").val()) {
        err_msg = "Passwords Doesn't match";
        document.getElementById("ok").disabled = !0
    } else {
        document.getElementById("ok").disabled = !1
    }
    return err_msg
}

function fetchstudentdata() {
    $.ajax({
        type: 'post',
        url: 'sql_actions.php',
        dataType: 'json',
        data: {
            'submit': 'fetchstudentdata'
        },
        success: function(response) {
            console.log(response);
            if (response == '0') {
                $(".student").append("<tr><td colspan='4'><center>No Child Has Been Mapped So Far</center></td></tr>")
            } else {
                $.each(response, function(i, row) {
                    $(".student").append('<tr class ="warddata"><td>' + row.studentName + '</td><td>' + row.class_list + '</td><td>' + row.section + '</td><td><a href="sql_actions.php?action=unmap&id=' + row.studentId + '" data-toggle="tooltip" title="Unmap child"><i class="fa fa-close"></i></a></td></tr>')
                })
            }
        }
    })
}

function check() {
    var err_msg = '';
    var mobile = document.getElementById('mobile');
    var goodColor = "green";
    var badColor = "red";
    if (mobile.value.length != 10) {
        err_msg = "Please Enter Valid Mobile Number";
        document.getElementById("ok").disabled = !0
    } else {
        document.getElementById("ok").disabled = !1
    }
    return err_msg
}

function phone() {
    var err_msg = '';
    var phone = document.getElementById('phone');
    var goodColor = "green";
    var badColor = "red";
    if (phone.value.length != 10) {
        err_msg = "Please Enter Valid Mobile Number";
        document.getElementById("ok").disabled = !0
    } else {
        document.getElementById("ok").disabled = !1
    }
    return err_msg
}

function rmveSfsUtility(amt, id, qty) {
    var old_tot = $(".grand_tot").val();
    var new_tot = parseInt(old_tot) - parseInt(amt);
    var total = parseInt($("#sfsutilitestbl").closest('tr').next('tr').find('#grouptot').html()) - parseInt(amt);
    var list = $('#sfsutilitiesinput').val();
    var removeValue = removingutilities(list, id);
    var qtylist = $('#sfsutilitiesinputqty').val();
    var qtyremoveValue = removingutilities(qtylist, qty);
    var data = $("#studDataModal").serializeArray();
    var deletedamount = amt;
    $('#sfsutilitiesinput').val(removeValue);
    $('#sfsutilitiesinputqty').val(qtyremoveValue);
    $('table#sfsutilitestbl tr.' + id).remove();
    $("#tot").val(new_tot);
    $("#grand_tot").html(new_tot);
    $(".grand_tot").val(new_tot);
    $("#sfsutilitestbl").closest('tr').next('tr').find('#grouptot').html(total);
    $("#sfsutilitestbl").closest('tr').next('tr').next('tr').find('#paygroup_amt').val(total);
    var sfs_amt = $("#sfsutilitiesinputamount").val();
    console.log(sfs_amt);
    var sfs_tot = parseInt(sfs_amt) - parseInt(amt);
    console.log(sfs_tot);
    $("#sfsutilitiesinputamount").val(sfs_tot)
}

function rmveSchoolUtility(amt, id) {
    var old_tot = $(".grand_tot").val();
    var new_tot = parseInt(old_tot) - parseInt(amt);
    var total = parseInt($("#schoolutilitestbl").closest('tr').next('tr').find('#grouptotal').val()) - parseInt(amt);
    var list = $('#schoolutilitiesinput').val();
    var removeValue = removingutilities(list, id);
    var data = $("#studDataModal").serializeArray();
    var deletedamount = amt;
    $('#schoolutilitiesinput').val(removeValue);
    $('table#schoolutilitestbl tr.' + id).remove();
    $("#tot").val(new_tot);
    $("#grand_tot").html(new_tot);
    $(".grand_tot").val(new_tot);
    $("#schoolutilitestbl").closest('tr').next('tr').next('tr').find('#paygroup_amt').val(total);
    $("#schoolutilitestbl").closest('tr').next('tr').find('#grouptotal').val(total);
    $("#schoolutilitestbl").closest('tr').next('tr').find('#grouptot').html(total);
    var school_amt = $("#schoolutilitiesinputamount").val();
    console.log(school_amt);
    var school_tot = parseInt(school_amt) - parseInt(amt);
    console.log(school_tot);
    $("#schoolutilitiesinputamount").val(school_tot)
}

function removingutilities(list, value, separator) {
    separator = separator || ",";
    var values = list.split(separator);
    for (var i = 0; i < values.length; i++) {
        if (values[i] == value) {
            values.splice(i, 1);
            return values.join(separator)
        }
    }
    return list
}

function formatPhone(obj) {
    var numbers = obj.value.replace(/\D/g, ''),
        char = {
            0: '**********'
        };
    obj.value = '';
    for (var i = 0; i < numbers.length; i++) {
        obj.value += (char[i] || '')
    }
}