$(document).ready(function() {
    var current_page_URL = location.href;
    $("#top_menu a").each(function() {
        if ($(this).attr("href") !== "#") {
            var target_URL = $(this).prop("href");
            if (target_URL == current_page_URL) {
                $('nav a').parents('li, ul').removeClass('active');
                $(this).parent('li').addClass('active');
                $(this).closest('.dropdown').addClass('active');
                return !1
            }
        }
    });
    if ($('.contentcheque').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        })
    }
    if ($('.passchk').length > 0) {
        var myInput = document.getElementById("password");
        var letter = document.getElementById("letter");
        var number = document.getElementById("number");
        var length = document.getElementById("length");
        document.getElementById("password_confirmation").disabled = !0;
        myInput.onclick = function() {
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
                    document.getElementById("password_confirmation").disabled = !1
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
    if ($(".feeEntryReport").length > 0) {
        getPaymentData()
    }
    if ($(".paymentReport").length > 0) {
        getPaidData()
    }
    if ($(".streamdata").length > 0) {
        addomega()
    }
    if ($(".nonfeeconfigdata").length > 0) {
        loadfilterdata()
    }
    if ($('.feetab').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        })
    }
    if ($('.feetab1').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
            minDate: 0,
        })
    }
    $(document).ready(function() {
        if ($('.comments').length > 0) {
            $("#startdate").datepicker({
                dateFormat:   "yy-mm-dd",
                minDate: 0,
                numberOfMonths: 1,
                onSelect: function(selected) {
                    $("#enddate").datepicker("option", "minDate", selected)
                }
            });
            $("#enddate").datepicker({
                dateFormat:   "yy-mm-dd",
                minDate: 0,
                numberOfMonths: 1,
                onSelect: function(selected) {
                    $("#startdate").datepicker("option", "maxDate", selected)
                }
            })
        }
        if ($('.splitreport').length > 0) {
            $("#startdate").datepicker({
                dateFormat:   "dd-mm-yy",
                minDate: new Date(2018, 1 - 1, 1),
                maxDate: new Date(),
                numberOfMonths: 1,
                changeMonth: !0,
                changeYear: !0,
                onSelect: function(selected) {
                    $("#enddate").datepicker("option", "minDate", selected)
                }
            });
            $("#enddate").datepicker({
                dateFormat:   "dd-mm-yy",
                minDate: new Date(2018, 1 - 1, 1),
                maxDate: new Date(),
                numberOfMonths: 1,
                changeMonth: !0,
                changeYear: !0,
                onSelect: function(selected) {
                    $("#startdate").datepicker("option", "maxDate", selected)
                }
            })
        }
    });
    if ($('.challancreate').length > 0) {
        $("#datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        })
    }
    $(".name").keypress(function(event) {
        var inputValue = event.charCode;
        if (!(inputValue >= 65 && inputValue <= 122) && (inputValue != 32 && inputValue != 0)) {
            event.preventDefault()
        }
    });
    $("#groupamount").hide();
    $('.admintab').DataTable({
        "aLengthMenu": [
            [10, 25, 50, 100, 200, -1],
            [10, 25, 50, 100, 200, "All"]
        ]
    });
    if ($(".dataTableParents").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addpar.php"><button class="btn btn-info">Add Parent</button></a>')
    }
    if ($(".dataTableAdmin").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addadm.php"><button class="btn btn-info">Add Admin</button></a>')
    }
    if ($(".dataTableStudent").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addstd.php"><button class="btn btn-info">Add Student</button></a>')
    }
    if ($(".dataTableClass").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addclass.php"><button class="btn btn-info">Add Class</button></a>')
    }
    if ($(".dataTableLateFee").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addlatefee.php"><button class="btn btn-info">Add Late Fee</button></a>')
    }
    if ($(".dataTableStream").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addstream.php"><button class="btn btn-info">Add Stream</button></a>')
    }
    if ($(".dataTableTax").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addtax.php"><button class="btn btn-info">Add Tax</button></a>')
    }
    if ($(".dataTableTeacher").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addteacher.php"><button class="btn btn-info">Add Teacher</button></a>')
    }
    if ($(".dataTableFeeType").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addfeetype.php"><button class="btn btn-info">Add Fee Type</button></a>')
    }
    if ($(".dataTableComments").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addcomments.php"><button class="btn btn-info">Add Messages</button></a>')
    }
    if ($(".dataTableTransport").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addtransport.php"><button class="btn btn-info">Add Transport</button></a>')
    }
    if ($(".dataTableYear").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addyear.php"><button class="btn btn-info">Add Year</button></a>')
    }
    if ($(".dataTableFeeGroup").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addfeegroup.php"><button class="btn btn-info">Add Fee Group</button></a>')
    }
    if ($(".dataTableNonfee").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addnonfeechallan.php" class="btn btn-info"><span>Add Non-fee Challan</span></a>')
    }
    if ($(".dataTableProduct").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addproduct.php"><button class="btn btn-info">Add Prouct</button></a>')
    }
    if ($(".dataTableTaxExemption").length > 0) {
        $("#DataTables_Table_0_filter").append('<a href="addtax_exemption.php"><button class="btn btn-info">Generate Tax Cert.</button></a>')
    }
    $(".dataTables_filter input").addClass('form-control input-sm');
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
    $(".updatewavier").on("click", function() {
        var selected = [];
        $("input:checkbox[name=checkme]:checked").each(function() {
            selected.push($(this).attr("id"))
        });
        $('.id').val(selected)
    });
    $('.quizsetid').multiselect({
        includeSelectAllOption: !0,
        nonSelectedText: '--Select--',
        numberDisplayed: 1,
        onChange: function(element, checked) {
            var quizsetid = $('.quizsetid option:selected');
            var selected = [];
            $(quizsetid).each(function(index, brand) {
                selected.push([$(this).val()])
            })
        },
        onDropdownHidden: function(e) {
            $('.selected_quizsetids').val($('.quizsetid').val().join(','))
        }
    });
    if ($(".edittech").length > 0) {
        var selected = [];
        $('.quizsetid :selected').each(function() {
            selected.push($(this).val())
        });
        $('.selected_quizsetids').val(selected.join(', '))
    }
    $('#filter').on('click', function() {
        if (($(".streamselect").val() == '') && ($(".classselect").val() == '')) {
            $("#waviererror").html("<p class='error-msg'>Please Choose Atleast One field to Filter</p>");
            return !1
        } else {
            return !0
        }
    });
    if ($("#drp").length > 0) {
        $("#drp").daterangepicker({
            datepickerOptions: {
                numberOfMonths: 2,
                dateFormat: 'dd-mmm-yyyy'
            }
        })
    }
    if ($('.pagetax').length > 0) {
        $(".datepicker").datepicker({
            dateFormat:   "yy-mm-dd",
        })
    }
    $("#checkAll").change(function() {
        var status = this.checked;
        $('.checkme').each(function() {
            this.checked = status
        })
    });
    $(document).on('click', '.checkme', function() {
        if ($(".checkme").is(':checked')) {
            $('.updatewavier').attr('disabled', !1);
            $('.sendnewsms').attr('disabled', !1)
        } else {
            $('.updatewavier').attr('disabled', !0);
            $('.sendnewsms').attr('disabled', !0)
        }
    });
    $(function() {
        $("#checkAll").change(function() {
            if ($(".checkme").is(':checked')) {
                $('.updatewavier').attr('disabled', !1);
                $('.sendnewsms').attr('disabled', !1)
            } else {
                $('.updatewavier').attr('disabled', !0);
                $('.sendnewsms').attr('disabled', !0)
            }
        })
    });
    if ($('.dataTableChallan').length > 0) {
        localStorage.setItem('filter', 0)
    }
    $(".dataTableChallan").each(function() {
        var url = $(location).attr('href'),
            parts = url.split("/"),
            last_part = parts[parts.length - 1];
        if (last_part == 'managechallans.php') {
            $(function() {
                $("#checkAll").click(function() {
                    var selected = $(".studtype").val();
                    if ((selected != '') && (localStorage.getItem('filter') != 0)) {
                        $('.updatewavier').attr('disabled', !1);
                        $('.sendnewsms').attr('disabled', !1)
                    } else {
                        alert('Please Select Any of the "Type" and Click Filter');
                        $('.studtype').focus();
                        $('.updatewavier').attr('disabled', !0);
                        $('.sendnewsms').attr('disabled', !0);
                        $('#checkAll').prop('checked', !1)
                    }
                })
            })
        }
    });
    if ($('.acayear').length > 0) {
        document.getElementById("subyear").disabled = !0
    }
    $('#year').on('keypress', function() {
        document.getElementById("subyear").disabled = !1
    });
    $("#createchallan").on('click', function() {
        window.location.href = 'adminactions.php?c=' + $("#challanId").val()
    });
    $(document).on('change', '#grouptype', function() {
        var selectedtype = $(this).find("option:selected").text();
        var rid = $(this).find("option:selected").val();
        var cno = $('.id').val();
        $("#feegrp").val(selectedtype);
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getgroupamount',
                'gt': selectedtype,
                'cno': cno,
                'rid': rid
            },
            success: function(response) {
                console.log(response);
                $("#groupamount").show();
                $("#groupamount").val(response)
            }
        })
    });
    $('#WavingPercentage').on('change', function() {
        var amount = $("#groupamount").val();
        var percentage = $("#WavingPercentage").val();
        var wavingamount = (amount * (percentage / 100));
        $('#WavingAmount').val(wavingamount)
    });
    $('#WavingAmount').on('change', function() {
        var amount = parseInt($("#groupamount").val());
        var percentage = parseInt($("#WavingPercentage").val());
        if (($('#WavingAmount').val()) != '') {
            var waivingamount = parseInt($("#WavingAmount").val());
            var percentage = amount * (percentage / 100);
            if (waivingamount < percentage) {
                alert("Please Give Amount higher than " + percentage + "");
                $("#WavingAmount").val('');
                $("#WavingAmount").focus()
            } else if (waivingamount > amount) {
                alert("The to be waived amount should be less than " + amount + "");
                $("#WavingAmount").val('');
                $("#WavingAmount").focus()
            }
        }
        $("#addMyStudent").attr('disabled', !1)
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
        $(".pageloader").show();
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                console.log(response);
                $(".pageloader").hide();
                $("#createchallan").show();
                $("#challanData1").html('');
                var html = '';
                if (response.challanData == 'Challan Already Exists') {
                    html += 'Challan for the same details already exists.futher details please contact admin.';
                    $("#createchallan").hide()
                } else if (response == "Fee Types empty") {
                    html += '<p class="error-msg">Selected Fee Type(s) are not configured yet. Please contact the admin.</p>';
                    $("#createchallan").hide()
                } else {
                    var d = new Date(duedate);
                    duedate = d.getDate() + '-' + (d.getMonth() + 1) + '-' + d.getFullYear();
                    html += '<form id="studDataModal"></form><input type="hidden" name="challanId" id="challanId" value="' + response.challanData.id + '" ><input type="hidden" name="class" value="' + clasid + '" /><table class="table table-striped"><tr><td colspan="2"><label> School Name: </label> LMOIS - ' + response.challanData.streamname + '</td></tr><tr><td><label>Name: </label> ' + name + '</td><td><label>Semester: </label> ' + semester + '</td></tr><tr><td><label>ID: </label> ' + studId + '</td><td><label>Class: </label> ' + classtxt + '</td></tr><tr><td colspan="2">';
                    html += '<table class="table table-striped"><tr><td><label>Challan Number: </label> ' + response.challanData.challanNo + '</td><td><label>Due Date: </label> ' + duedate + '</td></tr>';
                    var amount = 0;
                    $.each(response.feeData, function(i, row) {
                        html += '<tr><td><label>' + i + '</label></td><td></td></tr>';
                        $.each(row, function(index, el) {
                            $.each(el, function(am, val) {
                                if ($.trim(val[0]) != 0) {
                                    html += '<tr><td>' + val[1] + '</td><td>' + val[0] + '</td></tr>'
                                }
                                amount += parseInt(val[0])
                            })
                        })
                    });
                    html += '<tr></tr><tr><td><strong>Total</strong></td><td> ' + amount + '</td></tr></table>';
                    html += '</td></tr></table>'
                }
                $("#challanData1").append(html)
            },
            error: function() {}
        })
    });
    $(document).on("click", ".feewavier", function() {
        var id = this.id;
        $(".id").val(id);
        $(".pageloader").show();
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getwavierchallanno',
                'data': id
            },
            success: function(response) {
                $(".pageloader").hide();
                console.log(response);
                $(".selectwavier").html('');
                var html = '';
                html += '<select name="rid" id="grouptype" class="form-control">';
                html += '<option value="SELECT">SELECT</option>';
                $.each(response, function(index, val) {
                    html += '<option value="' + index + '">' + val + '</option>'
                });
                html += '</select>';
                $(".selectwavier").append(html)
            },
            error: function() {
                alert('hi')
            }
        })
    });
    $("#myModal").on("hidden.bs.modal", function() {
        $('#groupamount').hide();
        $('#WavingPercentage').val("");
        $('#WavingAmount').val("");
        $('#waivertype').val("");
        $("#addMyStudent").attr('disabled', !1)
    });
    $("#other").hide();
    $(".target").submit(function(event) {
        $('#mytable tbody').html('');
        event.preventDefault();
        var stream = $(".stream option:selected").val();
        var semester = $(".semester option:selected").val();
        var feetype = $(".feetype option:selected").val();
        if (stream != '' && semester != '' && feetype != '') {
            $(".msg").html('');
            var academic = $(".year").val();
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
            var data = $(".target").serialize();
            var html = '';
            var classdata = '';
            $(".pageloader").show();
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: data,
                success: function(response) {
                    $(".pageloader").hide();
                    $("#mytable tr:nth-child(2)>td").remove();
                    $("#mytable tbody").remove();
                    var classData = new Array();
                    console.log(response.feeData);
                    html = "<tbody><tr><td>" + academic_name + "</td><td>" + stream_name + "</td><td>" + semester_name + "</td><td>" + feetype_name + "</td>";
                    var count = 0;
                    $.each(response.classdetails, function(i, clas) {
                        $("#mytable tr:nth-child(2)").append("<td>" + clas.class_list + "</td>");
                        count++;
                        classData.push([clas.id, clas.class_list])
                    });
                    $('.classlist').val(count);
                    if (classData != null) {
                        $.each(classData, function(i, val) {
                            classdata += '<td><input type="number" class="form-control feeamt" step="0.1" name="' + val[1].trim() + '**' + val[0] + '" ';
                            $.each(response.feeData, function(k, v) {
                                if (v.class == val[0]) {
                                    classdata += 'value = "' + v.amount + '"'
                                }
                            });
                            classdata += '/></td>'
                        });
                        html += classdata
                    } else {
                        $.each(classData, function(i, val) {
                            html += '<td><input type="number" class="form-control feeamt" step="0.1" name="' + val.trim() + '**' + i + '"/></td>'
                        })
                    }
                    html += "</tr></tbody>";
                    $('#mytable tbody').html('');
                    $("#mytable").append(html);
                    $("#other").show()
                }
            })
        } else {
            $(".msg").html("<span class='error-msg'>Please Select all the Fields to Proceed.</span>")
        }
    });
    $(".nonfeetarget").submit(function(event) {
        $('#mytable tbody').html('');
        event.preventDefault();
        var stream = $(".stream option:selected").val();
        var semester = $(".semester option:selected").val();
        var feetype = $(".feetype option:selected").val();
        if (stream != '' && semester != '' && feetype != '') {
            $(".msg").html('');
            var academic = $(".year").val();
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
            var data = $(".nonfeetarget").serialize();
            var html = '';
            var classdata = '';
            console.log(data);
            $(".pageloader").show();
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: data,
                success: function(response) {
                    $(".pageloader").hide();
                    $("#mytable tr:nth-child(2)>td").remove();
                    $("#mytable tbody").remove();
                    var classData = new Array();
                    console.log(response);
                    html = "<tbody><tr><td>" + academic_name + "</td><td>" + stream_name + "</td><td>" + semester_name + "</td><td>" + feetype_name + "</td>";
                    var count = 0;
                    $.each(response.classdetails, function(i, clas) {
                        $("#mytable tr:nth-child(2)").append("<td>" + clas.class_list + "</td>");
                        count++;
                        classData.push([clas.id, clas.class_list])
                    });
                    $('.classlist').val(count);
                    if (classData != null) {
                        $.each(classData, function(i, val) {
                            classdata += '<td><input type="number" class="form-control feeamt" step="0.1" name="' + val[1].trim() + '**' + val[0] + '" ';
                            $.each(response.feeData, function(k, v) {
                                if (v.class == val[0]) {
                                    classdata += 'value = "' + v.amount + '"'
                                }
                            });
                            classdata += '/></td>'
                        });
                        html += classdata
                    } else {
                        $.each(classData, function(i, val) {
                            html += '<td><input type="number" class="form-control feeamt" step="0.1" name="' + val.trim() + '**' + i + '"/></td>'
                        })
                    }
                    html += "</tr></tbody>";
                    $('#mytable tbody').html('');
                    $("#mytable").append(html);
                    $("#other").show()
                }
            })
        } else {
            $(".msg").html("<span class='error-msg'>Please Select all the Fields to Proceed.</span>")
        }
    });
    $("#manualentry").hide();
    $(".strchange").on('change', function() {
        var strId = $(this).val();
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'getClassData',
                'data': strId
            },
            success: function(response) {
                var options = '';
                var selectedclasses = $(".selected_quizsetids").val();
                selectedclasses = selectedclasses.split(',');
                $.each(response, function(i, val) {
                    $.each(selectedclasses, function(k, v) {
                        var id = v.trim();
                        console.log(val);
                        if (id == val.id) {
                            options += '<option value="' + val.id + '" selected>' + val.class_list + '</option>'
                        } else {
                            options += '<option value="' + val.id + '">' + val.class_list + '</option>'
                        }
                    })
                });
                $("#classlist").multiselect('dataprovider', response)
            }
        })
    });
    $('.streamchange').on('change', function() {
        var strId = $(this).val();
        if (strId.length != 0) {
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: {
                    'submit': 'getClassData',
                    'data': strId
                },
                success: function(response) {
                    console.log(response);
                    var options = '<option value="">Class</option>';
                    $.each(response, function(i, val) {
                        options += '<option value="' + val.value + '">' + val.label + '</option>'
                    });
                    $(".classselect").html(options)
                }
            })
        } else {
            $(".classselect").html('<option value="">Class</option>')
        }
    });
    $('.streamchangeforstud').on('change', function() {
        var strId = $(this).val();
        if (strId.length != 0) {
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: {
                    'submit': 'getClassDataForStd',
                    'data': strId
                },
                success: function(response) {
                    console.log(response);
                    var options = '<option value="">Class</option>';
                    $.each(response, function(i, val) {
                        options += '<option value="' + val.value + '">' + val.label + '</option>'
                    });
                    $(".classselectstud").html(options)
                }
            })
        } else {
            $(".classselectstud").html('<option value="">Class</option>')
        }
    });
    $('.classchange').on('change', function() {
        var strId = $(".streamselect").val();
        var classId = $(this).val();
        if (strId.length != 0) {
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: {
                    'submit': 'getSectionData',
                    'strId': strId,
                    'classId': classId
                },
                success: function(response) {
                    console.log(response[0]);
                    var options = '<option value="">Section</option>';
                    if (response == null || $.trim(response[0].section) == '') {
                        options += '<option value="A">A</option><option value="B">B</option><option value="C">C</option><option value="D">D</option><option value="E">E</option><option value="F">F</option><option value="G">G</option><option value="H">H</option><option value="I">I</option><option value="J">J</option><option value="NEW">NEW</option>'
                    } else {
                        $.each(response, function(i, val) {
                            options += '<option value="' + val.section + '">' + val.section + '</option>'
                        })
                    }
                    $(".sectionselect").html(options)
                }
            })
        } else {
            $(".sectionselect").html('<option value="">Section</option>')
        }
    });
    $('.classcoclass').on('change', function() {
        var strId = $("#classcostream").val();
        var classId = $(this).val();
        if (strId.length != 0) {
            $.ajax({
                url: 'adminactions.php',
                method: 'post',
                dataType: 'json',
                data: {
                    'submit': 'getClassCoSectionData',
                    'strId': strId,
                    'classId': classId
                },
                success: function(response) {
                    console.log(response);
                    var options = '<option value="">Section</option>';
                    $.each(response, function(i, val) {
                        options += '<option value="' + val.section + '">' + val.section + '</option>'
                    });
                    $(".classcosectionselect").html(options)
                }
            })
        } else {
            $(".classcosectionselect").html('<option value="">Section</option>')
        }
    })
    $("#Gobacknon").on('click', function() {
        $.ajax({
            type: 'post',
            url: 'adminactions.php',
            dataType: 'json',
            data: {
                'Gobacknon': 'Gobacknon'
            },
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    window.location.href = 'createnonfeechallans.php'
                }
            }
        })
    });
    $("#goBack").on('click', function() {
        $.ajax({
            type: 'post',
            url: 'adminactions.php',
            dataType: 'json',
            data: {
                'back': 'back'
            },
            success: function(response) {
                console.log(response);
                if (response == 1) {
                    window.location.href = 'addnonfeechallan.php'
                } else if (response == 2) {
                    window.location.href = 'managechallans.php'
                } else {
                    window.location.href = 'home.php'
                }
            }
        })
    });
    $(document).on('change', '#hostelneed', function() {
        var hostelneed = $('#hostelneed option:selected').html();
        if (hostelneed == 'Y') {
            $('#transtg').val(0)
        } else {
            $('#transtg').focus()
        }
    });
    $(document).on("click", ".viewmodal", function() {
        var studId = $(this).data('id');
        var id = this.id;
        var feegroup = $(this).data('feegroup');
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'submit': 'viewChallanData',
                'studId': studId,
                'cid': id,
                'feegroup': feegroup
            },
            success: function(response) {
                console.log(response);
                $("#challanData").html('');
                var html = '<input type="hidden" name="studId" id="studId" value="' + studId + '"><input type="hidden" name="sem" id="sem" value="' + response.term + '"><input type="hidden" name="class" id="class" value="' + response.clid + '"><input class="grand_tot" type="hidden" name="grand_tot"/><input type="hidden" name="challanNo" id="challanNo" value="' + response.challanNo + '"><input type="hidden" name="stream"  value="' + response.stream + '"/><input type="hidden" name="sfsextrautilities" id="sfsutilitiesinput" /><input type="hidden" name="schoolextrautilities" id="schoolutilitiesinput" /><input type="hidden" name="sfsextrautilitiesamount" id="sfsutilitiesinputamount"/><input type="hidden" name="schoolextrautilitiesamount" id="schoolutilitiesinputamount"/><input type="hidden" name="sfsextrautilitiesqty" id="sfsutilitiesinputqty"/><input type="hidden" name="schoolextrautilitiesqty" id="schoolutilitiesinputqty"/><table class="table table-striped"><tr><td colspan="2"><label> School Name: </label> LMOIS - ' + response.steamname + '</td></tr><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Semester: </label> ' + response.term + '</td></tr><tr><td><label>ID: </label> ' + response.studentId + '</td><td><label>Class: </label> ' + response.class_list + '</td></tr><tr><td colspan="2">';
                html += '<table class="table table-striped"><tr class="innerborder1"><td colspan="2"><label>Challan Number: </label> ' + response.challanNo + '</td><td><label>Due Date: </label> ' + response.duedate + '</td></tr>';
                var amount = 0;
                $.each(response.feeData, function(i, row) {
                    html += '<tr><input type="hidden" name="paygroup[]" value="' + i + '" /><td colspan="2"><label>' + i + '</label></td><td></td></tr>';
                    var pamt = 0;
                    var wpamt = 0;
                    $.each(row, function(index, el) {
                        if (index == 'waived' && el != 0) {
                            console.log(el.length);
                            html += '<tr><td colspan="2"><b>Waiver</b> - ' + el[0].waiver_type + '</td><td>' + el[0].waiver_total + '</td></tr>';
                            wpamt = parseInt(el[0].waiver_total)
                        } else {
                            if ($.trim(el[0]) != 0) {
                                html += '<tr><td colspan="2">' + el[1] + '</td><td>' + el[0] + '</td></tr>';
                                pamt += parseInt(el[0])
                            }
                        }
                    });
                    amount += pamt;
                    amount -= wpamt;
                    html += '<tr class="innerborder2"><td colspan="2"><input type="hidden" id="grouptotal" name="grouptotal" value="' + pamt + '" /><strong>Total: </strong></td><td><span id="grouptot">' + (pamt - wpamt) + '</span></td></tr>';
                    html += '<tr><input type="hidden" name="paygroup_amt[]" id="paygroup_amt" value="' + pamt + '" /></tr>'
                });
                var feegroups = [];
                $.each(response.feeData, function(key, value) {
                    feegroups.push($.trim(key))
                });
                html += '<input type="hidden" name="tot" id="tot" value="' + amount + '" /> ';
                $(".grand_tot").val(amount);
                html += '<tr><td></td><td colspan ="2"><p class="tot" >Grand Total: <span id="grand_tot">' + amount + '</span></p></td></tr></table><tr><td><strong>Remarks: </strong></td><td>' + response.remarks + '</td></tr></table>';
                $("#challanData").append(html)
            }
        })
    });
    $("#mailcontent").hide();
    $(".notifyType").on("change", function() {
        if (this.value == 'sms') {
            $("#smscontent").show();
            $("#mailcontent").hide()
        } else if (this.value == 'mail') {
            $("#smscontent").hide();
            $("#mailcontent").show()
        }
    });
    $(document).on("click", "#shownonfeechallan", function() {
        var data = $("#nonfeechallanform").serialize();
        var studId = $("#studentId").val();
        var stream = $("#stream").val();
        var name = $("#studentName").val();
        var sem = $(".term").val();
        var clasid = $(".classlist").attr('id');
        var classtxt = $(".classlist").val();
        var duedate = $(".duedate").val();
        var semester = $("#semester").val();
        $(".pageloader").show();
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: data,
            success: function(response) {
                console.log(response);
                $(".pageloader").hide();
                var html = '';
                if (response.challanData == 'Challan Already Exists') {
                    html += '<p class="error-msg">Challan for the same details already exists.futher details please contact admin.</p>'
                } else if (response == "Fee Types empty") {
                    html += '<p class="error-msg">Selected Fee Type(s) are not configured yet. Please contact the admin.</p>'
                } else {
                    window.location.href = "createnonfeechallans.php"
                }
                $(".msg").append(html)
            }
        })
    });
    $('#getsfsdata').on('submit', function(e) {
        e.preventDefault();
        var sid = $.trim($('#studentid').val());
        $.ajax({
            url: 'adminactions.php',
            method: 'post',
            dataType: 'json',
            data: {
                'getsfsdata': 'find',
                'sid': sid
            },
            success: function(data) {
                console.log(data);
                if (data.selecteddata == null) {
                    $('.challandetails').hide();
                    $('.errormessage').html("<p class='error-msg'>Given Student ID doesn't Exist.</p>")
                } else {
                    $('.challandetails').show();
                    var response = data.selecteddata;
                    $('.cnum').val(response[0].challanNo);
                    $('.term').val(response[0].term);
                    $('.academicyear').val(response[0].academicYear);
                    $('.stream').val(response[0].streamid);
                    $('.class').val(response[0].classid);
                    $('.snum').text(response[0].studentName);
                    $('.studid').text(response[0].studentId);
                    $('.studid').val(response[0].studentId);
                    $('.classid').text(response[0].class_list);
                    $('.streamid').text(response[0].streamname);
                    var html = "";
                    if (typeof(response[0].feename) != "undefined" && response[0].feename !== null) {
                        html += "<table class='sfstbl' width='600'><tr><th width='30' class='text-center'>S.No</th><th width='250' class='text-center'>Particulars</th><th width='80' class='text-center'>Quantity</th><th class='text-center' width='120' >Amount</th><th>Action</th></tr>";
                        for (var i = 0; i < response.length; i++) {
                            html += '<tr class=""><td class="text-center m-t-15">' + (i + 1) + '</td><td class="text-center"><label class="control-label">' + response[i].feename + '</td><td class="text-center">' + response[i].quantity + '</td><td class="text-right">' + response[i].total + '</div></td><td><a href="adminactions.php?remove=sfs&sid=' + response[i].sfsid + '&cid=' + response[i].cid + '">Remove</a></td></tr>'
                        }
                        html += '</table>'
                    } else {
                        html += "<p>No SFS Utilities are added yet.</p>"
                    }
                    html += '<table width="600" class=" sfstbl m-t-15"><tr><td>Add/Edit SFS here:</td><td><select id="sfsutilites" class="form-control"><option value="">--SELECT--</option>';
                    $.each(data.sfs, function(index, val) {
                        html += '<option value="' + val.id + '">' + val.feeType + '</option>'
                    });
                    html += '</select></td><td><select id = "sfsextraqty" class="form-control"><option value="">--SELECT--</option><option value="1">1</option><option value="2">2</option><option value="3">3</option><option value="4">4</option><option value="5">5</option></select></td></tr>';
                    html += '</td></tr><tr><td colspan="3"><table id="sfsutilitestbl" class="table table-striped"> <tbody> </tbody></table></td></tr></table>';
                    console.log(html);
                    $(".groupdata").html(html)
                }
            }
        })
    });
    $(document).on('change', '#sfsutilites', function() {
        var selected = $(this).find("option:selected").val();
        var data = $("#sfsdatachange").serializeArray();
        var selectedtype = $(this).find("option:selected").text();
        var str1 = "TRANSPORT";
        if (selectedtype.toLowerCase().indexOf(str1.toLowerCase()) != -1) {
            $("#sfsextraqty").hide();
            var sfsqty = 1;
            $('#sfsutilites').prop('selectedIndex', 0);
            $.ajax({
                url: 'adminactions.php',
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
                    console.log(response);
                    if (response != null) {
                        var finalamount = response.amount * sfsqty;
                        var tormve = $('table#sfsutilitestbl tr.' + selected).find('td:eq(1)').text();
                        if (tormve != '') {
                            rmveSfsUtility(tormve, selected)
                        }
                        var html = '<tr class="' + response.id + '"><td>' + response.feeType + '</td><td>' + finalamount + '</td><td><i title="remove" class="fa fa-close" onclick="rmveSfsUtility(' + finalamount + ',' + response.id + ',' + sfsqty + ')" ></i></td></tr>';
                        $("#sfsutilitestbl tbody").append(html);
                        $('#sfsutilitiesinput').val(function(i, val) {
                            return val + (!val ? '' : ', ') + response.id
                        });
                        $('#sfsutilitiesinputqty').val(function(i, val) {
                            return val + (!val ? '' : ', ') + sfsqty
                        })
                    } else {
                        var html = '<tr><td>' + selectedtype + ' Fee Type is not configured. Please contact the Admin.</td><td></td><td></td></tr>';
                        $("#sfsutilitestbl tbody").append(html)
                    }
                    $("#sfsextraqty").val('')
                }
            })
        } else {
            $("#sfsextraqty").show();
            $("#sfsextraqty").focus()
        }
    });
    $(document).on('change', '#sfsextraqty', function() {
        var selected = $("#sfsutilites").find("option:selected").val();
        var sfsqty = $("#sfsextraqty").find("option:selected").val();
        var selectedtype = $("#sfsutilites").find("option:selected").text();
        var data = $("#sfsdatachange").serializeArray();
        $('#sfsutilites').prop('selectedIndex', 0);
        $.ajax({
            url: 'adminactions.php',
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
                console.log(response);
                if (response != null) {
                    var finalamount = response.amount * sfsqty;
                    var tormve = $('table#sfsutilitestbl tr.' + selected).find('td:eq(1)').text();
                    if (tormve != '') {
                        rmveSfsUtility(tormve, selected)
                    }
                    var html = '<tr class="' + response.id + '"><td>' + response.feeType + '</td><td>' + finalamount + '</td><td><i title="remove" class="fa fa-close" onclick="rmveSfsUtility(' + finalamount + ',' + response.id + ',' + sfsqty + ')" ></i></td></tr>';
                    $("#sfsutilitestbl tbody").append(html);
                    $('#sfsutilitiesinput').val(function(i, val) {
                        return val + (!val ? '' : ', ') + response.id
                    });
                    $('#sfsutilitiesinputqty').val(function(i, val) {
                        return val + (!val ? '' : ', ') + sfsqty
                    })
                } else {
                    var html = '<tr><td>' + selectedtype + ' Fee Type is not configured. Please contact the Admin.</td><td></td><td></td></tr>';
                    $("#sfsutilitestbl tbody").append(html)
                }
                $("#sfsextraqty").val('')
            }
        })
    });
    if ($(".dataTableChallan").length > 0) {
        $(document).on('change', ".checkme", function() {
            var id = this.id;
            var cid = $("#" + id).next('input').val();
            var sid_cid = this.value + '-' + cid;
            if ($(this).is(':checked')) {
                $('#cidd').val(function(i, val) {
                    return val + (!val ? '' : ',') + sid_cid
                })
            } else {
                var list = $("#cidd").val();
                var values = list.split(',');
                values = jQuery.grep(values, function(value) {
                    return value != sid_cid
                });
                var cdata = values.join(',');
                $('#cidd').val(cdata)
            }
        })
    }
    $("#file").on('change', function() {
        document.getElementById('frmExcelImport').submit()
    })
    $(".ledgerreportexportexcel").attr("disabled", !0);
    $(".studentledgerexportexcel").attr("disabled", !0);
    $(".ledgerreportcoloumnwiseexportexcel").attr("disabled", !0)
});

function rmveSfsUtility(amt, id, qty) {
    var list = $('#sfsutilitiesinput').val();
    var removeValue = removingutilities(list, id);
    var qtylist = $('#sfsutilitiesinputqty').val();
    var qtyremoveValue = removingutilities(qtylist, qty);
    var data = $("#studDataModal").serializeArray();
    var deletedamount = amt;
    $('#sfsutilitiesinput').val(removeValue);
    $('#sfsutilitiesinputqty').val(qtyremoveValue);
    $('table#sfsutilitestbl tr.' + id).remove()
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

function addomega() {
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'submit': 'addomega'
        },
        success: function(response) {
            $.each(response, function(i, row) {
                if (i == 0) {
                    $.each(row, function(i, stream) {
                        $(".stream").append("<option value='" + stream.id + "'>" + stream.stream + "</option>")
                    })
                }
                if (i == 1) {
                    $.each(row, function(i, sem) {
                        $(".semester").append("<option>" + sem.semester + "</option>")
                    })
                }
                if (i == 2) {
                    var feetype = new Array();
                    $.each(row, function(i, fee) {
                        $(".feetype").append("<option value='" + fee.id + "'>" + fee.feeType + "</option>");
                        feetype.push(fee.feeType.id)
                    });
                    $('.classlistfee').val(feetype)
                }
                if (i == 4) {
                    $.each(row, function(i, year) {
                        $(".ayear").append("<option value='" + year.id + "'>" + year.year + "</option>")
                    })
                }
            })
        }
    })
}

function comments() {
    var pageURL = window.location.pathname.split('/')
    var name = pageURL[2];
    getComments()
}

function getComments() {
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'submit': 'getComments'
        },
        success: function(response) {}
    })
}

function getPaymentData() {
    var data = $("#payment_details").serialize();
    data +=  '&' + 'submit=getPaymentData';
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: data,
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.payreport').DataTable();
            var i = 1;
            var result = response.map(function(item) {
                var result = [];
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
                return result
            });
            myTable.rows.add(result);
            myTable.draw()
        }
    })
}
$("#payment_details").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(".sectionselect option:selected").val();
    $(".pageloader").show();
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
            $(".pageloader").hide();
            var myTable = $('.payreport').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
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
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterform").submit(function() {
    event.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    var semesterselect = $(this).find(".semesterselect option:selected").val();
    var yearselect = $(this).find(".yearselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterbut',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect,
            'semesterselect': semesterselect,
            'yearselect': yearselect
        },
        success: function(response) {
            $(".pageloader").hide();
            var myTable = $('.dataTableWaiver').DataTable();
            if (response != null) {
                console.log(response);
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.stream);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.waiver_org_total);
                    result.push(item.waiver_total);
                    result.push(item.waiverdiff);
                    result.push(item.waiver_group);
                    result.push('<a href="#myModal" data-toggle="modal" name="wavier" id="' + item.challanNo + '" class="feewavier"><i class="fa fa-edit fafa"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#feeconfigdetails").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    $(".pageloader").show();
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
            $(".pageloader").hide();
            var msg = "'Fee Configured amount.If so then the challans having this particular FEE TYPE has to be deleted and Re-created once'";
            var myTable = $('.dataFeeConfiguration').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.year);
                    result.push(item.feeType);
                    result.push(item.stream);
                    result.push(item.class_list);
                    result.push(item.semester);
                    result.push(item.amount);
                    result.push('<a href="edidfeeconfig.php?id=' + item.id + '"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?action=delete&page=feeconfig&id=' + item.id + '" onclick="return confirmDelete(' + msg + ')"><i class="fa fa-trash-o fafa"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#nonfeeconfigdetails").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filternonfeeconfiguration',
            'classselect': classselect,
            'streamselect': streamselect
        },
        success: function(response) {
            $(".pageloader").hide();
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
                    result.push('<a href="editnonfeeconfig.php?id=' + item.id + '"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?action=delete&page=nonfeeconfig&id=' + item.id + '"><i class="fa fa-trash-o fafa"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filternonfeepaidchallans").submit(function() {
    event.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filternonfeepaidchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect,
            'common': $("#commonfee").val()
        },
        success: function(response) {
            $(".pageloader").hide();
            console.log(response);
            var myTable = $('.dataTableNonfeePaid').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.challanNo);
                    result.push(item.studentName);
                    result.push(item.steamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.feename);
                    result.push(item.total);
                    result.push(item.createdOn);
                    result.push(item.updatedOn);
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterstudentdetails").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $(".pageloader").show();
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
            $(".pageloader").hide();
            var myTable = $('.dataTableStudent').DataTable();
            if (response != null) {
                var Student = "'Student.'";
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
                    if (item.status == "ACTIVE") {
                        result.push('<a href="adminactions.php?status=ACTIVE&id=' + item.id + '&page=s"><i class="fa fa-check fafaactive fafa"></i></a><a href="editstd.php?id=' + item.id + '"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?action=delete&id=' + item.id + '&page=s" onclick="return confirmDelete(' + Student + ')"><i class="fa fa-trash-o fafa"></i></a>')
                    } else {
                        result.push('<a href="adminactions.php?status=INACTIVE&id=' + item.id + ' &page=s"><i class="fa fa-close fafainactive fafa"></i></a></a><a href="editstd.php?id=' + item.id + '"><i class="fa fa-edit fafa"></i></a><a href="adminactions.php?action=delete&id=' + item.id + '&page=s" onclick="return confirmDelete(' + Student + ')"><i class="fa fa-trash-o fafa"></i></a>')
                    }
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterchallan").submit(function(e) {
    e.preventDefault();
    localStorage.setItem('filter', 1);
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    var studtype = $(this).find(".studtype option:selected").val();
    var studdatatype = $(this).find(".studdatatype option:selected").val();
    var ttype = $(this).find(".transporttype option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect,
            'studtype': studtype,
            'studdatatype': studdatatype,
            'ttype': ttype
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableChallan').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push('<input type="checkbox" name="checkme[]" class="checkme" value="' + item.studentId + '" style="margin:10px;" id="checkme' + i + '"><input type="hidden" name="cid[]"  value="' + item.challanNo + '"><input type="hidden" name="cno" class="cno" value="' + item.challanNo + '">');
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.studStatus);
                    result.push(item.hostel_need);
                    result.push('<a href="editStudent.php?id=' + item.studentId + '&cid=' + item.challanNo + '"><i class="fa fa-edit fafa"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterTeacherdetails").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classcoclass option:selected").val();
    var sectionselect = $(this).find(".classcosectionselect option:selected").val();
    var stream = $(this).find('#classcostream').val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterteacher',
            'classselect': classselect,
            'sectionselect': sectionselect,
            'stream': stream
        },
        success: function(response) {
            $(".pageloader").hide();
            var myTable = $('.filterteachertables').DataTable();
            if (response != 'no data') {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push('<input type="checkbox" name="checkme[]" class="checkme" value="' + item.studentId + '" style="margin:10px;">');
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.stream);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push('<a href="updatestudentsstatus.php?id=' + item.studentId + '"><i class="fa fa-edit fafa"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filtercreatedchallans").submit(function() {
    event.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    var semesterselect = $(this).find(".semesterselect option:selected").val();
    var yearselect = $(this).find(".yearselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filtercreatedchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect,
            'semesterselect': semesterselect,
            'yearselect': yearselect
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableTeachers').DataTable();
            if (response != null) {
                var msg = "'Challan. If so you have to re-create the challan for the following Student Id'";
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.challanNo);
                    result.push(item.studentName);
                    result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.createdOn);
                    result.push(item.duedate);
                    if (item.waiver_total != 0) {
                        result.push((item.waiver_org_total) - (item.waiver_total))
                    } else {
                        result.push(item.waiver_org_total)
                    }
                    result.push('<a href="editcreatedchallans.php?id=' + item.challanNo + '"><i class="fa fa-edit fafa" style="font-size:10px;"></i></a><a href="adminactions.php?actions=delete&id=' + item.challanNo + '" onclick="return confirmDelete(' + msg + ')"><i class="fa fa-trash-o fafa"></i></a><a href="#viewModal" data-id="' + item.studentId + '" data-feegroup="' + item.feeGroup + '" class="viewmodal" id="' + item.challanNo + '" data-toggle="modal" data-target="#viewModal"><i class="fa fa-eye"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filternonfeecreatedchallans").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filternonfeecreatedchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
            $(".pageloader").hide();
            var myTable = $('.dataTableNonfee').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.challanNo);
                    result.push(item.studentName);
                    result.push(item.steamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.createdOn);
                    result.push(item.duedate);
                    result.push(item.feename);
                    result.push(item.total);
                    result.push('<a href="editnonfeecreatedchallans.php?id=' + item.cid + '"><i class="fa fa-edit fafa" style="font-size:10px;"></i></a><a href="adminactions.php?actions=delete&id=' + item.cid + '"><i class="fa fa-trash-o fafa"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterpaidchallans").submit(function() {
    event.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    var semesterselect = $(this).find(".semesterselect option:selected").val();
    var yearselect = $(this).find(".yearselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterpaidchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect,
            'semesterselect': semesterselect,
            'yearselect': yearselect
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableTeachers').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.challanNo);
                    result.push(item.studentName);
                    result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.term);
                    result.push(item.createdOn);
                    result.push(item.duedate);
                    if (item.waiver_total != 0) {
                        result.push((item.waiver_org_total) - (item.waiver_total))
                    } else {
                        result.push(item.waiver_org_total)
                    }
                    if (($.trim(item.pay_type) != 'Online') && ($.trim(item.pay_type) != '')) {
                        result.push('<a href="#myModal" data-toggle="modal" name="chequerev" class="chequerev" id="' + item.challanNo + '"><i class="fa fa-undo"></i></a>')
                    } else {
                        result.push("Null")
                    }
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterledgerreport").submit(function() {
    $('.challanthereornot').html("");
    sessionStorage.removeItem('successchallanstatus');
    sessionStorage.removeItem('errorchallanstatus');
    event.preventDefault();
    var yearselect = $(this).find(".yearselect option:selected").val();
    var semesterselect = $(this).find(".semesterselect option:selected").val();
    var feegroupselect = $(this).find(".feegroupselect option:selected").val();
    var feetypeselect = $(this).find(".feetypeselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var classselect = $(this).find(".classselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    var entrytype = $(this).find(".entrytype option:selected").val();
    var challanStatus = $(this).find(".challanStatus option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterledgerreport',
            'yearselect': yearselect,
            'semesterselect': semesterselect,
            'feegroupselect': feegroupselect,
            'feetypeselect': feetypeselect,
            'streamselect': streamselect,
            'classselect': classselect,
            'sectionselect': sectionselect,
            'entrytype': entrytype,
            'challanStatus': challanStatus
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableDemandReport').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.challanNo);
                    result.push(item.studentName);
                    result.push(item.academicYear);
                    result.push(item.class);
                    result.push(item.stream);
                    result.push(item.term);
                    result.push(item.date);
                    result.push(item.feeGroup);
                    result.push(item.feeType);
                    result.push(item.total);
                    result.push(item.remarks);
                    result.push(item.entryType);
                    result.push(item.challanStatus);
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
                $(".ledgerreportexportexcel").attr("disabled", !1);
                $(".ledgerreportcoloumnwiseexportexcel").attr("disabled", !1)
            } else {
                $(".ledgerreportexportexcel").attr("disabled", !0);
                $(".ledgerreportcoloumnwiseexportexcel").attr("disabled", !0);
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterstudentledger").submit(function() {
    $('.studentidthereornot').html("")
    event.preventDefault();
    var studentid = $(this).find(".studentidreport").val()
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterstudentledger',
            'studentid': studentid
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableDemandReport').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.challanNo);
                    result.push(item.studentName);
                    result.push(item.academicYear);
                    result.push(item.class);
                    result.push(item.stream);
                    result.push(item.term);
                    result.push(item.date);
                    result.push(item.feeGroup);
                    result.push(item.feeType);
                    result.push(item.total);
                    result.push(item.remarks);
                    result.push(item.entryType);
                    result.push(item.challanStatus);
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
                $(".studentledgerexportexcel").attr("disabled", !1)
            } else {
                $(".studentledgerexportexcel").attr("disabled", !0);
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
                $('.studentidthereornot').html('<p class="error-msg">Enter Correct Student ID!!!</p>')
            }
        }
    })
});

function getPaidData() {
    var data = $("#paid_details").serialize();
    data +=  '&' + 'submit=getPaidData';
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: data,
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            return !1;
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
                    result.push('SUCCESS')
                } else if (status.trim() == 'C') {
                    result.push('CANCELLED')
                } else if (status.trim() == 'F') {
                    result.push("FAILED")
                } else {
                    result.push(status.trim())
                }
                result.push(item.amount);
                i++;
                return result
            });
            myTable.rows.add(result);
            myTable.draw()
        }
    })
}
$("#paid_details").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $(".pageloader").show();
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
            $(".pageloader").hide();
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
                        result.push('SUCCESS')
                    } else if (status.trim() == 'C') {
                        result.push('CANCELLED')
                    } else if (status.trim() == 'F') {
                        result.push("FAILED")
                    } else {
                        result.push(status.trim())
                    }
                    result.push(item.amount);
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$('.challandetails').hide();
$('.chequeDetails').hide();
$("#getchallan").submit(function(e) {
    e.preventDefault();
    var cno = $.trim($('#challanno').val());
    $(".pageloader").show();
    $.ajax({
        url: 'adminactions.php',
        method: 'post',
        dataType: 'json',
        data: {
            'getchallan': 'new',
            'cno': cno
        },
        success: function(response) {
            $(".pageloader").hide();
            if (response == '0') {
                $('.errormessage').html("<p class='error-msg'>Given Student ID Doesnot Exist.</p>")
            }
            if (response == '1') {
                $('.errormessage').html("<p class='error-msg'>Given Student ID Have Been Paid.</p>")
            }
            var html = "";
            $.each(response.challandata, function(i, val) {
                console.log(val.challanNo);
                $('.challandetails').show();
                $('.sid').val(val.studentId);
                $('.sid').text(val.studentId);
                $('.cnum').val(val.challanNo);
                $('.term').val(val.term);
                $('.academicyear').val(val.academicYear);
                $('.stream').val(val.stream);
                $('.class').val(val.classList);
                $('.snum').text(val.studentName);
                $('.snum').val(val.studentName);
                $('.cno').text(val.challanNo);
                $('.cno').val(val.challanNo);
                $('.classid').text(val.class_list);
                $('.classid').val(val.class_list);
                $('.streamid').text(val.streamname);
                $('.streamid').val(val.streamname)
            });
            $.each(response.feegroups, function(i, val) {
                html += '<input class="feegroupcheck" required type="radio" name="feegroupradio" value="' + i + '">' + val + '&nbsp;&nbsp;'
            });
            console.log(html);
            $(document).on('click', '.feegroupcheck', function() {
                $('.chequeDetails').show();
                $('.onlinebank').hide();
                $('.onlinetrans').hide();
                $('.chequebank').hide();
                $('.chequeno').hide()
            });
            $(".challandetails").show();
            $(".groupdata").html(html)
        },
        error: function(response) {}
    })
});
$(document).on("change", "#ptype", function() {
    var val = $('#ptype').val();
    if (val == 'Online') {
        $('.onlinebank').show();
        $('.onlinetrans').show();
        $('.chequebank').hide();
        $('.chequeno').hide();
        $("#bank").attr('required', !0);
        $("#paymentmodetrans").attr('required', !0);
        $("#cbank").attr('required', !1);
        $("#paymentmode").attr('required', !1)
    } else {
        $('.onlinebank').hide();
        $('.onlinetrans').hide();
        $('.chequebank').show();
        $('.chequeno').show();
        $("#bank").attr('required', !1);
        $("#paymentmodetrans").attr('required', !1);
        $("#cbank").attr('required', !0);
        $("#paymentmode").attr('required', !0)
    }
});
$(document).on('click', '#closepay', function() {
    $(".chequeDetails").hide();
    $(".challandetails").hide()
});
$(document).on('click', '.chequerev', function() {
    $(".stdidforcheque").val($(this).attr('id'))
});
if ($('.chequerevoke').length > 0) {
    document.getElementById("chequerevokesubmit").disabled = !0
}
$(document).on('click', '#chequerevokecheck', function() {
    document.getElementById("chequerevokesubmit").disabled = !1
});
$(document).on('click', '.feegroupcheck', function() {
    var feegroup = $("input[name='feegroupradio']:checked").val();
    $(".feegroupradio").val(feegroup);
    var cnum = $.trim($('.cnum').val());
    $.ajax({
        url: 'adminactions.php',
        method: 'post',
        dataType: 'json',
        data: {
            'getfeegroupamount': 'new',
            'cno': cnum,
            'feegroup': feegroup
        },
        success: function(response) {
            console.log(response);
            $('#amount').val(response)
        },
    })
});
$(document).on("click", ".viewChallanModal", function() {
    var challanNo = $(this).data('challan');
    var studId = this.id;
    var selectedFeeType = $("#sfeetype").val();
    console.log(this);
    $(".pageloader").show();
    $.ajax({
        url: 'adminactions.php',
        method: 'post',
        dataType: 'json',
        data: {
            'submit': 'viewChallanUpdatedData',
            'studId': studId,
            'challanNo': challanNo,
            'feetype': selectedFeeType
        },
        success: function(response) {
            $(".pageloader").hide();
            console.log(response);
            $("#viewchallanData").html('');
            if (response == 'already exist') {
                $("#viewchallanData").html('<p class="error-msg">Selected feetype is already exists for this challan.</p>');
                $("#updatec").hide()
            } else {
                var html = '<input type="hidden" name="studId" id="studId" value="' + studId + '"><input type="hidden" name="sem" id="sem" value="' + response.term + '"><input type="hidden" name="class" id="class" value="' + response.clid + '"><input class="grand_tot" type="hidden" name="grand_tot"/><input type="hidden" name="challanNo" id="challanNo" value="' + response.challanNo + '"><input  type="hidden" name="feetype" value="' + selectedFeeType + '" /><input type="hidden" name="challanNo" id="challanNo" value="' + response.challanNo + '"><input type="hidden" name="stream"  value="' + response.stream + '"/><input type="hidden" name="sfsextrautilities" id="sfsutilitiesinput" /><input type="hidden" name="schoolextrautilities" id="schoolutilitiesinput" /><input type="hidden" name="sfsextrautilitiesamount" id="sfsutilitiesinputamount"/><input type="hidden" name="schoolextrautilitiesamount" id="schoolutilitiesinputamount"/><input type="hidden" name="sfsextrautilitiesqty" id="sfsutilitiesinputqty"/><input type="hidden" name="schoolextrautilitiesqty" id="schoolutilitiesinputqty"/><table class="table table-striped"><tr><td colspan="2"><label> School Name: </label> LMOIS - ' + response.steamname + '</td></tr><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Semester: </label> ' + response.term + '</td></tr><tr><td><label>ID: </label> ' + response.studentId + '</td><td><label>Class: </label> ' + response.class_list + '</td></tr><tr><td colspan="2">';
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
                var feegroups = [];
                $.each(response.feeData, function(key, value) {
                    feegroups.push($.trim(key))
                });
                html += '<input type="hidden" name="tot" id="tot" value="' + amount + '" /> ';
                $(".grand_tot").val(amount);
                html += '<tr><td></td><td colspan ="2"><p class="tot" >Grand Total: <span id="grand_tot">' + amount + '</span></p></td></tr></table><tr><td><strong>Remarks: </strong></td><td>' + response.remarks + '</td></tr></table>';
                $("#viewchallanData").append(html)
            }
        }
    })
});
$("#getStudentData").submit(function(e) {
    e.preventDefault();
    var chlno = $("#chlnno").val();
    $.ajax({
        url: 'adminactions.php',
        method: 'post',
        dataType: 'json',
        data: {
            'submit': 'getStudData',
            'chlno': chlno
        },
        success: function(response) {
            $(".errormessage").html('');
            if (response != null) {
                var html = '<table class="table borderless"><tr><td><label>Name: </label> ' + response.studentName + '</td><td><label>Semester: </label> ' + response.term + '</td></tr><tr><td><label>ID: </label> ' + response.studentId + '</td><td><label>Class: </label> ' + response.class_list + '-' + response.section + '</td></tr><tr></tr><tr><td colspan="2"><form method="post" class="form-horizontal" action="adminactions.php" ><input type="hidden" name="studId" value="' + response.studentId + '"><input type="hidden" name="chlno" value="' + chlno + '"><div class="form-group"><label class="control-label col-sm-3" for="pwd">FeeTypes: </label><div class="col-sm-9"><select name="feetype" id="sfeetype" class="form-control"><option value="">--Select--</option>';
                $.each(response.feeData, function(i, val) {
                    html += '<option value=' + val.id + '>' + $.trim(val.feeType) + '</option>'
                });
                html += '</select></div></div><div class="form-group"><div class="text-center"><a href="#viewChallanModal" data-challan="' + chlno + '" class="btn btn-primary viewChallanModal" id="' + response.studentId + '" data-toggle="modal" data-target="#viewChallanModal">Create</a></div></div></form></td><tr>';
                $("#studentData").html(html)
            } else {
                $("#studentData").html('');
                $(".errormessage").html('<p class="error-msg">Invalid Challan Number!!!</p>')
            }
        }
    })
});
$(document).on("click", "#commonnonfeetype", function() {
    $("#noforchallaninnonfee").prop("checked", !0)
});

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
    if ((phone.value.length <= 7) || (phone.value.length > 11)) {
        err_msg = "Please Enter Valid Phone Number";
        document.getElementById("ok").disabled = !0
    } else {
        document.getElementById("ok").disabled = !1
    }
    return err_msg
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
    $(".total").val(total)
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
$(".studID_input").hide();

function chkSendType() {
    var sendType = $("#sendType").val();
    if (sendType == '4') {
        $(".studID_input").show();
        $("#studId").attr("required", "required")
    } else {
        $(".studID_input").hide();
        $("#studId").removeAttr("required")
    }
}

function chkMSendType() {
    var sendType = $("#msendType").val();
    if (sendType == '4') {
        $(".studID_input").show();
        $("#studId").attr("required", "required")
    } else {
        $(".studID_input").hide();
        $("#studId").removeAttr("required")
    }
}

function loadfilterdata() {
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'submit': 'loadfilterdata'
        },
        success: function(response) {
            console.log(response);
            $.each(response, function(i, row) {
                if (i == 0) {
                    $.each(row, function(i, stream) {
                        $(".stream").append("<option value='" + stream.id + "'>" + stream.stream + "</option>")
                    })
                }
                if (i == 1) {
                    $.each(row, function(i, sem) {
                        $(".semester").append("<option>" + sem.semester + "</option>")
                    })
                }
                if (i == 2) {
                    var feetype = new Array();
                    $.each(row, function(i, fee) {
                        $(".feetype").append("<option value='" + fee.id + "'>" + fee.feeType + "</option>");
                        feetype.push(fee.feeType.id)
                    });
                    $('.classlistfee').val(feetype)
                }
                if (i == 4) {
                    $.each(row, function(i, year) {
                        $(".ayear").append("<option value='" + year.id + "'>" + year.year + "</option>")
                    })
                }
            })
        }
    })
}
$("#filtertopupchallans").submit(function(e) {
    e.preventDefault();
    var classselect = $(this).find(".classselect option:selected").val();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filtertopupchallan',
            'classselect': classselect,
            'streamselect': streamselect,
            'sectionselect': sectionselect
        },
        success: function(response) {
            $(".pageloader").hide();
            var myTable = $('.dataTableTopup').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    var challanno = 'CARDTOPUP' + item.tpid + '/' + item.studentId;
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.academic_yr);
                    result.push(item.steamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(challanno);
                    result.push(item.amount);
                    result.push(item.createdOn);
                    result.push(item.adminName);
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
})
$("#filterdemandreport").submit(function(e) {
    e.preventDefault();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var yearselect = $(this).find(".yearselect option:selected").val();
    var feetypeselect = $(this).find(".feetypeselect option:selected").val();
    var feegroupselect = $(this).find(".feegroupselect option:selected").val();
    var paiddetailsselect = $(this).find(".paidselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterdemandreport',
            'yearselect': yearselect,
            'streamselect': streamselect,
            'feetypeselect': feetypeselect,
            'feegroupselect': feegroupselect,
            'paidselect': paiddetailsselect
        },
        success: function(response) {
            $(".pageloader").hide();
            var myTable = $('.dataTableDemandReport').DataTable();
            if (response != null) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push('<a href="#demandreportviewmodal" data-id="' + item.studentId + '"  class="demandreportview" id="' + item.challanNo + '" data-toggle="modal" data-target="#demandreportviewmodal"><i class="fa fa-2x fa-eye"></i></a>');
                    result.push(item.academicYear);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.challanNo);
                    result.push(item.streamName);
                    result.push(item.challanStatus);
                    result.push(item.feeGroup);
                    result.push(item.feeType);
                    result.push(item.total);
                    result.push(item.createdOn);
                    result.push(item.remarks);
                    result.push(item.duedate);
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$("#filterreceiptreport").submit(function(e) {
    e.preventDefault();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var yearselect = $(this).find(".yearselect option:selected").val();
    var semesterselect = $(this).find(".semesterselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterreceiptreport',
            'streamselect': streamselect,
            'yearselect': yearselect,
            'semesterselect': semesterselect,
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableReceiptReport').DataTable();
            if (response != 0) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.studentId);
                    result.push(item.studentName);
                    result.push(item.stream);
                    result.push('<a href="receiptreportview.php?id=' + item.studentId + '"><i class="fa fa-2x fa-eye"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
})
$("#filterreceiptreportview").submit(function(e) {
    e.preventDefault();
    var yearselect = $(this).find(".yearselect option:selected").val();
    var semesterselect = $(this).find(".semesterselect option:selected").val();
    var studentid = $('#studentid').val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filter': 'filterreceiptreportview',
            'studentid': studentid,
            'yearselect': yearselect,
            'semesterselect': semesterselect,
        },
        success: function(response) {
            console.log(response);
            if (response != null) {
                $(".pageloader").hide();
                window.location.href = "receiptreportview.php?id=" + studentid + ""
            } else {
                $(".pageloader").hide();
                $('.contentreport').html('<p class="error-msg">No Data Available!!!</p>')
            }
        }
    })
});

function confirmDelete(type) {
    return confirm("Are you sure you want to delete this " + type + "?")
}

function print(doc) {
    var objFra = document.createElement('iframe');
    objFra.style.visibility = "hidden";
    objFra.src = doc;
    document.body.appendChild(objFra);
    objFra.contentWindow.focus();
    objFra.contentWindow.print()
}
$('.tax_exemption_form').on('submit', function() {
    event.preventDefault();
    var student_id = $('.student_id_get').val();
    var academic_year = $('.academic_year').val();
    var amount = $('.amount').val();
    $.ajax({
        url: 'adminactions.php',
        dataType: 'json',
        method: 'post',
        data: {
            'preview_tax_exemption': 'preview_tax_exemption',
            'student_id': student_id,
            'academic_year': academic_year,
            'amount': amount,
        },
        success: function(response) {
            if (response != "") {
                $('.tax_preview_modal').html(response);
                $('.download_tax_exemption').attr('disabled', !1);
                $('.print_tax_exemption').attr('disabled', !1)
            } else {
                $('.tax_preview_modal').html('<p class="error-msg">Please Enter Valid Student ID</p>');
                $('.download_tax_exemption').attr('disabled', !0);
                $('.print_tax_exemption').attr('disabled', !0)
            }
        }
    });
    $('.preview_modal').modal('show')
});
$(document).on('click', '.download_tax_exemption', function() {
    event.preventDefault();
    var student_id = $('.student_id_get').val();
    var academic_year = $('.academic_year').val();
    var amount = $('.amount').val();
    var flag_value = $('.flag_value').val();
    var row_id = $('.row_id').val();
    $(".pageloader").show();
    $.ajax({
        url: 'adminactions.php',
        dataType: 'json',
        method: 'post',
        data: {
            'generate_tax_exemption': 'generate_certificate',
            'student_id': student_id,
            'academic_year': academic_year,
            'amount': amount,
            'flag_value': flag_value,
            'row_id': row_id
        },
        success: function(response) {
            $(".pageloader").hide();
            console.log(response);
            location.reload()
        }
    })
});
$(document).on('click', '.edit_amt', function() {
    var id = $(this).attr('id');
    $.ajax({
        url: 'adminactions.php',
        dataType: 'json',
        method: 'post',
        data: {
            'edit_amount_tax_exemption': 'edit_amount_tax_exemption',
            'id': id
        },
        success: function(response) {
            console.log(response);
            if (response != null) {
                $.each(response, function(i, row) {
                    if (i == 'academic_year') {
                        $('.academic_year').val(row)
                    }
                    if (i == 'amount') {
                        $('.amount').val(row)
                    }
                    if (i == 'student_id') {
                        $('.student_id_get').val(row)
                    }
                });
                $('#modal_edit').modal('show');
                $('.row_id').val(id)
            }
        }
    })
});
$(document).on('click', '.delete_entry', function() {
    var id = $(this).attr('id');
    $.ajax({
        url: 'adminactions.php',
        dataType: 'json',
        method: 'post',
        data: {
            'delete_entry': 'delete_entry',
            'id': id
        },
        success: function(response) {
            console.log(response);
            location.reload()
        }
    })
});
$("#tax_exemption_filter").submit(function(e) {
    e.preventDefault();
    var streamselect = $(this).find(".streamselect option:selected").val();
    var classselect = $(this).find(".classselect option:selected").val();
    var sectionselect = $(this).find(".sectionselect option:selected").val();
    $(".pageloader").show();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'filtertaxexemption': 'filtertaxexemption',
            'streamselect': streamselect,
            'classselect': classselect,
            'sectionselect': sectionselect,
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableTaxExemption').DataTable();
            if (response != 0) {
                myTable.clear();
                var i = 1;
                var result = response.map(function(item) {
                    var result = [];
                    result.push(i);
                    result.push(item.student_id);
                    result.push(item.studentName);
                    result.push(item.streamname);
                    result.push(item.class_list);
                    result.push(item.section);
                    result.push(item.amount);
                    result.push(item.userName);
                    console.log(item.pdf_url);
                    result.push('<a class="edit_amt" id="' + item.id + '"><i class="fa fa-edit"></i></a> <a href="' + item.pdf_url + '" target = "_blank"><i class="fa fa-eye"></i></a> <a onclick="print(\'' + item.pdf_url + '\')"><i class="fa fa-print"></i></a> <a class="delete_entry" id="' + item.id + '"><i class="fa fa-trash-o"></i></a>');
                    i++;
                    result.push("");
                    return result
                });
                myTable.rows.add(result);
                myTable.draw()
            } else {
                myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                myTable.clear().draw()
            }
        }
    })
});
$('.btn-toggle').click(function() {
    $(this).find('.btn').toggleClass('active');
    if ($(this).find('.btn-primary').length > 0) {
        $(this).find('.btn').toggleClass('btn-primary');
        var selected = $(this).find('.btn-primary').val();
        $(".pageloader").show();
        $.ajax({
            type: 'post',
            url: 'adminactions.php',
            dataType: 'json',
            data: {
                'filter': 'nonfeetoggle',
                'selected': selected
            },
            success: function(response) {
                console.log(response);
                $(".pageloader").hide();
                var myTable = $('.dataTableNonfeePaid').DataTable();
                if (response != 0) {
                    if (selected == 'non-fee') {
                        $(".btnexcel").attr('href', 'adminactions.php?excel=nonfeepaid&common=0');
                        $("#commonfee").val('0')
                    } else {
                        $(".btnexcel").attr('href', 'adminactions.php?excel=nonfeepaid&common=1');
                        $("#commonfee").val('1')
                    }
                    myTable.clear();
                    var i = 1;
                    var result = response.map(function(item) {
                        var result = [];
                        result.push(i);
                        result.push(item.studentId);
                        result.push(item.challanNo);
                        result.push(item.studentName);
                        result.push(item.steamname);
                        result.push(item.class_list);
                        result.push(item.section);
                        result.push(item.term);
                        result.push(item.feename);
                        result.push(item.total);
                        result.push(item.createdOn);
                        result.push(item.updatedOn);
                        i++;
                        result.push("");
                        return result
                    });
                    myTable.rows.add(result);
                    myTable.draw()
                } else {
                    myTable.context[0].oLanguage.sEmptyTable = "No matching records found...";
                    myTable.clear().draw()
                }
            }
        })
    }
    $(this).find('.btn').toggleClass('btn-default')
});
var x_timer;
$("#ledgerstudentid").keyup(function(e) {
    clearTimeout(x_timer);
    var stdid = $(this).val();
    x_timer = setTimeout(function() {
        $.ajax({
            type: 'post',
            url: 'adminactions.php',
            dataType: 'json',
            data: {
                'findchallanforstdid': 'findchallanforstdid',
                'stdid': stdid
            },
            success: function(response) {
                $('.challanthereornot').html("")
                if (response != 0) {
                    var options = '<option value="">Select Challan No</option>';
                    $.each(response, function(i, val) {
                        options += '<option value="' + val + '">' + val + '</option>'
                    });
                    $(".challanselect").html(options)
                } else {
                    $('.challanthereornot').html('<p class="error-msg">Enter Correct Student ID!!!</p>')
                }
            }
        })
    }, 1000)
});
$('.challanselect').change(function() {
    var challannumber = $('.challanselect').find("option:selected").val();
    $.ajax({
        type: 'post',
        url: 'adminactions.php',
        dataType: 'json',
        data: {
            'getstudentledger': 'getstudentledger',
            'challannumber': challannumber
        },
        success: function(response) {
            console.log(response);
            $(".pageloader").hide();
            var myTable = $('.dataTableNonfeePaid').DataTable();
            if (response != 0) {
                studentname = response[0].studentName;
                acayear = response[0].academicYear;
                sem = response[0].term;
                classsection = response[0].class;
                status = response[0].challanStatus;
                $(".ldgstdname").val(studentname);
                $(".ldgacayear").val(acayear);
                $(".ldgsem").val(sem);
                $(".ldgclass").val(classsection);
                if (status == '1') {
                    $("#active").prop("checked", !0)
                } else {
                    $("#inactive").prop("checked", !0)
                }
            }
        }
    })
});
$("#ledgermodal").on("hidden.bs.modal", function() {
    $('.ledgerstudentid').val("");
    $('.ldgstdname').val("");
    $('.ldgacayear').val("");
    $('.ldgsem').val("");
    $('.ldgclass').val("");
    $(".challanselect").val("");
    $("#active").prop("checked", !1);
    $("#inactive").prop("checked", !1)
});
$(document).on('click', '.ledgerreportexportexcel', function() {
    if (!$('.ledgerreportexportexcel').is('[disabled=disabled]')) {
        $(".ledgerreportexportexcel").attr("href", "adminactions.php?ledgerreport=exportexcel")
    } else {
        event.preventDefault();
        $(".ledgerreportexportexcel").attr("href", "")
    }
});
$(document).on('click', '.studentledgerexportexcel', function() {
    if (!$('.studentledgerexportexcel').is('[disabled=disabled]')) {
        $(".studentledgerexportexcel").attr("href", "adminactions.php?studentledger=exportexcel")
    } else {
        event.preventDefault();
        $(".studentledgerexportexcel").attr("href", "")
    }
})
$(document).on('click', '.ledgerreportcoloumnwiseexportexcel', function() {
    if (!$('.ledgerreportcoloumnwiseexportexcel').is('[disabled=disabled]')) {
        $(".ledgerreportcoloumnwiseexportexcel").attr("href", "adminactions.php?ledgerreportcolumnwise=exportexcel")
    } else {
        event.preventDefault();
        $(".ledgerreportcoloumnwiseexportexcel").attr("href", "")
    }
})

$(document).on('change','.entrytype',function() {
    if($('.entrytype').val() == ''){
        alert("After choosing all the parameters please click filter button for refreshing the data");
        $(".ledgerreportcoloumnwiseexportexcel").show();
    }
    else{
        $(".ledgerreportcoloumnwiseexportexcel").hide();
    }
    
});