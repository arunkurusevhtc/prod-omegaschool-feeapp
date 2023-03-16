<?php
VIEWS AND FUNCTION IN OMEGA FEE APP TILL 30.08.2019

/********VIEWS - Start*******/

/***View for addsemesterdata - Start ****/
CREATE VIEW addsemesterdata AS  SELECT 
	tbl_semester.id, 
	tbl_semester.semester 
FROM tbl_semester;
/***View for addsemesterdata - End ****/

/***View for admbloodgrpcheck - Start ****/
CREATE VIEW admbloodgrpcheck AS SELECT
    CASE
        WHEN tbl_blood_group.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_blood_group.id,
    tbl_blood_group.blood_group
FROM tbl_blood_group
WHERE tbl_blood_group.deleted = 0
ORDER BY tbl_blood_group.id;
/***View for admbloodgrpcheck - End ****/

/***View for admboardcheck - Start ****/
CREATE VIEW admboardcheck AS SELECT
    CASE
        WHEN tbl_board.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_board.id,
    tbl_board.board_name,
    tbl_board.stream_id
FROM tbl_board
WHERE tbl_board.deleted = 0
ORDER BY tbl_board.stream_id;
/***View for admboardcheck - End ****/


/***View for admclasscheck - Start ****/
CREATE VIEW admclasscheck AS SELECT
    CASE
        WHEN tbl_class.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_class.id,
    tbl_class.class_list,
    tbl_class.description,
    tbl_class."streamId",
    tbl_class.admission,
    tbl_class.openings,
    tbl_class.allowed_application
FROM tbl_class
WHERE tbl_class.deleted = 0
ORDER BY tbl_class.id;
/***View for admclasscheck - End ****/

/***View for admgroupcheck - Start ****/
CREATE VIEW admgroupcheck AS SELECT
    CASE
        WHEN g.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    g.id,
    gs.group_id,
    gs.subject_id,
    gs."createdOn",
    gs."createdBy",
    gs."updatedOn",
    gs."updatedBy",
    g.deleted,
    g.group_name,
    g.stream_id,
    s.subject_name,
    st.stream
FROM tbl_group_subject gs
LEFT JOIN tbl_admission_group g ON gs.group_id = g.id
LEFT JOIN tbl_admission_subject s ON gs.subject_id = s.id
LEFT JOIN tbl_stream st ON g.stream_id = st.id
WHERE gs.status = '1'::status AND g.deleted = 0;
/***View for admgroupcheck - End ****/

/***View for admincheck - Start ****/
CREATE VIEW admincheck AS SELECT
    CASE
        WHEN tbl_admin.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
	tbl_admin.id,
	tbl_admin."adminName",
	tbl_admin."adminEmail",
	tbl_admin."adminPassword"
FROM tbl_admin
WHERE tbl_admin.deleted = 0
ORDER BY tbl_admin."adminName";
/***View for admincheck - End ****/

/***View for adminchk - Start ****/
CREATE VIEW adminchk AS  SELECT 
	tbl_admin.id,
    tbl_admin."adminEmail",
    tbl_admin."adminPassword",
    tbl_admin."adminName",
    tbl_admin.deleted,
    tbl_admin."createdBy",
    tbl_admin."updatedBy",
    tbl_admin.status,
    tbl_admin."updatedOn",
    tbl_admin."createdOn"
FROM tbl_admin;
/***View for adminchk - End ****/

/***View for admintlangcheck - Start ****/
CREATE VIEW admintlangcheck AS  SELECT
    CASE
        WHEN tbl_interaction_language.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_interaction_language.id,
    tbl_interaction_language.language,
    tbl_interaction_language.stream_id,
    tbl_interaction_language.class_id
FROM tbl_interaction_language
WHERE tbl_interaction_language.deleted = 0
ORDER BY tbl_interaction_language.id;
/***View for admintlangcheck - End ****/

/***View for admissionadminchk - Start ****/
CREATE VIEW admissionadminchk AS  SELECT
    CASE
        WHEN tbl_admission_admin.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_admission_admin.id,
    tbl_admission_admin."adminName",
    tbl_admission_admin."adminEmail",
    tbl_admission_admin."adminPassword",
    tbl_admission_admin.role
FROM tbl_admission_admin
WHERE tbl_admission_admin.deleted = 0
ORDER BY tbl_admission_admin."adminName";
/***View for admissionadminchk - End ****/

/***View for admlangcheck - Start ****/
CREATE VIEW admlangcheck AS  SELECT
    CASE
        WHEN tbl_interaction_language.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_interaction_language.id,
    tbl_interaction_language.language,
    tbl_interaction_language.stream_id,
    tbl_interaction_language.class_id
FROM tbl_interaction_language
WHERE tbl_interaction_language.deleted = 0
ORDER BY tbl_interaction_language.language;
/***View for admlangcheck - End ****/

/***View for admstreamcheck - Start ****/
CREATE VIEW admstreamcheck AS  SELECT
    CASE
        WHEN tbl_stream.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_stream.id,
    tbl_stream.stream,
    tbl_stream.description,
    tbl_stream."notifyEmail"
FROM tbl_stream
WHERE tbl_stream.deleted = 0
ORDER BY tbl_stream.stream;
/***View for admstreamcheck - End ****/

/***View for admsubjectcheck - Start ****/
CREATE VIEW admsubjectcheck AS  SELECT
    CASE
        WHEN tbl_admission_subject.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_admission_subject.id,
    tbl_admission_subject.subject_name
FROM tbl_admission_subject
WHERE tbl_admission_subject.deleted = 0
ORDER BY tbl_admission_subject.id;
/***View for admsubjectcheck - End ****/

/***View for applicantchk - Start ****/
CREATE VIEW applicantchk AS SELECT 
	a.id,
    a.applicant_id,
    a.academic_year_id,
    a.class_id,
    a.child_name,
    a.dob,
    a.age,
    a.gender,
    a.application_issued_date,
    a.religion_id,
    a.income,
    a.caste_id,
    a.father_name,
    a.father_occupation,
    a.father_company,
    a.father_mobile_no,
    a.father_email,
    a.mother_name,
    a.mother_occupation,
    a.mother_company,
    a.mother_mobile_no,
    a.mother_email,
    a.guardian_name,
    a.guardian_occupation,
    a.guardian_company,
    a.guardian_mobile_no,
    a.guardian_email,
    a.email,
    a.mobile_no,
    a.address,
    a.city,
    a.phone_no,
    a.created_by,
    a.created_on,
    a.modified_by,
    a.modified_on,
    a.second_language_id,
    a.visa_details,
    a.fathers_qualification,
    a.mothers_qualification,
    a.street1,
    a.street2,
    a.state,
    a.country,
    a.nationality_id,
    a.aadhar_card_no,
    a.passport_no,
    a.valid_until,
    a.allergic_to,
    a.medical_condtion,
    a.ad_sources_id,
    a.ssn,
    a.transporation_need,
    a.hostel_need,
    a.access_card_number,
    a.pickup_point,
    a.drop_point,
    a.lunch,
    a.old_student_id,
    a.remarks,
    a.doj,
    a.joiningstandard,
    a.sorting_name,
    a.photo_upload,
    a.activate_deactivate_remarks,
    a.stream_id,
    a.locality,
    a.distance,
    a.pin_code,
    a.mother_tongue,
    a.old_student_id2,
    a.appl_status,
    a.child_firstname,
    a.child_middlename,
    a.child_lastname,
    a.guardian_qualification,
    a.employee_id,
    ap."transStatus",
    ap.amount,
    ap."transDate",
    ac.year,
    ap."applicationId",
    a.sent_notification,
    s.stream,
    c.class_list,
    ap.id AS apid,
    cy.name AS cityname,
    st.name AS statename,
    ct.name AS countryname
FROM tbl_applicant a
LEFT JOIN tbl_application_payments ap ON a.applicant_id::text = ap."applicationId"::text
LEFT JOIN tbl_stream s ON s.id = a.stream_id
LEFT JOIN tbl_class c ON c.id = a.class_id
LEFT JOIN tbl_state st ON st.id = a.state::integer
LEFT JOIN tbl_country ct ON ct.id = a.country::integer
LEFT JOIN tbl_city cy ON cy.id = a.city::integer
LEFT JOIN tbl_academic_year ac ON ac.id = a.academic_year_id;
/***View for applicantchk - End ****/

/***View for challandata - Start ****/
CREATE VIEW challandata AS  SELECT 
	s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c."feeType",
    c.id AS cid,
    c."studStatus",
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.org_total,
    c."feeGroup",
    c.paid_date,
    c.cheque_dd_no,
    c.pay_type,
    c."academicYear",
    c."updatedOn",
    s.academic_yr,
    c.bank
FROM tbl_student s
LEFT JOIN tbl_challans c ON s."studentId"::bpchar = c."studentId"::bpchar OR s.application_no::bpchar = c."studentId"::bpchar
LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term::bpchar
LEFT JOIN tbl_class cl ON c."classList" = cl.id
LEFT JOIN tbl_stream str ON s.stream::integer = str.id
WHERE c.deleted = 0 AND c.status = '1'::status AND s.deleted = 0 AND s.status = '1'::status;
/***View for challandata - End ****/

/***View for challandatanew - Start ****/
CREATE VIEW challandatanew AS  SELECT 
DISTINCT 
	c."feeType",
    s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c.id AS cid,
    c."studStatus",
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.org_total,
    c."feeGroup",
    c.cheque_dd_no,
    c.pay_type,
    c.paid_date,
    s.academic_yr,
    c.bank,
    c.remarks,
    s.hostel_need,
    c."academicYear",
    c.total
FROM tbl_student s
LEFT JOIN tbl_challans c ON s."studentId"::bpchar = c."studentId"::bpchar OR s.application_no::bpchar = c."studentId"::bpchar
LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term::bpchar
LEFT JOIN tbl_class cl ON c."classList" = cl.id
LEFT JOIN tbl_stream str ON s.stream::integer = str.id
WHERE c.deleted = 0 AND c.status = '1'::status;
/***View for challandatanew - End ****/

/***View for chequedddata - Start ****/
CREATE VIEW chequedddata AS  SELECT 
	c.id,
    c."challanNo",
    c."studentId",
    c."feeType",
    c."classList",
    c.term,
    c."studStatus",
    c."createdOn",
    c."createdBy",
    c."updatedOn",
    c."updatedBy",
    c.status,
    c.deleted,
    c."challanStatus",
    c.total,
    c.org_total,
    c.stream,
    c.remarks,
    c.duedate,
    c.pay_type,
    c.bank,
    c.cheque_dd_no,
    c."feeGroup",
    c.paid_date,
    c."academicYear",
    st."studentName",
    cl.class_list,
    s.stream AS streamname,
    st.section
FROM tbl_challans c
LEFT JOIN tbl_class cl ON c."classList" = cl.id
LEFT JOIN tbl_stream s ON c.stream = s.id
LEFT JOIN tbl_student st ON c."studentId"::bpchar = st."studentId"::bpchar;
/***View for chequedddata - End ****/

/***View for classcheck  - Start ****/
CREATE VIEW classcheck AS  SELECT
    CASE
        WHEN tbl_class.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_class.id,
    tbl_class.class_list,
    tbl_class.description,
    tbl_class."streamId"
FROM tbl_class
WHERE tbl_class.deleted = 0
ORDER BY tbl_class.id;
/***View for classcheck  - End ****/

/***View for commentscheck  - Start ****/
CREATE VIEW commentscheck AS  SELECT
    CASE
        WHEN tbl_comments.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_comments.id,
    tbl_comments."pageName",
    tbl_comments.comments,
    tbl_comments.startdate,
    tbl_comments.enddate
FROM tbl_comments
WHERE tbl_comments.deleted = 0
ORDER BY tbl_comments.id;
/***View for commentscheck  - End ****/

/***View for feeconigdata   - Start ****/
CREATE VIEW feeconigdata AS  SELECT 
	ac.year AS "academicYear",
    f.id,
    t."feeType",
    s.stream,
    c.class_list,
    f.amount,
    f.semester,
    f."dueDate"
FROM tbl_fee_configuration f
LEFT JOIN tbl_fee_type t ON f."feeType"::integer = t.id
LEFT JOIN tbl_stream s ON f.stream::integer = s.id
LEFT JOIN tbl_academic_year ac ON ac.id = f."academicYear"::integer
LEFT JOIN tbl_class c ON f.class = c.id
WHERE f.deleted = 0;
/***View for feeconigdata   - End ****/

/***View for feegroupcheck   - Start ****/
CREATE VIEW feegroupcheck AS  SELECT
    CASE
        WHEN tbl_fee_group.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_fee_group.id,
    tbl_fee_group."feeGroup",
    tbl_fee_group.description,
    tbl_fee_group.product
FROM tbl_fee_group
WHERE tbl_fee_group.deleted = 0
ORDER BY tbl_fee_group.id;
/***View for feegroupcheck   - End ****/

/***View for feetypecheck   - Start ****/
CREATE VIEW feetypecheck AS  SELECT
    CASE
        WHEN t.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    t.id,
    t."feeType",
    t.description,
    t.tax,
    g."feeGroup",
    t.mandatory,
    t.applicable
FROM tbl_fee_type t
JOIN tbl_fee_group g ON t."feeGroup"::integer = g.id
WHERE t.deleted = 0
ORDER BY t.id;
/***View for feetypecheck   - End ****/

/***View for fetchstudentdata   - Start ****/
CREAET VIEW fetchstudentdata AS  SELECT 
	tbl_student.id,
    tbl_student."studentName",
    tbl_student."studentId",
    tbl_student."parentId",
    tbl_student.class,
    tbl_student.section,
    tbl_student.term,
    tbl_student.status,
    tbl_student.deleted,
    tbl_student."createdOn",
    tbl_student."updatedOn",
    tbl_student."createdBy",
    tbl_student."updatedBy",
    tbl_student.stream,
    tbl_student."mobileNumber",
    tbl_student.email,
    tbl_student.hostel_need,
    tbl_student.transport_need,
    tbl_student.academic_yr,
    tbl_student.lunch_need
   FROM tbl_student
  WHERE tbl_student.deleted = 0 AND tbl_student.status = '1'::status;
/***View for fetchstudentdata   - End ****/

/***View for filterfeedetails   - Start ****/
CREATE VIEW filterfeedetails AS  SELECT 
	f."academicYear",
    f.id,
    t."feeType",
    s.stream,
    c.class_list,
    f.amount,
    f.semester,
    f."dueDate",
    s.id AS strid,
    c.id AS clid,
    ac.year
FROM tbl_fee_configuration f
LEFT JOIN tbl_fee_type t ON f."feeType"::integer = t.id
LEFT JOIN tbl_stream s ON f.stream::integer = s.id
LEFT JOIN tbl_class c ON f.class = c.id
LEFT JOIN tbl_academic_year ac ON ac.id = f."academicYear"::integer
WHERE f.deleted = 0;
/***View for filterfeedetails   - End ****/

/***View for filterstudentdetails   - Start ****/
CREATE VIEW filterstudentdetails AS  SELECT
    CASE
        WHEN s.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    s.id,
    s."studentName",
    s.class,
    c.class_list,
    str.stream AS streamname,
    s.section,
    s."studentId",
    s.term,
    p."userName",
    s.stream
FROM tbl_student s
LEFT JOIN tbl_class c ON c.id = s.class::integer
LEFT JOIN tbl_stream str ON str.id = s.stream::integer
LEFT JOIN tbl_parents p ON s."parentId" = p.id
WHERE s.deleted = 0
ORDER BY s."studentName";
/***View for filterstudentdetails   - End ****/

/***View for getchallandata   - Start ****/
CREATE VIEW getchallandata AS  SELECT 
	c."challanNo",
    c."classList",
    c.stream,
    c.term,
    c.duedate,
    c.total,
    s.section,
    cl.class_list,
    str.stream AS streamname,
    s."studentName",
    c."feeType",
    c.org_total,
    c."createdOn",
    c."feeGroup",
    c."studentId",
    c."challanStatus",
    c.pay_type
FROM tbl_challans c
LEFT JOIN tbl_student s ON c."studentId"::bpchar = s."studentId"::bpchar
LEFT JOIN tbl_class cl ON cl.id = c."classList"
LEFT JOIN tbl_stream str ON str.id = c.stream;
/***View for getchallandata   - End ****/

/***View for getchallandatanew    - Start ****/
CREATE VIEW getchallandatanew AS  SELECT 
	c."challanNo",
    c."classList",
    c.stream,
    c.term,
    c.duedate,
    c.total,
    s.section,
    cl.class_list,
    str.stream AS streamname,
    s."studentName",
    c."feeType",
    c.org_total,
    c."createdOn",
    c."studentId",
    c."feeGroup",
    c."challanStatus",
    c.pay_type,
    c."academicYear"
FROM tbl_challans c
LEFT JOIN tbl_student s ON c."studentId"::bpchar = s."studentId"::bpchar OR c."studentId"::bpchar = s.application_no::bpchar
LEFT JOIN tbl_class cl ON cl.id = c."classList"
LEFT JOIN tbl_stream str ON str.id = c.stream;
/***View for getchallandatanew    - End ****/

/***View for getdemanddata     - Start ****/
CREATE VIEW getdemanddata AS  SELECT 
	d."academicYear",
    y.year,
    d.term,
    d."studentId",
    sd."studentName",
    d."challanNo",
    d.stream,
    s.stream AS "streamName",
    d."feeType" AS feeid,
    f."feeType",
    d."feeGroup" AS feegid,
    fg."feeGroup",
    d."studStatus",
    d."createdOn",
    d."createdBy",
    a."adminName",
    d.total,
    d."challanStatus",
    d.remarks,
    d.duedate
FROM tbl_demand d
LEFT JOIN tbl_stream s ON d.stream = s.id
LEFT JOIN tbl_student sd ON d."studentId"::bpchar = sd."studentId"::bpchar
LEFT JOIN tbl_admin a ON d."createdBy" = a.id
LEFT JOIN tbl_fee_type f ON d."feeType" = f.id
LEFT JOIN tbl_fee_group fg ON d."feeGroup" = fg.id
LEFT JOIN tbl_academic_year y ON d."academicYear" = y.id;
/***View for getdemanddata     - End ****/

/***View for getdemanddatanew     - Start ****/
CREATE VIEW getdemanddatanew AS  SELECT 
	d."academicYear",
    y.year,
    d.term,
    d."studentId",
    sd."studentName",
    d."challanNo",
    d.stream,
    s.stream AS "streamName",
    d."feeType" AS feeid,
    f."feeType",
    d."feeGroup" AS feegid,
    fg."feeGroup",
    d."studStatus",
    d."createdOn",
    d."createdBy",
    a."adminName",
    d.total,
    d."challanStatus",
    d.remarks,
    d.duedate
FROM tbl_demand d
LEFT JOIN tbl_stream s ON d.stream = s.id
LEFT JOIN tbl_student sd ON d."studentId"::bpchar = sd."studentId"::bpchar
LEFT JOIN tbl_admin a ON d."createdBy" = a.id
LEFT JOIN tbl_fee_type f ON d."feeType" = f.id
LEFT JOIN tbl_fee_group fg ON d."feeGroup" = fg.id
LEFT JOIN tbl_academic_year y ON d."academicYear" = y.id;
/***View for getdemanddatanew     - End ****/

/***View for getfeetypedata      - Start ****/
CREATE VIEW getfeetypedata AS  SELECT 
	f.id,
    c."feeType",
    c.amount,
    c.semester,
    c.class,
    c.stream,
    f."feeType" AS feename,
    f."feeGroup",
    c."academicYear"
FROM tbl_fee_configuration c
LEFT JOIN tbl_fee_type f ON f.id = c."feeType"::integer
WHERE f.status = '1'::status AND f.deleted = 0;
/***View for getfeetypedata      - End ****/

/***View for getfeetypes      - Start ****/
CREATE VIEW getfeetypes AS  SELECT 
	f.id,
    f."feeType",
    f.description,
    f."createdBy",
    f."updatedBy",
    f.status,
    f.deleted,
    f."createdOn",
    f."updatedOn",
    f.tax,
    f."feeGroup",
    f.mandatory,
    f.applicable,
    g."feeGroup" AS feegroupname
FROM tbl_fee_type f
JOIN tbl_fee_group g ON f."feeGroup"::integer = g.id
WHERE f.deleted = 0 AND f.status = '1'::status;
/***View for getfeetypes      - End ****/

/***View for getnonfeetypedata      - Start ****/
CREATE VIEW getnonfeetypedata AS  SELECT 
	f.id,
    c."feeType",
    c.amount,
    c.semester,
    c.class,
    c.stream,
    f."feeType" AS feename,
    f."feeGroup",
    c."academicYear",
    f.challan
FROM tbl_nonfee_configuration c
LEFT JOIN tbl_nonfee_type f ON f.id = c."feeType"
WHERE f.status = '1'::status AND f.deleted = 0;
/***View for getnonfeetypedata      - End ****/

/***View for getnonfeetypedata      - Start ****/
CREATE VIEW getpaiddata AS SELECT 	
	p."transStatus",
    p."transDate",
    p."transNum",
    p."studentId",
    s."studentName",
    s.section,
    s.class,
    s.stream,
    p.amount,
    par."userName" AS parentname,
    p.id,
    f."challanNo",
    p.remarks
FROM tbl_payments p
LEFT JOIN tbl_student s ON p."studentId" = s."studentId"::bpchar
LEFT JOIN tbl_variable_fee_entry f ON f."transId"::integer = p.id
LEFT JOIN tbl_parents par ON p."parentId" = par.id;
/***View for getnonfeetypedata      - End ****/

/***View for getpaiddata      - Start ****/
CREATE VIEW getpaiddata AS  SELECT 
	p."transStatus",
    p."transDate",
    p."transNum",
    p."studentId",
    s."studentName",
    s.section,
    s.class,
    s.stream,
    p.amount,
    par."userName" AS parentname,
    p.id,
    f."challanNo",
    p.remarks
FROM tbl_payments p
LEFT JOIN tbl_student s ON p."studentId" = s."studentId"::bpchar
LEFT JOIN tbl_variable_fee_entry f ON f."transId"::integer = p.id
LEFT JOIN tbl_parents par ON p."parentId" = par.id;
/***View for getpaiddata      - End ****/

/***View for getpaiddatafilter      - Start ****/
CREATE VIEW getpaiddatafilter AS  SELECT 
	p."transStatus",
    p."transDate",
    p."transNum",
    p."studentId",
    s."studentName",
    s.section,
    s.class,
    c.class_list,
    str.stream AS streamname,
    s.stream,
    p.amount,
    pr."userName",
    f."challanNo"
FROM tbl_payments p
LEFT JOIN tbl_variable_fee_entry f ON f."transId"::integer = p.id
LEFT JOIN tbl_student s ON p."studentId" = s."studentId"::bpchar
LEFT JOIN tbl_parents pr ON pr.id = p."parentId"
LEFT JOIN tbl_class c ON c.id = s.class::integer
LEFT JOIN tbl_stream str ON str.id = s.stream::integer;
/***View for getpaiddatafilter      - End ****/

/***View for getparentdata      - Start ****/
CREATE VIEW getparentdata AS  SELECT c."challanNo",
    p.id,
    p."userName",
    p.email,
    p."secondaryEmail",
    p."mobileNumber",
    p."secondaryNumber",
    s."studentId",
    c."feeType",
    s."studentName",
    s.stream,
    s.class,
    s.term,
    c."feeGroup",
    s.academic_yr,
    c."academicYear",
    s.status,
    s.deleted
   FROM tbl_parents p
     LEFT JOIN tbl_student s ON p.id = s."parentId"
     LEFT JOIN tbl_challans c ON s."studentId"::bpchar = c."studentId"::bpchar
  WHERE p.status = '1'::status AND p.deleted = 0;
/***View for getparentdata      - End ****/

/***View for getparentdatachallan      - Start ****/
CREATE VIEW getparentdatachallan AS  SELECT 
	c."challanNo",
    p.id,
    p."userName",
    p.email,
    p."secondaryEmail",
    p."mobileNumber",
    p."secondaryNumber",
    s."studentId",
    c."feeType",
    s."studentName",
    s.stream,
    c."classList" AS class,
    c.term,
    c."feeGroup",
    c."academicYear" AS academic_yr
FROM tbl_parents p
LEFT JOIN tbl_student s ON p.id = s."parentId"
LEFT JOIN tbl_challans c ON s."studentId"::bpchar = c."studentId"::bpchar
WHERE p.status = '1'::status AND p.deleted = 0;
/***View for getparentdatachallan      - End ****/

/***View for getpaymentdata      - Start ****/
CREATE VIEW getpaymentdata AS  SELECT 
	f.id,
    f."studentId",
    f."studentName",
    f."academicYear",
    f.semester,
    f.class,
    f.stream,
    s.section,
    c.class_list,
    str.stream AS streamname,
    f.total,
    f."challanNo"
FROM tbl_variable_fee_entry f
LEFT JOIN tbl_student s ON f."studentId" = s."studentId"::bpchar
LEFT JOIN tbl_class c ON c.id = f.class::integer
LEFT JOIN tbl_stream str ON str.id = f.stream::integer
WHERE f.deleted = 0 AND f.status = '1'::status AND f."transId" <> ''::bpchar
ORDER BY f.id DESC;
/***View for getpaymentdata      - End ****/

/***View for getreceiptdata      - Start ****/
CREATE VIEW getreceiptdata AS  SELECT 
	r."academicYear",
    y.year,
    r.term,
    r."studentId",
    sd."studentName",
    r."challanNo",
    r.stream,
    s.stream AS "streamName",
    r."feeType" AS feeid,
    f."feeType",
    r."feeGroup" AS feegid,
    fg."feeGroup",
    r."studStatus",
    r."createdOn",
    r."createdBy",
    r."updatedOn",
    r."updatedBy",
    r.total,
    r."challanStatus",
    r.remarks,
    r.duedate,
    r.pay_type,
    r.paid_date,
    CASE
        WHEN r.paid_date IS NOT NULL THEN r.paid_date
        ELSE date(r."updatedOn")
    END AS paiddate
FROM tbl_receipt r
LEFT JOIN tbl_class cl ON r."classList" = cl.id
LEFT JOIN tbl_stream s ON r.stream = s.id
LEFT JOIN tbl_student sd ON r."studentId"::bpchar = sd."studentId"::bpchar
LEFT JOIN tbl_parents p ON r."updatedBy" = p.id
LEFT JOIN tbl_admin a ON r."createdBy" = a.id
LEFT JOIN tbl_fee_type f ON r."feeType" = f.id
LEFT JOIN tbl_fee_group fg ON r."feeGroup" = fg.id
LEFT JOIN tbl_academic_year y ON r."academicYear" = y.id;
/***View for getreceiptdata      - End ****/

/***View for getreceiptdatanew      - Start ****/
CREATE VIEW getreceiptdatanew AS  SELECT 
	r."academicYear",
    y.year,
    r.term,
    r."studentId",
    sd."studentName",
    r."challanNo",
    r.stream,
    s.stream AS "streamName",
    r."feeType" AS feeid,
    f."feeType",
    r."feeGroup" AS feegid,
    fg."feeGroup",
    r."studStatus",
    r."createdOn",
    r."createdBy",
    r."updatedOn",
    r."updatedBy",
    r.total,
    r."challanStatus",
    r.remarks,
    r.duedate,
    r.pay_type,
    r.paid_date,
    CASE
        WHEN r.paid_date IS NOT NULL THEN r.paid_date
        ELSE date(r."updatedOn")
    END AS paiddate
FROM tbl_receipt r
LEFT JOIN tbl_class cl ON r."classList" = cl.id
LEFT JOIN tbl_stream s ON r.stream = s.id
LEFT JOIN tbl_student sd ON r."studentId"::bpchar = sd."studentId"::bpchar
LEFT JOIN tbl_parents p ON r."updatedBy" = p.id
LEFT JOIN tbl_admin a ON r."createdBy" = a.id
LEFT JOIN tbl_fee_type f ON r."feeType" = f.id
LEFT JOIN tbl_fee_group fg ON r."feeGroup" = fg.id
LEFT JOIN tbl_academic_year y ON r."academicYear" = y.id;
/***View for getreceiptdatanew      - End ****/

/***View for getsfsdata      - Start ****/
CREATE VIEW getsfsdata AS  SELECT 
	s."studentName",
    c."classList" AS classid,
    cl.class_list,
    s.stream AS streamid,
    st.stream AS streamname,
    c.term,
    c."academicYear",
    sq.id AS sfsid,
    c."challanNo",
    c."studentId",
    c."feeType" AS feeid,
    ft."feeType" AS feename,
    sq.quantity,
    sq.amount AS perproduct,
    sq."totalAmount" AS total,
    c."challanStatus",
    c.id AS cid
FROM tbl_challans c
LEFT JOIN tbl_sfs_qty sq ON c."studentId"::text = sq."studentId"::text
LEFT JOIN tbl_student s ON s."studentId"::text = c."studentId"::text OR s.application_no::text = c."studentId"::text
LEFT JOIN tbl_class cl ON cl.id = c."classList"
LEFT JOIN tbl_fee_type ft ON ft.id = c."feeType"
LEFT JOIN tbl_stream st ON st.id = s.stream::integer
WHERE sq."feeTypes"::integer = c."feeType" AND sq."challanNo" = c."challanNo"::bpchar;
/***View for getsfsdata      - End ****/

/***View for getsfsstuddata      - Start ****/
CREATE VIEW getsfsstuddata AS  SELECT 
	s."studentName",
    c."classList" AS classid,
    cl.class_list,
    s.stream AS streamid,
    st.stream AS streamname,
    c.term,
    c."academicYear",
    c."challanNo",
    c."studentId",
    c."challanStatus"
FROM tbl_challans c
LEFT JOIN tbl_student s ON s."studentId"::text = c."studentId"::text OR s.application_no::text = c."studentId"::text
LEFT JOIN tbl_class cl ON cl.id = c."classList"
LEFT JOIN tbl_stream st ON st.id = s.stream::integer
GROUP BY cl.class_list, c."challanNo", s."studentName", c."classList", s.stream, st.stream, c.term, c."academicYear", c."studentId", c."challanStatus";
/***View for getsfsstuddata      - End ****/

/***View for getstudentdata      - Start ****/
CREATE VIEW getstudentdata AS  SELECT 
	s."studentName",
    s."studentId",
    s."parentId",
    c."classList",
    s.section,
    c.term,
    s.status,
    s.deleted,
    s.stream,
    c."challanNo",
    c."feeType",
    c."studStatus",
    c."challanStatus",
    cl.class_list,
    c.org_total,
    c."feeGroup"
FROM tbl_student s
LEFT JOIN tbl_challans c ON s."studentId"::bpchar = c."studentId"::bpchar OR s.application_no::bpchar = c."studentId"::bpchar
LEFT JOIN tbl_class cl ON cl.id = c."classList"
WHERE c."studStatus"::bpchar = 'Prov.Promoted'::bpchar AND s.deleted = 0 AND s.status = '1'::status;
/***View for getstudentdata      - End ****/

/***View for gettaxexemapplied      - Start ****/
CREATE VIEW gettaxexemapplied AS  SELECT 
	t.id,
    t.student_id,
    t.academic_year,
    t.pdf_url,
    t.deleted,
    t.amount,
    t.created_on,
    t.created_by,
    s."studentName",
    s.class,
    s.section,
    s.term,
    s.stream,
    s."studentId",
    s.email,
    s."mobileNumber",
    s."parentId",
    s.transport_stg,
    s.hostel_need,
    s.lunch_need,
    s.academic_yr,
    c.class_list,
    p."userName",
    acadyr.year,
    st.stream AS streamname
FROM tbl_tax_exemption t
LEFT JOIN tbl_student s ON s."studentId"::text = t.student_id::text
LEFT JOIN tbl_class c ON c.id = s.class::integer
LEFT JOIN tbl_parents p ON p.id = s."parentId"
LEFT JOIN tbl_academic_year acadyr ON acadyr.id = t.academic_year
LEFT JOIN tbl_stream st ON st.id = s.stream::integer;
/***View for gettaxexemapplied      - End ****/

/***View for gettempdatanew      - Start ****/
CREATE VIEW gettempdatanew AS  SELECT 
	t."studentId",
    t."studentName",
    c.class_list,
    t."classList",
    t.stream,
    t.term,
    t."studStatus",
    str.stream AS streamname,
    s.section,
    t."challanNo",
    s.hostel_need,
    s.transport_need,
    s.transport_stg
FROM tbl_temp_challans t
LEFT JOIN tbl_student s ON t."studentId"::bpchar = s."studentId"::bpchar
LEFT JOIN tbl_class c ON c.id = t."classList"
LEFT JOIN tbl_stream str ON str.id = t.stream
WHERE t."feeType" IS NULL;
/***View for gettempdatanew      - End ****/

/***View for latefeecheck      - Start ****/
CREATE VIEW latefeecheck AS  SELECT
    CASE
        WHEN tbl_late_fee.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_late_fee.id,
    tbl_late_fee."noOfDays",
    tbl_late_fee.amount
FROM tbl_late_fee
WHERE tbl_late_fee.deleted = 0
ORDER BY tbl_late_fee.id;
/***View for latefeecheck      - End ****/

/***View for loginchk      - Start ****/
CREATE VIEW loginchk AS  SELECT 
	tbl_parents.id,
    tbl_parents."firstName",
    tbl_parents."lastName",
    tbl_parents."userName",
    tbl_parents.email,
    tbl_parents."phoneNumber",
    tbl_parents."mobileNumber",
    tbl_parents.password,
    tbl_parents."verifyCode",
    tbl_parents.status,
    tbl_parents."createdBy",
    tbl_parents."updatedBy",
    tbl_parents.deleted,
    tbl_parents."createdOn",
    tbl_parents."updatedOn",
    tbl_parents."secondaryNumber"
FROM tbl_parents
WHERE tbl_parents.deleted = 0 AND tbl_parents.status = '1'::status;
/***View for loginchk      - End ****/

/***View for nadmloginchk       - Start ****/
CREATE VIEW nadmloginchk AS  SELECT 
	tbl_applicant_parents.id,
    tbl_applicant_parents."userName",
    tbl_applicant_parents.email,
    tbl_applicant_parents.password,
    tbl_applicant_parents."verifyCode",
    tbl_applicant_parents.status,
    tbl_applicant_parents."createdBy",
    tbl_applicant_parents."updatedBy",
    tbl_applicant_parents.deleted,
    tbl_applicant_parents."createdOn",
    tbl_applicant_parents."updatedOn"
FROM tbl_applicant_parents
WHERE tbl_applicant_parents.deleted = 0 AND tbl_applicant_parents.status = '1'::status;
/***View for nadmloginchk       - End ****/

/***View for newchallandata       - Start ****/
CREATE VIEW newchallandata AS  SELECT 
	s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c."feeType",
    c.id AS cid,
    c."studStatus",
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.org_total,
    c."feeGroup",
    t.paid_date,
    t.cheque_dd_no,
    t.pay_type,
    t."academicYear",
    c."updatedOn",
    s.academic_yr
FROM tbl_student s
LEFT JOIN tbl_challans c ON s."studentId"::bpchar = c."studentId"::bpchar
LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term::bpchar
LEFT JOIN tbl_class cl ON c."classList" = cl.id
LEFT JOIN tbl_stream str ON s.stream::integer = str.id
LEFT JOIN tbl_variable_fee_entry t ON c."challanNo"::bpchar = t."challanNo"
WHERE c.deleted = 0 AND c.status = '1'::status;
/***View for newchallandata       - End ****/

/***View for newchallandatanew       - Start ****/
CREATE VIEW newchallandatanew AS  SELECT 
DISTINCT 
	c."feeType",
    s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c.id AS cid,
    c."studStatus",
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.org_total,
    c."feeGroup",
    c.cheque_dd_no,
    c.pay_type,
    c.paid_date,
    s.academic_yr,
    c.bank,
    c.remarks,
    s.hostel_need
   FROM tbl_student s
     LEFT JOIN tbl_challans c ON s."studentId"::bpchar = c."studentId"::bpchar
     LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term::bpchar
     LEFT JOIN tbl_class cl ON c."classList" = cl.id
     LEFT JOIN tbl_stream str ON s.stream::integer = str.id
  WHERE c.deleted = 0 AND c.status = '1'::status;
/***View for newchallandatanew       - End ****/

/***View for nonfeechallandata       - Start ****/
CREATE VIEW nonfeechallandata AS  SELECT 
DISTINCT 
	c."feeType",
    s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c.id AS cid,
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.total,
    c."feeGroup",
    c.cheque_dd_no,
    c.pay_type,
    c.paid_date,
    s.academic_yr,
    c.bank,
    c.remarks,
    c."updatedOn",
    date(c."createdOn") AS "createdOn",
    c.visible,
    nf."feeType" AS feename,
    s.hostel_need,
    c."createdOn" AS created
FROM tbl_student s
LEFT JOIN tbl_nonfee_challans c ON s."studentId"::bpchar = c."studentId"::bpchar OR s.application_no::bpchar = c."studentId"::bpchar
LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term::bpchar
LEFT JOIN tbl_class cl ON c."classList" = cl.id
LEFT JOIN tbl_stream str ON s.stream::integer = str.id
LEFT JOIN tbl_nonfee_type nf ON nf.id = c."feeType"
WHERE c.deleted = 0 AND c.status = '1'::status AND nf.status = '1'::status AND s.deleted = 0 AND s.status = '1'::status;
/***View for nonfeechallandata       - End ****/

/***View for nonfeechallanpaid       - Start ****/
CREATE VIEW nonfeechallanpaid AS  SELECT 
DISTINCT 
	c."feeType",
    s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    c.term,
    sem.semester,
    c."challanNo",
    c.id AS cid,
    s.id AS sid,
    c."challanStatus",
    s."parentId",
    s.stream,
    str.stream AS steamname,
    c.duedate,
    c.total,
    c."feeGroup",
    c.cheque_dd_no,
    c.pay_type,
    c.paid_date,
    s.academic_yr,
    c.bank,
    c.remarks,
    c."updatedOn",
    date(c."createdOn") AS "createdOn",
    c.visible,
    nf."feeType" AS feename,
    s.hostel_need,
    c."createdOn" AS created
FROM tbl_student s
LEFT JOIN tbl_nonfee_challans c ON s."studentId"::bpchar = c."studentId"::bpchar
LEFT JOIN tbl_semester sem ON sem.id::character(1) = c.term::bpchar
LEFT JOIN tbl_class cl ON c."classList" = cl.id
LEFT JOIN tbl_stream str ON s.stream::integer = str.id
LEFT JOIN tbl_nonfee_type nf ON nf.id = c."feeType"
WHERE c.deleted = 0 AND c.status = '1'::status;
/***View for nonfeechallanpaid       - End ****/

/***View for nonfeeconfigdata       - Start ****/
CREATE VIEW nonfeeconfigdata AS  SELECT 
	y.year AS "academicYear",
    f.id,
    t."feeType",
    s.stream,
    c.class_list,
    f.amount,
    f.semester,
    f."dueDate",
    s.id AS streamid,
    c.id AS class_list_id,
    y.id AS acdemic_yr_id
FROM tbl_nonfee_configuration f
LEFT JOIN tbl_nonfee_type t ON f."feeType" = t.id
LEFT JOIN tbl_academic_year y ON f."academicYear" = y.id
LEFT JOIN tbl_stream s ON f.stream = s.id
LEFT JOIN tbl_class c ON f.class = c.id
WHERE f.deleted = 0;
/***View for nonfeeconfigdata       - End ****/

/***View for nonfeeconigdata       - Start ****/
CREATE VIEW nonfeeconigdata AS  SELECT 
	y.year AS "academicYear",
    f.id,
    t."feeType",
    s.stream,
    c.class_list,
    f.amount,
    f.semester,
    f."dueDate",
    s.id AS streamid,
    c.id AS class_list_id
FROM tbl_nonfee_configuration f
LEFT JOIN tbl_nonfee_type t ON f."feeType" = t.id
LEFT JOIN tbl_academic_year y ON f."academicYear" = y.id
LEFT JOIN tbl_stream s ON f.stream = s.id
LEFT JOIN tbl_class c ON f.class = c.id
WHERE f.deleted = 0;
/***View for nonfeeconigdata       - End ****/

/***View for nonfeetypecheck       - Start ****/
CREATE VIEW  nonfeetypecheck AS  SELECT
    CASE
        WHEN t.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    t.id,
    t."feeType",
    t.description,
    g."feeGroup",
    t.applicable
FROM tbl_nonfee_type t
JOIN tbl_fee_group g ON t."feeGroup" = g.id
WHERE t.deleted = 0
ORDER BY t.id;
/***View for nonfeetypecheck       - End ****/

/***View for parentcheck       - Start ****/
CREATE VIEW parentcheck AS  SELECT
    CASE
        WHEN tbl_parents.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_parents.id,
    tbl_parents."firstName",
    tbl_parents."lastName",
    tbl_parents."userName",
    tbl_parents.email,
    tbl_parents."secondaryEmail",
    tbl_parents."mobileNumber",
    tbl_parents."secondaryNumber",
    tbl_parents.password
FROM tbl_parents
WHERE tbl_parents.deleted = 0
ORDER BY tbl_parents."userName";
/***View for parentcheck       - End ****/

/***View for productcheck       - Start ****/
CREATE VIEW productcheck AS  SELECT
    CASE
        WHEN tbl_products.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_products.id,
    tbl_products.acc_no,
    tbl_products.product_name
FROM tbl_products
ORDER BY tbl_products.id;
/***View for productcheck       - End ****/

/***View for semestercheck       - Start ****/
CREATE VIEW semestercheck AS  SELECT
    CASE
        WHEN tbl_semester.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_semester.id,
    tbl_semester.semester
FROM tbl_semester
ORDER BY tbl_semester.id;
/***View for semestercheck       - End ****/

streamcheck

/***View for studentcheck       - Start ****/
CREATE VIEW studentcheck AS  SELECT
	CASE
	    WHEN s.status = '1'::status THEN 'ACTIVE'::text
	    ELSE 'INACTIVE'::text
	END AS status,
	s.id,
    s."studentName",
    s.class,
    s.section,
    s.term,
    c.class_list,
    str.stream AS streamname,
    s.stream,
    s."studentId",
    s.email,
    s."mobileNumber",
    s."parentId",
    p."userName",
    s.transport_stg,
    s.hostel_need,
    s.lunch_need,
    s.academic_yr,
    s.gender
FROM tbl_student s
LEFT JOIN tbl_class c ON c.id = s.class::integer
LEFT JOIN tbl_stream str ON str.id = s.stream::integer
LEFT JOIN tbl_parents p ON s."parentId" = p.id
WHERE s.deleted = 0
ORDER BY s."studentName";
/***View for studentcheck       - End ****/

/***View for taxcheck       - Start ****/
CREATE VIEW taxcheck AS  SELECT
	CASE
	WHEN tbl_tax.status = '1'::status THEN 'ACTIVE'::text
	ELSE 'INACTIVE'::text
	END AS status,
    tbl_tax.id,
    tbl_tax."effectiveDate",
    tbl_tax."taxType",
    tbl_tax."centralTax",
    tbl_tax."stateTax"
   FROM tbl_tax
  WHERE tbl_tax.deleted = 0
  ORDER BY tbl_tax.id;
/***View for taxcheck       - End ****/

/***View for taxtypecheck       - Start ****/
CREATE VIEW taxtypecheck AS  SELECT 
	f.id,
    f.tax,
    u."centralTax",
    u."stateTax",
    u."effectiveDate"
FROM tbl_fee_type f
LEFT JOIN tbl_tax u ON u.id::text = ANY (string_to_array(f.tax::text, ','::text));
/***View for taxtypecheck       - End ****/

/***View for teacherchk       - Start ****/
CREATE VIEW teacherchk AS  SELECT 
	tbl_teachers.id,
    tbl_teachers.name,
    tbl_teachers.email,
    tbl_teachers.password,
    tbl_teachers.class,
    tbl_teachers."createdOn",
    tbl_teachers."createdBy",
    tbl_teachers."updatedOn",
    tbl_teachers."updatedBy",
    tbl_teachers.deleted,
    tbl_teachers."phoneNumber",
    tbl_teachers."mobileNumber",
    tbl_teachers.status,
    tbl_teachers.section,
    tbl_teachers.stream
FROM tbl_teachers
WHERE tbl_teachers.deleted = 0;
/***View for teacherchk       - End ****/

/***View for tempchallan       - Start ****/
CREATE VIEW tempchallan AS  SELECT 
	t."challanNo",
    t."studentId",
    t."feeType",
    t."classList",
    t.term,
    t."studStatus",
    t."createdOn",
    t."createdBy",
    t."updatedOn",
    t."updatedBy",
    t.status,
    t.deleted,
    t.total,
    t.stream,
    t."studentName",
    t.remarks,
    t.duedate,
    g."feeGroup",
    t.id,
    s.stream AS streamname
FROM tbl_temp_challans t
JOIN tbl_fee_group g ON t."feeGroup" = g.id
LEFT JOIN tbl_stream s ON t.stream = s.id
WHERE t.deleted = 0 AND t.status = '1'::status;
/***View for tempchallan       - End ****/

/***View for termdata       - Start ****/
CREATE VIEW termdata AS  SELECT 
	tbl_semester.id,
    tbl_semester.semester
FROM tbl_semester;
/***View for termdata       - End ****/

/***View for topupdata       - Start ****/
CREATE VIEW topupdata AS  SELECT 
	s."studentId",
    s."studentName",
    cl.class_list,
    cl.id AS clid,
    s.section,
    s.term,
    tp.id AS tpid,
    s.id AS sid,
    s."parentId",
    s.stream,
    str.stream AS steamname,
    tp.amount,
    date(tp."createdOn") AS "createdOn",
    s.academic_yr,
    p."firstName" AS "adminName"
FROM tbl_student s
LEFT JOIN tbl_topup_payments tp ON s."studentId"::bpchar = tp."studentId"::bpchar
LEFT JOIN tbl_class cl ON s.class::integer = cl.id
LEFT JOIN tbl_stream str ON s.stream::integer = str.id
LEFT JOIN tbl_parents p ON p.id = tp."parentId"
WHERE tp."transStatus"::text = 'Ok'::text;
/***View for topupdata       - End ****/

/***View for transportcheck       - Start ****/
CREATE VIEW transportcheck AS  SELECT
    CASE
        WHEN tbl_transport.status = '1'::status THEN 'ACTIVE'::text
        ELSE 'INACTIVE'::text
    END AS status,
    tbl_transport.id,
    tbl_transport."pickUp",
    tbl_transport."dropDown",
    tbl_transport.amount,
    tbl_transport.stage
FROM tbl_transport
WHERE tbl_transport.deleted = 0
ORDER BY tbl_transport.id;
/***View for transportcheck       - End ****/

/***View for waviercheck       - Start ****/
CREATE VIEW waviercheck AS  SELECT 
	s."studentName",
    cl.class_list,
    st.stream,
    c.term,
    c.total,
    c."studentId",
    c."challanNo",
    c.org_total,
    c.id,
    c."feeType",
    c."feeGroup",
    s.section,
    s.email,
    c."challanStatus",
    c."academicYear",
    ss."studentName" AS app_name,
    ss.section AS asec,
    ss.email AS aemail
FROM tbl_challans c
LEFT JOIN tbl_student s ON s."studentId"::bpchar = c."studentId"::bpchar
LEFT JOIN tbl_student ss ON ss.application_no::text = c."studentId"::text
LEFT JOIN tbl_class cl ON cl.id = c."classList"
LEFT JOIN tbl_stream st ON st.id = c.stream
WHERE c.deleted = 0
ORDER BY c.id;
/***View for waviercheck       - End ****/

/***View for yearcheck       - Start ****/
CREATE VIEW yearcheck AS  SELECT
	CASE
	    WHEN tbl_academic_year.status = '1'::status THEN 'ACTIVE'::text
	    ELSE 'INACTIVE'::text
	END AS status,
    tbl_academic_year.id,
    tbl_academic_year.year,
    tbl_academic_year.active
FROM tbl_academic_year
WHERE tbl_academic_year.deleted = 0
ORDER BY tbl_academic_year.id;
/***View for yearcheck       - End ****/

/********VIEWS - End*******/





/********Functions - Start*******/

/****Student function - Start*******/
CREATE OR REPLACE FUNCTION add_stud(pid integer,mob character(30),sid character(10))
  	RETURNS int AS $$
	DECLARE fid int;
BEGIN
	PERFORM  1 FROM public.tbl_student WHERE ("parentId" IS NULL OR "parentId" = '0') AND "studentId"=sid ;      

	IF found THEN
		UPDATE public.tbl_student SET "parentId"=pid ,  "mobileNumber" = mob , "updatedOn"=CURRENT_TIMESTAMP WHERE "studentId" = sid RETURNING id INTO fid ;        
	ELSE 
		fid = 0;
	END IF;
	return fid; 
END;
$$ LANGUAGE plpgsql;
/****Student function - End*******/

/****Admin function - Start*******/
CREATE OR REPLACE FUNCTION addadm(did integer,admn character(30), adme character(30), admp character(20)) 
  	RETURNS int AS $$
BEGIN
	PERFORM  1 FROM public.tbl_admin WHERE "adminEmail" = adme AND "deleted" = '0';      

	IF NOT found THEN
		INSERT INTO public.tbl_admin("adminEmail", "adminPassword", "adminName", "createdOn", "createdBy") VALUES (adme,admp,admn,CURRENT_TIMESTAMP,did);
	 
	return 1;
	ELSE 
	return 0;
	END IF;
END;
$$ LANGUAGE plpgsql;
/****Admin function - End*******/


/****Addadmissionadm function - Start*******/
CREATE OR REPLACE FUNCTION addadmissionadm(did integer,admn character(30), adme character(30), admp character(20), admr integer) 
 	 RETURNS int AS $$
BEGIN
    
    PERFORM  1 FROM public.tbl_admission_admin WHERE "adminEmail" = adme AND "deleted" = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_admission_admin("adminEmail", "adminPassword", "adminName", "createdOn", "createdBy","role")
       VALUES (adme,admp,admn,CURRENT_TIMESTAMP,did,admr);
         
    return 1;
    ELSE 
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Addadmissionadm function - End*******/

/****Addadmissionboard function - Start*******/
CREATE OR REPLACE FUNCTION addadmissionboard(did integer,brdn character varying(50),strid integer)
  	RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_board WHERE "board_name" = brdn AND "stream_id" = strid  AND "deleted" = 0;
    IF NOT found THEN
      INSERT INTO public.tbl_board("board_name", "stream_id", "createdOn","createdBy") VALUES (brdn, strid,CURRENT_TIMESTAMP,did) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addadmissionboard function - End*******/

/****Addadmissiongroup function - Start*******/
CREATE OR REPLACE FUNCTION addadmissiongroup(did integer,grpn character varying(50),strid integer)
  	RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_admission_group WHERE "group_name" = grpn AND "stream_id"= strid AND "deleted" = 0;
    IF NOT found THEN
      INSERT INTO public.tbl_admission_group("group_name","stream_id", "createdOn","createdBy") VALUES (grpn,strid,CURRENT_TIMESTAMP,did) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addadmissiongroup function - End*******/

/****Addadmissionintlang function - Start*******/
CREATE OR REPLACE FUNCTION addadmissionintlang(langn character varying(100),strmid integer)
  	RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_interaction_language WHERE "language" = langn AND "stream_id" = strmid;
    IF NOT found THEN
      INSERT INTO public.tbl_interaction_language("language","stream_id") VALUES (langn,strmid) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addadmissionintlang function - End*******/

/****Addadmissionsubject function - Start*******/
CREATE OR REPLACE FUNCTION addadmissionsubject(did integer,subn character varying(50))
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_admission_subject WHERE "subject_name" = subn AND "deleted" = 0;
    IF NOT found THEN
      INSERT INTO public.tbl_admission_subject("subject_name", "createdOn","createdBy") VALUES (subn,CURRENT_TIMESTAMP,did) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addadmissionsubject function - End*******/

/****Addbloodgroup function - Start*******/
CREATE OR REPLACE FUNCTION addbloodgroup(did integer,bgrpn character varying(50))
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_blood_group WHERE "blood_group" = bgrpn AND "deleted" = 0;
    IF NOT found THEN
      INSERT INTO public.tbl_blood_group("blood_group", "createdOn","createdBy") VALUES (bgrpn,CURRENT_TIMESTAMP,did) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addbloodgroup function - End*******/

/****Addcity function - Start*******/
CREATE OR REPLACE FUNCTION addcity(stateid integer, cityn character varying(255))
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_city WHERE "name" = cityn AND "state_id" = stateid;
    IF NOT found THEN
      INSERT INTO public.tbl_city("state_id","name") VALUES (stateid,cityn) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addcity function - End*******/


/****Class function - Start*******/
CREATE OR REPLACE FUNCTION addclass(cn character(20),des character(60),stid integer,uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_class WHERE "class_list" = cn AND "streamId" = stid AND "deleted" = '0';      
   	IF NOT found THEN

       INSERT INTO public.tbl_class("class_list", "description","streamId","createdOn","createdBy")
       VALUES (cn,des,stid,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Class function - End*******/

/****Comments function - Start*******/
CREATE OR REPLACE FUNCTION addcomments(pn character(20),com text,sdate date,enddate date, uid integer) 
  	RETURNS int AS $$
BEGIN
	PERFORM  1 FROM public.tbl_comments WHERE "pageName" =pn ;      

	IF NOT found THEN
		INSERT INTO public.tbl_comments("pageName","comments","startdate","enddate","createdOn","createdBy") VALUES (pn,com,sdate,enddate,CURRENT_TIMESTAMP,uid);
	 
	return 1;
	ELSE
	return 0;
	END IF;
END;
$$ LANGUAGE plpgsql;
/****Comments function - End*******/

/****Addcountry function - Start*******/
CREATE OR REPLACE FUNCTION addcountry(counn character varying(255),counc character varying(10), counphc integer)
  	RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_country WHERE "name" = counn;
    IF NOT found THEN
      INSERT INTO public.tbl_country("name","code","phonecode") VALUES (counn,counc,counphc) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addcountry function - End*******/

/****Addfeeconfiguration1 function - Start*******/
  DECLARE exist int;
BEGIN
      PERFORM  1 FROM public.tbl_fee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0' AND amount = amt ;

       IF NOT FOUND THEN
           INSERT INTO public.tbl_fee_configuration("academicYear","stream","semester","feeType","createdBy","createdOn","amount","class")
           VALUES (yr,str,sem,feetype,createdby,CURRENT_TIMESTAMP,amt,clas);
          exist = 1; 
 
 ELSE 
         exist = 0;
      END IF;  
    return exist; 
END;
/****Addfeeconfiguration1 function - End*******/

/****Addfeeconfiguration function - Start*******/
  DECLARE exist int;
BEGIN
      PERFORM  1 FROM public.tbl_fee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0' AND amount = amt ;

        IF NOT FOUND THEN
            PERFORM  1 FROM public.tbl_fee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0';
            IF NOT FOUND THEN 
                INSERT INTO public.tbl_fee_configuration("academicYear","stream","semester","feeType","createdBy","createdOn","amount","class") VALUES (yr,str,sem,feetype,createdby,CURRENT_TIMESTAMP,amt,clas);
            ELSE
                UPDATE public.tbl_fee_configuration SET amount = amt WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0';
            END IF;
          exist = 1;  
        ELSE 
            exist = 0;
        END IF;  
    return exist; 
END;
/****Addfeeconfiguration function - End*******/

/****Addfeegroup function - Start*******/
CREATE OR REPLACE FUNCTION addfeegroup(fn character(20),des character(100),uid integer) 
 	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_group WHERE "feeGroup" =fn AND deleted = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_fee_group("feeGroup", "description","createdOn","createdBy")
       VALUES (fn,des,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addfeegroup function - End*******/

/****Addfeegroup with product function - Start*******/
CREATE OR REPLACE FUNCTION addfeegroup(fn character(20),des character(100),uid integer, pro integer) 
 	RETURNS int AS $$
 	DECLARE  fid int;
BEGIN
    PERFORM  1 FROM public.tbl_fee_group WHERE "feeGroup" =fn AND "deleted" = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_fee_group("feeGroup", "description","createdOn","createdBy","product")
       VALUES (fn,des,CURRENT_TIMESTAMP,uid,pro) RETURNING id INTO fid;  
    
    ELSE
    fid =  0;
    END IF;
    return fid;
END;
$$ LANGUAGE plpgsql
/****Addfeegroup with product function - End*******/

/****Addfeetype function - Start*******/
CREATE OR REPLACE FUNCTION addfeetype(ft character(20),des character(100),uid integer,tt character(100),grp character(40),mand integer, app character(10)) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_type WHERE "feeType" =ft ;      

    IF NOT found THEN
       INSERT INTO public.tbl_fee_type("feeType", "description","createdOn","createdBy","tax","feeGroup","mandatory","applicable")
       VALUES (ft,des,CURRENT_TIMESTAMP,uid,tt,grp,mand,app);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addfeetype function - End*******/

/****Addgroupsubject function - Start*******/
CREATE OR REPLACE FUNCTION addgroupsubject(did integer,grpid integer,subid integer)
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_group_subject WHERE "group_id" = grpid AND "subject_id"=subid AND "deleted" = 0;
    IF NOT found THEN
      INSERT INTO public.tbl_group_subject("group_id","subject_id", "createdOn","createdBy") VALUES (grpid,subid,CURRENT_TIMESTAMP,did) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addgroupsubject function - End*******/

/****Addlatefee function - Start*******/
CREATE OR REPLACE FUNCTION addlatefee(n integer,amt real,uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_late_fee WHERE "noOfDays" = n ;      

    IF NOT found THEN
    	INSERT INTO public.tbl_late_fee("noOfDays", "amount","createdOn","createdBy")
    	VALUES (n,amt,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addlatefee function - End*******/

/****Addnonfeeconfiguration function - Start*******/
  DECLARE exist int;
BEGIN
      PERFORM  1 FROM public.tbl_nonfee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0' AND amount = amt ;

        IF NOT FOUND THEN
            PERFORM  1 FROM public.tbl_nonfee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0';
            IF NOT FOUND THEN 
                INSERT INTO public.tbl_nonfee_configuration("academicYear","stream","semester","feeType","createdBy","createdOn","amount","class") VALUES (yr,str,sem,feetype,createdby,CURRENT_TIMESTAMP,amt,clas);
            ELSE
                UPDATE public.tbl_nonfee_configuration SET amount = amt WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND deleted = '0';
            END IF;
          exist = 1;  
        ELSE 
            exist = 0;
        END IF;  
    return exist; 
END;
/****Addnonfeeconfiguration function - End*******/

/****Addnonfeetype function - Start*******/
  DECLARE nfid int;
BEGIN
    PERFORM  1 FROM public.tbl_nonfee_type WHERE "feeType" =ft ; 

    IF NOT found THEN
       INSERT INTO public.tbl_nonfee_type("feeType", "description","createdOn","createdBy","feeGroup","applicable","status","challan")
       VALUES (ft,des,CURRENT_TIMESTAMP,uid,grp,app,'1',chln) RETURNING id INTO nfid ;    
    ELSE
        nfid = 0;
    END IF;
    return nfid;
END;


/****Addnonfeetype function - End*******/

/****Addpar function - Start*******/
CREATE OR REPLACE FUNCTION addpar(fn character(20),ln character(20),un character(40), ep character(50),es character(50),p character(20),pn bigint,mn bigint, uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "email" = ep AND "deleted" = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_parents("firstName", "lastName", "userName","email", "secondaryEmail","password","mobileNumber","secondaryNumber","createdOn","createdBy") VALUES (fn,ln,un,ep,es,p,pn,mn,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addpar function - End*******/

/****Addproduct function - Start*******/
DECLARE pid int;
BEGIN
    PERFORM  1 FROM public.tbl_products WHERE "acc_no" =accno AND "product_name" = pn;      

    IF NOT found THEN
       INSERT INTO public.tbl_products("product_name", "acc_no","created_on","created_by")
       VALUES (pn,accno,CURRENT_TIMESTAMP,uid) RETURNING id INTO pid ;  
    ELSE
      pid = 0;
    END IF;
    return pid;
END;
/****Addproduct function - End*******/

/****Addstate function - Start*******/
CREATE OR REPLACE FUNCTION addstate(staten character varying(255), conid integer)
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_state WHERE "name" = staten AND "country_id" = conid;
    IF NOT found THEN
      INSERT INTO public.tbl_state("name","country_id") VALUES (staten,conid) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Addstate function - End*******/

/****Addstd function - Start*******/
CREATE OR REPLACE FUNCTION addstd(sid character(10),sn character(20),s character(15),c character(20),sec character(40), t character(30),pi integer,mail character(150),mnum character(30),transtg integer,transneed character(10),hostel character(1),lunch character(10),acayear character(15),uid integer,appl character varying(50),gendr character varying(1)) 
	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_student WHERE "studentId" = sid AND "deleted" = '0';     

    IF NOT found THEN
       INSERT INTO public.tbl_student("studentId", "studentName", "stream", "class", "section","term", "parentId","email", "mobileNumber", "createdOn","createdBy","transport_stg", "transport_need", "hostel_need", "lunch_need", "academic_yr","application_no","gender")
       VALUES (sid,sn,s,c,sec,t,pi,mail,mnum,CURRENT_TIMESTAMP,uid,transtg,transneed,hostel,lunch,acayear,appl, gendr);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addstd function - End*******/

/****Addstream function - Start*******/
CREATE OR REPLACE FUNCTION addstream(s character(20),des character(100),uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_stream WHERE "stream" =s AND deleted = '0';      

    IF NOT found THEN
       INSERT INTO public.tbl_stream("stream", "description","createdOn","createdBy")
  		VALUES (s,des,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addstream function - End*******/

/****Addtax function - Start*******/
CREATE OR REPLACE FUNCTION addtax(ed date,tt character(20),ct real, st real,uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_tax WHERE "taxType" = tt ;      

    IF NOT found THEN
    INSERT INTO public.tbl_tax("effectiveDate", "taxType", "centralTax","stateTax","createdOn","createdBy")
    VALUES (ed,tt,ct,st,CURRENT_TIMESTAMP,uid);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addtax function - End*******/

/****Addteacher function - Start*******/
CREATE OR REPLACE FUNCTION addTeacher(name character(50),e character(50),pass character(150),clas character(10),pn1 bigint,pn2 bigint,createdby character(50),st character(10))
 	 RETURNS int AS $$
	DECLARE exist int;
BEGIN
    PERFORM  1 FROM public.tbl_teachers WHERE email = e ;      

      IF NOT found THEN
      INSERT INTO public.tbl_teachers(name, email, password, class,"phoneNumber", "mobileNumber","createdBy", "createdOn",stream) VALUES (name, e, pass,clas,pn1,pn2,createdby,CURRENT_TIMESTAMP,st);         
         exist = 0;
       ELSE 
         exist = 1;
      END IF;  
      return exist; 
END;
$$ LANGUAGE plpgsql
/****Addteacher function - End*******/

/****Addtransport function - Start*******/
CREATE OR REPLACE FUNCTION addtransport(ppoint character(20),dpoint character(100),uid integer,st character(50),amt real) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_transport WHERE lower("pickUp")=lower(ppoint) ;      

    IF NOT found THEN
       INSERT INTO public.tbl_transport("pickUp", "dropDown","createdOn","createdBy","stage","amount")
       VALUES (ppoint,dpoint,CURRENT_TIMESTAMP,uid,st,amt);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addtransport function - End*******/

/****Addyear function - Start*******/
CREATE OR REPLACE FUNCTION addyear(yr character,uid integer, activeyear integer) 
 	 RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_academic_year WHERE tbl_academic_year."year" = yr AND "deleted" = '0' ;      

    IF NOT found THEN
       INSERT INTO public.tbl_academic_year("year","createdOn","createdBy","active")
       VALUES (yr,CURRENT_TIMESTAMP,uid,activeyear);
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Addyear function - End*******/

/****Admeditbloodgroup function - Start*******/
CREATE OR REPLACE FUNCTION admeditbloodgroup(did integer, bldgrpn character varying(50),uid integer) 
  RETURNS int AS $$
  DECLARE sid int;
BEGIN
    UPDATE tbl_blood_group SET "blood_group" = bldgrpn,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did RETURNING id INTO sid;      
    return sid;
END;
$$ LANGUAGE plpgsql;
/****Admeditbloodgroup function - End*******/

/****Admeditbloodgroup function - Start*******/
CREATE OR REPLACE FUNCTION admeditboard(did integer, brdn character varying(50),strid integer,uid integer) 
  RETURNS int AS $$
  DECLARE sid int;
BEGIN
    UPDATE tbl_board SET "board_name" = brdn,"stream_id" = strid,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did RETURNING id INTO sid;      
    return sid;
END;
$$ LANGUAGE plpgsql;
/****Admeditbloodgroup function - End*******/

/****Admeditclass function - Start*******/
CREATE OR REPLACE FUNCTION admeditclass(did integer, cn character(20),des character(60),stid integer,uid integer, adm int, admc int, appc int) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_class WHERE "class_list" = cn AND "streamId" = stid AND "id"!= did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_class SET "class_list"=cn,"description"=des,"streamId" = stid, "admission" = adm , "updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid, "openings" = admc, "allowed_application" = appc WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Admeditclass function - End*******/

/****Admeditstream function - Start*******/
CREATE OR REPLACE FUNCTION admeditstream(did integer, s character(20),des character(20),uid integer, nemail character varying(100)) 
  RETURNS int AS $$
  DECLARE sid int;
BEGIN
    UPDATE tbl_stream SET "stream" = s,"description" = des,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid, "notifyEmail" = nemail WHERE "id" = did RETURNING id INTO sid;      
    return sid;
END;
$$ LANGUAGE plpgsql;
/****Admeditstream function - End*******/

/****Admeditsubject function - Start*******/
CREATE OR REPLACE FUNCTION admeditsubject(did integer, subn character varying(50),uid integer) 
  RETURNS int AS $$
  DECLARE sid int;
BEGIN
    UPDATE tbl_admission_subject SET "subject_name" = subn,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did RETURNING id INTO sid;      
    return sid;
END;
    $$ LANGUAGE plpgsql;
/****Admeditsubject function - End*******/

/****Applicantawrj function - Start*******/
CREATE OR REPLACE FUNCTION applicantawrj(did character varying, awrj integer, reason character varying) 
  	RETURNS int AS $$
  	DECLARE sid int;
BEGIN
    UPDATE tbl_applicant SET "awarded_rejected" = awrj, "remarks" = reason WHERE "applicant_id" = did RETURNING id INTO sid;      
    return sid;
END;
    $$ LANGUAGE plpgsql;
/****Applicantawrj function - End*******/

/****Changeadminpassword function - Start*******/
CREATE OR REPLACE FUNCTION changeadminpassword(mail character(50), newpass character(150)) 
 	RETURNS int AS $$
	DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_admin WHERE "adminEmail" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_admin SET "adminPassword" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "adminEmail" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
$$ LANGUAGE plpgsql
/****Changeadminpassword function - End*******/

/****Changeparentpassword function - Start*******/
CREATE OR REPLACE FUNCTION changeparentpassword(mail character(50), newpass character(150)) 
 	RETURNS int AS $$
	DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "email" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_parents SET "password" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "email" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
$$ LANGUAGE plpgsql
/****Changeparentpassword function - End*******/

/****Changepass function - Start*******/
CREATE OR REPLACE FUNCTION changepass(mail character(50), newpass character(150)) 
  	RETURNS int AS $$
	DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "email" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_parents SET "password" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "email" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
$$ LANGUAGE plpgsql
/****Changepass function - End*******/

/****Changeteacherpassword function - Start*******/
CREATE OR REPLACE FUNCTION changeteacherpassword(mail character(50), newpass character(150)) 
  	RETURNS int AS $$
	DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_teachers WHERE "email" = mail ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_teachers SET "password" = newpass, "updatedOn"=CURRENT_TIMESTAMP WHERE "email" = mail;
        cnt = 1;
    END IF;  
    return cnt;
END;
$$ LANGUAGE plpgsql    
/****Changeteacherpassword function - End*******/

/****Chequerevoke function - Start*******/
CREATE OR REPLACE FUNCTION chequerevoke(cno character varying(50), uid integer, stdid character varying (50),trm character varying (5), acayear integer) 
  	RETURNS int AS $$
  	DECLARE cid int;
BEGIN
    PERFORM  1 FROM public.tbl_challans WHERE "challanNo" = cno AND "challanStatus" = '1' AND "studentId" = stdid AND "term" = trm AND "academicYear" = acayear AND "pay_type" = 'Cheque';      

    IF found THEN
      UPDATE tbl_challans SET "pay_type" = null,"bank" = null,"cheque_dd_no" = null,"chequeRemarks" = null,"challanStatus" = '0',"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid, "paid_date" = null WHERE "challanNo" = cno AND "studentId" = stdid AND "term" = trm AND "academicYear" = acayear AND "pay_type" = 'Cheque';

      UPDATE tbl_demand SET "pay_type" = null,"bank" = null,"cheque_dd_no" = null,"chequeRemarks" = null,"challanStatus" = '0',"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid, "paid_date" = null WHERE "challanNo" = cno AND "studentId" = stdid AND "term" = trm AND "academicYear" = acayear AND "pay_type" = 'Cheque';

      UPDATE tbl_waiver SET "challanStatus" = '0',"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid,  WHERE "challanNo" = cno AND "studentId" = stdid;

      DELETE FROM tbl_receipt WHERE "challanNo" = cno AND "studentId" = stdid AND "term" = trm AND "academicYear" = acayear AND "pay_type" = 'Cheque';
      cid = 1;
    ELSE
      cid = 0;
      END IF;

    return cid;
END;
$$ LANGUAGE plpgsql    
/****Chequerevoke function - End*******/

/****Createchallan function - Start*******/
CREATE OR REPLACE FUNCTION createChallan(cid integer)
  	RETURNS int AS $$     
BEGIN

    INSERT INTO tbl_challans("challanNo", "studentId", "feeTypes", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","org_total","stream","remarks","duedate","feeGroup","academicYear")  SELECT "challanNo", "studentId", "feeTypes", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","total","stream"::int,"remarks","duedate","feeGroup","academicYear"  FROM tbl_temp_challans WHERE id=cid;
    return 1;
END;
$$ LANGUAGE plpgsql;
/****Createchallan function - End*******/

/****Createchallannew function - Start*******/
CREATE OR REPLACE FUNCTION createChallanNew(cid integer)
  	RETURNS int AS $$    
 	DECLARE clid int;  
BEGIN

    INSERT INTO tbl_challans("challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","org_total","stream","remarks","duedate","feeGroup","academicYear")  SELECT "challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","total","stream","remarks","duedate","feeGroup","academicYear"  FROM tbl_temp_challans WHERE id=cid RETURNING id INTO clid;

 	INSERT INTO tbl_demand("challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","org_total","stream","remarks","duedate","feeGroup","academicYear")  SELECT "challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","total","stream","remarks","duedate","feeGroup","academicYear"  FROM tbl_temp_challans WHERE id=cid;

    return clid;
END;
$$ LANGUAGE plpgsql;
/****Createchallannew function - End*******/

/****Createlatefeechallan function - Start*******/
CREATE OR REPLACE FUNCTION createlatefeechallannew(cn character varying(50),studId character varying(50),classlist integer,trm character varying(5),latefee real,str integer,due date, acayear integer, uid integer, clid integer)
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_challans WHERE "studentId" =studId AND "classList" = classlist  AND "term" = trm  AND "stream" = str;
    IF found THEN
       PERFORM 1 FROM public.tbl_challans WHERE "challanNo"= cn AND "feeGroup" =0 AND "challanStatus" = 0 AND "feeType" = 0;
          IF found THEN
            DELETE FROM tbl_challans WHERE "challanNo"= cn AND "feeGroup" =0 AND "challanStatus" = 0 AND "feeType" = 0;

            DELETE FROM tbl_demand WHERE "challanNo"= cn AND "feeGroup" = '0' AND "challanStatus" = 0 AND "studentId" = studId AND "academicYear" = acayear AND "term" = trm;

            INSERT INTO public.tbl_challans("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "total","feeType") VALUES (cn,studId,classlist,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,0,acayear, uid,latefee,0) RETURNING id INTO cid;

            INSERT INTO public.tbl_demand("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "feeType", "total") VALUES (cn,studId,clid,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,0,acayear, uid, 0, latefee) RETURNING id INTO cid;

          ELSE       
            INSERT INTO public.tbl_challans("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "total", "feeType") VALUES (cn,studId,classlist,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,0,acayear, uid,latefee,0) RETURNING id INTO cid; 

            INSERT INTO public.tbl_demand("challanNo","studentId","classList", "term","org_total", "stream","studStatus","challanStatus","createdOn","duedate","feeGroup","academicYear", "createdBy", "feeType", "total") VALUES (cn,studId,clid,trm,latefee,str,'Prov.Promoted',0,CURRENT_TIMESTAMP,due,0,acayear, uid, 0, latefee) RETURNING id INTO cid; 

          END IF;
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Createlatefeechallan function - End*******/

/****Createnonfeechallan function - Start*******/
 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_nonfee_challans WHERE "feeType" = feetypes AND "studentId" = studId AND "academicYear"=yr;
    IF NOT found THEN
        INSERT INTO public.tbl_nonfee_challans("challanNo", "studentId", "createdBy", "createdOn","stream","classList","term", "feeType","total","remarks","duedate", "feeGroup","academicYear","visible") VALUES (cn, studId,createdBy,CURRENT_TIMESTAMP,str,classlist,term,feetypes,tot,remark,due,feegrp,yr,chln) RETURNING id INTO cid; 
    ELSE 
      cid = 0;
    END IF;

    return cid;
END;
$$ LANGUAGE plpgsql;
/****Createnonfeechallan function - End*******/

/****Createreceiptrows function - Start*******/
 CREATE OR REPLACE FUNCTION createReceiptRows(cid integer)
  	RETURNS int AS $$ 
  	DECLARE clid int;  
BEGIN

    INSERT INTO tbl_receipt("challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","org_total","stream","remarks","duedate","pay_type","bank","cheque_dd_no","feeGroup","paid_date","chequeRemarks","academicYear")SELECT "challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","updatedOn","updatedBy","total","org_total","stream","remarks","duedate","pay_type","bank","cheque_dd_no","feeGroup","paid_date","chequeRemarks","academicYear"  FROM tbl_challans WHERE id=cid RETURNING id INTO clid;

    return clid;
END;
$$ LANGUAGE plpgsql;
/****Createreceiptrows function - End*******/

/****Createtempchallan function - Start*******/
CREATE OR REPLACE FUNCTION createTempChallan(cn character(50),studId character(30),createdBy integer,str integer,classlist character(5),trm character(5),name character(50),yr character(20))
  	RETURNS int AS $$
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_challans WHERE "studentId" =studId AND "classList" = classlist  AND "term" = trm  AND "stream" = str;
    IF NOT found THEN
      INSERT INTO public.tbl_temp_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","studentName","academicYear") VALUES (cn, studId,'Prov.Promoted',createdBy,CURRENT_TIMESTAMP,str,classlist,trm,name,yr) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Createtempchallan function - End*******/

/****Createtempchallannew function - Start*******/
CREATE OR REPLACE FUNCTION createTempChallanNew(cn character varying(50),studId character varying(50),createdBy integer,str integer,classlist integer,trm character varying(5),name character varying(100),yr integer)
  	RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_challans WHERE "studentId" = studId AND "classList" = classlist  AND "term" = trm  AND "stream" = str AND "academicYear" = yr;
    IF NOT found THEN
      INSERT INTO public.tbl_temp_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","studentName","academicYear") VALUES (cn, studId,'Prov.Promoted',createdBy,CURRENT_TIMESTAMP,str,classlist,trm,name,yr) RETURNING id INTO cid;    
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Createtempchallannew function - End*******/


/****Deleteupdate function - Start*******/
CREATE OR REPLACE FUNCTION deleteupdate(param character varying,uid integer,id integer) 
	RETURNS integer AS $$
BEGIN
      EXECUTE 'UPDATE ' || quote_ident(param) || ' SET deleted=1, "updatedOn"=CURRENT_TIMESTAMP, "updatedBy"=$1 WHERE id = $2' USING uid,id;
      return 1;
END;
$$ LANGUAGE plpgsql;
/****Deleteupdate function - End*******/

/****Editadm function - Start*******/
CREATE OR REPLACE FUNCTION editAdm(did integer, admn character(20),admm character(30), admp character(20), uid integer)
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_admin WHERE "adminEmail" = admm AND "id"!=did ;      

    IF NOT found THEN
    
        UPDATE tbl_admin SET "adminName" = admn,"adminEmail"=admm,"adminPassword"=admp,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF; 
END;
$$ LANGUAGE plpgsql;
/****Editadm function - End*******/

/****Editadmissionadm function - Start*******/
CREATE OR REPLACE FUNCTION editadmissionadm(did integer, admn character(20),admm character(30), admp character(20), uid integer, admr integer) 
  	RETURNS int AS $$
BEGIN

    PERFORM  1 FROM public.tbl_admission_admin WHERE "adminEmail" = admm AND "id" != did AND "deleted" = '0';      

    IF NOT found THEN
    
        UPDATE tbl_admission_admin SET "adminName" = admn,"adminEmail" = admm,"adminPassword" = admp,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid, "role" = admr WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF; 
END;
$$ LANGUAGE plpgsql;
/****Editadmissionadm function - End*******/

/****Editadmissiongroup function - Start*******/
CREATE OR REPLACE FUNCTION editadmissiongroup(did integer,grpn character varying(50),strid integer,uid integer)
  RETURNS int AS $$ 
    DECLARE sid int;
BEGIN
    UPDATE tbl_admission_group SET "group_name" = grpn,"stream_id" = strid,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did RETURNING id INTO sid;      
    return sid;
END;
$$ LANGUAGE plpgsql;
/****Editadmissiongroup function - End*******/

/****Editclass function - Start*******/
CREATE OR REPLACE FUNCTION editclass(did integer, cn character(20),des character(60),stid integer,uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_class WHERE "class_list" = cn AND "streamId" = stid AND "id"!= did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_class SET "class_list"=cn,"description"=des,"streamId" = stid,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editclass function - End*******/

/****Editcomments function - Start*******/
CREATE OR REPLACE FUNCTION editcomments(did integer, pn character(20),com text,sdate date,edate date,uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_comments WHERE "pageName" =pn AND "id"!=did;      

    IF NOT found THEN
        UPDATE tbl_comments SET "pageName"=pn,"comments"=com,"startdate"=sdate,"enddate"=edate,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editcomments function - End*******/

/****Editcreatedchallans function - Start*******/
CREATE OR REPLACE FUNCTION editcreatedchallans(cn character(50),studId character(30),classlist character(5),feetypes character(100),term character(5),studStatus character(15),createdBy integer,tot real,str integer,remark character(256),due date,feegrp character(40),wavep integer,wavea real,wavet real,orgtotal real,yr character(20))
  	RETURNS int AS $$
	DECLARE cid int;
BEGIN
    INSERT INTO public.tbl_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","feeTypes","total","remarks","duedate", "feeGroup","waivedPercentage","waivedAmount","waivedTotal","org_total","academicYear") VALUES (cn, studId,studStatus,createdBy,CURRENT_TIMESTAMP,str,classlist,term,feetypes,tot,remark,due,feegrp,wavep,wavea,wavet,orgtotal,yr); 
      cid = 1; 
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Editcreatedchallans function - End*******/

/****Editcreatedchallansnew function - Start*******/
CREATE OR REPLACE FUNCTION editcreatedchallansnew(cn character varying(50),studId character varying(30),classlist integer,feetypes integer,trm character varying(5),studStatus character varying(15),createdBy integer,tot real,str integer,remark text,due date,feegrp integer,yr integer)
  	RETURNS int AS $$
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_challans WHERE "challanNo" = cn AND "feeType" = feetypes;
    IF NOT found THEN
      INSERT INTO public.tbl_challans("challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","total","org_total","stream","remarks","duedate","feeGroup","academicYear") VALUES (cn, studId,feetypes,classlist,trm,'Prov.Promoted',CURRENT_TIMESTAMP,createdBy,tot,tot,str,remark,due,feegrp,yr) RETURNING id INTO cid; 

      INSERT INTO public.tbl_demand("challanNo", "studentId", "feeType", "classList", "term", "studStatus","createdOn","createdBy","total","org_total","stream","remarks","duedate","feeGroup","academicYear") VALUES (cn, studId,feetypes,classlist,trm,'Prov.Promoted',CURRENT_TIMESTAMP,createdBy,tot,tot,str,remark,due,feegrp,yr);   
    ELSE 
       UPDATE tbl_challans SET "challanNo" = cn, "studentId" = studId, "feeType" = feetypes, "classList" = classlist, "term" = trm, "studStatus" = 'Prov.Promoted', "updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = createdBy, "total" = tot, "org_total" = tot, "stream" = str, "remarks" = remark, "duedate" = due, "feeGroup" = feegrp, "academicYear" = yr WHERE "feeType" = feetypes AND "challanNo" = cn RETURNING id INTO cid;

       UPDATE tbl_demand SET "challanNo" = cn, "studentId" = studId, "feeType" = feetypes, "classList" = classlist, "term" = trm, "studStatus" = 'Prov.Promoted', "updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = createdBy, "total" = tot, "org_total" = tot, "stream" = str, "remarks" = remark, "duedate" = due, "feeGroup" = feegrp, "academicYear" = yr WHERE "feeType" = feetypes AND "challanNo" = cn;

    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Editcreatedchallansnew function - End*******/

/****Editduedate function - Start*******/
CREATE OR REPLACE FUNCTION editduedate(due date, academicyr integer, trm character varying(5))
  	RETURNS int AS $$
BEGIN
    UPDATE tbl_challans SET "duedate"=due WHERE "challanStatus" = 0 AND "term" = trm AND "academicYear" = acdemicyr; 
    return 1;
END;
$$ LANGUAGE plpgsql;
/****Editduedate function - End*******/

/****Editfeeconfiguration function - Start*******/
CREATE OR REPLACE FUNCTION editfeeconfiguration(yr character(10), str character(20),sem character(5),feetype character(30),updatedby integer,amt real,clas integer, fid integer)
  RETURNS int AS $$
  DECLARE exist int;
BEGIN
      PERFORM  1 FROM public.tbl_fee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND id!=fid; 
 
IF NOT FOUND THEN
            UPDATE public.tbl_fee_configuration SET "academicYear"= yr,stream=str,semester=sem,"feeType"=feetype,"updatedBy"=updatedby,"updatedOn"=CURRENT_TIMESTAMP,amount=amt,"class"=clas WHERE id = fid;           
          exist = 1;
       ELSE 
         exist = 0;
      END IF;  
    return exist; 
END;
$$ LANGUAGE plpgsql;
/****Editfeeconfiguration function - End*******/

/****Editfeegroup function - Start*******/
CREATE OR REPLACE FUNCTION editfeegroup(did integer, fn character(20),des character(20),uid integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_group WHERE "feeGroup" =fn AND "id"!=did AND deleted = '0';      

    IF NOT found THEN
        UPDATE tbl_fee_group SET "feeGroup"=fn,"description"=des,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editfeegroup function - End*******/

/****Editfeegroup function - Start*******/
CREATE OR REPLACE FUNCTION editfeegroup(did integer, fn character(20),des character(20),uid integer, pro integer) 
  	RETURNS int AS $$
  	DECLARE fid int;
BEGIN
    PERFORM  1 FROM public.tbl_fee_group WHERE "feeGroup" =fn AND "id"!=did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_fee_group SET "feeGroup"=fn,"description"=des,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid, product=pro WHERE "id" = did RETURNING id INTO fid;
    ELSE
    fid = 0;
    END IF;
    return fid;
END;
$$ LANGUAGE plpgsql;
/****Editfeegroup function - End*******/

/****Editfeetype function - Start*******/
CREATE OR REPLACE FUNCTION editfeetype(did integer, ft character(20),des character(20),uid integer,tt character(100),grp character(40),mand integer, app character(10)) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_fee_type WHERE "feeType" =ft AND "id"!=did;      

    IF NOT found THEN
        UPDATE tbl_fee_type SET "feeType"=ft,"description"=des,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid, "tax"=tt,"feeGroup"=grp, "mandatory"=mand,"applicable" = app  WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editfeetype function - End*******/

/****Editgroupsubject function - Start*******/
CREATE OR REPLACE FUNCTION editgroupsubject(grpid integer,subid integer,did integer)
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_group_subject WHERE "group_id" = grpid AND "subject_id"= subid;
    IF NOT found THEN
      INSERT INTO public.tbl_group_subject("group_id","subject_id", "createdOn","createdBy") VALUES (grpid,subid,CURRENT_TIMESTAMP,did) RETURNING id INTO cid;    
    ELSE 
      DELETE From public.tbl_group_subject WHERE "group_id" = grpid AND "subject_id"= subid RETURNING id INTO cid;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Editgroupsubject function - End*******/

/****Editlatefee function - Start*******/
CREATE OR REPLACE FUNCTION editlatefee(did integer, n integer,amt real, uid integer) 
 	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_late_fee WHERE "noOfDays" = n AND "id" != did;      

    IF NOT found THEN
    UPDATE tbl_late_fee SET "noOfDays" = n,"amount" = amt,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editlatefee function - End*******/

/****Editnonfeeconfiguration function - Start*******/
  DECLARE exist int;
BEGIN
      PERFORM  1 FROM public.tbl_nonfee_configuration WHERE "academicYear" = yr AND stream = str AND  semester = sem  AND "class" = clas AND "feeType"=feetype AND id!=fid; 

 
IF NOT FOUND THEN
            UPDATE public.tbl_nonfee_configuration SET "academicYear"= yr,stream=str,semester=sem,"feeType"=feetype,"updatedBy"=updatedby,"updatedOn"=CURRENT_TIMESTAMP,amount=amt,"class"=clas WHERE id = fid;           
          exist = 1;
       ELSE 
         exist = 0;
      END IF;  
    return exist; 
END;
$$ LANGUAGE plpgsql;
/****Editnonfeeconfiguration function - End*******/

/****Editnonfeetype function - Start*******/
 	 DECLARE nfid int;
BEGIN
    PERFORM  1 FROM public.tbl_nonfee_type WHERE "feeType" =ft AND "id"!=did;      

    IF NOT found THEN
        UPDATE tbl_nonfee_type SET "feeType"=ft,"description"=des,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid, "feeGroup"=grp, "applicable" = app, "challan" = chln  WHERE "id" = did RETURNING id INTO nfid ;        
    ELSE
         nfid = 0;
    END IF;
    return nfid;
END;
$$ LANGUAGE plpgsql;
/****Editnonfeetype function - End*******/

/****Editpar function - Start*******/
CREATE OR REPLACE FUNCTION editpar(did integer, fn character(20),ln character(20),un character(40), ep character(50), es character(50),p character(20),pn bigint,mn bigint,uid integer) 
 	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "email" = ep AND "id" != did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_parents SET "firstName" = fn,"lastName" = ln,"userName" = un,"email" = ep,"secondaryEmail"=es,"password" = p,"mobileNumber" = pn,"secondaryNumber" = mn,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE 
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editpar function - End*******/

/****Editstd function - Start*******/
CREATE OR REPLACE FUNCTION editstd(sid character(10),sn character(20),s character(15),c character(20),sec character(40), t character(30),pi integer,mail character(150),mnum character(30),transtg integer,transneed character(10),hostel character(1),lunch character(10),acayear character(15),uid integer,did integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_student WHERE "studentId" = sid AND "id" != did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_student SET "studentId" = sid,"studentName" = sn,"stream" = s,"class" = c,"section" = sec,"term" = t,"parentId" = pi,"email" = mail, "mobileNumber" = mnum, "transport_stg" = transtg, "transport_need" = transneed, "hostel_need" = hostel, "lunch_need" = lunch, "academic_yr" = acayear, "updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid, "gender" = gendr WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editstd function - End*******/

/****Editstream function - Start*******/
CREATE OR REPLACE FUNCTION editstream(did integer, s character(20),des character(20),uid integer) 
  RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_stream WHERE "stream" =s AND "id"!=did AND deleted = '0';      

    IF NOT found THEN
        UPDATE tbl_stream SET "stream"=s,"description"=des,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Editstream function - End*******/

/****Edittax function - Start*******/
CREATE OR REPLACE FUNCTION edittax(did integer, ed date,tt character(20), ct real,st real,uid integer) 
  RETURNS int AS $$
BEGIN
	PERFORM  1 FROM public.tbl_tax WHERE "taxType" = tt AND "id"!=did;      

    IF NOT found THEN
         UPDATE tbl_tax SET "effectiveDate" = ed,"taxType" = tt,"centralTax" = ct,"stateTax" = st,"updatedOn" = CURRENT_TIMESTAMP,"updatedBy" = uid WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Edittax function - End*******/

/****Editteacher function - Start*******/
 CREATE OR REPLACE FUNCTION editTeacher(tid integer,tname character(50), clas character(10),e character(50),pn1 bigint,pn2 bigint,pass character(150),updatedBy character(50),st character(10))
 	RETURNS int AS $$
	DECLARE exist int;
BEGIN

    PERFORM  1 FROM public.tbl_teachers WHERE email = e AND id != tid ;      

      IF NOT found THEN
        UPDATE tbl_teachers SET "name"=tname,"class"=clas,"email"=e,"phoneNumber"=pn1,"mobileNumber"=pn2,"password"=pass,"updatedBy"=updatedBy,"updatedOn"=CURRENT_TIMESTAMP,"stream"=st WHERE id=tid;     
         exist = 0;
       ELSE 
         exist = 1;
      END IF;  
      return exist; 
END;
$$ LANGUAGE plpgsql;
/****Editteacher function - End*******/

/****Edittransport function - Start*******/
CREATE OR REPLACE FUNCTION edittransport(did integer, ppoint character(20),dpoint character(20),uid integer,st character(50),amt real) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_transport WHERE lower("pickUp")=lower(ppoint) AND "id"!=did;      

    IF NOT found THEN
        UPDATE tbl_transport SET "pickUp"=ppoint,"dropDown"=dpoint,"updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid,"stage"=st,"amount"=amt WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Edittransport function - End*******/

/****Edityear function - Start*******/
CREATE OR REPLACE FUNCTION edityear(did integer,yr character,uid integer, activeyear integer) 
  	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_academic_year WHERE "year" =yr AND "id"!=did AND "deleted" = '0';      

    IF NOT found THEN
        UPDATE tbl_academic_year SET "year" = yr, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = uid, "active" = activeyear WHERE "id" = did;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql;
/****Edityear function - End*******/

/****Fee_entry function - Start*******/
CREATE OR REPLACE FUNCTION fee_entry(sid character(8),sname character(20),ayr character(20),stream character(10),clas character(10),sem character(5),ftypes character(50),total numeric(10),cby integer,chlno character(40))
  	RETURNS int AS $$
  	DECLARE fid int;
BEGIN
     INSERT INTO tbl_variable_fee_entry ("studentId","studentName","academicYear","stream","class","semester","feeType","total","createdBy","createdOn","challanNo") VALUES (sid,sname,ayr,stream,clas,sem,ftypes,total,cby,CURRENT_TIMESTAMP,chlno) RETURNING id INTO fid ; 
     return fid;     
END;
$$ LANGUAGE plpgsql;
/****Fee_entry function - End*******/

/****Fee_entry_update function - Start*******/
CREATE OR REPLACE FUNCTION fee_entry_update(transid character(8),fid character(20),uby integer)
  	RETURNS int AS $$
BEGIN
    UPDATE tbl_variable_fee_entry SET "transId" = transid, "updatedBy" = uby, "updatedOn" = CURRENT_TIMESTAMP WHERE id = fid;
     return 1;    
END;
$$ LANGUAGE plpgsql;
/****Fee_entry_update function - End*******/

/****New_cheque_fee_entry function - Start*******/
CREATE OR REPLACE FUNCTION new_cheque_fee_entry(sid character varying(50),sem character varying(5),chlno character varying(50),ptype character varying(30),bnk character varying(100), cheque_no character varying(100),pdate date,grp integer,academic integer,uby integer,remark text,dateupdate date)
  	RETURNS int AS $$
BEGIN
    
     UPDATE tbl_challans SET "challanStatus" = 1,"updatedBy" = uby, "updatedOn" = dateupdate, "pay_type" = ptype, "bank" = bnk, "cheque_dd_no" = cheque_no, "paid_date" = pdate, "chequeRemarks" = remark WHERE "challanNo" = chlno AND "feeGroup" = grp;


     UPDATE tbl_demand SET "challanStatus" = 1, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = uby, "pay_type" = ptype, "bank" = bnk, "cheque_dd_no" = cheque_no, "paid_date" = pdate, "chequeRemarks" = remark WHERE "challanNo" = chlno AND "feeGroup" = grp AND "academicYear" = academic AND "term" = sem;

     UPDATE tbl_waiver SET "challanStatus" = 1, "modified_on" = CURRENT_TIMESTAMP, "modified_by" = uby WHERE "challanNo" = chlno AND "feeGroup" = grp AND "studentId" = sid;

     return 1;
END;
 $$ LANGUAGE plpgsql;
/****New_cheque_fee_entry function - End*******/

/****Nonfeechallanpaymententry function - Start*******/
 
  DECLARE payid int;
BEGIN
     UPDATE  tbl_nonfee_payments SET "parentId" = pid,"studentId" = studId,"amount" = amt,"transStatus" = tstatus, "returnCode" = retrnCde,"remarks" = remrks,"transId" = tid,"transDate" = tdate,"createdby" = cby,"createdOn" = CURRENT_TIMESTAMP WHERE id = pay_id RETURNING id INTO payid ; 
     return payid;
END;
$$ LANGUAGE plpgsql;
/****Nonfeechallanpaymententry function - End*******/

/****Nonfeepaymententry function - Start*******/
 
  DECLARE payid int;
BEGIN
     UPDATE  tbl_topup_payments SET "parentId" = pid,"studentId" = studId,"amount" = amt,"transStatus" = tstatus, "returnCode" = retrnCde,"remarks" = remrks,"transId" = tid,"transDate" = tdate,"createdby" = cby,"createdOn" = CURRENT_TIMESTAMP WHERE id = pay_id RETURNING id INTO payid ; 
     return payid;
END;
$$ LANGUAGE plpgsql;
/****Nonfeepaymententry function - End*******/

/****Paymententry function - Start*******/
CREATE OR REPLACE FUNCTION paymentEntry(pid integer,sid character(50),amt real,tstatus character(20),tnum character(20),retrnCde text,remrks character(30),tid character(20),tdate date,cby integer,pay_id integer)
  	RETURNS int AS $$
 	DECLARE payid int;
BEGIN
     UPDATE  tbl_payments SET "parentId" = pid,"studentId" = sid,"amount" = amt,"transStatus" = tstatus,"transNum" = tnum,"returnCode" = retrnCde,"remarks" = remrks,"transId" = tid,"transDate" = tdate,"createdby" = cby,"createdOn" = CURRENT_TIMESTAMP WHERE id = pay_id RETURNING id INTO payid ; 
     return payid;
END;
$$ LANGUAGE plpgsql;
/****Paymententry function - End*******/

/****Reg_val function - Start*******/
CREATE OR REPLACE FUNCTION reg_val(fname character(20),lname character(20),un character(30),ep character(50),es character(50),pn1 bigint,pn2 bigint,pw character(150),vc character(15))
    RETURNS int AS $$
  	DECLARE exist int;
BEGIN
 	PERFORM  1 FROM public.tbl_parents WHERE email = ep;      

    IF NOT found THEN
       INSERT INTO public.tbl_parents("firstName", "lastName", "userName", "email","secondaryEmail" , "mobileNumber","secondaryNumber", "password", "verifyCode", "createdOn") VALUES(fname,lname,un,ep,es,pn1,pn2,pw,vc,CURRENT_TIMESTAMP) RETURNING id INTO exist;
     ELSE 
       exist = 0;
    END IF;  
    return exist; 
END;
$$ LANGUAGE plpgsql;
/****Reg_val function - End*******/

/****Sfstableentry function - Start*******/
CREATE OR REPLACE FUNCTION sfstableentry(cn character(50),feetypes character(100),tot real,qty integer,totamt real, cby integer, studid character(100))
  	RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_sfs_qty WHERE "challanNo" = cn AND "feeTypes" = feetypes AND "studentId" = studid;
    IF NOT found THEN

      INSERT INTO public.tbl_sfs_qty("challanNo", "feeTypes", "amount", "quantity", "totalAmount", "createdBy", "createdOn", "studentId") VALUES(cn, feetypes, tot, qty, totamt, cby, CURRENT_TIMESTAMP, studid)  returning id into cid;

    ELSE 
      UPDATE tbl_sfs_qty SET "amount" = tot, "quantity" = qty, "totalAmount" = totamt, "updatedOn" = CURRENT_TIMESTAMP, "updatedBy" = cby WHERE "challanNo" = cn AND "feeTypes" = feetypes AND "studentId" = studid returning id into cid;

    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Sfstableentry function - End*******/

/****Statusupdate function - Start*******/
CREATE OR REPLACE FUNCTION statusupdate(param character varying, stat status,uid integer,id integer) 
RETURNS integer AS $$
BEGIN
      EXECUTE 'UPDATE ' || quote_ident(param) || ' SET status=$1, "updatedOn"=CURRENT_TIMESTAMP, "updatedBy"=$2 WHERE id = $3' USING stat,uid,id;
      return 1;
END;
$$ LANGUAGE plpgsql; 
/***Statusupdate function - End*******/

/****Transportchallancreate function - Start*******/
CREATE OR REPLACE FUNCTION transportchallancreate(cno character(50), sid character(50), stgid character(100), cls character(5), term character(5), ss character(15), uid integer, cs integer, tot real, orgtot real, stream integer, due date, feegroup character(40), acayear character(10))
  	RETURNS int AS $$
  	DECLARE cid int;
BEGIN
    INSERT INTO public.tbl_challans("challanNo", "studentId", "feeTypes", "classList", term, "studStatus", "createdOn", "createdBy", "challanStatus", total, org_total, stream, duedate,"feeGroup","academicYear")
  VALUES (cno, sid, stgid, cls, term, ss, CURRENT_TIMESTAMP, uid, 0, tot, orgtot, stream, due, feegroup, acayear) RETURNING id INTO cid;
   return cid;

END;
$$ LANGUAGE plpgsql
/****Transportchallancreate function - End*******/

/****Updatenonfeechallan function - Start*******/
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_nonfee_challans WHERE "feeType" = feetypes AND "studentId" = studId AND "academicYear"=yr AND "remarks"= remark AND "duedate" = due;
    IF NOT found THEN
        UPDATE tbl_nonfee_challans SET "challanNo" = cn,"studentId" = studId, "createdBy"= createdBy, "createdOn" = CURRENT_TIMESTAMP,"stream" = str,"classList" =classlist ,"term" = trm, "feeType" = feetypes,"total" = tot,"remarks"= remark,"duedate" = due, "feeGroup" = feegrp,"academicYear" = yr WHERE "challanNo" = cn AND "studentId" = studId RETURNING id INTO cid;         
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql
/****Updatenonfeechallan function - End*******/

/****Updatetempchallan function - Start*******/
CREATE OR REPLACE FUNCTION updatetempchallan(cn character(50),studId character(30),classlist character(5),feetypes character(100),term character(5),name character(50),studStatus character(15),createdBy integer,tot real,str integer,remark character(256),due date,feegrp character(40),yr character(20))
 	RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_temp_challans WHERE "challanNo" = cn;
    IF found THEN
        INSERT INTO public.tbl_temp_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","studentName","feeTypes","total","remarks","duedate", "feeGroup","academicYear") VALUES (cn, studId,studStatus,createdBy,CURRENT_TIMESTAMP,str,classlist,term,name,feetypes,tot,remark,due,feegrp,yr); 
      cid = 1; 
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql;
/****Updatetempchallan function - End*******/

/****Updatetempchallan function - Start*******/
CREATE OR REPLACE FUNCTION updatetempchallanNew(cn character varying(50),studId character varying(30),classlist integer,feetypes integer,term character varying(5),name character varying(100),studStatus character varying(15),createdBy integer,tot real,str integer,remark text,due date,feegrp integer,yr integer)
  RETURNS int AS $$ 
    DECLARE cid int;
BEGIN
    PERFORM 1 FROM public.tbl_temp_challans WHERE "challanNo" = cn;
    IF found THEN
        INSERT INTO public.tbl_temp_challans("challanNo", "studentId", "studStatus","createdBy", "createdOn","stream","classList","term","studentName","feeType","total","remarks","duedate", "feeGroup","academicYear") VALUES (cn, studId,studStatus,createdBy,CURRENT_TIMESTAMP,str,classlist,term,name,feeType,tot,remark,due,feegrp,yr) RETURNING id INTO cid; 
    ELSE 
      cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql
/****Updatetempchallan function - End*******/

/****Updatewavingamount function - Start*******/
CREATE OR REPLACE FUNCTION updatewavingamount(cn character(50),newamt real,uid integer,fg character(40),wp integer,wa real,wt real,waivertype character(80),rid integer) 
 	RETURNS int AS $$
BEGIN
    PERFORM  1 FROM public.tbl_challans WHERE "challanNo"=cn;      

    IF found THEN
        UPDATE tbl_challans SET "org_total"=newamt,"waivedPercentage" = wp, "waivedAmount" = wa, "waivedTotal" = wt, "updatedOn"=CURRENT_TIMESTAMP,"updatedBy"=uid, "waivedType" = waivertype WHERE "challanNo" = cn AND "feeGroup" = fg AND id=rid;
         
    return 1;
    ELSE
    return 0;
    END IF;
END;
$$ LANGUAGE plpgsql
/****Updatewavingamount function - End*******/

/****Updatewavingamountnew function - Start*******/
CREATE OR REPLACE FUNCTION updatewavingamountnew(cn character varying(50),newamt real,uid integer,fg integer,wp integer,wa real,wt real, stdid character varying (80), wtype character varying(80)) 
 	RETURNS int AS $$
   	DECLARE cid int;
BEGIN
    PERFORM  1 FROM public.tbl_challans WHERE "challanNo"=cn;      

    IF found THEN
    PERFORM 1 FROM public.tbl_waiver WHERE "challanNo" = cn AND "studentId" = stdid AND "feeGroup" = fg;
      IF found THEN 
      UPDATE tbl_waiver SET "waiver_type" = wtype, "waiver_percentage" = wp, "waiver_amount" = wa, "waiver_total" = wt, "modified_on" = CURRENT_TIMESTAMP, "modified_by" = uid, "total" = newamt WHERE "challanNo" = cn AND "studentId" = stdid AND "feeGroup" = fg returning id into cid;
      ELSE
      INSERT INTO tbl_waiver("studentId", "challanNo", "waiver_type", "waiver_percentage", "waiver_amount", "waiver_total", "createdOn", "createdBy","total","feeGroup") VALUES (stdid, cn, wtype, wp, wa, wt, CURRENT_TIMESTAMP,uid,newamt,fg) returning id into cid;
      END IF;
    ELSE
    cid = 0;
    END IF;
    return cid;
END;
$$ LANGUAGE plpgsql
/****Updatewavingamountnew function - End*******/

/****Verifycode function - Start*******/
CREATE OR REPLACE FUNCTION verifyCode(code character(100)) 
  RETURNS int AS $$
	DECLARE cnt int;
BEGIN
    PERFORM  1 FROM public.tbl_parents WHERE "verifyCode" = code ;
    IF NOT found THEN
        cnt = 0;
    ELSE 
        UPDATE tbl_parents SET "verifyCode" = 0 WHERE "verifyCode" = code;
        cnt = 1;
    END IF;  
    return cnt;
END;
$$ LANGUAGE plpgsql
/****Verifycode function - End*******/

/****Studentledgeradddata function - Start*******/
CREATE OR REPLACE FUNCTION studentledgeradddata(sid character varying(50), cno character varying(50), sname character varying (100),acayear character varying (15), classname character varying(20),stream character varying(20),term character varying(5), date date, feegrp character varying(30), amt real, remarks character varying(80), entrytype character varying(50)) 
    RETURNS int AS $$
    DECLARE cid int;
BEGIN
    PERFORM  1 FROM public.tbl_student_ledger WHERE "challanNo" = cno AND "studentId" = sid AND "entryType" = entrytype AND "feeGroup" = feegrp;

    IF NOT found THEN
        INSERT INTO public.tbl_student_ledger("studentId", "challanNo", "studentName", "academicYear","class","stream","term", "date","feeGroup","amount","remarks", "entryType") VALUES (sid, cno,sname,acayear,classname,stream,term,date,feegrp,amt,remarks,entrytype) RETURNING id INTO cid; 
    ELSE 
      cid = 0;
    END IF;
    return cid;
   
END;
$$ LANGUAGE plpgsql 
/****Studentledgeradddata function - End*******/





/********Functions - Start*******/

?>