$(document).ready(function() {
    var current_page_URL = location.href;
    $("#top_menu a").each(function() {
        if ($(this).attr("href") !== "#") {
            var target_URL = $(this).prop("href");
            if (target_URL == current_page_URL) {
                $('nav a').parents('li, ul').removeClass('active');
                $(this).parent('li').addClass('active');
                return false;
            }
        }
    });   
    if ($('#manualentry').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        });
    }
    if ($('.passchk').length > 0) {
        var myInput = document.getElementById("password");
        var letter = document.getElementById("letter");
        var number = document.getElementById("number");
        var length = document.getElementById("length");
        document.getElementById("password_confirmation").disabled = true;
        // When the user clicks on the password field, show the message box
        myInput.onclick = function() {
            document.getElementById("message").style.display = "block";
            document.getElementById("password_confirmation").disabled = true;
            document.getElementById("ok").disabled = true;
        }
        // // // When the user starts to type something inside the password field
        myInput.onkeyup = function() {
            // alert("hi");
            // Validate lowercase letters
            var lowerCaseLetters = /[a-z]/g;
            if (myInput.value.match(lowerCaseLetters)) {
                letter.classList.remove("invalid");
                letter.classList.add("valid");
            } else {
                letter.classList.remove("valid");
                letter.classList.add("invalid");
            }
            // Validate capital letters
            var upperCaseLetters = /[A-Z]/g;
            if (myInput.value.match(upperCaseLetters)) {
                capital.classList.remove("invalid");
                capital.classList.add("valid");
            } else {
                capital.classList.remove("valid");
                capital.classList.add("invalid");
            }
            // Validate numbers
            var numbers = /[0-9]/g;
            if (myInput.value.match(numbers)) {
                number.classList.remove("invalid");
                number.classList.add("valid");
            } else {
                number.classList.remove("valid");
                number.classList.add("invalid");
            }
            // Validate length
            if (myInput.value.length > 7) {
                length.classList.remove("invalid");
                length.classList.add("valid");
            } else {
                length.classList.remove("valid");
                length.classList.add("invalid");
            }
            if ((myInput.value.length > 7) && (myInput.value.match(numbers)) && (myInput.value.match(upperCaseLetters)) && (myInput.value.match(lowerCaseLetters))) {
                correct.classList.remove("invalid");
                correct.classList.add("valid");
                myInput.onblur = function() {
                    document.getElementById("message").style.display = "none";
                    document.getElementById("password_confirmation").disabled = false;
                }
            } else {
                correct.classList.remove("valid");
                correct.classList.add("invalid");
                myInput.onblur = function() {
                    document.getElementById("message").style.display = "block";
                    document.getElementById("password_confirmation").disabled = true;
                    document.getElementById("ok").disabled = true;
                }
            }
        }
    }
    $('#user_registered input').on('keyup', function() {
        if ($("#password_confirmation").val() != '') {
            validatePassword();
            $("#error").text(validatePassword());
        }
    });
    $("#addMyStudent").on('click', function() {
        $('#addstudent').submit();
    });
    if ($(".studData").length > 0) {
        getStudentData();
    }
    if ($(".studentdata").length > 0) {
        fetchstudentdata();
    }
    
    $('#phone').on('keyup', function() {
        phone();
        $("#phoneerror").text(phone());
    });
    $('#mobile').on('keyup', function() {
        check();
        $("#moberror").text(check());
    });
    $('#mobile').on('keyup', function() {
       $mobile = $(this).val();
        // alert($mobile);
        // $('#mobileok')
        $("#mobileok").val($mobile);
        formatPhone();
    });
    $('#signupok').on('click', function() {
        // alert("hi");
        if (("#phoneerror" != '') && ("#moberror" != '') && ("#error" != '')) {
            return false;
        } else {
            return true;
        }
    });
    $(".closed").click(function(){
        $("#addstudent")[0].reset();
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
                'feegroup':feegroup
            },
                success: function(response) {
                console.log(response);
                // return false;
                if($.trim(response.feeGroup) != 'LATE FEE'){
                $("#challanData").html('');
                var html = '<input type="hidden" name="studId" id="studId" value="' + studId + '"><input type="hidden" name="sem" id="sem" value="' + response.term + '"><input type="hidden" name="class" id="class" value="' + response.clid + '"><input class="grand_tot" type="hidden" name="grand_tot"/><input type="hidden" name="challanNo" id="challanNo" value="' + response.challanNo + '"><input type="hidden" name="extrautilities" id="utilitiesinput" /><table class="table table-striped"><tr><td colspan="2"><label> School Name: </label> LMOIS - CBSE</td></tr><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Semeste: </label> ' + response.term + '</td></tr><tr><td><label>ID: </label> ' + response.studentId + '</td><td><label>CLASS: </label> ' + response.class_list + '</td></tr><tr><td colspan="2">';
                // return false;
                // $.each(response, function(i, row) {
                html += '<table class="table table-striped"><tr><td><label>Challan Number:</label> ' + response.challanNo + '</td><td><label>Due Date:</label> ' + response.duedate + '</td></tr>';
                var amount = 0;
                $.each(response.feeData, function(i, row) {
                    html += '<tr><td><label>' + i + '</label></td><td></td></tr><input type="hidden" name="paygroup" value="'+$.trim(i)+'" />';
                    $.each(row, function(index, el) {
                        html += '<tr><td>' + el[1] + '</td><td>' + el[0] + '</td></tr>';
                        amount += parseInt(el[0]);
                    });
                });
                // html += '<tr><td>Waived Percentage</td><td>' + response.waivedPercentage +'%</td></tr>';
                // html += '<tr><td>Waived Amount</td><td>' + response.waivedAmount + '</td></tr>';
                html += '<tr><td>Waived</td><td>' + response.waivedTotal+ '</td></tr>';
                if (response.waivedTotal != '') {
                    // html += '<tr><td>LateFee</td><td>' + response.latefee + '</td></tr>';
                    amount -= parseInt(response.waivedTotal);
                }
                // if (response.latefee > 0) {
                //     html += '<tr><td>LateFee</td><td>' + response.latefee + '</td></tr>';
                //     amount += parseInt(response.latefee);
                // }
                $(".grand_tot").val(amount);
                html += '<tr><td><strong>Total</strong></td><td><input type="hidden" name="total" id="tot" value="' + amount + '" /> ' + amount + '</td></tr></table>';
                if($.trim(response.feeGroup) == 'SCHOOL UTILITY FEE'){
                html += '<tr><td>If you need any other utilites please select Here :</td><td><select id="utilites" class="form-control"><option value="">--SELECT--</option>';
                $.each(response.otherFees, function(index, val) {
                    html += '<option value="' + index + '">' + val + '</option>';
                });
                html += '</select></td></tr>';
                html += '</td></tr><tr><td colspan="2"><table id="utilitestbl" class="table table-striped"> <tbody> </tbody></table></td></tr>';
                }
                // html += '<tr><td>Transport Fee</td><td><input type="hidden" id="transId" /><select class="form-control transfee"><option id="0" value="">--SELECT--</option>';
                // $.each(response.transportData, function(i, row) {
                //     html += '<option id="' + row.id + '" value="' + row.amount + '"">' + row.stage + '[' + row.pickUp + '-' + row.dropDown + ']</option>';
                // });
                // html += '</select></td><tr>';
                html += '<tr><td></td><td><p class="tot" >Grand Total : <span id="grand_tot">' + amount + '</span></p></td></tr><tr><td> Payment Mode</td><td><input type="radio" class="payment_mode" name="payment_mode" value="online" checked>Online</input>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="payment_mode" name="payment_mode" value="manual">Cheque_DD</input></td></tr></table>';
                }
                else{
                     $("#challanData").html('');
                var html = '<input type="hidden" name="paygroup" value="LATE FEE"><input type="hidden" name="studId" id="studId" value="' + studId + '"><input type="hidden" name="sem" id="sem" value="' + response.term + '"><input type="hidden" name="class" id="class" value="' + response.clid + '"><input class="grand_tot" type="hidden" name="grand_tot"/><input type="hidden" name="challanNo" id="challanNo" value="' + response.challanNo + '"><input type="hidden" name="extrautilities" id="utilitiesinput" /><table class="table table-striped"><tr><td colspan="2"><label> School Name : </label> LMOIS - CBSE</td></tr><tr><td><label>Name : </label> ' + response.studentName + '</td><td><label>Semester : </label> ' + response.term + '</td></tr><tr><td><label>ID : </label> ' + response.studentId + '</td><td><label>CLASS : </label> ' + response.class_list + '</td></tr><tr><td colspan="2">';
                // return false;
                // $.each(response, function(i, row) {
                html += '<table class="table table-striped"><tr><td><label>Challan Number : </label>' + response.challanNo + '</td><td><label>Due Date : </label>' + response.duedate + '</td></tr>';
                var amount = 0;
                $(".grand_tot").val(amount);
                html += '<tr><td><strong>' + response.feeGroup + '</strong></td><td><input type="hidden" name="total" id="tot" value="' + response.latefee + '" /> ' + response.latefee + '</td></tr></table>';
                html += '<tr><td></td><td><p class="tot" >Grand Total : <span id="grand_tot">' + response.latefee + '</span></p></td></tr><tr><td> Payment Mode</td><td><input type="radio" class="payment_mode" name="payment_mode" value="online" checked>Online</input>&nbsp;&nbsp;&nbsp;&nbsp;<input type="radio" class="payment_mode" name="payment_mode" value="manual">Manual</input></td></tr></table>';
                }
                $("#challanData").append(html);
                // })
            }
        });
    });
    $(document).on('click', '.payment_mode', function() {
        $("#manualentry").hide();
        if (this.value == 'manual') {
            $("#manualentry").show();
        }
    });
    $(document).on('change', '.transfee', function() {
        var selected = $(this).find("option:selected").val();
        // selected = selected == ''? 0 :selected;
        // alert(selected);
        if ($("#transId").val() != '') {
            var grand_tot = parseInt($("#tot").val()) - parseInt($("#transId").val());
            $("#tot").val(grand_tot);
            $("#grand_tot").html(grand_tot);
            $(".grand_tot").val(grand_tot);
        }
        if (selected != '') {
            var grand_tot = parseInt($("#tot").val()) + parseInt(selected);
            $("#tot").val(grand_tot);
            $("#grand_tot").html(grand_tot);
            $(".grand_tot").val(grand_tot);
            $("#transId").val(selected);
        } else {
            $("#transId").val('');
        }
    });
    $(document).on('change', '#utilites', function() {
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
                'data': data
            },
            dataType: 'json',
            success: function(response) {
                console.log($("#tot").val());
                if (response != null) {
                    var html = '<tr id="' + response.id + '"><td>' + response.feeType + '</td><td>' + response.amount + '</td><td><i title="remove" class="fa fa-close" onclick="rmveUtility(' + response.amount + ',' + response.id + ')" ></i></td></tr>';
                    var grand_tot = parseInt($("#tot").val()) + parseInt(response.amount);
                    var total = parseInt($(".total").val()) + parseInt(response.amount);
                    $("#tot").val(grand_tot);
                    $("#grand_tot").html(grand_tot);
                    $(".grand_tot").val(grand_tot);
                    $("#grand_tot").html(grand_tot);
                    $(".grand_tot").val(grand_tot);
                    $("#total").html(total);
                    $(".total").val(total);
                    $("#utilitestbl tbody").append(html);
                        // alert($('#utilitiesinput').val());

                    $('#utilitiesinput').val(function(i, val) {
                        return val + (!val ? '' : ', ') + response.id;
                    });

                } else {
                    // $("#utilitestbl tbody").empty();
                    var html = '<tr><td>' + selectedtype + ' Fee Type is not configured. Please contact the Admin.</td><td></td><td></td></tr>';
                    $("#utilitestbl tbody").append(html);
                }
            }
        });
    });
    $("#manualentry").hide();
    $('.collapse').collapse()
});
function comments() {
    var pageURL = window.location.pathname.split('/')
    getUiComments();
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
                    break;
                }
            }
        }
    });
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
            console.log(response);
            if (response == 'nodata') {
                $(".opayment").append("<tr><td colspan='7'><center>No Challan Data Available.</center></td></tr>");
            } else {
                $.each(response, function(i, row) {
                    $(".opayment").append("<tr><td>"+(i+1)+"</td><td>" + row.studentName + "</td><td>" + row.class_list + "</td><td>" + row.section + "</td><td>" + row.term + "</td><td>" + row.org_total + "</td><td>" + "<button type='button' data-id='" + row.studentId + "' data-feegroup='" + $.trim(row.feeGroup) + "' class='btn btn-info btn-sm paymodal' id='" + row.challanNo + "' data-toggle='modal' data-target='#payModal'>PAY CHALLANS</button>" + "</td></tr>");
                });
            }
        }
    });
}
function validatePassword() {
    var err_msg = '';
    if ($("#password").val() != $("#password_confirmation").val()) {
        err_msg = "Passwords Doesn't match";
        document.getElementById("ok").disabled = true;
    } else {
        document.getElementById("ok").disabled = false;
    }
    return err_msg;
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
            // var data = JSON.parse(response);
            // console.log(response);
            $.each(response, function(i, row) {
                $(".student").append("<tr class ='warddata'><td>" + row.studentName + "</td><td>" + row.class_list + "</td><td>" + row.section + "</td></tr>");
            })
        }
    });
}

function check() {
    var err_msg = '';
    var mobile = document.getElementById('mobile');
    var goodColor = "green";
    var badColor = "red";
    if (mobile.value.length != 10) {
        err_msg = "Please Enter Valid Mobile Number";
        document.getElementById("ok").disabled = true;
    } else {
        document.getElementById("ok").disabled = false;
    }
    return err_msg;
}
function phone() {
    var err_msg = '';
    var phone = document.getElementById('phone');
    var goodColor = "green";
    var badColor = "red";
    if ((phone.value.length <= 7) || (phone.value.length > 11)) {
        err_msg = "Please Enter Valid Phone Number";
        document.getElementById("ok").disabled = true;
    } else {
        document.getElementById("ok").disabled = false;
    }
    return err_msg;
}
function rmveUtility(amt, id) {
    var old_tot = $(".grand_tot").val();
    var new_tot = parseInt(old_tot) - parseInt(amt);
    var total = parseInt($(".total").val()) - parseInt(amt);
    var list = $('#utilitiesinput').val();

    var removeValue = removingutilities(list,id);
    $('#utilitiesinput').val(removeValue);
    $('table#utilitestbl tr#' + id).remove();
    $("#tot").val(new_tot);
    $("#grand_tot").html(new_tot);
    $(".grand_tot").val(new_tot);
    $("#total").html(total);
    $(".total").val(total);                    
}
function removingutilities(list, value, separator) {
    separator = separator || ",";
    var values = list.split(separator);
    for(var i = 0 ; i < values.length ; i++) {
            if(values[i] == value) {
              values.splice(i, 1);
              return values.join(separator);
            }
        } 
        return list;
    }
function formatPhone(obj) {
    // alert("hi");
    var numbers = obj.value.replace(/\D/g, ''),
        char = {
            0: '**********'
        };
    obj.value = '';
    for (var i = 0; i < numbers.length; i++) {
        obj.value += (char[i] || '');
    }
}
