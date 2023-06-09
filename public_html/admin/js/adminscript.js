$(document).ready(function() {
    var current_page_URL = location.href;
    $("#top_menu a").each(function() {
        if ($(this).attr("href") !== "#") {
            var target_URL = $(this).prop("href");
            if (target_URL == current_page_URL) {
                $('nav a').parents('li, ul').removeClass('active');
                $(this).parent('li').addClass('active');
                $(this).closest('.dropdown').addClass('active');
                return false;
            }
        }
    });


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
    if ($(".feeEntryReport").length > 0) {
        getPaymentData();
    }
    if ($(".paymentReport").length > 0) {
        getPaidData();
    }
    if ($(".streamdata").length > 0) {
        addomega();
    }
    if ($('.feetab').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        });
    }
    if ($('.feetab1').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        });
    }
    if ($('.challancreate').length > 0) {
        $("#datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        });
    }
    /**********VALIDATION FOR NAME IN ADMIN - Start************/
    $(".name").keypress(function(event) {
        var inputValue = event.charCode;
        if (!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) {
            event.preventDefault();
        }
    });
        $("#groupamount").hide();
    /**********VALIDATION FOR NAME IN ADMIN - End************/
    // $("#datepicker").datepicker({
    //     dateFormat: "yy-mm-dd",
    // });
    $('.admintab').DataTable();
    if ($(".dataTableParents").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addpar.php"><button class="btn btn-info">Add Parent</button></a>');
    }
    if ($(".dataTableAdmin").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addadm.php"><button class="btn btn-info">Add Admin</button></a>');
    }
    if ($(".dataTableStudent").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addstd.php"><button class="btn btn-info">Add Student</button></a>');
    }
    if ($(".dataTableClass").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addclass.php"><button class="btn btn-info">Add Class</button></a>');
    }
    if ($(".dataTableLateFee").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addlatefee.php"><button class="btn btn-info">Add Late Fee</button></a>');
    }
    if ($(".dataTableStream").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addstream.php"><button class="btn btn-info">Add Stream</button></a>');
    }
    if ($(".dataTableTax").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addtax.php"><button class="btn btn-info">Add Tax</button></a>');
    }
    if ($(".dataTableTeacher").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addteacher.php"><button class="btn btn-info">Add Teacher</button></a>');
    }
    if ($(".dataTableFeeType").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addfeetype.php"><button class="btn btn-info">Add Fee Type</button></a>');
    }
    if ($(".dataTableComments").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addcomments.php"><button class="btn btn-info">Add Messages</button></a>');
    }
    if ($(".dataTableTransport").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addtransport.php"><button class="btn btn-info">Add Transport</button></a>');
    }
    // if ($(".dataTableWaiver").length > 0) {
    //     $("#DataTables_Table_0_filter").append('<a href="#myModal"data-toggle="modal"><button id="clickme" value="changestudStatus" class = "btn btn-info updatewavier"  disabled="disabled">ADD WAVING</button></a>');
    // }
    if ($(".dataTableYear").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addyear.php"><button class="btn btn-info">Add Year</button></a>');
    }
    $(".dataTables_filter input").addClass('form-control input-sm');
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
    $(".feewaivermodal").on('click', function() {
        var id = this.id;
        $(".id").val(id);
    });
    $(".updatewavier").on("click", function() {
        var selected = [];
        // var selected='';
        $("input:checkbox[name=checkme]:checked").each(function() {
            selected.push($(this).attr("id"));
        });
        $('.id').val(selected);
    });
    $('.quizsetid').multiselect({
        // alert("hi");
        includeSelectAllOption: true,
        nonSelectedText: '--Select--',
        numberDisplayed: 1,
        onChange: function(element, checked) {
            var quizsetid = $('.quizsetid option:selected');
            var selected = [];
            $(quizsetid).each(function(index, brand) {
                selected.push([$(this).val()]);
            });
        },
        onDropdownHidden: function(e) {
            $('.selected_quizsetids').val($('.quizsetid').val().join(','));
        }
    });
    // $(".date").datepicker({
    // })
    // $("#demandData").hide();
    // $("#studStatus").change(function() {
    //     $("#demandData").show();
    //     $(".hidedemand").hide();
    // });
    $('#filter').on('click', function() {
        if (($(".streamselect").val() == '') && ($(".classselect").val() == '')) {
            // alert("Please Choose Atleast One feild to Filter");
            $("#waviererror").html("<p class='error-msg'>Please Choose Atleast One field to Filter</p>");
            return false;
        } else {
            return true;
        }
    });
    if ($("#drp").length > 0) {
        $("#drp").daterangepicker({
            datepickerOptions: {
                numberOfMonths: 2,
                dateFormat: 'dd-mmm-yyyy'
            }
        });
    }
    if ($('.pagetax').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        });
    }
    $("#checkAll").change(function() {
        // alert("hi");
        var status = this.checked;
        $('.checkme').each(function() {
            this.checked = status;
        });
    });
    $(document).on('click','.checkme',function(){
       if ($(".checkme").is(':checked')) {
            $('.updatewavier').attr('disabled', false);
            $('.sendnewsms').attr('disabled', false);
        } else {
            $('.updatewavier').attr('disabled', true);
            $('.sendnewsms').attr('disabled', true);
        }   
    });    
    $(function() {
        $("#checkAll").change(function() {
           // var selected =  $(".studtype").val();
           // alert(selected);
            if ($(".checkme").is(':checked')) {
                $('.updatewavier').attr('disabled', false);
                $('.sendnewsms').attr('disabled', false);
            } else {
                // alert("Please Select Any of the Type to Filter");
                // $('.studtype').focus();
                $('.updatewavier').attr('disabled', true);
                $('.sendnewsms').attr('disabled', true);
            }
        });
    });
    if ($('.dataTableChallan').length > 0) {
        localStorage.setItem('filter', 0);  

    }
    $(".dataTableChallan").each(function() {
        var url = $(location).attr('href'),
        parts = url.split("/"),
        last_part = parts[parts.length-1];
        if (last_part == 'managechallans.php') {
            $(function() {
        $("#checkAll").click(function() {
           var selected =  $(".studtype").val();
            if ((selected != '') && (localStorage.getItem('filter') != 0)) {
                $('.updatewavier').attr('disabled', false);
                $('.sendnewsms').attr('disabled', false);
            } else {
                alert('Please Select Any of the "Type" and Click Filter');
                $('.studtype').focus();
                $('.updatewavier').attr('disabled', true);
                $('.sendnewsms').attr('disabled', true);
                $('#checkAll').prop('checked', false);
            }
        });
    });
        }
    });
    if ($('.acayear').length > 0) {
        document.getElementById("subyear").disabled = true;
    }
    $('#year').on('keypress', function() {
        document.getElementById("subyear").disabled = false;
    });
    $("#createchallan").on('click', function() {
        window.location.href = 'adminactions.php?c=' + $("#challanId").val();
    });
    // $('#close').on('change', function() {
    // }
    $(document).on('change', '#grouptype', function() {
       var selectedtype = $(this).find("option:selected").text();
       var cno = $('.id').val();
       // $("#groupamount").hide();
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getgroupamount',
                'gt': selectedtype,
                'cno': cno
            },
            success: function(response) {
                console.log(response);
                 $("#groupamount").show();
                $("#groupamount").val(response);
            }
        });
    });
    $('#WavingPercentage').on('change',function(){
        var amount = $("#groupamount").val();
        var percentage = $("#WavingPercentage").val();
        var wavingamount = (amount * (percentage/100));
        // alert(wavingamount);
        $('#WavingAmount').val(wavingamount);
    });
        $('#WavingAmount').on('change',function(){
        // alert("hi");
        var amount = $("#groupamount").val();
        var percentage = $("#WavingPercentage").val();
        if(($('#WavingAmount').val())!= ''){
        var waivingamount = $("#WavingAmount").val();
        var percentage = amount * (percentage/100);
        if(waivingamount < percentage){
            alert("Please Give Amount higher than "+percentage+"");
            $("#WavingAmount").focus();
        }
        }
    });
    comments();
    $(document).on("click", "#updateTempChallan", function() {
        var data = $("#demandCreation").serialize();
        var studId = $("#studentId").val();
        var stream = $("#stream").val();
        var name = $("#studentName").val();
        var sem = $(".term").val();
        var clasid = $(".classlist").attr('id');
        var classtxt = $(".classlist").val();
        var duedate = $(".duedate").val();
        var semester = $("#semester").val();
        // var selectedNames=$('#selectednames').val();
        // alert(classtxt);
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                console.log(response);
                // return false;
                $("#createchallan").show();
                $("#challanData1").html('');
                var html = '';
                if (response.challanData == 'Challan Already Exists') {
                    html += 'Challan for the same details already exists.futher details please contact admin.';
                    $("#createchallan").hide();
                } else if (response == "Fee Types empty") {
                    html += '<p class="error-msg">Selected Fee Type(s) are not configured yet. Please contact the admin.</p>';
                    $("#createchallan").hide();
                } else {
                    html += '<form id="studDataModal"></form><input type="hidden" name="challanId" id="challanId" value="' + response.challanData.id + '" ><input type="hidden" name="class" value="' + clasid + '" /><table class="table table-striped"><tr><td colspan="2"><label> School Name: </label> LMOIS - CBSE</td></tr><tr><td><label>Name: </label> ' + name + '</td><td><label>Semester: </label> ' + semester + '</td></tr><tr><td><label>ID: </label> ' + studId + '</td><td><label>Class: </label> ' + classtxt + '</td></tr><tr><td colspan="2">';
                    // return false;
                    // $.each(response, function(i, row) {
                    // var duedates = $.format(response.duedate, "dd-MM-yyyy");
                    // var newDate = (response.duedate).toString('dd-MM-yy');
                    html += '<table class="table table-striped"><tr><td><label>Challan Number: </label> ' + response.challanData.challanNo + '</td><td><label>Due Date: </label> ' + duedate + '</td></tr>';
                    var amount = 0;
                    $.each(response.feeData, function(i, row) {
                        html += '<tr><td><label>' + i + '</label></td><td></td></tr>';
                        $.each(row, function(index, el) {
                            html += '<tr><td>' + el[1] + '</td><td>' + el[0] + '</td></tr>';
                            amount += parseInt(el[0]);
                        });
                    });
                    html += '<tr></tr><tr><td><strong>Total</strong></td><td> ' + amount + '</td></tr></table>';
                    html += '</td></tr></table>';
                }
                $("#challanData1").append(html);
                // })
            },
            error: function() {
                // alert('hi');
            }
        })
    });
    $(".feewavier").on('click', function() {
        var id = this.id;
        // alert(id);
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getwavierchallanno',
                'data': id
            },
            success: function(response) {
                // console.log(response);
                $(".selectwavier").html('');
                var html = '';
                html += '<select name="grouptype" id="grouptype" class="form-control">';
                html += '<option value="SELECT">SELECT</option>';
                $.each(response, function(index, val) {
                    html += '<option value="' + val + '">' + val + '</option>';
                });
                html += '</select>';
                $(".selectwavier").append(html);
            },
            error: function() {
                alert('hi');
            }
        })
    });
    
    $("#other").hide();
    $(".target").submit(function(event) {
        $('#mytable tbody').html('');
        // return false;
        event.preventDefault();
        var stream = $(".stream option:selected").val();
        var semester = $(".semester option:selected").val();
        var feetype = $(".feetype option:selected").val();
        if (stream != '' && semester != '' && feetype != '') {
            $(".msg").html('');
            var academic = $(".year").val();
            // var academic = $("#mytable").append('<input type=text value=' + year + '>');
            var stream = $(".stream option:selected").val();
            var semester = $(".semester option:selected").val();
            var stream_name = $(".stream option:selected").text();
            var academic_name = $(".ayear option:selected").text();
            var semester_name = $(".semester option:selected").text();
            var feetype_name = $(".feetype option:selected").text();
            var feetype = $(".feetype option:selected").val();
            var classlist = $(".classlist").val();
            var classliststr = $(".classlistarray").val();
            var classlistarr = classliststr.split(',');
            $(".streamtbl").val(stream);
            $(".semestertbl").val(semester);
            $(".feetypetbl").val(feetype);
            $(".ayear").val($(".ayear option:selected").val());
            // console.log(classData);
            var data = $(".target").serialize();
            var html = '';
            var classdata = '';
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: data,
                success: function(response) {
                    // console.log(response);
                    html = "<tbody><tr><td>" + academic_name + "</td><td>" + stream_name + "</td><td>" + semester_name + "</td><td>" + feetype_name + "</td>";
                    if (response != null) {
                        $.each(classData, function(i, val) {
                            classdata += '<td><input type="text" class="form-control feeamt" name="' + val.trim() + '**' + i + '" ';
                            $.each(response, function(k, v) {
                                if (v.class == i) {
                                    classdata += 'value = "' + v.amount + '"';
                                }
                            });
                            classdata += '/></td>';
                        });
                        html += classdata;
                    } else {
                        $.each(classData, function(i, val) {
                            html += '<td><input type="text" class="form-control feeamt" name="' + val.trim() + '**' + i + '"/></td>';
                        });
                    }
                    html += "</tr></tbody>";
                    $('#mytable tbody').html('');
                    $("#mytable").append(html);
                    $("#other").show();
                }
            });
        } else {
            $(".msg").html("<span class='error-msg'>Please Select all the Fields to Proceed.</span>")
        }
    });
    $("#manualentry").hide();
});

var classData = {};
function addomega() {
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'submit': 'addomega'
        },
        success: function(response) {
            // var data = JSON.parse(response);
            // console.log(response);
            $.each(response, function(i, row) {
                if (i == 0) {
                    $.each(row, function(i, stream) {
                        $(".stream").append("<option value='" + stream.id + "'>" + stream.stream + "</option>");
                    });
                }
                if (i == 1) {
                    $.each(row, function(i, sem) {
                        $(".semester").append("<option>" + sem.semester + "</option>");
                    });
                }
                if (i == 2) {
                    var feetype = new Array();
                    $.each(row, function(i, fee) {
                        $(".feetype").append("<option value='" + fee.id + "'>" + fee.feeType + "</option>");
                        feetype.push(fee.feeType['id']);
                    });
                    $('.classlistfee').val(feetype);
                }
                if (i == 3) {
                    var count = 0;
                    // var classData = new Array();
                    $.each(row, function(i, clas) {
                        $("#mytable tr:nth-child(2)").append("<td>" + clas.class_list + "</td>");
                        count++;
                        classData[clas.id] = clas.class_list;
                    });
                    $('.classlist').val(count);
                    // $('.classlistarray').val(classData);
                }
                if (i == 4) {
                    $.each(row, function(i, year) {
                        $(".ayear").append("<option value='" + year.id + "'>" + year.year + "</option>");
                    });
                }
            });
        }
    });
}
function comments() {
    var pageURL = window.location.pathname.split('/')
    var name = pageURL[2];
   getComments();
}
function getComments() {
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'submit': 'getComments'
        },
        success: function(response) {
            var pageURL = window.location.pathname.split('/')
            var name = pageURL[3];
            for (var i = 0; i < response.length; i++) {
                if ($.trim(response[i].pageName) == name) {
                    $(".comment").append("<p class='comment'>" + response[i].comments + "</p>");
                    break;
                }
            }
        }
    });
}

function getPaymentData() {
    var data = $("#payment_details").serialize();
    data +=  '&' + 'submit=getPaymentData';
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: data,
        success: function(response) {
            console.log(response);
            var myTable = $('.payreport').DataTable();
            var result = response.map(function(item) {
                var result = [];
                result.push(item.id);
                result.push(item.studentId);
                result.push(item.studentName);
                result.push(item.academicYear);
                result.push(item.streamname);
                 result.push(item.class_list);
                 result.push(item.section);
                result.push(item.semester);
                result.push(item.total);
                result.push("");
                return result;
            });
            // console.log(result);
            myTable.rows.add(result);
            myTable.draw();
        }
    });
}
$("#payment_details").submit(function() {
    event.preventDefault();
    // $(".feeentryreport").hide();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(".sectionselect option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'feeentry',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
            console.log(response);
            var myTable = $('.payreport').DataTable();
            if (response != null) {
              myTable.clear();
                var i = 1;
                  var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.academicYear);
                     result.push(item.streamname);
                     result.push(item.class_list);
                     result.push(item.section);
                    result.push(item.semester); 
                    result.push(item.total);
                          i++;
                    result.push("");
                    return result;
                });
                myTable.rows.add(result);
                myTable.draw();
             } else {
               myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
$("#filterform").submit(function() {
    event.preventDefault();
    // $(".filterrespose").hide();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterbut',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
            console.log(response);
            var myTable = $('.dataTableWaiver').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentName);
                    result.push(item.stream);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.total);
                    result.push(item.waived);
                    result.push(item.org_total);
                    result.push('<a href="#myModal" data-toggle="modal" name="wavier" id="' + item.challanNo + '" class="feewaivermodal feewavier"><i class="fa fa-edit fafa"></i></a>');
                    i++;
                    result.push("");
                    return result;
                });
                // console.log(result);
                myTable.rows.add(result);
                myTable.draw();
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
$("#feeconfigdetails").submit(function() {
    event.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterfeeconfiguration',
            'classselect': classselect,
            'streamselect': streamselect
        },
        success: function(response) {
            console.log(response);
            var myTable = $('.dataFeeConfiguration').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.academicYear);
                    result.push(item.feeType);
                    result.push(item.stream);
                    result.push(item.class_list);
                    result.push(item.semester);
                    result.push(item.amount);
                    result.push('<a href="edidfeeconfig.php?id='+item.id+'"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?action=delete&page=feeconfig&id='+item.id+'"><i class="fa fa-trash-o fafa"></i></a>');
                    i++;
                    result.push("");
                    return result;
                });
                // console.log(result);
                myTable.rows.add(result);
                myTable.draw();
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
$("#filterstudentdetails").submit(function() {
    event.preventDefault();
    // $(".filterrespose").hide();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterstudent',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
            console.log(response);
      // $.each(response, function(row) {
            var myTable = $('.dataTableStudent').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.userName);
                    result.push(item.status);
                   if(item.status == "ACTIVE") {
                        result.push('<a href="adminactions.php?status=ACTIVE&id='+item.id+'&page=s"><i class="fa fa-check fafaactive fafa"></i></a><a href="editstd.php?id='+item.id+'"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?action=delete&id='+item.id+'&page=s"><i class="fa fa-trash-o fafa"></i></a>');
                    }else{
                        result.push('<a href="adminactions.php?status=INACTIVE&id='+item.id+' &page=s"><i class="fa fa-close fafainactive fafa"></i></a></a><a href="editstd.php?id='+item.id+'"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?action=delete&id='+item.id+'&page=s"><i class="fa fa-trash-o fafa"></i></a>');
                    }
  // result.push('<a href="editstd.php?id="'+ item.id + '""><i class="fa fa-edit"></i></a><a href="adminactions.php?action=delete&id="' + item.id +'"&page=s"><i class="fa fa-trash-o"></i></a>'); 
                    i++;
                    result.push("");
                    return result;
                });
                // console.log(result);
                myTable.rows.add(result);
                myTable.draw();
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
$("#filterchallan").submit(function() {
    event.preventDefault();
    // $(".filtertempchallan").hide();
    localStorage.setItem('filter', 1);  
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    var studtype = $(this).find(".studtype option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect,
            'studtype':studtype
        },
        success: function(response) {
            console.log(response);
            var myTable = $('.dataTableChallan').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                        // var studentno = $.trim(item.studentId);
                result.push('<input type="checkbox" name="checkme[]" class="checkme" value="' + item.studentId + '" style="margin:10px;">');
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName); 
                    result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.studStatus);
                    result.push(item.hostel_need);
result.push('<a href="editStudent.php?id='+item.studentId+'"><i class="fa fa-edit fafa"></i></a>');                    
                    i++;
                    result.push("");
                    return result;
                });
                myTable.rows.add(result);
                myTable.draw();
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
$("#filterTeacherdetails").submit(function() {
    event.preventDefault();
    // $(".filtertempchallan").hide();
    var classselect = $(this).find(".classselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterteacher',
            'classselect': classselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
            console.log(response);
            var myTable = $('.filterteachertables').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                        // var studentno = $.trim(item.studentId);
                result.push('<input type="checkbox" name="checkme[]" class="checkme" value="' + item.studentId + '" style="margin:10px;">');
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName); 
                    result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
       result.push('<a href="updatestudentsstatus.php?id='+item.studentId+'"><i class="fa fa-edit fafa"></i></a>');                    
                    i++;
                    result.push("");
                    return result;
                });
                myTable.rows.add(result);
                myTable.draw();
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
$("#filtercreatedchallans").submit(function() {
    event.preventDefault();
    // $(".filterrowchallan").hide();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filternewchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
            console.log(response);
            var myTable = $('.dataTableTeachers').DataTable();
            if (response != null) {
                // console.log(response[0].challanNo);
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    // var challanNo = $.trim(item.challanNo);
                    result.push(i);
                    result.push(item.challanNo);
                    result.push(item.studentName);
                    result.push(item.streamname);
                    result.push(item.class_list);
                     result.push(item.section);
                    result.push(item.term);
                    result.push(item.createddate);
                    result.push(item.duedate);
                    result.push(item.fee);
                    result.push('<a href="editcreatedchallans.php?id='+item.challanNo+'"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?actions=delete&id='+item.challanNo+'"><i class="fa fa-trash-o fafa"></i></a>');
                    i++;
                    result.push("");
                    return result;
                });
                // console.log(result);
                myTable.rows.add(result);
                myTable.draw();
            } else {
               myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
function getPaidData() {
    var data = $("#paid_details").serialize();
    data +=  '&' + 'submit=getPaidData';
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: data,
        success: function(response) {
            console.log(response);return false;
            var myTable = $('.paidreport').DataTable();
            var i = 1;
            var result = response.map(function(item) {
                var result = [];
                result.push(i);
                result.push(item.studentId);
                result.push(item.studentName);
                result.push(item.parentname);
                result.push(item.transDate);
                result.push(item.transNum);
                var status = item.transStatus;
                if (status.trim() == 'Ok') {
                    result.push('SUCCESS');
                } else if (status.trim() == 'C') {
                    result.push('CANCELLED');
                } else if (status.trim() == 'F') {
                    result.push("FAILED");
                } else {
                    result.push(status.trim());
                }
                result.push(item.amount);
                i++;
                return result;
            });
            // console.log(result);
            myTable.rows.add(result);
            myTable.draw();
        }
    });
}
$("#paid_details").submit(function() {
    event.preventDefault();
    // $(".filterpayment").hide();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterpayment',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
           console.log(response);
            var myTable = $('.paidreport').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.userName);
                     result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.transDate);
                    result.push(item.transNum);
                    var status = item.transStatus;
                    if (status.trim() == 'Ok') {
                        result.push('SUCCESS');
                    } else if (status.trim() == 'C') {
                        result.push('CANCELLED');
                    } else if (status.trim() == 'F') {
                        result.push("FAILED");
                    } else {
                        result.push(status.trim());
                    }
                    result.push(item.amount);
                    i++;
                    result.push("");
                    return result;
                });
                // console.log(result);
                myTable.rows.add(result);
                myTable.draw();
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw();
            }
        }
    });
});
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
    $('table#utilitestbl tr#' + id).remove();
    $("#tot").val(new_tot);
    $("#grand_tot").html(new_tot);
    $(".grand_tot").val(new_tot);
    $("#total").html(total);
    $(".total").val(total);                    
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